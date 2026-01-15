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
    }
}
