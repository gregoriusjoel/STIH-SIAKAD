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

        return view('page.dosen.dashboard.index', [
            'total_mata_kuliah' => $kelasList->count(),
            'total_kelas_aktif' => $kelasList->count(),
            'total_students' => 0,
            'sks_load' => $kelasList->sum(fn($k) => $k->mataKuliah->sks ?? 0),
            'krs_approval' => 0,
            'schedules' => $todaySchedules,
            'upcomingSchedules' => $upcomingSchedules,
            'all_schedules' => $allSchedules
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

    public function absensi($id)
    {
        $kelas = Kelas::with([
            'mataKuliah',
            'jadwals' => function ($q) {
                $q->where('status', 'active');
            }
        ])->findOrFail($id);

        $jadwal = $kelas->jadwals->first();

        // Calculate current meeting
        $semesterAktif = \App\Models\Semester::where('status', 'aktif')->first()
            ?? \App\Models\Semester::latest()->first();

        $pertemuanKe = 1;
        if ($semesterAktif && $semesterAktif->tanggal_mulai) {
            $start = \Carbon\Carbon::parse($semesterAktif->tanggal_mulai);
            $now = \Carbon\Carbon::now();
            if ($now->gte($start)) {
                $pertemuanKe = (int) min(16, $start->diffInWeeks($now) + 1);
            }
        }

        $class_info = [
            'name' => $kelas->mataKuliah->nama_mk,
            'code' => $kelas->mataKuliah->kode_mk,
            'section' => $kelas->section,
            'pertemuan' => $pertemuanKe,
            'topic' => 'Pertemuan Ke-' . $pertemuanKe,
            'date' => now()->locale('id')->isoFormat('dddd, D MMMM YYYY'),
            'room' => $jadwal?->ruangan ?? '-',
            'time' => $jadwal ? substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) : '-',
            'dosen_name' => $kelas->dosen->name ?? 'Dosen Belum Ditentukan',
        ];

        // Fetch students from the related KelasMataKuliah -> krs
        // Match the same way as generateQr: prefer exact kode_kelas match, fallback to first with same mata_kuliah_id
        $kelasMataKuliah = \App\Models\KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where(function ($q) use ($kelas) {
                $q->where('kode_kelas', $kelas->section)
                    ->orWhere('kode_kelas', $kelas->section . '');
            })->first();

        if (!$kelasMataKuliah) {
            $kelasMataKuliah = \App\Models\KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)->first();
        }

        // Ensure the kelas_mata_kuliah has a qr_token so QR can be generated; generate if missing
        if ($kelasMataKuliah && empty($kelasMataKuliah->qr_token)) {
            $kelasMataKuliah->qr_token = \Illuminate\Support\Str::random(40);
            // Do not enable the QR automatically when creating the token
            $kelasMataKuliah->qr_enabled = $kelasMataKuliah->qr_enabled ?? false;
            $kelasMataKuliah->save();
        }

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
                'ipk' => $m->ipk ?? null,
                'status' => 'Aktif',
            ];
        })->toArray();

        // Build a `class` array expected by the blade templates (includes QR token)
        $class = [
            'id' => $kelas->id,
            'name' => $kelas->mataKuliah->nama_mk,
            'code' => $kelas->mataKuliah->kode_mk,
            'section' => $kelas->section,
            'pertemuan' => $pertemuanKe,
            'topic' => 'Pertemuan Ke-' . $pertemuanKe,
            'date' => now()->locale('id')->isoFormat('dddd, D MMMM YYYY'),
            'room' => $jadwal?->ruangan ?? '-',
            'time' => $jadwal ? substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) : '-',
            'dosen_name' => $kelas->dosen->name ?? 'Dosen Belum Ditentukan',
            'qr_token' => $kelasMataKuliah->qr_token ?? null,
            'qr_enabled' => $kelasMataKuliah->qr_enabled ?? false,
            'qr_expires_at' => $kelasMataKuliah->qr_expires_at ?? null,
        ];

        // Determine which pertemuan we are viewing (request param, or the kelas's QR current pertemuan, otherwise the calculated pertemuanKe)
        $currentPertemuan = request()->input('pertemuan') ?? ($kelasMataKuliah->qr_current_pertemuan ?? $pertemuanKe);

        // Load presensi records for this kelas_mata_kuliah and current pertemuan (recent first)
        $presensis = collect();
        if ($kelasMataKuliah) {
            $presensiQuery = Presensi::where('kelas_mata_kuliah_id', $kelasMataKuliah->id)
                ->with(['krs.mahasiswa.user'])
                ->orderByDesc('created_at');

            // Only filter by pertemuan if the column exists in DB
            // Include both matching pertemuan AND NULL pertemuan (legacy records)
            if (Schema::hasColumn('presensis', 'pertemuan')) {
                $presensiQuery->where(function($q) use ($currentPertemuan) {
                    $q->where('pertemuan', $currentPertemuan)
                      ->orWhereNull('pertemuan');
                });
            }

            $presensis = $presensiQuery->get();
        }

        // Expose convenient variables expected by the Blade templates
        $token = $class['qr_token'] ?? null;
        $qrEnabled = (bool) ($class['qr_enabled'] ?? false);
        $qrExpires = $class['qr_expires_at'] ?? null;

        if (request()->ajax()) {
            return view('page.dosen.kelas.partials.absensi-content', compact('class_info', 'students', 'id', 'class', 'presensis', 'token', 'qrEnabled', 'qrExpires'))->with('is_modal', true);
        }

        return view('page.dosen.kelas.absensi', compact('class_info', 'students', 'id', 'class', 'presensis', 'token', 'qrEnabled', 'qrExpires'))->with('is_modal', false);
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
        if ($kelasMataKuliah) {
            $attendanceQuery = Presensi::where('kelas_mata_kuliah_id', $kelasMataKuliah->id);
            
            // Filter by pertemuan (meeting number) if column exists
            if (Schema::hasColumn('presensis', 'pertemuan')) {
                $attendanceQuery->where(function($q) use ($pertemuan) {
                    $q->where('pertemuan', $pertemuan)
                      ->orWhereNull('pertemuan');
                });
            }
            
            $attendanceRecords = $attendanceQuery->get()->keyBy('mahasiswa_id');
        }

        $students = $krsCollection->map(function ($krs) use ($attendanceRecords) {
            $m = $krs->mahasiswa;
            $userName = $m->user->name ?? ($m->nama ?? 'Mahasiswa');
            
            // Check if student has attendance record
            $hasAttended = $attendanceRecords->has($m->id);
            $attendanceTime = null;
            
            if ($hasAttended) {
                $attendanceRecord = $attendanceRecords->get($m->id);
                $attendanceTime = $attendanceRecord->waktu 
                    ? \Carbon\Carbon::parse($attendanceRecord->waktu)->format('H:i')
                    : null;
            }
            
            return [
                'name' => $userName,
                'nim' => $m->nim ?? null,
                'prodi' => $m->prodi ?? null,
                'status' => 'Aktif',
                'attendance_status' => $hasAttended ? 'hadir' : 'belum_absen',
                'attendance_time' => $attendanceTime,
            ];
        })->toArray();

        // Get tugas (assignments) for this mata kuliah and pertemuan
        $tasks = \App\Models\Tugas::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where('pertemuan', $pertemuan)
            ->latest()
            ->get();

        return view('page.dosen.kelas.lihat-rincian', compact('kelas', 'meeting', 'students', 'tasks'));
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
}
