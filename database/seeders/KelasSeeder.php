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
        // Get all dosen users
        $dosens = User::where('role', 'dosen')->get();
        
        if ($dosens->isEmpty()) {
            $this->command->warn('No dosen user found. Skipping KelasSeeder.');
            return;
        }

        // Current academic year and semester
        $tahunAjaran = '2024/2025';
        $semesterType = 'Ganjil'; // Change to 'Genap' for even semester
        
        $this->command->info("Creating kelas for {$tahunAjaran} - {$semesterType}");
        
        // Get all mata kuliah
        $mataKuliahs = MataKuliah::all();
        
        if ($mataKuliahs->isEmpty()) {
            $this->command->warn('No mata kuliah found. Please seed mata kuliah first.');
            return;
        }

        $sections = ['A', 'B', 'C', 'D'];
        $kelasCount = 0;

        foreach ($mataKuliahs as $mk) {
            // Determine if this mata kuliah should be offered this semester
            // based on kode_id (sms1,3,5,7 = Ganjil; sms2,4,6,8 = Genap)
            $kodeIdNum = null;
            if (preg_match('/sms(\d+)/', $mk->kode_id, $matches)) {
                $kodeIdNum = (int)$matches[1];
            }
            
            // Skip if this mata kuliah doesn't match the current semester type
            if ($kodeIdNum !== null) {
                $isOddSemester = ($kodeIdNum % 2 == 1);
                if ($isOddSemester && $semesterType !== 'Ganjil') {
                    continue; // Skip odd semester courses in even semester
                }
                if (!$isOddSemester && $semesterType !== 'Genap') {
                    continue; // Skip even semester courses in odd semester
                }
            }
            
            // Create 1-2 sections for each mata kuliah
            $numSections = rand(1, 2);
            
            for ($i = 0; $i < $numSections; $i++) {
                $section = $sections[$i];
                
                // Assign a random dosen
                $dosen = $dosens->random();
                
                Kelas::updateOrCreate(
                    [
                        'mata_kuliah_id' => $mk->id,
                        'section' => $section,
                        'tahun_ajaran' => $tahunAjaran,
                        'semester_type' => $semesterType,
                    ],
                    [
                        'dosen_id' => $dosen->id,
                        'kapasitas' => rand(30, 50),
                    ]
                );
                
                $kelasCount++;
                $this->command->info("Created: [{$mk->kode_id}] {$mk->nama_mk} - Section {$section} - Dosen: {$dosen->name}");
            }
        }
        
        $this->command->info("Kelas seeder completed! Created {$kelasCount} classes.");
    }
}
