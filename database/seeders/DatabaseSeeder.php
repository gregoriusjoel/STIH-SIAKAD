<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\ParentModel;
use App\Models\MataKuliah;
use App\Models\Semester;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\SystemSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@stih.ac.id'],
            [
                'name' => 'Admin STIH',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ]
        );

        Admin::firstOrCreate(
            ['user_id' => $adminUser->id],
            [
                'nip' => '198501012010011001',
                'phone' => '081234567890',
                'address' => 'Jl. Kampus STIH No. 1'
            ]
        );

        // Create Dosen Users
        $dosen1User = User::firstOrCreate(
            ['email' => 'ahmad.fauzi@stih.ac.id'],
            [
                'name' => 'Dr. Ahmad Fauzi, S.H., M.H.',
                'password' => Hash::make('dosen123'),
                'role' => 'dosen'
            ]
        );

        // Dosen profile (linked to user)
        $dosen1 = Dosen::firstOrCreate(
            ['user_id' => $dosen1User->id],
            [
                'nidn' => '0101018501',
                'prodi' => 'Hukum Tata Negara',
                'phone' => '081234567891',
                'address' => 'Jl. Dosen No. 1',
                'status' => 'aktif'
            ]
        );

        $dosen2User = User::firstOrCreate(
            ['email' => 'siti.nurjanah@stih.ac.id'],
            [
                'name' => 'Prof. Dr. Siti Nurjanah, S.H., M.H.',
                'password' => Hash::make('dosen123'),
                'role' => 'dosen'
            ]
        );

        Dosen::firstOrCreate(
            ['user_id' => $dosen2User->id],
            [
                'nidn' => '0102028601',
                'prodi' => 'Hukum Bisnis',
                'phone' => '081234567892',
                'address' => 'Jl. Dosen No. 2',
                'status' => 'aktif'
            ]
        );

        $dosen3User = User::firstOrCreate(
            ['email' => 'budi.santoso@stih.ac.id'],
            [
                'name' => 'Dr. Budi Santoso, S.H., M.H.',
                'password' => Hash::make('dosen123'),
                'role' => 'dosen'
            ]
        );

        Dosen::firstOrCreate(
            ['user_id' => $dosen3User->id],
            [
                'nidn' => '0103038701',
                'prodi' => 'Hukum Pidana',
                'phone' => '081234567893',
                'address' => 'Jl. Dosen No. 3',
                'status' => 'aktif'
            ]
        );

        // Create Mahasiswa Users
        $mhs1User = User::firstOrCreate(
            ['email' => 'andi.pratama@student.stih.ac.id'],
            [
                'name' => 'Andi Pratama',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa'
            ]
        );

        $mhs1 = Mahasiswa::firstOrCreate(
            ['user_id' => $mhs1User->id],
            [
                'npm' => '2024010001',
                'prodi' => 'Hukum Tata Negara',
                'angkatan' => '2024',
                'phone' => '081234567894',
                'address' => 'Jl. Mahasiswa No. 1',
                'status' => 'aktif'
            ]
        );

        // Additional Mahasiswa...
        $mhs2User = User::firstOrCreate(
            ['email' => 'dewi.lestari@student.stih.ac.id'],
            [
                'name' => 'Dewi Lestari',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa'
            ]
        );

        $mhs2 = Mahasiswa::firstOrCreate(
            ['user_id' => $mhs2User->id],
            [
                'npm' => '2024010002',
                'prodi' => 'Hukum Bisnis',
                'angkatan' => '2024',
                'phone' => '081234567895',
                'address' => 'Jl. Mahasiswa No. 2',
                'status' => 'aktif'
            ]
        );

        $mhs3User = User::firstOrCreate(
            ['email' => 'rizki.firmansyah@student.stih.ac.id'],
            [
                'name' => 'Rizki Firmansyah',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa'
            ]
        );

        $mhs3 = Mahasiswa::firstOrCreate(
            ['user_id' => $mhs3User->id],
            [
                'npm' => '2024010003',
                'prodi' => 'Hukum Pidana',
                'angkatan' => '2024',
                'phone' => '081234567896',
                'address' => 'Jl. Mahasiswa No. 3',
                'status' => 'aktif'
            ]
        );

        // Create Parent User
        $parentUser = User::firstOrCreate(
            ['email' => 'parent.pratama@stih.ac.id'],
            [
                'name' => 'Bapak Pratama',
                'password' => Hash::make('parent123'),
                'role' => 'parent'
            ]
        );

        ParentModel::firstOrCreate(
            ['user_id' => $parentUser->id],
            [
                'mahasiswa_id' => $mhs1->id,
                'phone' => '081234567897',
                'address' => 'Jl. Orang Tua No. 1'
            ]
        );

        // Create Mata Kuliah (Courses)
        // using correct columns: kode_mk, prodi, jenis enum
        $mk1 = MataKuliah::firstOrCreate(
            ['kode_mk' => 'HTN201'],
            [
                'nama_mk' => 'Hukum Tata Negara',
                'sks' => 3,
                'semester' => 3,
                'jenis' => 'wajib',
                'prodi' => 'Hukum Tata Negara'
            ]
        );

        $mk2 = MataKuliah::firstOrCreate(
            ['kode_mk' => 'HB101'],
            [
                'nama_mk' => 'Pengantar Hukum Bisnis',
                'sks' => 3,
                'semester' => 1,
                'jenis' => 'wajib',
                'prodi' => 'Hukum Bisnis'
            ]
        );

        $mk3 = MataKuliah::firstOrCreate(
            ['kode_mk' => 'HP301'],
            [
                'nama_mk' => 'Hukum Pidana',
                'sks' => 4,
                'semester' => 5,
                'jenis' => 'wajib',
                'prodi' => 'Hukum Pidana'
            ]
        );

        $mk4 = MataKuliah::firstOrCreate(
            ['kode_mk' => 'HP302'],
            [
                'nama_mk' => 'Praktikum Hukum Pidana',
                'sks' => 2,
                'semester' => 5,
                'jenis' => 'wajib', // assuming praktikum is wajib
                'prodi' => 'Hukum Pidana'
            ]
        );

        $mk5 = MataKuliah::firstOrCreate(
            ['kode_mk' => 'HTN202'],
            [
                'nama_mk' => 'Hukum Administrasi Negara',
                'sks' => 3,
                'semester' => 4,
                'jenis' => 'wajib',
                'prodi' => 'Hukum Tata Negara'
            ]
        );

        // Create Kelas (Classes)
        // Linking to 'kelas' table via Kelas model.
        // Requires 'dosen_id' (User ID from users table), 'mata_kuliah_id', 'section', 'tahun_ajaran', 'semester_type'

        $kelas1 = Kelas::firstOrCreate(
            ['section' => 'HTN-A', 'mata_kuliah_id' => $mk1->id],
            [
                'dosen_id' => $dosen1User->id,
                'kapasitas' => 40,
                'tahun_ajaran' => '2023/2024',
                'semester_type' => 'Ganjil'
            ]
        );

        $kelas2 = Kelas::firstOrCreate(
            ['section' => 'HB-A', 'mata_kuliah_id' => $mk2->id],
            [
                'dosen_id' => $dosen2User->id,
                'kapasitas' => 35,
                'tahun_ajaran' => '2023/2024',
                'semester_type' => 'Ganjil'
            ]
        );

        $kelas3 = Kelas::firstOrCreate(
            ['section' => 'HP-A', 'mata_kuliah_id' => $mk3->id],
            [
                'dosen_id' => $dosen3User->id,
                'kapasitas' => 30,
                'tahun_ajaran' => '2023/2024',
                'semester_type' => 'Ganjil'
            ]
        );

        $kelas4 = Kelas::firstOrCreate(
            ['section' => 'HP-P1', 'mata_kuliah_id' => $mk4->id],
            [
                'dosen_id' => $dosen3User->id,
                'kapasitas' => 20,
                'tahun_ajaran' => '2023/2024',
                'semester_type' => 'Ganjil'
            ]
        );

        $kelas5 = Kelas::firstOrCreate(
            ['section' => 'HTN-B', 'mata_kuliah_id' => $mk5->id],
            [
                'dosen_id' => $dosen1User->id,
                'kapasitas' => 40,
                'tahun_ajaran' => '2023/2024',
                'semester_type' => 'Ganjil'
            ]
        );

        // Create Jadwal (Schedules)
        Jadwal::firstOrCreate(
            ['kelas_id' => $kelas1->id, 'hari' => 'Senin', 'jam_mulai' => '08:00:00'],
            [
                'jam_selesai' => '10:30:00',
                'ruangan' => 'R. Teori 201',
                'status' => 'active'
            ]
        );

        Jadwal::firstOrCreate(
            ['kelas_id' => $kelas2->id, 'hari' => 'Selasa', 'jam_mulai' => '10:30:00'],
            [
                'jam_selesai' => '13:00:00',
                'ruangan' => 'R. Teori 102',
                'status' => 'active'
            ]
        );

        Jadwal::firstOrCreate(
            ['kelas_id' => $kelas3->id, 'hari' => 'Rabu', 'jam_mulai' => '13:00:00'],
            [
                'jam_selesai' => '16:00:00',
                'ruangan' => 'R. Teori 301',
                'status' => 'active'
            ]
        );

        Jadwal::firstOrCreate(
            ['kelas_id' => $kelas4->id, 'hari' => 'Kamis', 'jam_mulai' => '08:00:00'],
            [
                'jam_selesai' => '10:00:00',
                'ruangan' => 'Lab Hukum',
                'status' => 'active'
            ]
        );

        Jadwal::firstOrCreate(
            ['kelas_id' => $kelas5->id, 'hari' => 'Jumat', 'jam_mulai' => '08:00:00'],
            [
                'jam_selesai' => '10:30:00',
                'ruangan' => 'R. Teori 202',
                'status' => 'active'
            ]
        );
        // Create KRS (Enrollments)
        \App\Models\Krs::firstOrCreate(['mahasiswa_id' => $mhs1->id, 'kelas_id' => $kelas1->id], ['status' => 'disetujui']);
        \App\Models\Krs::firstOrCreate(['mahasiswa_id' => $mhs2->id, 'kelas_id' => $kelas1->id], ['status' => 'disetujui']);
        \App\Models\Krs::firstOrCreate(['mahasiswa_id' => $mhs3->id, 'kelas_id' => $kelas1->id], ['status' => 'disetujui']);

        \App\Models\Krs::firstOrCreate(['mahasiswa_id' => $mhs1->id, 'kelas_id' => $kelas5->id], ['status' => 'disetujui']);

        \App\Models\Krs::firstOrCreate(['mahasiswa_id' => $mhs2->id, 'kelas_id' => $kelas2->id], ['status' => 'disetujui']);

        \App\Models\Krs::firstOrCreate(['mahasiswa_id' => $mhs3->id, 'kelas_id' => $kelas3->id], ['status' => 'disetujui']);
    }
}
