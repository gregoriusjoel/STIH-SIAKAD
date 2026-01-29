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

        // Get data from database
        // Only consider kelas that have an active jadwal assigned
        $kelasList = Kelas::where('dosen_id', $user->id)
            ->whereHas('jadwals', function($q){ $q->where('status', 'active'); })
            ->with('mataKuliah')
            ->get();
        $activeJadwals = Jadwal::whereHas('kelas', function ($q) use ($user) {
            $q->where('dosen_id', $user->id);
        })->where('status', 'active')->with(['kelas.mataKuliah'])->get();

        // Get today's schedules
        $today = now()->locale('id')->isoFormat('dddd'); // Senin, Selasa, etc
        $todaySchedules = $activeJadwals->where('hari', $today)->map(function ($jadwal) {
            return [
                'subject' => $jadwal->kelas->mataKuliah->nama_mk,
                'code' => $jadwal->kelas->mataKuliah->kode_mk,
                'class' => $jadwal->kelas->section,
                'time' => substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5),
                'room' => $jadwal->ruangan,
                'status' => 'Menunggu',
            ];
        })->values()->toArray();

        return view('page.dosen.dashboard.index', [
            'total_mata_kuliah' => $kelasList->count(),
            'total_kelas_aktif' => $kelasList->count(),
            'total_students' => 0, // TODO: implement student count
            'sks_load' => $kelasList->sum(fn($k) => $k->mataKuliah->sks ?? 0),
            'krs_approval' => 0, // TODO: implement
            'schedules' => $todaySchedules,
            'all_schedules' => $activeJadwals->map(function ($jadwal) {
                return [
                    'title' => $jadwal->kelas->mataKuliah->nama_mk,
                    'code' => $jadwal->kelas->mataKuliah->kode_mk,
                    'section' => $jadwal->kelas->section,
                    'day' => $jadwal->hari,
                    'time' => substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5),
                    'room' => $jadwal->ruangan,
                    'color' => 'bg-blue-100 text-blue-700', // Default color, can be randomized
                ];
            })->values()->toArray()
        ]);
    }

    public function classes()
    {
        $user = Auth::user();

        // Get classes from database - only classes with an active jadwal
        $kelasList = Kelas::where('dosen_id', $user->id)
            ->whereHas('jadwals', function($q){ $q->where('status','active'); })
            ->with([
                'mataKuliah',
                'jadwals' => function ($q) {
                    $q->where('status', 'active');
                }
            ])
            ->get();

        $classes = $kelasList->map(function ($kelas) {
            $jadwal = $kelas->jadwals->first();

            // Calculate student count manually: consider KRS rows that reference either
            // `kelas_mata_kuliah_id` (old system) or `kelas_id` (current system).
            $kelasMkRecord = \App\Models\KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
                ->where('kode_kelas', $kelas->section)
                ->where('dosen_id', $kelas->dosen_id)
                ->first();

            if ($kelasMkRecord) {
                $krsCount = \App\Models\Krs::whereIn('status', ['approved', 'disetujui'])
                    ->where(function ($q) use ($kelasMkRecord, $kelas) {
                        $q->where('kelas_mata_kuliah_id', $kelasMkRecord->id)
                          ->orWhere('kelas_id', $kelas->id);
                    })->count();
            } else {
                $krsCount = \App\Models\Krs::whereIn('status', ['approved', 'disetujui'])
                    ->where('kelas_id', $kelas->id)
                    ->count();
            }

            // Calculate progress percentage
            $semesterAktif = \App\Models\Semester::where('status', 'aktif')->first()
                ?? \App\Models\Semester::latest()->first();

            $progress = 0;
            if ($semesterAktif && $semesterAktif->tanggal_mulai) {
                $start = \Carbon\Carbon::parse($semesterAktif->tanggal_mulai);
                $now = \Carbon\Carbon::now();
                if ($now->gte($start)) {
                    $weeks = $start->diffInWeeks($now);
                    $progress = min(100, round(($weeks / 16) * 100));
                }
            }

            return [
                'id' => $kelas->id,
                'name' => $kelas->mataKuliah->nama_mk,
                'code' => $kelas->mataKuliah->kode_mk,
                'section' => $kelas->section,
                'students' => $krsCount,
                'day' => $jadwal?->hari ?? '-',
                'time' => $jadwal ? substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) : '-',
                'room' => $jadwal?->ruangan ?? '-',
                'sks' => $kelas->mataKuliah->sks,
                'progress' => $progress,
            ];
        })->toArray();

        return view('page.dosen.kelas.index', compact('classes'));
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
                'nim' => $m->npm ?? null,
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
            if (Schema::hasColumn('presensis', 'pertemuan')) {
                $presensiQuery->where('pertemuan', $currentPertemuan);
            }

            $presensis = $presensiQuery->get();
        }

        if (request()->ajax()) {
            return view('page.dosen.kelas.partials.absensi-content', compact('class_info', 'students', 'id', 'class', 'presensis'))->with('is_modal', true);
        }

        return view('page.dosen.kelas.absensi', compact('class_info', 'students', 'id', 'class', 'presensis'))->with('is_modal', false);
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
                'nim' => $m->npm ?? null,
                'prodi' => $m->prodi ?? null,
                'semester' => $m->semester ?? null,
                'ipk' => $m->ipk !== null ? number_format((float)$m->ipk, 2) : '-',
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

        $students = $krsCollection->map(function ($krs) {
            $m = $krs->mahasiswa;
            $userName = $m->user->name ?? ($m->nama ?? 'Mahasiswa');
            return [
                'name' => $userName,
                'nim' => $m->npm ?? null,
                'prodi' => $m->prodi ?? null,
                'status' => 'Aktif',
            ];
        })->toArray();

        return view('page.dosen.kelas.lihat-rincian', compact('kelas', 'meeting', 'students'));
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

        return view('page.dosen.kelas.materi', compact('kelas', 'meeting'));
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

        if (! $kelasMataKuliah) {
            return back()->with('error', 'Tidak dapat menemukan record kelas mata kuliah untuk menonaktifkan QR.');
        }

        $kelasMataKuliah->qr_enabled = false;
        $kelasMataKuliah->qr_expires_at = null;
        $kelasMataKuliah->save();

        \Log::info('QR token manually disabled', ['kelas_mk_id' => $kelasMataKuliah->id, 'qr_token' => $kelasMataKuliah->qr_token]);

        return back()->with('success', 'QR dinonaktifkan.');
    }
}
