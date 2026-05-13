<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Krs;
use App\Models\Mahasiswa;

class PopulateKrsTahunAjaran extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:populate-krs-tahun-ajaran';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate tahun_ajaran for existing KRS records based on student angkatan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get all KRS records with NULL tahun_ajaran
        $krsRecords = Krs::whereNull('tahun_ajaran')
            ->with(['mahasiswa', 'mataKuliah'])
            ->get();

        $this->info("Found " . $krsRecords->count() . " KRS records with NULL tahun_ajaran");

        $updated = 0;
        $skipped = 0;

        foreach ($krsRecords as $krs) {
            try {
                $mahasiswa = $krs->mahasiswa;
                if (!$mahasiswa || !$mahasiswa->angkatan) {
                    $this->warn("KRS #{$krs->id}: No mahasiswa or angkatan found");
                    $skipped++;
                    continue;
                }

                // Try to get semester from mata_kuliah kode_id (e.g., 'sms3' -> semester 3)
                $semester = null;
                if ($krs->mataKuliah && $krs->mataKuliah->kode_id) {
                    if (preg_match('/sms(\d+)/', $krs->mataKuliah->kode_id, $matches)) {
                        $semester = (int) $matches[1];
                    }
                }

                // If no semester from kode_id, use student's current semester
                if (!$semester) {
                    $semester = $mahasiswa->getCurrentSemester() ?? 1;
                }

                // Calculate tahun_ajaran
                $baseYear = (int) $mahasiswa->angkatan;
                $yearOffset = floor(($semester - 1) / 2);
                $academicStartYear = $baseYear + $yearOffset;
                $academicEndYear = $academicStartYear + 1;
                $tahunAjaran = $academicStartYear . '/' . $academicEndYear;

                // Update the KRS record
                $krs->update(['tahun_ajaran' => $tahunAjaran]);
                $this->info("KRS #{$krs->id}: Updated with tahun_ajaran={$tahunAjaran}");
                $updated++;
            } catch (\Exception $e) {
                $this->error("KRS #{$krs->id}: Error - " . $e->getMessage());
                $skipped++;
            }
        }

        $this->info("\n✅ Completed! Updated: $updated, Skipped: $skipped");
    }
}
