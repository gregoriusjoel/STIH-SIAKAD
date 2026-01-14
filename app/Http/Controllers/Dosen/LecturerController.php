<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    public function dashboard()
    {
        return view('dosen.dashboard', [
            'total_mata_kuliah' => 6,
            'total_students' => 40,
            'sks_load' => 19,
            'krs_approval' => 15,
            'schedules' => $this->getDummySchedules()
        ]);
    }

    public function classes()
    {
        $classes = [
            ['id' => 1, 'name' => 'Pemrograman Web', 'code' => 'IT-402', 'section' => 'IF-A', 'students' => 5, 'day' => 'Senin', 'time' => '08:00 - 10:30', 'room' => 'Lab Komputer 1', 'sks' => 3, 'progress' => 75],
            ['id' => 2, 'name' => 'Basis Data Lanjut', 'code' => 'IT-405', 'section' => 'IF-B', 'students' => 8, 'day' => 'Selasa', 'time' => '10:30 - 13:00', 'room' => 'Lab Komputer 2', 'sks' => 3, 'progress' => 70],
            ['id' => 3, 'name' => 'Algoritma & Struktur Data', 'code' => 'IT-201', 'section' => 'IF-A', 'students' => 7, 'day' => 'Rabu', 'time' => '08:00 - 10:30', 'room' => 'Ruang 304', 'sks' => 4, 'progress' => 60],
            ['id' => 4, 'name' => 'Kecerdasan Buatan', 'code' => 'IT-501', 'section' => 'IF-C', 'students' => 4, 'day' => 'Kamis', 'time' => '13:00 - 15:30', 'room' => 'Ruang 302', 'sks' => 3, 'progress' => 45],
            ['id' => 5, 'name' => 'Sistem Operasi', 'code' => 'IT-303', 'section' => 'IF-A', 'students' => 6, 'day' => 'Jumat', 'time' => '09:00 - 11:30', 'room' => 'Ruang 201', 'sks' => 3, 'progress' => 80],
            ['id' => 6, 'name' => 'Proyek Perangkat Lunak', 'code' => 'IT-601', 'section' => 'IF-D', 'students' => 10, 'day' => 'Senin', 'time' => '13:00 - 15:30', 'room' => 'Lab RPL', 'sks' => 3, 'progress' => 55],
        ];

        return view('dosen.kelas.index', compact('classes'));
    }

    public function inputNilai(Request $request)
    {
        // Dummy data for classes dropdown
        $classes = [
            ['id' => 1, 'name' => 'Pemrograman Web (IF-A)'],
            ['id' => 2, 'name' => 'Basis Data Lanjut (IF-B)'],
            ['id' => 3, 'name' => 'Algoritma & Struktur Data (IF-A)'],
        ];

        $students = [];
        if ($request->has('class_id')) {
            // Dummy students for a selected class
            $students = $this->getDummyStudents();
        }

        return view('dosen.input-nilai.index', compact('classes', 'students'));
    }

    public function students(Request $request)
    {
        $students = $this->getDummyStudents();

        // Simple dummy filter
        if ($request->has('search') && $request->search) {
            $search = strtolower($request->search);
            $students = array_filter($students, function ($s) use ($search) {
                return str_contains(strtolower($s['name']), $search) || str_contains($s['nim'], $search);
            });
        }

        return view('dosen.mahasiswa.index', compact('students'));
    }

    private function getDummySchedules()
    {
        return [
            ['subject' => 'Pemrograman Web', 'code' => 'IT-402', 'class' => 'IF-A', 'time' => '08:00 - 10:30', 'room' => 'Lab Komputer 1', 'status' => 'Selesai'],
            ['subject' => 'Proyek Perangkat Lunak', 'code' => 'IT-601', 'class' => 'IF-D', 'time' => '13:00 - 15:30', 'room' => 'Lab RPL', 'status' => 'Menunggu'],
        ];
    }

    private function getDummyStudents()
    {
        return [
            ['id' => 1, 'nim' => '2023001', 'name' => 'Budi Santoso', 'program_study' => 'Teknik Informatika', 'semester' => 5, 'status' => 'Aktif'],
            ['id' => 2, 'nim' => '2023002', 'name' => 'Siti Aminah', 'program_study' => 'Teknik Informatika', 'semester' => 5, 'status' => 'Aktif'],
            ['id' => 3, 'nim' => '2023003', 'name' => 'Ahmad Rizki', 'program_study' => 'Ekonomi', 'semester' => 3, 'status' => 'Cuti'],
            ['id' => 4, 'nim' => '2023004', 'name' => 'Dewi Lestari', 'program_study' => 'Hukum', 'semester' => 7, 'status' => 'Aktif'],
            ['id' => 5, 'nim' => '2023005', 'name' => 'Eko Prasetyo', 'program_study' => 'Teknik Informatika', 'semester' => 5, 'status' => 'Non-Aktif'],
            ['id' => 6, 'nim' => '2023006', 'name' => 'Fani Rahmawati', 'program_study' => 'Manajemen', 'semester' => 1, 'status' => 'Aktif'],
            ['id' => 7, 'nim' => '2023007', 'name' => 'Gunawan Wibowo', 'program_study' => 'Teknik Informatika', 'semester' => 5, 'status' => 'Aktif'],
            ['id' => 8, 'nim' => '2023008', 'name' => 'Hanna Pertiwi', 'program_study' => 'Sistem Informasi', 'semester' => 5, 'status' => 'Aktif'],
        ];
    }
    public function krs()
    {
        $students = [
            [
                'name' => 'Budi Santoso',
                'nim' => '2023001',
                'semester' => 3,
                'sks' => 22,
                'status' => 'Waiting Approval',
                'date' => '12 Jan 2024'
            ],
            [
                'name' => 'Siti Aminah',
                'nim' => '2023005',
                'semester' => 3,
                'sks' => 20,
                'status' => 'Waiting Approval',
                'date' => '13 Jan 2024'
            ],
            [
                'name' => 'Ahmad Rizki',
                'nim' => '2023012',
                'semester' => 5,
                'sks' => 24,
                'status' => 'Waiting Approval',
                'date' => '14 Jan 2024'
            ],
            [
                'name' => 'Dewi Putri',
                'nim' => '2023015',
                'semester' => 1,
                'sks' => 18,
                'status' => 'Waiting Approval',
                'date' => '14 Jan 2024'
            ],
            [
                'name' => 'Rian Hidayat',
                'nim' => '2023020',
                'semester' => 7,
                'sks' => 10,
                'status' => 'Waiting Approval',
                'date' => '14 Jan 2024'
            ],
        ];

        return view('dosen.krs.index', compact('students'));
    }

    public function absensi($id)
    {
        // Dummy class info based on ID (simplified)
        $class_info = [
            'name' => 'Pemrograman Web',
            'code' => 'IT-402',
            'section' => 'IF-A',
            'pertemuan' => 12,
            'topic' => 'Deployment & Hosting',
            'date' => 'Senin, 15 Jan 2024'
        ];

        $students = $this->getDummyAttendance($id);

        if (request()->ajax()) {
            return view('dosen.kelas.partials.absensi-content', compact('class_info', 'students', 'id'))->with('is_modal', true);
        }

        return view('dosen.kelas.absensi', compact('class_info', 'students', 'id'))->with('is_modal', false);
    }

    public function detail($id)
    {
        // Dummy class info matching screenshot
        $class_info = [
            'name' => 'Pemrograman Web',
            'code' => 'IT-402',
            'sks' => 3,
            'semester' => 4,
            'section' => 'IF-A',
            'day' => 'Senin',
            'time' => '08:00 - 10:30',
            'room' => 'Lab Komputer 1',
            'students_count' => 5,
            'progress' => 12, // Progress Pertemuan
            'total_pertemuan' => 16
        ];

        // Dummy Student List specific to Detail View
        $students = [
            ['nim' => '2021001', 'name' => 'Ahmad Rizki', 'prodi' => 'Informatika'],
            ['nim' => '2021002', 'name' => 'Budi Santoso', 'prodi' => 'Informatika'],
            ['nim' => '2021003', 'name' => 'Citra Dewi', 'prodi' => 'Informatika'],
            ['nim' => '2021004', 'name' => 'Dian Pratama', 'prodi' => 'Sistem Informasi'],
            ['nim' => '2021005', 'name' => 'Eka Saputra', 'prodi' => 'Informatika'],
        ];

        if (request()->ajax()) {
            return view('dosen.kelas.partials.detail-content', compact('class_info', 'students', 'id'))->with('is_modal', true);
        }

        return view('dosen.kelas.detail', compact('class_info', 'students', 'id'))->with('is_modal', false);
    }

    private function getDummyAttendance($class_id)
    {
        // Return different calculated counts based on ID if needed, 
        // but for now returning a fixed list of ~20 students with random statuses
        $base_students = [
            ['nim' => '2023001', 'name' => 'Budi Santoso', 'status' => 'Hadir'],
            ['nim' => '2023002', 'name' => 'Siti Aminah', 'status' => 'Hadir'],
            ['nim' => '2023003', 'name' => 'Ahmad Rizki', 'status' => 'Sakit'],
            ['nim' => '2023004', 'name' => 'Dewi Lestari', 'status' => 'Hadir'],
            ['nim' => '2023005', 'name' => 'Eko Prasetyo', 'status' => 'Izin'],
            ['nim' => '2023006', 'name' => 'Fani Rahmawati', 'status' => 'Hadir'],
            ['nim' => '2023007', 'name' => 'Gunawan Wibowo', 'status' => 'Hadir'],
            ['nim' => '2023008', 'name' => 'Hanna Pertiwi', 'status' => 'Alpha'],
            ['nim' => '2023009', 'name' => 'Iwan Kurniawan', 'status' => 'Hadir'],
            ['nim' => '2023010', 'name' => 'Joko Susilo', 'status' => 'Hadir'],
        ];

        // Ensure we respect the max 40 rule or specific class counts if needed
        // For this view, we'll just return these 10 for display demo
        return $base_students;
    }
}
