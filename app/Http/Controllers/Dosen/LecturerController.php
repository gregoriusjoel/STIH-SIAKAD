<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\KelasMataKuliah;
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
            'total_students' => 0, // TODO: implement student count
            'sks_load' => $kelasList->sum(fn($k) => $k->mataKuliah->sks ?? 0),
            'krs_approval' => 0, // TODO: implement
            'schedules' => $todaySchedules
        ]);
    }

    public function classes()
    {
        $user = Auth::user();
        
        // Get classes from database
        $kelasList = Kelas::where('dosen_id', $user->id)
            ->with(['mataKuliah', 'jadwals' => function ($q) {
                $q->where('status', 'active');
            }])
            ->get();

        $classes = $kelasList->map(function ($kelas) {
            $jadwal = $kelas->jadwals->first();
            return [
                'id' => $kelas->id,
                'name' => $kelas->mataKuliah->nama,
                'code' => $kelas->mataKuliah->kode,
                'section' => $kelas->section,
                'students' => 0, // TODO: implement student count
                'day' => $jadwal?->hari ?? '-',
                'time' => $jadwal ? substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) : '-',
                'room' => $jadwal?->ruangan ?? '-',
                'sks' => $kelas->mataKuliah->sks,
                'progress' => rand(40, 80), // TODO: implement real progress
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
        $kelas = Kelas::with(['mataKuliah', 'jadwals' => function ($q) {
            $q->where('status', 'active');
        }])->findOrFail($id);

        $jadwal = $kelas->jadwals->first();

        $class_info = [
            'name' => $kelas->mataKuliah->nama,
            'code' => $kelas->mataKuliah->kode,
            'section' => $kelas->section,
            'pertemuan' => 12, // TODO: implement from database
            'topic' => 'Pertemuan ' . 12, // TODO: implement from database
            'date' => now()->locale('id')->isoFormat('dddd, D MMMM YYYY')
        ];

        $students = [];
        // TODO: Implement attendance data from database when available

        // Try to find a matching KelasMataKuliah (used for KRS/QR storage) by mata_kuliah_id and section/kode_kelas
        $kelasMataKuliah = KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where(function ($q) use ($kelas) {
                $q->where('kode_kelas', $kelas->section)
                  ->orWhere('kode_kelas', $kelas->section . '');
            })->first();

        // Fallback: pick first kelas_mata_kuliah for that mata_kuliah_id if not found
        if (! $kelasMataKuliah) {
            $kelasMataKuliah = KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)->first();
        }

        // Ensure the kelas_mata_kuliah has a qr_token so QR can be displayed; generate if missing
        if ($kelasMataKuliah && empty($kelasMataKuliah->qr_token)) {
            $kelasMataKuliah->qr_token = \Illuminate\Support\Str::random(40);
            $kelasMataKuliah->save();
        }

        // debug_absensi removed: no longer flashing debug info to the view

        // Build a `class` array expected by the blade templates (includes QR token)
        $class = [
            'id' => $kelas->id,
            'name' => $kelas->mataKuliah->nama,
            'code' => $kelas->mataKuliah->kode,
            'section' => $kelas->section,
            'pertemuan' => 12,
            'topic' => 'Pertemuan ' . 12,
            'date' => now()->locale('id')->isoFormat('dddd, D MMMM YYYY'),
            'teacher' => [
                'name' => $kelas->dosen?->user->name ?? auth()->user()->name ?? 'Nama Dosen'
            ],
            'qr_token' => $kelasMataKuliah->qr_token ?? null,
        ];

        if (request()->ajax()) {
            return view('page.dosen.kelas.partials.absensi-content', compact('class_info', 'students', 'id', 'class'))->with('is_modal', true);
        }

        return view('page.dosen.kelas.absensi', compact('class_info', 'students', 'id', 'class'))->with('is_modal', false);
    }

    public function detail($id)
    {
        $kelas = Kelas::with(['mataKuliah', 'jadwals' => function ($q) {
            $q->where('status', 'active');
        }])->findOrFail($id);

        $jadwal = $kelas->jadwals->first();

        $class_info = [
            'name' => $kelas->mataKuliah->nama,
            'code' => $kelas->mataKuliah->kode,
            'sks' => $kelas->mataKuliah->sks,
            'semester' => $kelas->mataKuliah->semester,
            'section' => $kelas->section,
            'day' => $jadwal?->hari ?? '-',
            'time' => $jadwal ? substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) : '-',
            'room' => $jadwal?->ruangan ?? '-',
            'students_count' => 0, // TODO: implement from database
            'progress' => 12, // TODO: implement from database
            'total_pertemuan' => 16
        ];

        $students = [];
        // TODO: Implement student data from database when available

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
        if (! $kelasMataKuliah) {
            $kelasMataKuliah = KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)->first();
        }

        if (! $kelasMataKuliah) {
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
}
