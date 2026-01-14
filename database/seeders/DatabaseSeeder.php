<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test Mahasiswa',
            'email' => 'mahasiswa@example.com',
            'password' => 'mahasiswa123',
            'role' => 'mahasiswa'
        ]);
        User::factory()->create([
            'name' => 'Test Dosen',
            'email' => 'dosen@example.com',
            'password' => 'dosen123',
            'role' => 'dosen'
        ]);
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'admin123',
            'role' => 'admin'
        ]);
        User::factory()->create([
            'name' => 'Test Parent',
            'email' => 'parent@example.com',
            'password' => 'parent123',
            'role' => 'parent'
        ]);
    }
}
