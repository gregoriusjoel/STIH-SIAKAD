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
use Illuminate\Support\Facades\Storage;
use App\Models\Presensi;
use App\Models\Pertemuan;
use App\Models\Dosen;
use App\Models\DokumenKelas;
use App\Services\ActiveMeetingResolver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

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
                'is_rescheduled' => false,
                'url' => route('dosen.kelas.detail', $jadwal->kelas_id),
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

        $mappedKmk = $kelasMataKuliahSchedules->map(function ($kmk) use ($dosen) {
            $hari = $kmk->hari;
            $jam_mulai = $kmk->jam_mulai;
            $jam_selesai = $kmk->jam_selesai;
            $ruang = $kmk->ruang ?? '-';
            $section = $kmk->kode_kelas ?? $kmk->section ?? '-';

            // Find matching kelas ID for routing
            $kelas = \App\Models\Kelas::where('mata_kuliah_id', $kmk->mata_kuliah_id)
                ->where('section', $section)
                ->first();
            if (!$kelas) {
                $kelas = \App\Models\Kelas::where('mata_kuliah_id', $kmk->mata_kuliah_id)->first();
            }
            $targetId = $kelas?->id ?? $kmk->id;

            // Check if there is an approved reschedule for this week
            $weekStart = \Carbon\Carbon::today()->startOfWeek(\Carbon\Carbon::MONDAY);
            $reschedule = \App\Models\KelasReschedule::where('kelas_mata_kuliah_id', $kmk->id)
                ->where('week_start', $weekStart->toDateString())
                ->whereIn('status', ['approved', 'room_assigned'])
                ->first();

            if ($reschedule) {
                $hari = $reschedule->new_hari;
                $jam_mulai = $reschedule->new_jam_mulai;
                $jam_selesai = $reschedule->new_jam_selesai;
                $ruang = $reschedule->new_ruang ?: $ruang;
                $section = $reschedule->new_kelas ?: $section;
            }

            return [
                'title' => $kmk->mataKuliah->nama_mk,
                'code' => $kmk->mataKuliah->kode_mk,
                'section' => $section,
                'day' => $hari,
                'time' => substr($jam_mulai, 0, 5) . ' - ' . substr($jam_selesai, 0, 5),
                'class' => $section,
                'room' => $ruang,
                'subject' => $kmk->mataKuliah->nama_mk,
                'sks' => $kmk->mataKuliah->sks ?? 0,
                'raw_time' => $jam_mulai,
                'is_rescheduled' => $reschedule ? true : false,
                'url' => route('dosen.kelas.detail', $targetId),
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
                'is_rescheduled' => $s['is_rescheduled'] ?? false,
                'url' => $s['url'] ?? '#',
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
                'is_rescheduled' => $s['is_rescheduled'] ?? false,
                'url' => $s['url'] ?? '#',
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
                'url' => route('dosen.kelas.detail', $jadwal->kelas_id),
            ];
        });

        $mappedKmk = $kelasMataKuliahSchedules->map(function ($kmk) {
            $section = $kmk->kode_kelas ?? $kmk->section ?? '-';
            $kelas = \App\Models\Kelas::where('mata_kuliah_id', $kmk->mata_kuliah_id)
                ->where('section', $section)
                ->first();
            if (!$kelas) {
                $kelas = \App\Models\Kelas::where('mata_kuliah_id', $kmk->mata_kuliah_id)->first();
            }
            $targetId = $kelas?->id ?? $kmk->id;

            return [
                'title' => $kmk->mataKuliah->nama_mk,
                'code' => $kmk->mataKuliah->kode_mk,
                'section' => $section,
                'day' => $kmk->hari,
                'time' => substr($kmk->jam_mulai, 0, 5) . ' - ' . substr($kmk->jam_selesai, 0, 5),
                'class' => $kmk->kode_kelas ?? '-',
                'room' => $kmk->ruang ?? '-',
                'subject' => $kmk->mataKuliah->nama_mk,
                'color' => 'bg-purple-100 text-purple-700',
                'url' => route('dosen.kelas.detail', $targetId ?? $kmk->id),
            ];
        });

        // Unique merge based on day + time + code
        // Convert to plain arrays first to avoid Eloquent model behavior when items are arrays
        $merged = collect(array_merge($mappedJadwals->toArray(), $mappedKmk->toArray()));
        $allSchedules = $merged->unique(function ($item) {
            return ($item['day'] ?? '') . ($item['time'] ?? '') . ($item['code'] ?? '');
        })->values()->toArray();

        // Generate 16 meeting dates from perkuliahan period start (Kalender Akademik)
        $periodServiceDash = app(\App\Services\AcademicPeriodService::class);
        $perkuliahanRangeDash = $periodServiceDash->getDateRange(\App\Services\AcademicPeriodService::TYPE_PERKULIAHAN);
        $semesterAktif = \App\Models\Semester::where('status', 'aktif')->first()
            ?? \App\Models\Semester::where('is_active', true)->first()
            ?? \App\Models\Semester::latest()->first();

        $startDate = $perkuliahanRangeDash
            ? $perkuliahanRangeDash['start']
            : ($semesterAktif?->tanggal_mulai ? \Carbon\Carbon::parse($semesterAktif->tanggal_mulai) : null);

        $meetingDates = [];
        if ($startDate) {
            // Generate dates for each schedule based on their day
            foreach ($allSchedules as $schedule) {
                $dayName = $schedule['day'] ?? null;
                if (!$dayName) continue;
                
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
                
                // Find the first occurrence of this day from perkuliahan start date
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
        try {
            $periodService = app(\App\Services\AcademicPeriodService::class);
            $perkuliahanRange = $periodService->getDateRange(\App\Services\AcademicPeriodService::TYPE_PERKULIAHAN);

            $start = $perkuliahanRange
                ? $perkuliahanRange['start']
                : (\App\Models\Semester::where('status', 'aktif')->first()?->tanggal_mulai
                    ? Carbon::parse(\App\Models\Semester::where('status', 'aktif')->first()->tanggal_mulai)
                    : null);

            if ($start) {
                $now = Carbon::now();
                if ($now->gte($start)) {
                    $weeks = $start->diffInWeeks($now);
                    return min(100, round(($weeks / 16) * 100));
                }
            }
        } catch (\Exception $e) {}
        return 0;
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
            },
            'silabus',
            'rps'
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

        // --- Dates from Kalender Akademik (AcademicPeriodService) ---
        $periodService = app(\App\Services\AcademicPeriodService::class);
        $perkuliahanRange = $periodService->getDateRange(\App\Services\AcademicPeriodService::TYPE_PERKULIAHAN);
        $utsRange         = $periodService->getDateRange(\App\Services\AcademicPeriodService::TYPE_UTS);
        $uasRange         = $periodService->getDateRange(\App\Services\AcademicPeriodService::TYPE_UAS);

        // Prefer perkuliahan event start; fallback to semester.tanggal_mulai
        $perkuliahanStart = $perkuliahanRange
            ? $perkuliahanRange['start']
            : ($semesterAktif?->tanggal_mulai ? Carbon::parse($semesterAktif->tanggal_mulai) : null);

        // Recalculate progress using resolved perkuliahan start
        if ($perkuliahanStart) {
            $now = Carbon::now();
            if ($now->gte($perkuliahanStart)) {
                $pertemuanKe = (int) min(16, $perkuliahanStart->diffInWeeks($now) + 1);
            }
        }

        // Determine class weekday (hari) from KelasMataKuliah or Jadwal
        $hari = $kelasMataKuliah?->hari ?? $jadwal?->hari;
        $dayMap = ['Minggu' => 0, 'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6];
        $targetDay = isset($hari) ? ($dayMap[$hari] ?? null) : null;

        // Find first occurrence of class weekday on or after perkuliahan start
        $firstOccurrence = null;
        if ($perkuliahanStart && $targetDay !== null) {
            $firstOccurrence = $perkuliahanStart->copy();
            while ($firstOccurrence->dayOfWeek !== $targetDay) {
                $firstOccurrence->addDay();
            }
        }

        // Load existing Pertemuan records that have a stored tanggal
        $savedPertemuanDates = $kelasMataKuliah
            ? \App\Models\Pertemuan::where('kelas_mata_kuliah_id', $kelasMataKuliah->id)
                ->whereNotNull('tanggal')
                ->get()
                ->keyBy(fn ($p) => ($p->tipe_pertemuan ?? 'kuliah') . ':' . $p->nomor_pertemuan)
            : collect();

        // Build ordered meeting slots via ActiveMeetingResolver
        $resolver = app(ActiveMeetingResolver::class);
        $meetingSlots = $resolver->buildMeetingSlots();

        // Build pertemuanDatesMap: 'tipe:nomor' => 'Y-m-d' (or null)
        // Priority: 1. actual tanggal from pertemuan table
        //           2. UTS/UAS start from academic_events (kalender akademik)
        //           3. calculated from first class-weekday + week offset
        $pertemuanDatesMap = [];
        foreach ($meetingSlots as $slot) {
            $key = $slot['tipe'] . ':' . $slot['nomor'];

            if ($savedPertemuanDates->has($key)) {
                $pertemuanDatesMap[$key] = $savedPertemuanDates->get($key)->tanggal->format('Y-m-d');
                continue;
            }
            if ($slot['tipe'] === 'uts' && $utsRange) {
                $pertemuanDatesMap[$key] = $utsRange['start']->format('Y-m-d');
                continue;
            }
            if ($slot['tipe'] === 'uas' && $uasRange) {
                $pertemuanDatesMap[$key] = $uasRange['start']->format('Y-m-d');
                continue;
            }
            if ($slot['tipe'] === 'kuliah' && $firstOccurrence) {
                // slot number gives correct week offset including UTS/UAS gaps
                $pertemuanDatesMap[$key] = $firstOccurrence->copy()->addWeeks($slot['slot'] - 1)->format('Y-m-d');
                continue;
            }
            $pertemuanDatesMap[$key] = null;
        }

        $class_info = [
            'name' => $kelas->mataKuliah->nama_mk,
            'code' => $kelas->mataKuliah->kode_mk,
            'sks' => $kelas->mataKuliah->sks,
            'semester' => $kelas->mataKuliah->semester,
            'section' => $kelas->section,
            'day' => $hari ?? $jadwal?->hari ?? '-',
            'hari' => $hari,
            'time' => ($kelasMataKuliah?->jam_mulai && $kelasMataKuliah?->jam_selesai)
                ? substr($kelasMataKuliah->jam_mulai, 0, 5) . ' - ' . substr($kelasMataKuliah->jam_selesai, 0, 5)
                : ($jadwal ? substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) : '-'),
            'room' => $kelasMataKuliah?->ruangan?->kode_ruangan ?? $kelasMataKuliah?->ruang ?? $jadwal?->ruangan ?? '-',
            'students_count' => count($students),
            'progress' => $pertemuanKe,
            'total_pertemuan' => 16,
            'semester_start_date' => $perkuliahanStart?->format('Y-m-d'),
        ];

        if (request()->ajax()) {
            return view('page.dosen.kelas.partials.detail-content', compact('class_info', 'students', 'id', 'kelas', 'meetingSlots', 'pertemuanDatesMap'))->with('is_modal', true);
        }

        return view('page.dosen.kelas.detail', compact('class_info', 'students', 'id', 'kelas', 'meetingSlots', 'pertemuanDatesMap'))->with('is_modal', false);
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

        // ✅ Get pertemuan number and type from request
        $pertemuanNo = request()->input('pertemuan', 1);
        $tipePertemuan = request()->input('tipe_pertemuan', Pertemuan::TIPE_KULIAH);
        
        // ✅ Find or create Pertemuan record with tipe_pertemuan
        $resolver = app(ActiveMeetingResolver::class);
        $pertemuan = $resolver->findOrCreatePertemuan($kelasMataKuliah, $tipePertemuan, $pertemuanNo);

        // ✅ Generate QR token (but don't activate yet)
        $pertemuan->generateQrToken(5);

        \Log::info('Generated QR token', ['pertemuan_id' => $pertemuan->id, 'nomor' => $pertemuanNo, 'qr_token' => $pertemuan->qr_token]);

        $debug = [
            'pertemuan_id' => $pertemuan->id,
            'nomor_pertemuan' => $pertemuanNo,
            'qr_token' => $pertemuan->qr_token,
        ];

        return back()->with(['success' => 'QR dibuat dan diaktifkan.', 'debug_info' => $debug]);
    }

    public function meetingDetail(Request $request, $id, $pertemuan)
    {
        $kelas = Kelas::with([
            'mataKuliah',
            'jadwals' => function ($q) {
                $q->where('status', 'active');
            }
        ])->findOrFail($id);

        $jadwal = $kelas->jadwals->first();

        // Resolve tipe_pertemuan and nomor from route parameter
        // Supports: "kuliah:3", "uts:1", "uas:1" or plain integer (backward compat → slot number)
        $resolver = app(ActiveMeetingResolver::class);
        if (str_contains($pertemuan, ':')) {
            [$tipe, $nomor] = explode(':', $pertemuan, 2);
            $nomor = (int) $nomor;
        } else {
            // Legacy: plain integer = slot number; map to tipe:nomor
            $mapped = $resolver->slotToTipeNomor((int) $pertemuan);
            $tipe = $mapped['tipe'];
            $nomor = $mapped['nomor'];
        }

        // Calculate slot number for ordering/display
        $slotNumber = $resolver->tipeNomorToSlot($tipe, $nomor);

        // Build label based on tipe
        $meetingLabel = $resolver->labelFor($tipe, $nomor);

        // --- Resolve meeting date (same priority as detail() page) ---
        // Early fetch of KelasMataKuliah to get hari (day-of-week) for date calculation
        $kmkForDate = \App\Models\KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where('kode_kelas', $kelas->section)
            ->where('dosen_id', $kelas->dosen_id)
            ->first()
            ?? \App\Models\KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)->first();

        // 1. Stored tanggal from Pertemuan record
        $storedPertemuan = $kmkForDate
            ? \App\Models\Pertemuan::where('kelas_mata_kuliah_id', $kmkForDate->id)
                ->where('nomor_pertemuan', $nomor)
                ->where('tipe_pertemuan', $tipe)
                ->whereNotNull('tanggal')
                ->first()
            : null;

        $periodService = app(\App\Services\AcademicPeriodService::class);
        $meetingDate = '-';

        if ($storedPertemuan?->tanggal) {
            // Priority 1: actual date stored in pertemuan record
            $meetingDate = $storedPertemuan->tanggal->locale('id')->isoFormat('D MMMM YYYY');
        } elseif ($tipe === 'uts') {
            // Priority 2a: UTS date from Kalender Akademik
            $range = $periodService->getDateRange(\App\Services\AcademicPeriodService::TYPE_UTS);
            if ($range) $meetingDate = $range['start']->locale('id')->isoFormat('D MMMM YYYY');
        } elseif ($tipe === 'uas') {
            // Priority 2b: UAS date from Kalender Akademik
            $range = $periodService->getDateRange(\App\Services\AcademicPeriodService::TYPE_UAS);
            if ($range) $meetingDate = $range['start']->locale('id')->isoFormat('D MMMM YYYY');
        } else {
            // Priority 3: perkuliahan period start + day-of-week + week offset
            $perkuliahanRange = $periodService->getDateRange(\App\Services\AcademicPeriodService::TYPE_PERKULIAHAN);
            $semesterAktif = \App\Models\Semester::where('status', 'aktif')->first()
                ?? \App\Models\Semester::where('is_active', true)->first()
                ?? \App\Models\Semester::latest()->first();
            $start = $perkuliahanRange
                ? $perkuliahanRange['start']
                : ($semesterAktif?->tanggal_mulai ? Carbon::parse($semesterAktif->tanggal_mulai) : null);

            if ($start) {
                $hari = $kmkForDate?->hari ?? $jadwal?->hari;
                $dayMap = ['Minggu' => 0, 'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6];
                $targetDay = isset($hari) ? ($dayMap[$hari] ?? null) : null;
                if ($targetDay !== null) {
                    $firstOccurrence = $start->copy();
                    while ($firstOccurrence->dayOfWeek !== $targetDay) { $firstOccurrence->addDay(); }
                    $meetingDate = $firstOccurrence->addWeeks($slotNumber - 1)->locale('id')->isoFormat('D MMMM YYYY');
                } else {
                    $meetingDate = $start->copy()->addWeeks($slotNumber - 1)->locale('id')->isoFormat('D MMMM YYYY');
                }
            }
        }

        $meeting = [
            'no' => $slotNumber,
            'nomor_pertemuan' => $nomor,
            'tipe_pertemuan' => $tipe,
            'label' => $meetingLabel,
            'date' => $meetingDate,
            'time' => ($kmkForDate?->jam_mulai && $kmkForDate?->jam_selesai)
                ? substr($kmkForDate->jam_mulai, 0, 5) . ' - ' . substr($kmkForDate->jam_selesai, 0, 5)
                : ($jadwal ? substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) : '-'),
            'room' => $kmkForDate?->ruangan?->kode_ruangan ?? $kmkForDate?->ruang ?? $jadwal?->ruangan ?? '-',
            'is_exam' => in_array($tipe, [Pertemuan::TIPE_UTS, Pertemuan::TIPE_UAS]),
        ];

        // Fetch students
        $kelasMataKuliah = \App\Models\KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where('kode_kelas', $kelas->section)
            ->where('dosen_id', $kelas->dosen_id)
            ->first();
        
        // Fallback: if not found by all criteria, try with just mata_kuliah_id and dosen_id
        if (!$kelasMataKuliah) {
            $kelasMataKuliah = \App\Models\KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
                ->where('dosen_id', $kelas->dosen_id)
                ->first();
        }

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
            
            // Get records for current meeting only (using slot number for backward compat with presensis.pertemuan)
            $currentMeetingQuery = clone $attendanceQuery;
            if (Schema::hasColumn('presensis', 'pertemuan')) {
                $currentMeetingQuery->where('pertemuan', $slotNumber);
            }
            $attendanceRecords = $currentMeetingQuery->get()->keyBy('mahasiswa_id');

            // Calculate total attendance per student
            $totalAttendanceCounts = Presensi::where('kelas_mata_kuliah_id', $kelasMataKuliah->id)
                ->where('status', 'hadir')
                ->selectRaw('mahasiswa_id, count(*) as total')
                ->groupBy('mahasiswa_id')
                ->pluck('total', 'mahasiswa_id');
        }

        $students = $krsCollection->map(function ($krs) use ($attendanceRecords, $totalAttendanceCounts, $kelas, $kelasMataKuliah) {
            $m = $krs->mahasiswa;
            $userName = $m->user->name ?? ($m->nama ?? 'Mahasiswa');
            $isInternship = (bool) $krs->is_internship_conversion;

            // Magang conversion students are automatically HADIR — skip Presensi lookup
            if ($isInternship) {
                $semester = $m->semester ?? $m->getCurrentSemester();
                return [
                    'id' => $m->id,
                    'name' => $userName,
                    'nim' => $m->nim ?? null,
                    'prodi' => $m->prodi ?? null,
                    'semester' => $semester,
                    'status_mahasiswa' => $m->status ?? 'Aktif',
                    'attendance_status' => 'hadir',
                    'attendance_time' => null,
                    'total_attendance' => $kelasMataKuliah->meeting_count ?? 0,
                    'krs_id' => $krs->id,
                    'kelas_mata_kuliah_id' => $kelasMataKuliah->id ?? null,
                    'presence_mode' => 'internship',
                    'distance_meters' => null,
                    'reason_category' => null,
                    'reason_detail' => null,
                    'is_internship' => true,
                ];
            }

            // Normal student — check if student has attendance record for this meeting
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
                'kelas_mata_kuliah_id' => $kelasMataKuliah->id ?? null,
                // Location-based attendance data
                'presence_mode' => $attendanceRecord->presence_mode ?? null,
                'distance_meters' => $attendanceRecord->distance_meters ?? null,
                'reason_category' => $attendanceRecord->reason_category ?? null,
                'reason_detail' => $attendanceRecord->reason_detail ?? null,
                'is_internship' => false,
            ];
        })->toArray();

        // Get tugas (assignments) for this mata kuliah and pertemuan
        $tasks = \App\Models\Tugas::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where('pertemuan', $slotNumber)
            ->latest()
            ->get();

        // Ensure the kelas_mata_kuliah has a qr_token so QR can be generated; generate if missing



        // ✅ Read QR from pertemuans table (using tipe_pertemuan)
        $pertemuanRecord = null;
        $token = null;
        $qrEnabled = false;
        $qrExpires = null;

        if ($kelasMataKuliah) {
            $pertemuanRecord = Pertemuan::where('kelas_mata_kuliah_id', $kelasMataKuliah->id)
                ->where('nomor_pertemuan', $nomor)
                ->where('tipe_pertemuan', $tipe)
                ->first();
            
            if ($pertemuanRecord) {
                $token = $pertemuanRecord->qr_token;
                $qrEnabled = (bool) $pertemuanRecord->qr_enabled;
                $qrExpires = $pertemuanRecord->qr_expires_at;
            }
        }

        // Get materials for this mata kuliah and pertemuan (slot-based for backward compat)
        $materis = \App\Models\Materi::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where('pertemuan', $slotNumber)
            ->with('dosen')
            ->latest()
            ->get();

        // Build meeting slot list for the type dropdown selector
        $meetingSlots = $resolver->buildMeetingSlots();

        if ($request->has('reload_attendance') && $request->ajax()) {
            $html = view('page.dosen.kelas.partials.student_attendance_table', compact('students'))->render();
            $attendedCount = collect($students)->where('attendance_status', 'hadir')->count();
            $totalStudents = count($students);
            
            return response()->json([
                'html' => $html,
                'attended' => $attendedCount,
                'total' => $totalStudents
            ]);
        }

        return view('page.dosen.kelas.lihat-rincian', compact('kelas', 'meeting', 'students', 'tasks', 'materis', 'token', 'qrEnabled', 'qrExpires', 'id', 'pertemuanRecord', 'kelasMataKuliah', 'meetingSlots'));
    }

    public function updateAttendance(Request $request, $id, $pertemuan)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'status' => 'required|in:hadir,izin,sakit,alpa',
        ]);

        $kelas = Kelas::findOrFail($id);
        
        // Resolve tipe + nomor → slot number for storage in presensis.pertemuan
        $resolver = app(ActiveMeetingResolver::class);
        if (str_contains((string) $pertemuan, ':')) {
            [$tipe, $nomor] = explode(':', $pertemuan, 2);
            $slotNumber = $resolver->tipeNomorToSlot($tipe, (int) $nomor);
        } else {
            $slotNumber = (int) $pertemuan;
        }

        // Find or create KelasMataKuliah
        $kelasMataKuliah = KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where('kode_kelas', $kelas->section)
            ->first();

        if (!$kelasMataKuliah) {
             return response()->json(['success' => false, 'message' => 'Kelas Mata Kuliah not found'], 404);
        }

        $studentId = $request->mahasiswa_id;
        $status = $request->status;

        // Block manual attendance change for internship conversion students (auto-hadir)
        $isInternshipKrs = \App\Models\Krs::where('mahasiswa_id', $studentId)
            ->where(function($q) use ($kelas, $kelasMataKuliah) {
                $q->where('kelas_id', $kelas->id)
                  ->orWhere('kelas_mata_kuliah_id', $kelasMataKuliah->id);
            })
            ->where('is_internship_conversion', true)
            ->exists();

        if ($isInternshipKrs) {
            return response()->json([
                'success' => false,
                'message' => 'Kehadiran mahasiswa magang otomatis tercatat HADIR dan tidak dapat diubah.',
            ], 422);
        }

        // Find existing record or create new (uses slot number for backward compat)
        $attendance = Presensi::updateOrCreate(
            [
                'kelas_mata_kuliah_id' => $kelasMataKuliah->id,
                'mahasiswa_id' => $studentId,
                'pertemuan' => $slotNumber,
            ],
            [
                'status' => $status,
                'tanggal' => now()->toDateString(),
                'waktu' => now(),
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
        // Resolve slot number for backward compat
        $resolver = app(ActiveMeetingResolver::class);
        if (str_contains((string) $pertemuan, ':')) {
            [$tipe, $nomor] = explode(':', $pertemuan, 2);
            $slotNumber = $resolver->tipeNomorToSlot($tipe, (int) $nomor);
            $meetingLabel = $resolver->labelFor($tipe, (int) $nomor);
        } else {
            $slotNumber = (int) $pertemuan;
            $mapped = $resolver->slotToTipeNomor($slotNumber);
            $meetingLabel = $resolver->labelFor($mapped['tipe'], $mapped['nomor']);
        }

        $kelas = Kelas::with([
            'mataKuliah',
            'jadwals' => function ($q) {
                $q->where('status', 'active');
            }
        ])->findOrFail($id);

        $jadwal = $kelas->jadwals->first();

        // Re-calculate meeting date based on slot number
        $semesterAktif = \App\Models\Semester::where('status', 'aktif')->first()
            ?? \App\Models\Semester::where('is_active', true)->first()
            ?? \App\Models\Semester::latest()->first();

        $meetingDate = '-';
        if ($semesterAktif && $semesterAktif->tanggal_mulai) {
            $start = \Carbon\Carbon::parse($semesterAktif->tanggal_mulai);
            $meetingDate = $start->copy()->addDays(($slotNumber - 1) * 7)->locale('id')->isoFormat('D MMMM YYYY');
        }

        $meeting = [
            'no' => $slotNumber,
            'label' => $meetingLabel,
            'date' => $meetingDate,
            'time' => $jadwal ? substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) : '-',
            'room' => $jadwal?->ruangan ?? '-',
        ];

        // Get materials for this mata kuliah and pertemuan (slot-based)
        $materis = \App\Models\Materi::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where('pertemuan', $slotNumber)
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

        // ✅ Get pertemuan number from request
        $pertemuanNo = $request->input('pertemuan', 1);
        
        // ✅ Find or create Pertemuan record
        $pertemuan = Pertemuan::firstOrCreate(
            [
                'kelas_mata_kuliah_id' => $kelasMataKuliah->id,
                'nomor_pertemuan' => $pertemuanNo
            ],
            [
                'topik' => 'Pertemuan ' . $pertemuanNo,
                'status' => 'scheduled'
            ]
        );

        // ✅ Activate QR on this specific pertemuan
        $pertemuan->activateQr(5);

        \Log::info('Activated QR token', ['pertemuan_id' => $pertemuan->id, 'nomor' => $pertemuanNo, 'qr_token' => $pertemuan->qr_token]);

        return back()->with('success', 'QR ditampilkan untuk 5 menit.');
    }

    /**
     * Manually deactivate the QR for the pertemuan.
     * ✅ NOW: Deactivate QR in pertemuans table
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
            return back()->with('error', 'Tidak dapat menemukan record kelas mata kuliah.');
        }

        // ✅ Disable all active QR for this kelas
        Pertemuan::where('kelas_mata_kuliah_id', $kelasMataKuliah->id)
            ->where('qr_enabled', true)
            ->update([
                'qr_enabled' => false,
                'qr_expires_at' => null,
            ]);

        \Log::info('QR disabled for kelas', ['kelas_mk_id' => $kelasMataKuliah->id]);

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
                    'is_internship' => (bool) $krs->is_internship_conversion,
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
        
        $has_published_grades = \App\Models\Nilai::where('kelas_id', $id)
            ->where('is_published', true)
            ->exists();
        
        // ── Academic period status for UTS/UAS gating ──
        $periodService = app(\App\Services\AcademicPeriodService::class);
        $utsStatus = $periodService->getStatus(\App\Services\AcademicPeriodService::TYPE_UTS);
        $uasStatus = $periodService->getStatus(\App\Services\AcademicPeriodService::TYPE_UAS);
        $periodStatuses = [
            'uts' => $utsStatus,
            'uas' => $uasStatus,
        ];

        return view('page.dosen.input-nilai.kelas', compact('class_info', 'students', 'bobot', 'has_published_grades', 'periodStatuses'));
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
        
        $isAutoSave = $request->boolean('is_auto_save', false);
        
        // ── Period-based gating for UTS/UAS components ──
        $periodService = app(\App\Services\AcademicPeriodService::class);
        $utsOpen = $periodService->isActive(\App\Services\AcademicPeriodService::TYPE_UTS);
        $uasOpen = $periodService->isActive(\App\Services\AcademicPeriodService::TYPE_UAS);
        $periodWarnings = [];

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
                
                // UTS/UAS: only update if their period is open, or keep existing value
                if ($utsOpen || !$nilai->exists || $nilai->nilai_uts == 0) {
                    $nilai->nilai_uts = $nilaiData['nilai_uts'] ?? 0;
                    if (!$utsOpen && ($nilaiData['nilai_uts'] ?? 0) > 0) {
                        $periodWarnings['uts'] = true;
                    }
                }
                if ($uasOpen || !$nilai->exists || $nilai->nilai_uas == 0) {
                    $nilai->nilai_uas = $nilaiData['nilai_uas'] ?? 0;
                    if (!$uasOpen && ($nilaiData['nilai_uas'] ?? 0) > 0) {
                        $periodWarnings['uas'] = true;
                    }
                }
                
                // Auto calculate final grade and bobot
                $nilai->autoCalculateGrade($bobot);
                
                if (!$isAutoSave) {
                    // Final publish: set as published immediately
                    $nilai->is_published = true;
                    $nilai->published_at = now();
                    $nilai->published_by = Auth::id();
                } else if ($nilai->is_published === null) {
                    // For initial auto-saves, explicitly set to false
                    $nilai->is_published = false;
                }
                
                $nilai->save();
                $savedCount++;
            }

            if (!$isAutoSave && $savedCount > 0) {
                \App\Models\AuditLog::log('grades.published', $kelas, [
                    'saved_count' => $savedCount,
                    'mata_kuliah' => $kelas->mataKuliah->nama_mk
                ]);
            }
            
            \DB::commit();
            
            $msg = $isAutoSave ? "Autosave berhasil." : "Berhasil menyimpan {$savedCount} nilai mahasiswa.";
            if (!empty($periodWarnings)) {
                $parts = [];
                if (isset($periodWarnings['uts'])) $parts[] = 'UTS';
                if (isset($periodWarnings['uas'])) $parts[] = 'UAS';
                $msg .= ' (Catatan: Periode ' . implode(' & ', $parts) . ' belum dibuka di Kalender Akademik)';
            }

            return response()->json([
                'success' => true,
                'message' => $msg,
                'period_warnings' => array_keys($periodWarnings),
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
     * Menarik nilai mahasiswa sehingga tidak bisa dilihat mahasiswa
     */
    public function tarikNilai($id)
    {
        $kelas = Kelas::with('dosen')->findOrFail($id);
        
        // Check ownership
        if (!$kelas->dosen || $kelas->dosen->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke kelas ini.'
            ], 403);
        }
        
        \DB::beginTransaction();
        try {
            $updatedCount = \App\Models\Nilai::where('kelas_id', $id)
                ->where('is_published', true)
                ->update([
                    'is_published' => false,
                    'published_at' => null,
                    'published_by' => null,
                ]);
            
            \DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Berhasil menarik nilai ({$updatedCount} data). Nilai disembunyikan dari mahasiswa.",
            ]);
            
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error unpublishing nilai: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menarik nilai: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download CSV template pre-filled with enrolled students for nilai import.
     */
    public function downloadNilaiTemplate($id)
    {
        $kelas = Kelas::with(['mataKuliah', 'dosen'])->findOrFail($id);

        if (!$kelas->dosen || $kelas->dosen->user_id != Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        $bobot = \App\Models\BobotPenilaian::where('kelas_id', $id)->first();

        $students = \App\Models\Krs::where('kelas_id', $id)
            ->whereIn('status', ['approved', 'disetujui'])
            ->with(['mahasiswa.user', 'nilai'])
            ->get();

        $filename = 'template_nilai_' . str_replace(' ', '_', $kelas->mataKuliah->kode_mk) . '_' . $kelas->section . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($students, $bobot) {
            $file = fopen('php://output', 'w');
            // BOM for Excel UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['NIM', 'Nama Mahasiswa', 'Partisipatif', 'Proyek', 'Quiz', 'Tugas', 'UTS', 'UAS']);

            foreach ($students as $krs) {
                $m = $krs->mahasiswa;
                $n = $krs->nilai;
                fputcsv($file, [
                    $m->nim ?? '',
                    $m->user->name ?? $m->nama ?? '',
                    $n->nilai_partisipatif ?? '',
                    $n->nilai_proyek ?? '',
                    $n->nilai_quiz ?? '',
                    $n->nilai_tugas ?? '',
                    $n->nilai_uts ?? '',
                    $n->nilai_uas ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }

    /**
     * Import nilai from CSV file.
     */
    public function importNilai(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $kelas = Kelas::with(['mataKuliah', 'dosen'])->findOrFail($id);

        if (!$kelas->dosen || $kelas->dosen->user_id != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $bobot = \App\Models\BobotPenilaian::where('kelas_id', $id)->first();
        if (!$bobot || !$bobot->is_locked) {
            return response()->json(['success' => false, 'message' => 'Bobot penilaian belum dikunci.'], 422);
        }

        // Build NIM → krs_id lookup
        $krsRecords = \App\Models\Krs::where('kelas_id', $id)
            ->whereIn('status', ['approved', 'disetujui'])
            ->with('mahasiswa')
            ->get();

        $nimToKrs = [];
        foreach ($krsRecords as $krs) {
            $nim = $krs->mahasiswa->nim ?? null;
            if ($nim) {
                $nimToKrs[$nim] = $krs;
            }
        }

        // Period gating
        $periodService = app(\App\Services\AcademicPeriodService::class);
        $utsOpen = $periodService->isActive(\App\Services\AcademicPeriodService::TYPE_UTS);
        $uasOpen = $periodService->isActive(\App\Services\AcademicPeriodService::TYPE_UAS);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        if (!$handle) {
            return response()->json(['success' => false, 'message' => 'Gagal membuka file.'], 422);
        }

        // Detect delimiter
        $firstLine = fgets($handle);
        rewind($handle);
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

        $header = null;
        $updated = 0;
        $skipped = 0;
        $errors = [];
        $rowNum = 0;
        $importedStudents = [];

        $nilaiFields = ['partisipatif', 'proyek', 'quiz', 'tugas', 'uts', 'uas'];

        \DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                $rowNum++;

                if (!$header) {
                    // Clean header
                    $header = array_map(function ($h) {
                        $h = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $h);
                        return strtolower(trim($h));
                    }, $row);
                    continue;
                }

                $data = [];
                foreach ($header as $i => $key) {
                    $data[$key] = isset($row[$i]) ? trim($row[$i]) : null;
                }

                $nim = $data['nim'] ?? null;
                if (!$nim || !isset($nimToKrs[$nim])) {
                    $skipped++;
                    if ($nim) {
                        $errors[] = "Baris {$rowNum}: NIM '{$nim}' tidak ditemukan di kelas ini.";
                    }
                    continue;
                }

                $krs = $nimToKrs[$nim];
                $nilai = \App\Models\Nilai::firstOrNew([
                    'krs_id' => $krs->id,
                    'kelas_id' => $id,
                ]);

                // Map CSV columns to nilai fields
                $columnMap = [
                    'partisipatif' => 'nilai_partisipatif',
                    'proyek' => 'nilai_proyek',
                    'quiz' => 'nilai_quiz',
                    'tugas' => 'nilai_tugas',
                    'uts' => 'nilai_uts',
                    'uas' => 'nilai_uas',
                ];

                $periodWarnings = [];
                foreach ($columnMap as $csvKey => $dbField) {
                    $val = $data[$csvKey] ?? null;
                    if ($val === null || $val === '') continue;

                    $numVal = floatval(str_replace(',', '.', $val));
                    if ($numVal < 0) $numVal = 0;
                    if ($numVal > 100) $numVal = 100;

                    // UTS/UAS gating
                    if ($dbField === 'nilai_uts' && !$utsOpen && $nilai->exists && $nilai->nilai_uts > 0) {
                        $periodWarnings[] = 'UTS';
                        continue;
                    }
                    if ($dbField === 'nilai_uas' && !$uasOpen && $nilai->exists && $nilai->nilai_uas > 0) {
                        $periodWarnings[] = 'UAS';
                        continue;
                    }

                    $nilai->$dbField = $numVal;
                }

                $nilai->autoCalculateGrade($bobot);
                $nilai->save();
                $updated++;

                // Return student data for live-update in the UI
                $importedStudents[] = [
                    'krs_id' => $krs->id,
                    'nim' => $nim,
                    'nilai_partisipatif' => (float) $nilai->nilai_partisipatif,
                    'nilai_proyek' => (float) $nilai->nilai_proyek,
                    'nilai_quiz' => (float) $nilai->nilai_quiz,
                    'nilai_tugas' => (float) $nilai->nilai_tugas,
                    'nilai_uts' => (float) $nilai->nilai_uts,
                    'nilai_uas' => (float) $nilai->nilai_uas,
                    'nilai_akhir' => (float) $nilai->nilai_akhir,
                    'grade' => $nilai->grade,
                    'bobot' => (float) $nilai->bobot,
                ];
            }

            \DB::commit();
            fclose($handle);

            $msg = "Berhasil mengimpor nilai untuk {$updated} mahasiswa.";
            if ($skipped > 0) $msg .= " {$skipped} baris dilewati.";

            return response()->json([
                'success' => true,
                'message' => $msg,
                'updated' => $updated,
                'skipped' => $skipped,
                'errors' => $errors,
                'students' => $importedStudents,
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            fclose($handle);
            \Log::error('Error importing nilai: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
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

    /**
     * Upload dokumen (Silabus / RPS)
     */
    public function uploadDokumen(Request $request, $id)
    {
        try {
            $request->validate([
                'tipe_dokumen' => 'required|in:silabus,rps',
                'file' => 'required|file|mimes:pdf,doc,docx|max:10240', // Max 10MB
            ]);

            $kelas = Kelas::findOrFail($id);

            // Get dosen record for logged-in user
            $dosen = Dosen::where('user_id', auth()->user()->id)->first();
            
            if (!$dosen) {
                Log::warning('Dosen record not found for user', [
                    'user_id' => auth()->user()->id
                ]);
                return back()->withErrors(['error' => 'Data dosen tidak ditemukan.']);
            }

            // Verify that the logged-in dosen owns this class
            if ($dosen->id != $kelas->dosen_id) {
                Log::warning('Unauthorized document upload attempt', [
                    'user_id' => auth()->user()->id,
                    'dosen_id' => $dosen->id,
                    'kelas_id' => $kelas->id,
                    'kelas_dosen_id' => $kelas->dosen_id
                ]);
                return back()->withErrors(['error' => 'Anda tidak memiliki akses untuk mengupload dokumen ke kelas ini.']);
            }

            $tipeDokumen = $request->input('tipe_dokumen');
            $file = $request->file('file');

            // Check if document already exists
            $existingDoc = DokumenKelas::where('kelas_id', $kelas->id)
                ->where('tipe_dokumen', $tipeDokumen)
                ->first();

            // Generate new filename & store file
            $fileName = \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();
            $targetFolder = 'documents/dokumen-kelas';
            $resolvedDisk = \App\Helpers\FileHelper::resolveDiskForPath($targetFolder . '/' . $fileName);
            $path = $file->storeAs($targetFolder, $fileName, $resolvedDisk);

            if ($existingDoc) {
                // Hapus file lama secara aman (abaikan jika gagal, jangan gagalkan upload baru)
                if (!empty($existingDoc->path_file)) {
                    try {
                        Storage::disk(\App\Helpers\FileHelper::resolveDiskForPath($existingDoc->path_file))->delete($existingDoc->path_file);
                    } catch (\Throwable $deleteError) {
                        Log::warning('Gagal menghapus file dokumen lama', [
                            'kelas_id' => $kelas->id,
                            'tipe_dokumen' => $tipeDokumen,
                            'path_file' => $existingDoc->path_file,
                            'error' => $deleteError->getMessage(),
                        ]);
                    }
                }

                // Update record yang sudah ada
                $existingDoc->update([
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                    'uploaded_by' => auth()->user()->id,
                ]);
            } else {
                // Buat record baru jika belum ada
                DokumenKelas::create([
                    'kelas_id' => $kelas->id,
                    'tipe_dokumen' => $tipeDokumen,
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                    'uploaded_by' => auth()->user()->id,
                ]);
            }

            Log::info('Document uploaded successfully', [
                'kelas_id' => $kelas->id,
                'tipe_dokumen' => $tipeDokumen,
                'file_name' => $fileName
            ]);

            return back()->with('success', ucfirst($tipeDokumen) . ' berhasil diupload.');
        } catch (\Exception $e) {
            Log::error('Document upload failed', [
                'kelas_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengupload dokumen. Silakan coba lagi.']);
        }
    }

    /**
     * Download dokumen
     */
    public function downloadDokumen($id, $tipe)
    {
        $kelas = Kelas::findOrFail($id);
        
        $dokumen = DokumenKelas::where('kelas_id', $kelas->id)
            ->where('tipe_dokumen', $tipe)
            ->firstOrFail();

        if (!Storage::disk(\App\Helpers\FileHelper::resolveDiskForPath($dokumen->path_file))->exists($dokumen->path_file)) {
            Log::error('Document file not found on S3', [
                'kelas_id' => $id,
                'tipe'     => $tipe,
                'path'     => $dokumen->path_file,
            ]);
            return back()->with('error', 'File dokumen tidak ditemukan di cloud storage. Silakan upload ulang.');
        }

        $disk = \App\Helpers\FileHelper::resolveDiskForPath($dokumen->path_file);
        
        // Serve from storage
        if (request()->has('view')) {
            return Storage::disk($disk)->response($dokumen->path_file, $dokumen->nama_file);
        }

        return Storage::disk($disk)->download($dokumen->path_file, $dokumen->nama_file);
    }

    /**
     * Delete dokumen
     */
    public function deleteDokumen($id, $tipe)
    {
        $kelas = Kelas::findOrFail($id);

        // Get dosen record for logged-in user
        $dosen = Dosen::where('user_id', auth()->user()->id)->first();
        
        if (!$dosen) {
            return back()->withErrors(['error' => 'Data dosen tidak ditemukan.']);
        }

        // Verify that the logged-in dosen owns this class
        if ($dosen->id != $kelas->dosen_id) {
            return back()->withErrors(['error' => 'Anda tidak memiliki akses untuk menghapus dokumen dari kelas ini.']);
        }

        $dokumen = DokumenKelas::where('kelas_id', $kelas->id)
            ->where('tipe_dokumen', $tipe)
            ->first();

        if ($dokumen) {
            // Delete file from storage
            Storage::disk(\App\Helpers\FileHelper::resolveDiskForPath($dokumen->path_file))->delete($dokumen->path_file);
            // Delete database record
            $dokumen->delete();

            Log::info('Document deleted successfully', [
                'kelas_id' => $kelas->id,
                'tipe_dokumen' => $tipe
            ]);

            return back()->with('success', ucfirst($tipe) . ' berhasil dihapus.');
        }

        return back()->withErrors(['error' => 'Dokumen tidak ditemukan.']);
    }

    /**
     * Export Berita Acara Perkuliahan as PDF
     */
    public function exportBeritaAcara($id)
    {
        $kelas = Kelas::with(['mataKuliah.prodi', 'dosen.user', 'jadwals' => function ($q) {
            $q->where('status', 'active');
        }])->findOrFail($id);

        $jadwal = $kelas->jadwals->first();

        // Get time range from jadwal
        $waktu = $jadwal ? substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) : '-';

        // Get the KelasMataKuliah to find pertemuans
        $kelasMataKuliah = KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where('kode_kelas', $kelas->section)
            ->where('dosen_id', $kelas->dosen_id)
            ->first();

        // Get active semester
        $semesterAktif = Semester::where('status', 'aktif')->first()
            ?? Semester::where('is_active', true)->first()
            ?? Semester::latest()->first();

        // Count total students
        $totalStudents = \App\Models\Krs::whereIn('status', ['approved', 'disetujui'])
            ->where(function ($q) use ($kelasMataKuliah, $kelas) {
                $q->where('kelas_id', $kelas->id);
                if ($kelasMataKuliah) {
                    $q->orWhere('kelas_mata_kuliah_id', $kelasMataKuliah->id);
                }
            })->count();

        // Load pertemuans if available
        $pertemuans = collect();
        if ($kelasMataKuliah) {
            $pertemuans = Pertemuan::where('kelas_mata_kuliah_id', $kelasMataKuliah->id)
                ->orderBy('nomor_pertemuan')
                ->get()
                ->keyBy('nomor_pertemuan');
        }

        // Load materi indexed by pertemuan number
        $materis = \App\Models\Materi::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->get()
            ->groupBy('pertemuan');

        // Calculate start date from semester
        $startDate = $semesterAktif && $semesterAktif->tanggal_mulai
            ? Carbon::parse($semesterAktif->tanggal_mulai)
            : now();

        // Indonesian day names
        $dayNames = [
            0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
            4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'
        ];

        // Indonesian month names
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Build 16 rows
        $rows = [];
        for ($i = 1; $i <= 16; $i++) {
            // Rows 8 and 16 are exam rows
            if ($i === 8) {
                $rows[] = [
                    'no' => $i,
                    'is_exam' => true,
                    'exam_label' => 'Ujian Tengah Semester',
                ];
                continue;
            }
            if ($i === 16) {
                $rows[] = [
                    'no' => $i,
                    'is_exam' => true,
                    'exam_label' => 'Ujian Akhir Semester',
                ];
                continue;
            }

            // Calculate date for this meeting
            $pertemuan = $pertemuans->get($i);
            $meetingDate = null;

            if ($pertemuan && $pertemuan->tanggal) {
                $meetingDate = Carbon::parse($pertemuan->tanggal);
            } else {
                // Fallback: calculate based on semester start + weekly offset
                // But first find the first occurrence of the actual class day
                $dayMap = [
                    'Minggu' => 0, 'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3,
                    'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6
                ];
                $targetDay = $dayMap[$jadwal->hari ?? ''] ?? $startDate->dayOfWeek;
                
                $firstOccurrence = $startDate->copy();
                while ($firstOccurrence->dayOfWeek !== $targetDay) {
                    $firstOccurrence->addDay();
                }
                
                $meetingDate = $firstOccurrence->copy()->addWeeks($i - 1);
            }

            $dayName = $dayNames[$meetingDate->dayOfWeek] ?? '-';
            $formattedDate = $dayName . ', ' . $meetingDate->day . ' ' . ($monthNames[$meetingDate->month] ?? '') . ' ' . $meetingDate->year;

            // Get materi title for this meeting
            $materiList = $materis->get($i);
            $pokokBahasan = '-';
            if ($materiList && $materiList->count() > 0) {
                $pokokBahasan = $materiList->pluck('judul')->implode(', ');
            }

            // Count attendance for this meeting
            $hadir = 0;
            if ($kelasMataKuliah) {
                $hadir = Presensi::where('kelas_mata_kuliah_id', $kelasMataKuliah->id)
                    ->where('pertemuan', $i)
                    ->where('status', 'hadir')
                    ->count();
            }

            $tidakHadir = $totalStudents - $hadir;

            $rows[] = [
                'no' => $i,
                'is_exam' => false,
                'hari_tgl' => $formattedDate,
                'waktu' => $waktu,
                'pokok_bahasan' => $pokokBahasan,
                'hadir' => $hadir,
                'tidak_hadir' => $tidakHadir < 0 ? 0 : $tidakHadir,
            ];
        }

        $data = [
            'mataKuliah' => $kelas->mataKuliah->nama_mk,
            'kodeMK' => $kelas->mataKuliah->kode_mk,
            'kelas' => $kelas->section,
            'rows' => $rows,
            'semester' => $semesterAktif->nama_semester ?? 'Ganjil',
            'tahunAkademik' => $semesterAktif->tahun_ajaran ?? '2024/2025',
            'prodi' => $kelas->mataKuliah->prodi->nama_prodi ?? 'Hukum',
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.berita-acara-perkuliahan', $data)
            ->setPaper('a4', 'landscape');

        $filename = 'Berita Acara Perkuliahan ' . $kelas->mataKuliah->nama_mk . ' ' . $kelas->section . '.pdf';

        return $pdf->download($filename);
    }
}
