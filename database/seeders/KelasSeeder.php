<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\User;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the dosen user
        $dosen = User::where('role', 'dosen')->first();
        
        if (!$dosen) {
            $this->command->warn('No dosen user found. Skipping KelasSeeder.');
            return;
        }

        $mataKuliahs = MataKuliah::all();
        $sections = ['IF-A', 'IF-B', 'IF-C', 'IF-D', 'SI-A', 'SI-B'];

        foreach ($mataKuliahs->take(6) as $index => $mk) {
            // Create 1-2 classes per course
            Kelas::updateOrCreate(
                [
                    'mata_kuliah_id' => $mk->id,
                    'dosen_id' => $dosen->id,
                    'section' => $sections[$index % count($sections)],
                ],
                [
                    'kapasitas' => 40,
                    'tahun_ajaran' => '2023/2024',
                    'semester_type' => 'Ganjil',
                ]
            );

            // Add second section for some courses
            if ($index < 3) {
                Kelas::updateOrCreate(
                    [
                        'mata_kuliah_id' => $mk->id,
                        'dosen_id' => $dosen->id,
                        'section' => $sections[($index + 1) % count($sections)],
                    ],
                    [
                        'kapasitas' => 40,
                        'tahun_ajaran' => '2023/2024',
                        'semester_type' => 'Ganjil',
                    ]
                );
            }
        }
    }
}
