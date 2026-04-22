<?php

namespace App\Console\Commands;

use App\Models\KelasMataKuliah;
use App\Models\Ruangan;
use Illuminate\Console\Command;

class SyncKapasitasFromRuangan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:kapasitas-from-ruangan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync ruangan_id dan kapasitas kelas mata kuliah dari field ruang lama';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Step 1: Populate ruangan_id dari field ruang (string)...');
        
        // Step 1: Populate ruangan_id dari field ruang (string)
        $kelasWithoutRuanganId = KelasMataKuliah::whereNull('ruangan_id')->get();
        
        $populatedCount = 0;
        $notFoundRuangan = [];
        
        foreach ($kelasWithoutRuanganId as $kelas) {
            if (empty($kelas->ruang)) {
                continue;
            }
            
            // Cari ruangan berdasarkan kode_ruangan
            $ruangan = Ruangan::where('kode_ruangan', trim($kelas->ruang))
                ->orWhere('kode_ruangan', 'like', '%' . trim($kelas->ruang) . '%')
                ->first();
            
            if ($ruangan) {
                $kelas->ruangan_id = $ruangan->id;
                $kelas->save();
                $this->line("✓ Kelas (mata_kuliah_id: {$kelas->mata_kuliah_id}, kode: {$kelas->kode_kelas}): ruang '{$kelas->ruang}' → ruangan_id {$ruangan->id}");
                $populatedCount++;
            } else {
                $notFoundRuangan[] = $kelas->ruang;
            }
        }
        
        $this->info("\n✓ Populasi ruangan_id selesai: {$populatedCount} kelas diupdate");
        
        if (!empty($notFoundRuangan)) {
            $this->warn("⚠ Ruangan tidak ditemukan untuk: " . implode(', ', array_unique($notFoundRuangan)));
        }
        
        // Step 2: Sync kapasitas dari ruangan
        $this->info('\nStep 2: Sinkronisasi kapasitas dari ruangan...');
        
        $kelasDenganRuangan = KelasMataKuliah::whereNotNull('ruangan_id')->get();
        
        $syncedCount = 0;
        $mismatchedCapacity = [];
        
        foreach ($kelasDenganRuangan as $kelas) {
            $ruangan = Ruangan::find($kelas->ruangan_id);
            
            if ($ruangan) {
                if ($kelas->kapasitas != $ruangan->kapasitas) {
                    $oldKapasitas = $kelas->kapasitas;
                    $kelas->kapasitas = $ruangan->kapasitas;
                    $kelas->save();
                    
                    $this->line("✓ Kelas (mata_kuliah_id: {$kelas->mata_kuliah_id}): {$oldKapasitas} → {$ruangan->kapasitas} ({$ruangan->kode_ruangan})");
                    $syncedCount++;
                    
                    $mismatchedCapacity[] = [
                        'kelas_id' => $kelas->id,
                        'old' => $oldKapasitas,
                        'new' => $ruangan->kapasitas
                    ];
                }
            }
        }
        
        $this->info("\n✓ Selesai!");
        $this->info("• Ruangan_id dipopulate: {$populatedCount}");
        $this->info("• Kapasitas disinkronisasi: {$syncedCount}");
        
        return 0;
    }
}
