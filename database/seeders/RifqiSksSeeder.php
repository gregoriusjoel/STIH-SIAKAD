<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\KRS;
use App\Models\MataKuliah;

class RifqiSksSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswaId = 4;
        $now         = now();

        // Step 1: Give nilai A to all existing KRS
        $existingKrs = KRS::where('mahasiswa_id', $mahasiswaId)->get();
        foreach ($existingKrs as $krs) {
            $krs->update(['status' => 'approved']);
            DB::table('nilai')->updateOrInsert(
                ['krs_id' => $krs->id],
                $this->nilaiRow($now)
            );
        }

        $currentSks = MataKuliah::whereIn('id', $existingKrs->pluck('mata_kuliah_id'))->sum('sks');
        $this->command->info("SKS from existing KRS: {$currentSks}");

        // Step 2: Add more KRS+nilai until >= 120 SKS
        $needed    = 120 - $currentSks;
        $usedMkIds = $existingKrs->pluck('mata_kuliah_id')->toArray();
        $more      = MataKuliah::whereNotIn('id', $usedMkIds)->orderBy('id')->get();

        $addedSks = 0;
        $added    = 0;
        foreach ($more as $mk) {
            if ($addedSks >= $needed) break;

            $krs = KRS::create([
                'mahasiswa_id'   => $mahasiswaId,
                'mata_kuliah_id' => $mk->id,
                'kelas_id'       => null,
                'semester_id'    => null,
                'status'         => 'approved',
                'ambil_mk'       => 'ya',
            ]);

            DB::table('nilai')->insert(array_merge(
                ['krs_id' => $krs->id],
                $this->nilaiRow($now)
            ));

            $addedSks += $mk->sks;
            $added++;
        }

        $total = $currentSks + $addedSks;
        $this->command->info("Added {$added} KRS entries (+{$addedSks} SKS). Total: {$total} SKS");
    }

    private function nilaiRow($now): array
    {
        $kelasId = (int) \DB::table('kelas')->min('id');
        return [
            'kelas_id'            => $kelasId,
            'grade'               => 'A',
            'nilai_akhir'         => 90,
            'nilai_uas'           => 90,
            'nilai_uts'           => 85,
            'nilai_tugas'         => 90,
            'nilai_quiz'          => 88,
            'nilai_proyek'        => 90,
            'nilai_partisipatif'  => 90,
            'bobot'               => 4.00,
            'is_published'        => 1,
            'published_at'        => $now,
            'created_at'          => $now,
            'updated_at'          => $now,
        ];
    }
}
