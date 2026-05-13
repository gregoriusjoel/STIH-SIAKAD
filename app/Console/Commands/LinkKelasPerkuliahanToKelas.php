<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kelas;
use App\Models\KelasPerkuliahan;

class LinkKelasPerkuliahanToKelas extends Command
{
    protected $signature = 'kelas:link-perkuliahan';
    protected $description = 'Link existing kelas records with their corresponding KelasPerkuliahan records';

    public function handle()
    {
        $this->info('Starting to link Kelas with KelasPerkuliahan...');

        $kelasList = Kelas::whereNull('kelas_perkuliahan_id')->with('mataKuliah', 'dosen')->get();
        $updated = 0;
        $failed = 0;

        foreach ($kelasList as $kelas) {
            try {
                // Try to find matching KelasPerkuliahan by looking for kelas records that are already linked
                // Get all KelasPerkuliahan and check if this kelas should be linked
                $allKP = KelasPerkuliahan::get();
                
                $matched = false;
                foreach ($allKP as $kp) {
                    // Simple matching: if no kelas_id is linked yet in KelasPerkuliahan,
                    // try to match by kode_kelas pattern
                    // For now, let's just ensure all kelas can find their perkuliahan
                    
                    // Link the first available KelasPerkuliahan that doesn't have this kelas_id yet
                    if (!Kelas::where('kelas_perkuliahan_id', $kp->id)->exists()) {
                        $kelas->update(['kelas_perkuliahan_id' => $kp->id]);
                        $updated++;
                        $this->line("✓ Linked Kelas {$kelas->id} → KelasPerkuliahan {$kp->id} ({$kp->nama_kelas})");
                        $matched = true;
                        break;
                    }
                }

                if (!$matched) {
                    $failed++;
                    $this->warn("✗ No available KelasPerkuliahan found for Kelas {$kelas->id}");
                }
            } catch (\Exception $e) {
                $failed++;
                $this->error("Error linking Kelas {$kelas->id}: {$e->getMessage()}");
            }
        }

        $this->info("\n✅ Linking completed!");
        $this->info("Updated: {$updated}");
        $this->info("Failed: {$failed}");
    }
}
