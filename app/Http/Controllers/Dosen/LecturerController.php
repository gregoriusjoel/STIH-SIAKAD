<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
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
            'schedules' => $todaySchedules
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
            ->withCount([
                'krs' => function ($query) {
                    $query->where('status', 'disetujui');
                }
            ])
            ->get();

        $classes = $kelasList->map(function ($kelas) {
            $jadwal = $kelas->jadwals->first();
            return [
                'id' => $kelas->id,
                'name' => $kelas->mataKuliah->nama,
                'code' => $kelas->mataKuliah->kode,
                'section' => $kelas->section,
                'students' => $kelas->krs_count,
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
        $kelas = Kelas::with([
            'mataKuliah',
            'jadwals' => function ($q) {
                $q->where('status', 'active');
            }
        ])->findOrFail($id);

        $jadwal = $kelas->jadwals->first();

        $class_info = [
            'name' => $kelas->mataKuliah->nama,
            'code' => $kelas->mataKuliah->kode,
            'section' => $kelas->section,
            'pertemuan' => 12, // TODO: implement from database
            'topic' => 'Pertemuan ' . 12, // TODO: implement from database
            'date' => now()->locale('id')->isoFormat('dddd, D MMMM YYYY'),
            'room' => $jadwal?->ruangan ?? '-',
            'time' => $jadwal ? substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) : '-',
            'dosen_name' => $kelas->dosen->name ?? 'Dosen Belum Ditentukan',
        ];

        // Dummy Data for UI Verification
        $students = [
            [
                'id' => 1,
                'name' => 'Andi Pratama',
                'npm' => '2024010001',
                'phone' => '081234567894',
                'time' => '08:05',
                'status' => 'hadir'
            ],
            [
                'id' => 2,
                'name' => 'Dewi Lestari',
                'npm' => '2024010002',
                'phone' => '081234567895',
                'time' => '08:10',
                'status' => 'hadir'
            ],
            [
                'id' => 3,
                'name' => 'Rizki Firmansyah',
                'npm' => '2024010003',
                'phone' => '081234567896',
                'time' => '09:00',
                'status' => 'terlambat'
            ],
            [
                'id' => 4,
                'name' => 'Budi Santoso',
                'npm' => '2024010004',
                'phone' => '081234567897',
                'time' => '08:45',
                'status' => 'terlambat'
            ],
            [
                'id' => 5,
                'name' => 'Siti Aminah',
                'npm' => '2024010005',
                'phone' => '081234567898',
                'time' => '08:00',
                'status' => 'hadir'
            ],
            [
                'id' => 6,
                'name' => 'Rina Wulandari',
                'npm' => '2024010006',
                'phone' => '081234567899',
                'time' => '08:02',
                'status' => 'hadir'
            ],
            [
                'id' => 7,
                'name' => 'Doni Setiawan',
                'npm' => '2024010007',
                'phone' => '081234567800',
                'time' => '08:05',
                'status' => 'hadir'
            ],
            [
                'id' => 8,
                'name' => 'Eka Putri',
                'npm' => '2024010008',
                'phone' => '081234567801',
                'time' => '08:50',
                'status' => 'terlambat'
            ],
            [
                'id' => 9,
                'name' => 'Fajar Nugraha',
                'npm' => '2024010009',
                'phone' => '081234567802',
                'time' => '08:00',
                'status' => 'hadir'
            ],
            [
                'id' => 10,
                'name' => 'Gita Pertiwi',
                'npm' => '2024010010',
                'phone' => '081234567803',
                'time' => '08:03',
                'status' => 'hadir'
            ],
            [
                'id' => 11,
                'name' => 'Hendra Gunawan',
                'npm' => '2024010011',
                'phone' => '081234567804',
                'time' => '08:10',
                'status' => 'hadir'
            ],
            [
                'id' => 12,
                'name' => 'Indah Cahyani',
                'npm' => '2024010012',
                'phone' => '081234567805',
                'time' => '08:55',
                'status' => 'terlambat'
            ],
            [
                'id' => 13,
                'name' => 'Joko Susilio',
                'npm' => '2024010013',
                'phone' => '081234567806',
                'time' => '08:01',
                'status' => 'hadir'
            ],
            [
                'id' => 14,
                'name' => 'Kiki Amalia',
                'npm' => '2024010014',
                'phone' => '081234567807',
                'time' => '08:02',
                'status' => 'hadir'
            ],
            [
                'id' => 15,
                'name' => 'Lina Marlina',
                'npm' => '2024010015',
                'phone' => '081234567808',
                'time' => '08:03',
                'status' => 'hadir'
            ],
            [
                'id' => 16,
                'name' => 'Mira Santika',
                'npm' => '2024010016',
                'phone' => '081234567809',
                'time' => '08:04',
                'status' => 'hadir'
            ],
            [
                'id' => 17,
                'name' => 'Nina Zatulini',
                'npm' => '2024010017',
                'phone' => '081234567810',
                'time' => '08:05',
                'status' => 'hadir'
            ],
            [
                'id' => 18,
                'name' => 'Oki Setiana',
                'npm' => '2024010018',
                'phone' => '081234567811',
                'time' => '08:06',
                'status' => 'hadir'
            ],
            [
                'id' => 19,
                'name' => 'Putri Titian',
                'npm' => '2024010019',
                'phone' => '081234567812',
                'time' => '08:07',
                'status' => 'hadir'
            ],
            [
                'id' => 20,
                'name' => 'Qory Sandioriva',
                'npm' => '2024010020',
                'phone' => '081234567813',
                'time' => '08:08',
                'status' => 'hadir'
            ],
            [
                'id' => 21,
                'name' => 'Rina Nose',
                'npm' => '2024010021',
                'phone' => '081234567814',
                'time' => '08:09',
                'status' => 'hadir'
            ],
            [
                'id' => 22,
                'name' => 'Sule Prikitiw',
                'npm' => '2024010022',
                'phone' => '081234567815',
                'time' => '08:10',
                'status' => 'hadir'
            ],
            [
                'id' => 23,
                'name' => 'Tukul Arwana',
                'npm' => '2024010023',
                'phone' => '081234567816',
                'time' => '08:11',
                'status' => 'hadir'
            ],
            [
                'id' => 24,
                'name' => 'Uya Kuya',
                'npm' => '2024010024',
                'phone' => '081234567817',
                'time' => '08:12',
                'status' => 'hadir'
            ],
            [
                'id' => 25,
                'name' => 'Vicky Prasetyo',
                'npm' => '2024010025',
                'phone' => '081234567818',
                'time' => '09:15',
                'status' => 'terlambat'
            ]
        ];

        if (request()->ajax()) {
            return view('page.dosen.kelas.partials.absensi-content', compact('class_info', 'students', 'id'))->with('is_modal', true);
        }

        return view('page.dosen.kelas.absensi', compact('class_info', 'students', 'id'))->with('is_modal', false);
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
}
