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
        $kelasList = Kelas::where('dosen_id', $user->id)->with('mataKuliah')->get();
        $activeJadwals = Jadwal::whereHas('kelas', function ($q) use ($user) {
            $q->where('dosen_id', $user->id);
        })->where('status', 'active')->with(['kelas.mataKuliah'])->get();

        // Get today's schedules
        $today = now()->locale('id')->isoFormat('dddd'); // Senin, Selasa, etc
        $todaySchedules = $activeJadwals->where('hari', $today)->map(function ($jadwal) {
            return [
                'subject' => $jadwal->kelas->mataKuliah->nama,
                'code' => $jadwal->kelas->mataKuliah->kode,
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
                    'title' => $jadwal->kelas->mataKuliah->nama,
                    'code' => $jadwal->kelas->mataKuliah->kode,
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

        // Get classes from database
        $kelasList = Kelas::where('dosen_id', $user->id)
            ->with([
                'mataKuliah',
                'jadwals' => function ($q) {
                    $q->where('status', 'active');
                }
            ])
            ->get();

        $classes = $kelasList->map(function ($kelas) {
            $jadwal = $kelas->jadwals->first();

            // Calculate student count manually
            $krsCount = \App\Models\KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
                ->where('kode_kelas', $kelas->section)
                ->where('dosen_id', $kelas->dosen_id)
                ->first()
                    ?->krs()
                ->where('status', 'disetujui')
                ->count() ?? 0;

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
                'name' => $kelas->mataKuliah->nama,
                'code' => $kelas->mataKuliah->kode,
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
                'name' => $kelas->mataKuliah->nama . ' (' . $kelas->section . ')',
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
            'name' => $kelas->mataKuliah->nama,
            'code' => $kelas->mataKuliah->kode,
            'section' => $kelas->section,
            'pertemuan' => $pertemuanKe,
            'topic' => 'Pertemuan Ke-' . $pertemuanKe,
            'date' => now()->locale('id')->isoFormat('dddd, D MMMM YYYY'),
            'room' => $jadwal?->ruangan ?? '-',
            'time' => $jadwal ? substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) : '-',
            'dosen_name' => $kelas->dosen->name ?? 'Dosen Belum Ditentukan',
        ];

        // Fetch students from the related KelasMataKuliah -> krs
        $kelasMataKuliah = \App\Models\KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where('kode_kelas', $kelas->section)
            ->where('dosen_id', $kelas->dosen_id)
            ->first();

        // Ensure the kelas_mata_kuliah has a qr_token so QR can be displayed; generate if missing
        if ($kelasMataKuliah && empty($kelasMataKuliah->qr_token)) {
            $kelasMataKuliah->qr_token = \Illuminate\Support\Str::random(40);
            $kelasMataKuliah->save();
        }

        $students = $kelasMataKuliah ? $kelasMataKuliah->krs()
            ->where('status', 'disetujui')
            ->with('mahasiswa')
            ->get()
            ->map(function ($krs) {
                return [
                    'name' => $krs->mahasiswa->nama,
                    'nim' => $krs->mahasiswa->nim,
                    'prodi' => $krs->mahasiswa->prodi,
                    'semester' => $krs->mahasiswa->semester,
                    'ipk' => $krs->mahasiswa->ipk ?? 3.5, // Fallback if null
                    'status' => 'Aktif', // Default status
                ];
            })->toArray() : [];

        // Build a `class` array expected by the blade templates (includes QR token)
        $class = [
            'id' => $kelas->id,
            'name' => $kelas->mataKuliah->nama,
            'code' => $kelas->mataKuliah->kode,
            'section' => $kelas->section,
            'pertemuan' => $pertemuanKe,
            'topic' => 'Pertemuan Ke-' . $pertemuanKe,
            'date' => now()->locale('id')->isoFormat('dddd, D MMMM YYYY'),
            'room' => $jadwal?->ruangan ?? '-',
            'time' => $jadwal ? substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) : '-',
            'dosen_name' => $kelas->dosen->name ?? 'Dosen Belum Ditentukan',
            'qr_token' => $kelasMataKuliah->qr_token ?? null,
        ];

        if (request()->ajax()) {
            return view('page.dosen.kelas.partials.absensi-content', compact('class_info', 'students', 'id', 'class'))->with('is_modal', true);
        }

        return view('page.dosen.kelas.absensi', compact('class_info', 'students', 'id', 'class'))->with('is_modal', false);
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

        $students = $kelasMataKuliah ? $kelasMataKuliah->krs()
            ->where('status', 'disetujui')
            ->with('mahasiswa')
            ->get()
            ->map(function ($krs) {
                return [
                    'name' => $krs->mahasiswa->nama,
                    'nim' => $krs->mahasiswa->nim,
                    'prodi' => $krs->mahasiswa->prodi,
                    'semester' => $krs->mahasiswa->semester,
                    'ipk' => $krs->mahasiswa->ipk ?? 3.5,
                    'status' => 'Aktif',
                ];
            })->toArray() : [];

        $class_info = [
            'name' => $kelas->mataKuliah->nama,
            'code' => $kelas->mataKuliah->kode,
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

        if (empty($kelasMataKuliah->qr_token)) {
            $kelasMataKuliah->qr_token = \Illuminate\Support\Str::random(40);
            $kelasMataKuliah->qr_enabled = true;
            $kelasMataKuliah->save();
        }

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

        $students = $kelasMataKuliah ? $kelasMataKuliah->krs()
            ->where('status', 'disetujui')
            ->with('mahasiswa')
            ->get()
            ->map(function ($krs) {
                return [
                    'name' => $krs->mahasiswa->nama,
                    'nim' => $krs->mahasiswa->nim,
                    'prodi' => $krs->mahasiswa->prodi,
                    'status' => 'Aktif',
                ];
            })->toArray() : [];

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
}
