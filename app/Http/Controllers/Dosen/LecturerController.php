<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\KelasMataKuliah;
use App\Models\Semester;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Presensi;
use App\Models\Dosen;
use Illuminate\Support\Facades\Schema;

class LecturerController extends Controller
{
    public function dashboard()
    {
        if (auth()->user()->role !== 'dosen') {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Belum ada page');
        }

        if (request()->routeIs('dashboard')) {
            return redirect()->route('dosen.dashboard');
        }

        $user = Auth::user();
        $dosen = \App\Models\Dosen::where('user_id', $user->id)->first();

        // Get data from database
        // Only consider kelas that have an active jadwal assigned
        $kelasList = Kelas::where('dosen_id', $user->id)
            ->whereHas('jadwals', function ($q) {
                $q->where('status', 'active');
            })
            ->with('mataKuliah')
            ->get();
        $activeJadwals = Jadwal::whereHas('kelas', function ($q) use ($user) {
            $q->where('dosen_id', $user->id);
        })
            ->whereIn('status', ['active', 'scheduled', 'pending']) // Include other potential statuses
            ->orWhere(function ($query) use ($user) {
                // specific fallback: if schedule is active but status might be null or different in older records
                $query->whereHas('kelas', function ($q) use ($user) {
                    $q->where('dosen_id', $user->id);
                });
            })
            ->with(['kelas.mataKuliah'])->get();

        // Filter out cancelled ones if any
        $activeJadwals = $activeJadwals->filter(function ($j) {
            return $j->status !== 'cancelled' && $j->status !== 'inactive';
        });

        // Build unified schedule source from both Jadwal and KelasMataKuliah
        $mappedJadwals = $activeJadwals->map(function ($jadwal) {
            return [
                'title' => $jadwal->kelas->mataKuliah->nama_mk,
                'code' => $jadwal->kelas->mataKuliah->kode_mk,
                'section' => $jadwal->kelas->section,
                'day' => $jadwal->hari,
                'time' => substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5),
                'class' => $jadwal->kelas->section,
                'room' => $jadwal->ruangan,
                'subject' => $jadwal->kelas->mataKuliah->nama_mk,
                'sks' => $jadwal->kelas->mataKuliah->sks ?? 0,
                'raw_time' => $jadwal->jam_mulai,
            ];
        });

        // Fetch explicit schedules from KelasMataKuliah (often used by Admin)
        $kelasMataKuliahSchedules = collect();
        if ($dosen) {
            $kelasMataKuliahSchedules = \App\Models\KelasMataKuliah::where('dosen_id', $dosen->id)
                ->whereNotNull('hari')
                ->whereNotNull('jam_mulai')
                ->whereNotNull('jam_selesai')
                ->with('mataKuliah')
                ->get();
        }

        $mappedKmk = $kelasMataKuliahSchedules->map(function ($kmk) {
            return [
                'title' => $kmk->mataKuliah->nama_mk,
                'code' => $kmk->mataKuliah->kode_mk,
                'section' => $kmk->kode_kelas ?? $kmk->section ?? '-',
                'day' => $kmk->hari,
                'time' => substr($kmk->jam_mulai, 0, 5) . ' - ' . substr($kmk->jam_selesai, 0, 5),
                'class' => $kmk->kode_kelas ?? '-',
                'room' => $kmk->ruang ?? '-',
                'subject' => $kmk->mataKuliah->nama_mk,
                'sks' => $kmk->mataKuliah->sks ?? 0,
                'raw_time' => $kmk->jam_mulai,
            ];
        });

        // Merge and deduplicate by day+time+code
        $merged = collect(array_merge($mappedJadwals->toArray(), $mappedKmk->toArray()));

        // Today's schedules
        $today = now()->locale('id')->isoFormat('dddd');
        $todaySchedules = $merged->filter(function ($s) use ($today) {
            return !empty($s['day']) && $s['day'] === $today;
        })->map(function ($s) {
            return [
                'subject' => $s['subject'],
                'code' => $s['code'],
                'class' => $s['class'],
                'time' => $s['time'],
                'room' => $s['room'] ?? '-',
                'status' => 'Menunggu',
            ];
        })->values()->toArray();

        // Upcoming schedules
        $dayMap = [
            'Minggu' => 0,
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6
        ];
        $todayIndex = now()->dayOfWeek;

        $upcomingSchedules = $merged->filter(function ($s) use ($today) {
            return empty($s['day']) ? false : ($s['day'] !== $today);
        })->map(function ($s) use ($dayMap, $todayIndex) {
            $dayIndex = $dayMap[$s['day']] ?? 0;
            $diff = ($dayIndex - $todayIndex + 7) % 7;
            if ($diff === 0) $diff = 7;
            return [
                'subject' => $s['subject'],
                'code' => $s['code'],
                'class' => $s['class'],
                'day' => $s['day'],
                'time' => $s['time'],
                'room' => $s['room'] ?? '-',
                'sks' => $s['sks'] ?? 0,
                'diff' => $diff,
                'raw_time' => $s['raw_time'] ?? null,
            ];
        })->sortBy([['diff', 'asc'], ['raw_time', 'asc']])->take(5)->values()->toArray();

