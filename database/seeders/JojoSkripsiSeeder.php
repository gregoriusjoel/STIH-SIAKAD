<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa;
use App\Models\Krs;
use App\Models\Nilai;

class JojoSkripsiSeeder extends Seeder
{
    public function run(): void
    {
        $nim = '50421684';
        $now = now();

        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        if (!$mahasiswa) {
            $this->command->error("Mahasiswa with NIM {$nim} (Jojo) not found!");
            return;
        }

        $this->command->info("Found Student: {$mahasiswa->nama} (ID: {$mahasiswa->id}, NIM: {$mahasiswa->nim})");

        // Fetch all KRS for Jojo
        $krsEntries = Krs::where('mahasiswa_id', $mahasiswa->id)->get();
        $totalKrs = $krsEntries->count();

        $this->command->info("Processing {$totalKrs} KRS entries for Jojo...");

        $kelasIdDefault = DB::table('kelas')->min('id') ?? 1;
        $adminUserId = DB::table('users')->where('role', 'admin')->first()?->id ?? 1;

        $approvedCount = 0;
        $nilaiCount = 0;

        foreach ($krsEntries as $krs) {
            // Update KRS status to approved
            $krs->update(['status' => 'approved']);
            $approvedCount++;

            // Create realistic random grade components
            $partisipatif = mt_rand(7500, 9800) / 100;
            $proyek = mt_rand(7500, 9800) / 100;
            $quiz = mt_rand(7000, 9800) / 100;
            $tugas = mt_rand(7500, 9800) / 100;
            $uts = mt_rand(7000, 9600) / 100;
            $uas = mt_rand(7000, 9600) / 100;

            // Calculate final grade
            $nilaiAkhir = round(($partisipatif + $proyek + $quiz + $tugas + $uts + $uas) / 6, 2);

            // Convert to letter grade and bobot using SIAKAD convertToGrade
            $gradeData = Nilai::convertToGrade($nilaiAkhir);

            // Determine kelas_id
            $kelasId = $krs->kelas_id ?? $kelasIdDefault;

            // Insert or Update Nilai record
            DB::table('nilai')->updateOrInsert(
                ['krs_id' => $krs->id],
                [
                    'kelas_id' => $kelasId,
                    'nilai_partisipatif' => $partisipatif,
                    'nilai_proyek' => $proyek,
                    'nilai_quiz' => $quiz,
                    'nilai_tugas' => $tugas,
                    'nilai_uts' => $uts,
                    'nilai_uas' => $uas,
                    'nilai_akhir' => $nilaiAkhir,
                    'grade' => $gradeData['grade'],
                    'bobot' => $gradeData['bobot'],
                    'is_published' => 1,
                    'published_at' => $now,
                    'published_by' => $adminUserId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
            $nilaiCount++;
        }

        $this->command->info("Successfully approved {$approvedCount} KRS entries and seeded {$nilaiCount} realistic Nilai records for Jojo.");
    }
}
