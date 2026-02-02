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
                'prodi' => 'Hukum Tata Kabupaten',
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
                'nim' => '2024010001',
                'prodi' => 'Hukum Tata Kabupaten',
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
                'nim' => '2024010002',
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
                'nim' => '2024010003',
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

        // Import locations (countries/provinces/cities) from CSVs
        // Seed religions first so profile dropdowns can use them
        $this->call(ReligionSeeder::class);

        // Import locations (countries/provinces/cities) from CSVs
        $this->call(LocationsSeeder::class);
    }
}