        // Fetch explicit schedules from KelasMataKuliah (often used by Admin)
        $kelasMataKuliahSchedules = collect();
        if ($dosen) {
            $kelasMataKuliahSchedules = \App\Models\KelasMataKuliah::where('dosen_id', $dosen->id)
                ->whereNotNull('hari')
                ->whereNotNull('jam_mulai')
                ->whereNotNull('jam_selesai')
                ->with('mataKuliah')
                ->get();
        }

        // Merge both collections for the calendar
        $mappedJadwals = $activeJadwals->map(function ($jadwal) {
            return [
                'title' => $jadwal->kelas->mataKuliah->nama_mk,
                'code' => $jadwal->kelas->mataKuliah->kode_mk,
                'section' => $jadwal->kelas->section,
                'day' => $jadwal->hari,
                'time' => substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5),
                'class' => $jadwal->kelas->section,
                'room' => $jadwal->ruangan,
                'subject' => $jadwal->kelas->mataKuliah->nama_mk,
                'color' => 'bg-blue-100 text-blue-700',
            ];
        });

        $mappedKmk = $kelasMataKuliahSchedules->map(function ($kmk) {
            return [
                'title' => $kmk->mataKuliah->nama_mk,
                'code' => $kmk->mataKuliah->kode_mk,
                'section' => $kmk->kode_kelas ?? $kmk->section ?? '-',
                'day' => $kmk->hari,
                'time' => substr($kmk->jam_mulai, 0, 5) . ' - ' . substr($kmk->jam_selesai, 0, 5),
                'class' => $kmk->kode_kelas ?? '-',
                'room' => $kmk->ruang ?? '-',
                'subject' => $kmk->mataKuliah->nama_mk,
                'color' => 'bg-purple-100 text-purple-700',
            ];
        });

        // Unique merge based on day + time + code
        // Convert to plain arrays first to avoid Eloquent model behavior when items are arrays
        $merged = collect(array_merge($mappedJadwals->toArray(), $mappedKmk->toArray()));
        $allSchedules = $merged->unique(function ($item) {
            return ($item['day'] ?? '') . ($item['time'] ?? '') . ($item['code'] ?? '');
        })->values()->toArray();

        // Generate 16 meeting dates from semester start date
        $semesterAktif = \App\Models\Semester::where('status', 'aktif')->first()
            ?? \App\Models\Semester::where('is_active', true)->first()
            ?? \App\Models\Semester::latest()->first();

        $meetingDates = [];
        if ($semesterAktif && $semesterAktif->tanggal_mulai) {
            $startDate = \Carbon\Carbon::parse($semesterAktif->tanggal_mulai);
            
            // Generate dates for each schedule based on their day
            foreach ($allSchedules as $schedule) {
                $dayName = $schedule['day'] ?? null;
                if (!$dayName) continue;
                
                // Map day names to Carbon day constants
                $dayMap = [
                    'Minggu' => 0,
                    'Senin' => 1,
                    'Selasa' => 2,
                    'Rabu' => 3,
                    'Kamis' => 4,
                    'Jumat' => 5,
                    'Sabtu' => 6
                ];
                
                $targetDay = $dayMap[$dayName] ?? null;
                if ($targetDay === null) continue;
                
                // Find the first occurrence of this day from start date
                $firstOccurrence = $startDate->copy();
                while ($firstOccurrence->dayOfWeek !== $targetDay) {
                    $firstOccurrence->addDay();
                }
                
                // Generate 16 weekly occurrences
                for ($i = 0; $i < 16; $i++) {
                    $meetingDate = $firstOccurrence->copy()->addWeeks($i);
                    $meetingDates[] = $meetingDate->format('Y-m-d');
                }
            }
            
            // Remove duplicates
            $meetingDates = array_unique($meetingDates);
            sort($meetingDates);
        }

        return view('page.dosen.dashboard.index', [
            'total_mata_kuliah' => $kelasList->count(),
            'total_kelas_aktif' => $kelasList->count(),
            'total_students' => 0,
            'sks_load' => $kelasList->sum(fn($k) => $k->mataKuliah->sks ?? 0),
            'krs_approval' => 0,
            'schedules' => $todaySchedules,
            'upcomingSchedules' => $upcomingSchedules,
            'all_schedules' => $allSchedules,
            'meeting_dates' => $meetingDates
        ]);
    }

    public function classes()
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return view('page.dosen.kelas.index', ['classes' => []]);
        }

        // Get classes from 'kelas_mata_kuliahs' table (primary source)
        $kmkList = KelasMataKuliah::where('dosen_id', $dosen->id)
            ->with(['mataKuliah'])
            ->get();

        \Log::info('Kelas Saya Debug', [
            'user_id' => $user->id,
            'dosen_id' => $dosen->id,
            'kmk_count' => $kmkList->count(),
            'kmk_data' => $kmkList->map(fn($k) => [
                'id' => $k->id,
                'mata_kuliah_id' => $k->mata_kuliah_id,
                'kode_kelas' => $k->kode_kelas,
                'hari' => $k->hari,
                'jam_mulai' => $k->jam_mulai,
                'jam_selesai' => $k->jam_selesai,
                'mk_nama' => $k->mataKuliah?->nama_mk,
            ])->toArray(),
        ]);

        // Map KelasMataKuliah records
        $classes = $kmkList->map(function ($kmk) {
            // Find if there's a Kelas counterpart for routing purposes
            $kelas = \App\Models\Kelas::where('mata_kuliah_id', $kmk->mata_kuliah_id)
                ->where('section', $kmk->kode_kelas)
                ->first();

            // If exact kelas not found, attempt to fallback to any kelas with same mata_kuliah_id
            if (!$kelas) {
                $kelas = \App\Models\Kelas::where('mata_kuliah_id', $kmk->mata_kuliah_id)->first();
            }

            // Calculate student count from both possible foreign keys
            $krsCount = \App\Models\Krs::whereIn('status', ['approved', 'disetujui'])
                ->where(function ($q) use ($kmk, $kelas) {
                    $q->where('kelas_mata_kuliah_id', $kmk->id);
                    if ($kelas) {
                        $q->orWhere('kelas_id', $kelas->id);
                    }
                })->count();

            return [
                // Prefer the actual Kelas id for routing; fallback to KelasMataKuliah id only if no Kelas exists
                'id' => $kelas?->id ?? $kmk->id,
                'source' => 'kmk',
                'name' => $kmk->mataKuliah->nama_mk ?? 'N/A',
                'code' => $kmk->mataKuliah->kode_mk ?? 'N/A',
                'section' => $kmk->kode_kelas ?? '-',
                'students' => $krsCount,
                'day' => $kmk->hari ?? '-',
                'time' => ($kmk->jam_mulai && $kmk->jam_selesai) ? substr($kmk->jam_mulai, 0, 5) . ' - ' . substr($kmk->jam_selesai, 0, 5) : '-',
                'room' => $kmk->ruang ?? '-',
                'sks' => $kmk->mataKuliah->sks ?? 0,
                'progress' => $this->calculateProgress(),
            ];
        })->values()->toArray();

        return view('page.dosen.kelas.index', compact('classes'));
    }

    private function calculateProgress()
    {
        $semesterAktif = \App\Models\Semester::where('status', 'aktif')->first()
            ?? \App\Models\Semester::latest()->first();

        $progress = 0;
        if ($semesterAktif && $semesterAktif->tanggal_mulai) {
            try {
                $start = \Carbon\Carbon::parse($semesterAktif->tanggal_mulai);
                $now = \Carbon\Carbon::now();
                if ($now->gte($start)) {
                    $weeks = $start->diffInWeeks($now);
                    $progress = min(100, round(($weeks / 16) * 100));
                }
            } catch (\Exception $e) {
                $progress = 0;
            }
        }
        return $progress;
    }

    public function inputNilai(Request $request)
    {
        $user = Auth::user();

        $kelasList = Kelas::where('dosen_id', $user->id)
            ->with('mataKuliah')
            ->get();

        $classes = $kelasList->map(function ($kelas) {
            return [
                'id' => $kelas->id,
                'name' => $kelas->mataKuliah->nama_mk . ' (' . $kelas->section . ')',
            ];
        })->toArray();

        $students = [];
        // TODO: Implement student data from database when available

        return view('dosen.input-nilai.index', compact('classes', 'students'));
    }

    public function students(Request $request)
    {
        $students = [];
        // TODO: Implement student data from database when available

        return view('dosen.mahasiswa.index', compact('students'));
    }

    public function krs()
    {
        $students = [];
        // TODO: Implement KRS data from database when available

        return view('dosen.krs.index', compact('students'));
    }

    public function detail($id)
    {
        $kelas = Kelas::with([
            'mataKuliah',
            'jadwals' => function ($q) {
                $q->where('status', 'active');
            }
        ])->findOrFail($id);

        $jadwal = $kelas->jadwals->first();

        // Fetch active semester for logic
        $semesterAktif = Semester::where('status', 'aktif')->first()
            ?? Semester::where('is_active', true)->first()
            ?? Semester::latest()->first();

        // Calculate progress based on weeks
        $pertemuanKe = 1;
        if ($semesterAktif && $semesterAktif->tanggal_mulai) {
            $startDate = Carbon::parse($semesterAktif->tanggal_mulai);
            $now = Carbon::now();
            if ($now->gte($startDate)) {
                $pertemuanKe = (int) min(16, $startDate->diffInWeeks($now) + 1);
            }
        }

        // Fetch students from the related KelasMataKuliah
        $kelasMataKuliah = KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where('kode_kelas', $kelas->section)
            ->where('dosen_id', $kelas->dosen_id)
            ->first();

        $krsCollection = \App\Models\Krs::whereIn('status', ['approved', 'disetujui'])
            ->where(function ($q) use ($kelasMataKuliah, $kelas) {
                $q->where('kelas_id', $kelas->id);
                if ($kelasMataKuliah) {
                    $q->orWhere('kelas_mata_kuliah_id', $kelasMataKuliah->id);
                }
            })->with('mahasiswa')
            ->get();

        $students = $krsCollection->map(function ($krs) {
            $m = $krs->mahasiswa;
            $userName = $m->user->name ?? ($m->nama ?? 'Mahasiswa');
            return [
                'name' => $userName,
                'nim' => $m->nim ?? null,
                'prodi' => $m->prodi ?? null,
                'semester' => $m->semester ?? null,
                'ipk' => $m->ipk !== null ? number_format((float) $m->ipk, 2) : '-',
                'status' => 'Aktif',
            ];
        })->toArray();

        $class_info = [
            'name' => $kelas->mataKuliah->nama_mk,
            'code' => $kelas->mataKuliah->kode_mk,
            'sks' => $kelas->mataKuliah->sks,
            'semester' => $kelas->mataKuliah->semester,
            'section' => $kelas->section,
            'day' => $jadwal?->hari ?? '-',
            'time' => $jadwal ? substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) : '-',
            'room' => $jadwal?->ruangan ?? '-',
            'students_count' => count($students),
            'progress' => $pertemuanKe,
            'total_pertemuan' => 16,
            'semester_start_date' => $semesterAktif?->tanggal_mulai
        ];


        if (request()->ajax()) {
            return view('page.dosen.kelas.partials.detail-content', compact('class_info', 'students', 'id'))->with('is_modal', true);
        }

        return view('page.dosen.kelas.detail', compact('class_info', 'students', 'id'))->with('is_modal', false);
    }

    /**
     * Generate a QR token for the kelas's KelasMataKuliah record.
     */
    public function generateQr($id)
    {
        $kelas = Kelas::with('mataKuliah')->findOrFail($id);

        // Try matching by kode_kelas or nama_kelas (some records use different column names)
        $kelasMataKuliah = KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where(function ($q) use ($kelas) {
                $q->where('kode_kelas', $kelas->section)
                    ->orWhere('kode_kelas', $kelas->section . '');
            })->first();

        // Fallback: pick first kelas_mata_kuliah for that mata_kuliah_id
        if (!$kelasMataKuliah) {
            $kelasMataKuliah = KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)->first();
        }

        if (!$kelasMataKuliah) {
            return back()->with('error', 'Tidak dapat menemukan record KelasMataKuliah untuk kelas ini. Silakan buat kelas mata kuliah terlebih dahulu.');
        }

        // Always generate a fresh QR token when the lecturer requests it.
        // Do NOT enable the QR or start the expiry window here — activation must be explicit by the lecturer.
        $kelasMataKuliah->qr_token = \Illuminate\Support\Str::random(40);
        $kelasMataKuliah->qr_enabled = $kelasMataKuliah->qr_enabled ?? false;
        // If the lecturer submitted a pertemuan in the request, store it so the QR refers to that meeting
        $requestedPertemuan = request()->input('pertemuan');
        if ($requestedPertemuan) {
            $kelasMataKuliah->qr_current_pertemuan = (int) $requestedPertemuan;
        }
        $kelasMataKuliah->save();

        // Log and return debug info in session so UI can show whether token was created
        \Log::info('Generated QR token', ['kelas_mk_id' => $kelasMataKuliah->id, 'qr_token' => $kelasMataKuliah->qr_token]);

        $debug = [
            'kelas_mata_kuliah_id' => $kelasMataKuliah->id,
            'qr_token' => $kelasMataKuliah->qr_token,
        ];

        return back()->with(['success' => 'QR dibuat dan diaktifkan.', 'debug_info' => $debug]);
    }

    public function meetingDetail($id, $pertemuan)
    {
        $kelas = Kelas::with([
            'mataKuliah',
            'jadwals' => function ($q) {
                $q->where('status', 'active');
            }
        ])->findOrFail($id);

        $jadwal = $kelas->jadwals->first();

        // Re-calculate meeting date based on session number
        $semesterAktif = \App\Models\Semester::where('status', 'aktif')->first()
            ?? \App\Models\Semester::where('is_active', true)->first()
            ?? \App\Models\Semester::latest()->first();

        $meetingDate = '-';
        if ($semesterAktif && $semesterAktif->tanggal_mulai) {
            $start = \Carbon\Carbon::parse($semesterAktif->tanggal_mulai);
            $meetingDate = $start->copy()->addDays(($pertemuan - 1) * 7)->locale('id')->isoFormat('D MMMM YYYY');
        }

        $meeting = [
            'no' => $pertemuan,
            'label' => 'Pertemuan ' . $pertemuan,
            'date' => $meetingDate,
            'time' => $jadwal ? substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) : '-',
            'room' => $jadwal?->ruangan ?? '-',
        ];

        // Fetch students
        $kelasMataKuliah = \App\Models\KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where('kode_kelas', $kelas->section)
            ->where('dosen_id', $kelas->dosen_id)
            ->first();

        $krsCollection = \App\Models\Krs::whereIn('status', ['approved', 'disetujui'])
            ->where(function ($q) use ($kelasMataKuliah, $kelas) {
                $q->where('kelas_id', $kelas->id);
                if ($kelasMataKuliah) {
                    $q->orWhere('kelas_mata_kuliah_id', $kelasMataKuliah->id);
                }
            })->with('mahasiswa')
            ->get();

        // Fetch attendance records for this meeting
        $attendanceRecords = collect();
        $totalAttendanceCounts = collect();
        
        if ($kelasMataKuliah) {
            $attendanceQuery = Presensi::where('kelas_mata_kuliah_id', $kelasMataKuliah->id);
            
            // Get records for current meeting
            $currentMeetingQuery = clone $attendanceQuery;
            if (Schema::hasColumn('presensis', 'pertemuan')) {
                $currentMeetingQuery->where(function($q) use ($pertemuan) {
                    $q->where('pertemuan', $pertemuan)
                      ->orWhereNull('pertemuan');
                });
            }
            $attendanceRecords = $currentMeetingQuery->get()->keyBy('mahasiswa_id');

            // Calculate total attendance per student
            $totalAttendanceCounts = Presensi::where('kelas_mata_kuliah_id', $kelasMataKuliah->id)
                ->where('status', 'hadir')
                ->selectRaw('mahasiswa_id, count(*) as total')
                ->groupBy('mahasiswa_id')
                ->pluck('total', 'mahasiswa_id');
        }

        $students = $krsCollection->map(function ($krs) use ($attendanceRecords, $totalAttendanceCounts, $kelas) {
            $m = $krs->mahasiswa;
            $userName = $m->user->name ?? ($m->nama ?? 'Mahasiswa');
            
            // Check if student has attendance record for this meeting
            $attendanceRecord = $attendanceRecords->get($m->id);
            $attendanceStatus = $attendanceRecord ? $attendanceRecord->status : null; // 'hadir', 'izin', 'sakit', 'alpa'
            $attendanceTime = $attendanceRecord && $attendanceRecord->waktu 
                ? \Carbon\Carbon::parse($attendanceRecord->waktu)->format('H:i')
                : null;
            
            // Get semester - prefer explicit semester field, fallback to calculation if needed
            $semester = $m->semester ?? $m->getCurrentSemester();

            return [
                'id' => $m->id,
                'name' => $userName,
                'nim' => $m->nim ?? null,
                'prodi' => $m->prodi ?? null,
                'semester' => $semester,
                'status_mahasiswa' => $m->status ?? 'Aktif', // Status of the student (Aktif, Cuti, etc)
                'attendance_status' => $attendanceStatus, // Status for this meeting
                'attendance_time' => $attendanceTime,
                'total_attendance' => $totalAttendanceCounts->get($m->id, 0),
                'krs_id' => $krs->id,
            ];
        })->toArray();

        // Get tugas (assignments) for this mata kuliah and pertemuan
        $tasks = \App\Models\Tugas::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where('pertemuan', $pertemuan)
            ->latest()
            ->get();

        // Ensure the kelas_mata_kuliah has a qr_token so QR can be generated; generate if missing



        if ($kelasMataKuliah && empty($kelasMataKuliah->qr_token)) {
            $kelasMataKuliah->qr_token = \Illuminate\Support\Str::random(40);
            $kelasMataKuliah->qr_enabled = $kelasMataKuliah->qr_enabled ?? false;
            $kelasMataKuliah->save();
        }

        $token = $kelasMataKuliah->qr_token ?? null;
        $qrEnabled = (bool) ($kelasMataKuliah->qr_enabled ?? false);
        $qrExpires = $kelasMataKuliah->qr_expires_at ?? null;

        // Get materials for this mata kuliah and pertemuan
        $materis = \App\Models\Materi::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where('pertemuan', $pertemuan)
            ->with('dosen')
            ->latest()
            ->get();

        return view('page.dosen.kelas.lihat-rincian', compact('kelas', 'meeting', 'students', 'tasks', 'materis', 'token', 'qrEnabled', 'qrExpires', 'id'));
    }

    public function updateAttendance(Request $request, $id, $pertemuan)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'status' => 'required|in:hadir,izin,sakit,alpa',
        ]);

        $kelas = Kelas::findOrFail($id);
        
        // Find or create KelasMataKuliah
        $kelasMataKuliah = KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where('kode_kelas', $kelas->section)
            ->first();

        if (!$kelasMataKuliah) {
             // Fallback or create logic if needed, but usually should exist
             return response()->json(['success' => false, 'message' => 'Kelas Mata Kuliah not found'], 404);
        }

        $studentId = $request->mahasiswa_id;
        $status = $request->status;

        // Find existing record or create new
        $attendance = Presensi::updateOrCreate(
            [
                'kelas_mata_kuliah_id' => $kelasMataKuliah->id,
                'mahasiswa_id' => $studentId,
                'pertemuan' => $pertemuan,
            ],
            [
                'status' => $status,
                'tanggal' => now()->toDateString(),
                'waktu' => now(), // Update time to now
                // We might need krs_id if strict relationship is required
                'krs_id' => \App\Models\Krs::where('mahasiswa_id', $studentId)
                            ->where('kelas_id', $kelas->id)
                            ->whereIn('status', ['approved', 'disetujui'])
                            ->value('id')
            ]
        );

        // Recalculate total attendance for this student
        $totalAttendance = Presensi::where('kelas_mata_kuliah_id', $kelasMataKuliah->id)
            ->where('mahasiswa_id', $studentId)
            ->where('status', 'hadir')
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil diperbarui',
            'data' => [
                'status' => $status,
                'total_attendance' => $totalAttendance
            ]
        ]);
    }

    public function meetingMaterials($id, $pertemuan)
    {
        $kelas = Kelas::with([
            'mataKuliah',
            'jadwals' => function ($q) {
                $q->where('status', 'active');
            }
        ])->findOrFail($id);

        $jadwal = $kelas->jadwals->first();

        // Re-calculate meeting date based on session number
        $semesterAktif = \App\Models\Semester::where('status', 'aktif')->first()
            ?? \App\Models\Semester::where('is_active', true)->first()
            ?? \App\Models\Semester::latest()->first();

        $meetingDate = '-';
        if ($semesterAktif && $semesterAktif->tanggal_mulai) {
            $start = \Carbon\Carbon::parse($semesterAktif->tanggal_mulai);
            $meetingDate = $start->copy()->addDays(($pertemuan - 1) * 7)->locale('id')->isoFormat('D MMMM YYYY');
        }

        $meeting = [
            'no' => $pertemuan,
            'label' => 'Pertemuan ' . $pertemuan,
            'date' => $meetingDate,
            'time' => $jadwal ? substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) : '-',
            'room' => $jadwal?->ruangan ?? '-',
        ];

        // Get materials for this mata kuliah and pertemuan
        $materis = \App\Models\Materi::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where('pertemuan', $pertemuan)
            ->with('dosen')
            ->latest()
            ->get();

        return view('page.dosen.kelas.materi', compact('kelas', 'meeting', 'materis'));
    }

    /**
     * Activate the QR for the class: enable it and start a 5-minute expiry window.
     */
    public function activateQr(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $kelasMataKuliah = KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where(function ($q) use ($kelas) {
                $q->where('kode_kelas', $kelas->section)
                    ->orWhere('kode_kelas', $kelas->section . '');
            })->first();

        if (!$kelasMataKuliah) {
            return back()->with('error', 'Tidak dapat menemukan record kelas mata kuliah untuk mengaktifkan QR.');
        }

        // Ensure token exists
        if (empty($kelasMataKuliah->qr_token)) {
            $kelasMataKuliah->qr_token = \Illuminate\Support\Str::random(40);
        }

        $kelasMataKuliah->qr_enabled = true;
        $kelasMataKuliah->qr_expires_at = Carbon::now()->addMinutes(5);
        $kelasMataKuliah->save();

        \Log::info('Activated QR token', ['kelas_mk_id' => $kelasMataKuliah->id, 'qr_token' => $kelasMataKuliah->qr_token]);

        return back()->with('success', 'QR ditampilkan untuk 5 menit.');
    }

    /**
     * Manually deactivate the QR for the class.
     */
    public function deactivateQr(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $kelasMataKuliah = KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where(function ($q) use ($kelas) {
                $q->where('kode_kelas', $kelas->section)
                    ->orWhere('kode_kelas', $kelas->section . '');
            })->first();

        if (!$kelasMataKuliah) {
            return back()->with('error', 'Tidak dapat menemukan record kelas mata kuliah untuk menonaktifkan QR.');
        }

        $kelasMataKuliah->qr_enabled = false;
        $kelasMataKuliah->qr_expires_at = null;
        $kelasMataKuliah->save();

        \Log::info('QR token manually disabled', ['kelas_mk_id' => $kelasMataKuliah->id, 'qr_token' => $kelasMataKuliah->qr_token]);

        return back()->with('success', 'QR dinonaktifkan.');
    }

    /**
     * Get attendance data for AJAX polling (real-time updates)
     */
    public function getAttendanceData($id)
    {
        $kelas = Kelas::with('mataKuliah')->findOrFail($id);

        // Find the related KelasMataKuliah
        $kelasMataKuliah = KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where(function ($q) use ($kelas) {
                $q->where('kode_kelas', $kelas->section)
                    ->orWhere('kode_kelas', $kelas->section . '');
            })->first();

        if (!$kelasMataKuliah) {
            $kelasMataKuliah = KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)->first();
        }

        if (!$kelasMataKuliah) {
            return response()->json([
                'success' => false,
                'presensis' => []
            ]);
        }

        // Get current pertemuan
        $currentPertemuan = request()->input('pertemuan') ?? ($kelasMataKuliah->qr_current_pertemuan ?? 1);

        // Load presensi records
        $presensiQuery = Presensi::where('kelas_mata_kuliah_id', $kelasMataKuliah->id)
            ->with(['krs.mahasiswa.user', 'krs.kelas'])
            ->orderByDesc('created_at');

        // Filter by pertemuan if column exists
        if (Schema::hasColumn('presensis', 'pertemuan')) {
            $presensiQuery->where(function($q) use ($currentPertemuan) {
                $q->where('pertemuan', $currentPertemuan)
                  ->orWhereNull('pertemuan');
            });
        }

        $presensis = $presensiQuery->get();

        // Format the data for JSON response
        $formattedPresensis = $presensis->map(function($p, $index) use ($kelas) {
            return [
                'id' => $p->id,
                'no' => $index + 1,
                'nama' => $p->nama ?? ($p->krs->mahasiswa->user->name ?? ($p->krs->mahasiswa->nama ?? '-')),
                'kelas' => $p->krs?->kelas?->section ?? $kelas->section ?? '-',
                'kontak' => $p->kontak ?? '-',
                'waktu' => optional($p->waktu ?? $p->tanggal)->format('d M Y H:i') ?? (optional($p->tanggal)->format('d M Y') ?? '-'),
                'created_at' => $p->created_at->toIso8601String(),
            ];
        });

        return response()->json([
            'success' => true,
            'presensis' => $formattedPresensis,
            'count' => $presensis->count()
        ]);
    }

    /**
     * Display input nilai page for specific kelas
     */
    public function inputNilaiKelas($id)
    {
        $user = Auth::user();
        
        $kelas = Kelas::with(['mataKuliah', 'dosen'])->findOrFail($id);
        
        // Check if user is the dosen for this class
        // Note: dosen_id in kelas refers to dosen table, not users table
        if (!$kelas->dosen || $kelas->dosen->user_id != $user->id) {
            return redirect()->route('dosen.kelas')->with('error', 'Anda tidak memiliki akses ke kelas ini.');
        }
        
        // Get or create bobot penilaian
        $bobot = \App\Models\BobotPenilaian::firstOrCreate(
            ['kelas_id' => $id],
            [
                'bobot_partisipatif' => 25.00,
                'bobot_proyek' => 25.00,
                'bobot_quiz' => 5.00,
                'bobot_tugas' => 5.00,
                'bobot_uts' => 20.00,
                'bobot_uas' => 20.00,
                'is_locked' => false,
            ]
        );
        
        // Get students from KRS
        $students = \App\Models\Krs::where('kelas_id', $id)
            ->whereIn('status', ['approved', 'disetujui'])
            ->with(['mahasiswa.user', 'nilai'])
            ->get()
            ->map(function($krs) use ($id) {
                $mahasiswa = $krs->mahasiswa;
                $nilai = $krs->nilai ?? new \App\Models\Nilai(['kelas_id' => $id]);
                
                return [
                    'krs_id' => $krs->id,
                    'nim' => $mahasiswa->nim ?? '-',
                    'name' => $mahasiswa->user->name ?? $mahasiswa->nama ?? '-',
                    'nilai_id' => $nilai->id ?? null,
                    'nilai_partisipatif' => $nilai->nilai_partisipatif ?? 0,
                    'nilai_proyek' => $nilai->nilai_proyek ?? 0,
                    'nilai_quiz' => $nilai->nilai_quiz ?? 0,
                    'nilai_tugas' => $nilai->nilai_tugas ?? 0,
                    'nilai_uts' => $nilai->nilai_uts ?? 0,
                    'nilai_uas' => $nilai->nilai_uas ?? 0,
                    'nilai_akhir' => $nilai->nilai_akhir ?? 0,
                    'grade' => $nilai->grade ?? '-',
                    'bobot' => $nilai->bobot ?? 0,
                ];
            })->toArray();
        
        $class_info = [
            'id' => $kelas->id,
            'name' => $kelas->mataKuliah->nama_mk,
            'code' => $kelas->mataKuliah->kode_mk,
            'section' => $kelas->section,
            'sks' => $kelas->mataKuliah->sks,
        ];
        
        return view('page.dosen.input-nilai.kelas', compact('class_info', 'students', 'bobot'));
    }

    /**
     * Save bobot penilaian for a kelas
     */
    public function saveBobotPenilaian(Request $request, $id)
    {
        $request->validate([
            'bobot_partisipatif' => 'required|numeric|min:0|max:100',
            'bobot_proyek' => 'required|numeric|min:0|max:100',
            'bobot_quiz' => 'required|numeric|min:0|max:100',
            'bobot_tugas' => 'required|numeric|min:0|max:100',
            'bobot_uts' => 'required|numeric|min:0|max:100',
            'bobot_uas' => 'required|numeric|min:0|max:100',
        ]);
        
        // Check authorization
        $kelas = Kelas::with('dosen')->findOrFail($id);
        
        if (!$kelas->dosen || $kelas->dosen->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke kelas ini.'
            ], 403);
        }
        
        // Validate total = 100
        $total = $request->bobot_partisipatif + $request->bobot_proyek + $request->bobot_quiz + 
                 $request->bobot_tugas + $request->bobot_uts + $request->bobot_uas;
        
        if (abs($total - 100) > 0.01) {
            return response()->json([
                'success' => false,
                'message' => 'Total bobot harus sama dengan 100%. Saat ini: ' . $total . '%'
            ], 422);
        }
        
        $bobot = \App\Models\BobotPenilaian::where('kelas_id', $id)->first();
        
        if (!$bobot) {
            return response()->json([
                'success' => false,
                'message' => 'Bobot penilaian tidak ditemukan.'
            ], 404);
        }
        
        // Check if bobot is being edited (already locked)
        $isEditing = $bobot->is_locked;
        
        // Update bobot values
        $bobot->update($request->only([
            'bobot_partisipatif',
            'bobot_proyek',
            'bobot_quiz',
            'bobot_tugas',
            'bobot_uts',
            'bobot_uas',
        ]));
        
        // Lock the bobot if not already locked
        if (!$isEditing) {
            $bobot->lock(Auth::id());
        }
        
        // If editing, recalculate all existing grades
        if ($isEditing) {
            $this->recalculateAllGrades($id, $bobot);
        }
        
        $message = $isEditing 
            ? 'Bobot penilaian berhasil diupdate. Semua nilai telah dikalkulasi ulang.'
            : 'Bobot penilaian berhasil disimpan dan dikunci.';
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $bobot,
            'recalculated' => $isEditing
        ]);
    }

    /**
     * Recalculate all grades for a kelas when bobot changes
     */
    private function recalculateAllGrades($kelasId, $bobot)
    {
        \DB::beginTransaction();
        try {
            // Get all nilai records for this kelas
            $nilaiRecords = \App\Models\Nilai::where('kelas_id', $kelasId)->get();
            
            foreach ($nilaiRecords as $nilai) {
                // Recalculate using the new bobot
                $nilai->autoCalculateGrade($bobot);
                $nilai->save();
            }
            
            \DB::commit();
            \Log::info("Recalculated {$nilaiRecords->count()} grades for kelas {$kelasId}");
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error recalculating grades: ' . $e->getMessage());
        }
    }

    /**
     * Save nilai mahasiswa
     */
    public function simpanNilai(Request $request, $id)
    {
        $request->validate([
            'nilai' => 'required|array',
            'nilai.*.krs_id' => 'required|exists:krs,id',
            'nilai.*.nilai_partisipatif' => 'nullable|numeric|min:0|max:100',
            'nilai.*.nilai_proyek' => 'nullable|numeric|min:0|max:100',
            'nilai.*.nilai_quiz' => 'nullable|numeric|min:0|max:100',
            'nilai.*.nilai_tugas' => 'nullable|numeric|min:0|max:100',
            'nilai.*.nilai_uts' => 'nullable|numeric|min:0|max:100',
            'nilai.*.nilai_uas' => 'nullable|numeric|min:0|max:100',
        ]);
        
        $kelas = Kelas::with('dosen')->findOrFail($id);
        
        // Check ownership
        // Note: dosen_id in kelas refers to dosen table, not users table
        if (!$kelas->dosen || $kelas->dosen->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke kelas ini.'
            ], 403);
        }
        
        $bobot = \App\Models\BobotPenilaian::where('kelas_id', $id)->first();
        
        if (!$bobot || !$bobot->is_locked) {
            return response()->json([
                'success' => false,
                'message' => 'Bobot penilaian belum diset atau belum dikunci.'
            ], 422);
        }
        
        \DB::beginTransaction();
        try {
            $savedCount = 0;
            
            foreach ($request->nilai as $nilaiData) {
                $krsId = $nilaiData['krs_id'];
                
                // Find or create nilai record
                $nilai = \App\Models\Nilai::firstOrNew([
                    'krs_id' => $krsId,
                    'kelas_id' => $id,
                ]);
                
                // Set component values
                $nilai->nilai_partisipatif = $nilaiData['nilai_partisipatif'] ?? 0;
                $nilai->nilai_proyek = $nilaiData['nilai_proyek'] ?? 0;
                $nilai->nilai_quiz = $nilaiData['nilai_quiz'] ?? 0;
                $nilai->nilai_tugas = $nilaiData['nilai_tugas'] ?? 0;
                $nilai->nilai_uts = $nilaiData['nilai_uts'] ?? 0;
                $nilai->nilai_uas = $nilaiData['nilai_uas'] ?? 0;
                
                // Auto calculate final grade and bobot
                $nilai->autoCalculateGrade($bobot);
                
                // Auto-publish: set as published immediately
                $nilai->is_published = true;
                $nilai->published_at = now();
                $nilai->published_by = Auth::id();
                
                $nilai->save();
                $savedCount++;
            }
            
            \DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Berhasil menyimpan {$savedCount} nilai mahasiswa.",
            ]);
            
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error saving nilai: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan nilai: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get bobot penilaian for AJAX
     */
    public function getBobotPenilaian($id)
    {
        $bobot = \App\Models\BobotPenilaian::where('kelas_id', $id)->first();
        
        if (!$bobot) {
            return response()->json([
                'success' => false,
                'message' => 'Bobot penilaian tidak ditemukan.'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $bobot
        ]);
    }
}
