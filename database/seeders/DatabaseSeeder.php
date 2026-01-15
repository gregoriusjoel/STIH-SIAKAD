<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\ParentModel;
use App\Models\MataKuliah;
use App\Models\Semester;
use App\Models\KelasMataKuliah;
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
        $adminUser = User::create([
            'name' => 'Admin STIH',
            'email' => 'admin@stih.ac.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        Admin::create([
            'user_id' => $adminUser->id,
            'nip' => '198501012010011001',
            'phone' => '081234567890',
            'address' => 'Jl. Kampus STIH No. 1'
        ]);

        // Create Dosen Users
        $dosen1User = User::create([
            'name' => 'Dr. Ahmad Fauzi, S.H., M.H.',
            'email' => 'ahmad.fauzi@stih.ac.id',
            'password' => Hash::make('dosen123'),
            'role' => 'dosen'
        ]);

        $dosen1 = Dosen::create([
            'user_id' => $dosen1User->id,
            'nidn' => '0101018501',
            'prodi' => 'Hukum Tata Negara',
            'phone' => '081234567891',
            'address' => 'Jl. Dosen No. 1',
            'status' => 'aktif'
        ]);

        $dosen2User = User::create([
            'name' => 'Prof. Dr. Siti Nurjanah, S.H., M.H.',
            'email' => 'siti.nurjanah@stih.ac.id',
            'password' => Hash::make('dosen123'),
            'role' => 'dosen'
        ]);

        $dosen2 = Dosen::create([
            'user_id' => $dosen2User->id,
            'nidn' => '0102028601',
            'prodi' => 'Hukum Bisnis',
            'phone' => '081234567892',
            'address' => 'Jl. Dosen No. 2',
            'status' => 'aktif'
        ]);

        $dosen3User = User::create([
            'name' => 'Dr. Budi Santoso, S.H., M.H.',
            'email' => 'budi.santoso@stih.ac.id',
            'password' => Hash::make('dosen123'),
            'role' => 'dosen'
        ]);

        $dosen3 = Dosen::create([
            'user_id' => $dosen3User->id,
            'nidn' => '0103038701',
            'prodi' => 'Hukum Pidana',
            'phone' => '081234567893',
            'address' => 'Jl. Dosen No. 3',
            'status' => 'aktif'
        ]);

        // Create Mahasiswa Users
        $mhs1User = User::create([
            'name' => 'Andi Pratama',
            'email' => 'andi.pratama@student.stih.ac.id',
            'password' => Hash::make('mahasiswa123'),
            'role' => 'mahasiswa'
        ]);

        $mhs1 = Mahasiswa::create([
            'user_id' => $mhs1User->id,
            'npm' => '2024010001',
            'prodi' => 'Hukum Tata Negara',
            'angkatan' => '2024',
            'phone' => '081234567894',
            'address' => 'Jl. Mahasiswa No. 1',
            'status' => 'aktif'
        ]);

        $mhs2User = User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi.lestari@student.stih.ac.id',
            'password' => Hash::make('mahasiswa123'),
            'role' => 'mahasiswa'
        ]);

        $mhs2 = Mahasiswa::create([
            'user_id' => $mhs2User->id,
            'npm' => '2024010002',
            'prodi' => 'Hukum Bisnis',
            'angkatan' => '2024',
            'phone' => '081234567895',
            'address' => 'Jl. Mahasiswa No. 2',
            'status' => 'aktif'
        ]);

        $mhs3User = User::create([
            'name' => 'Rizki Firmansyah',
            'email' => 'rizki.firmansyah@student.stih.ac.id',
            'password' => Hash::make('mahasiswa123'),
            'role' => 'mahasiswa'
        ]);

        $mhs3 = Mahasiswa::create([
            'user_id' => $mhs3User->id,
            'npm' => '2024010003',
            'prodi' => 'Hukum Pidana',
            'angkatan' => '2024',
            'phone' => '081234567896',
            'address' => 'Jl. Mahasiswa No. 3',
            'status' => 'aktif'
        ]);

        // Create Parent User
        $parentUser = User::create([
            'name' => 'Bapak Pratama',
            'email' => 'parent.pratama@stih.ac.id',
            'password' => Hash::make('parent123'),
            'role' => 'parent'
        ]);

        ParentModel::create([
            'user_id' => $parentUser->id,
            'mahasiswa_id' => $mhs1->id,
            'phone' => '081234567897',
            'address' => 'Jl. Parent No. 1'
        ]);

        // Create Mata Kuliah
        $mk1 = MataKuliah::create([
            'kode_mk' => 'HTN101',
            'nama_mk' => 'Pengantar Hukum Tata Negara',
            'sks' => 3,
            'jenis' => 'wajib',
            'prodi' => 'Hukum Tata Negara',
            'deskripsi' => 'Mata kuliah dasar tentang hukum tata negara Indonesia'
        ]);

        $mk2 = MataKuliah::create([
            'kode_mk' => 'HB101',
            'nama_mk' => 'Hukum Perdata',
            'sks' => 3,
            'jenis' => 'wajib',
            'prodi' => 'Hukum Bisnis',
            'deskripsi' => 'Mata kuliah tentang hukum perdata dan bisnis'
        ]);

        $mk3 = MataKuliah::create([
            'kode_mk' => 'HP101',
            'nama_mk' => 'Hukum Pidana Umum',
            'sks' => 3,
            'jenis' => 'wajib',
            'prodi' => 'Hukum Pidana',
            'deskripsi' => 'Mata kuliah tentang hukum pidana umum'
        ]);

        $mk4 = MataKuliah::create([
            'kode_mk' => 'HTN201',
            'nama_mk' => 'Hukum Administrasi Negara',
            'sks' => 3,
            'jenis' => 'wajib',
            'prodi' => 'Hukum Tata Negara',
            'deskripsi' => 'Mata kuliah tentang hukum administrasi negara'
        ]);

        $mk5 = MataKuliah::create([
            'kode_mk' => 'HB201',
            'nama_mk' => 'Hukum Perusahaan',
            'sks' => 2,
            'jenis' => 'pilihan',
            'prodi' => 'Hukum Bisnis',
            'deskripsi' => 'Mata kuliah tentang hukum perusahaan'
        ]);

        // Create Semester
        $semester = Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2024/2025',
            'status' => 'aktif',
            'tanggal_mulai' => '2024-09-01',
            'tanggal_selesai' => '2025-01-31'
        ]);

        // Create Kelas Mata Kuliah
        $kelas1 = KelasMataKuliah::create([
            'mata_kuliah_id' => $mk1->id,
            'dosen_id' => $dosen1->id,
            'semester_id' => $semester->id,
            'kode_kelas' => 'A',
            'kapasitas' => 40,
            'ruang' => 'R.301'
        ]);

        $kelas2 = KelasMataKuliah::create([
            'mata_kuliah_id' => $mk2->id,
            'dosen_id' => $dosen2->id,
            'semester_id' => $semester->id,
            'kode_kelas' => 'A',
            'kapasitas' => 40,
            'ruang' => 'R.302'
        ]);

        $kelas3 = KelasMataKuliah::create([
            'mata_kuliah_id' => $mk3->id,
            'dosen_id' => $dosen3->id,
            'semester_id' => $semester->id,
            'kode_kelas' => 'A',
            'kapasitas' => 40,
            'ruang' => 'R.303'
        ]);

        // Create Jadwal
        Jadwal::create([
            'kelas_mata_kuliah_id' => $kelas1->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:30'
        ]);

        Jadwal::create([
            'kelas_mata_kuliah_id' => $kelas2->id,
            'hari' => 'Selasa',
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:30'
        ]);

        Jadwal::create([
            'kelas_mata_kuliah_id' => $kelas3->id,
            'hari' => 'Rabu',
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:30'
        ]);

        // Create System Settings
        SystemSetting::create([
            'key' => 'app_name',
            'value' => 'SIAKAD STIH',
            'description' => 'Nama aplikasi sistem informasi akademik'
        ]);

        SystemSetting::create([
            'key' => 'campus_name',
            'value' => 'Sekolah Tinggi Ilmu Hukum',
            'description' => 'Nama kampus'
        ]);

        SystemSetting::create([
            'key' => 'max_sks',
            'value' => '24',
            'description' => 'Maksimal SKS yang dapat diambil per semester'
        ]);
    }
}
