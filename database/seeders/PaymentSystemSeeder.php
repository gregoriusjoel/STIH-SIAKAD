<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentSystemSeeder extends Seeder
{
    /**
     * Seed test users for payment system
     */
    public function run(): void
    {
        // Finance User
        User::create([
            'name' => 'Staf Keuangan',
            'email' => 'finance@stih.ac.id',
            'password' => bcrypt('password'),
            'role' => 'finance',
            'email_verified_at' => now(),
        ]);

        // Student User 1
        $student1 = User::create([
            'name' => 'Ahmad Mahasiswa',
            'email' => 'student1@stih.ac.id',
            'password' => bcrypt('password'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        Student::create([
            'user_id' => $student1->id,
            'npm' => '2024001',
            'nama' => 'Ahmad Mahasiswa',
            'prodi' => 'Ilmu Hukum',
            'angkatan' => '2024',
        ]);

        // Student User 2
        $student2 = User::create([
            'name' => 'Siti Mahasiswi',
            'email' => 'student2@stih.ac.id',
            'password' => bcrypt('password'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        Student::create([
            'user_id' => $student2->id,
            'npm' => '2024002',
            'nama' => 'Siti Mahasiswi',
            'prodi' => 'Hukum Bisnis',
            'angkatan' => '2024',
        ]);

        $this->command->info('✅ Payment system test users created!');
        $this->command->info('Finance: finance@stih.ac.id / password');
        $this->command->info('Student 1: student1@stih.ac.id / password');
        $this->command->info('Student 2: student2@stih.ac.id / password');
    }
}
