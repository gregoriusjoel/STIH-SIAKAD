<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

/**
 * PopulateMataKuliahTipeSeeder
 * 
 * Normalize mata_kuliah data:
 * - Set all tipe to 'teori' (praktikum is just a flag, not a tipe)
 * - Normalize praktikum to 0/1 (0 = no practice, 1 = has practice)
 * - Jadwal praktikum detail ditentukan oleh dosen kemudian
 * 
 * This seeder is idempotent and can be run multiple times safely.
 * 
 * Usage:
 *   php artisan db:seed --class=PopulateMataKuliahTipeSeeder
 */
class PopulateMataKuliahTipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->line('');
        $this->command->info('🔄 Normalizing mata_kuliah data...');
        $this->command->line('');

        $updated = 0;
        $failed = 0;
        $total = MataKuliah::count();

        // Show progress bar
        $bar = $this->command->getOutput()->createProgressBar($total);
        $bar->start();

        try {
            MataKuliah::chunkById(100, function ($mataKuliahs) use (&$updated, &$failed, $bar) {
                foreach ($mataKuliahs as $mk) {
                    try {
                        // Always set tipe to 'teori' (praktikum is just a flag)
                        $tipe = 'teori';
                        
                        // Normalize praktikum to 0/1 (0 = no practice, 1 = has practice)
                        $praktikum = (!empty($mk->praktikum)) ? 1 : 0;
                        
                        // Update if tipe is different or praktikum needs normalization
                        if ($mk->tipe !== $tipe || $mk->praktikum != $praktikum) {
                            $mk->update([
                                'tipe' => $tipe,
                                'praktikum' => $praktikum
                            ]);
                            $updated++;
                        }
                    } catch (\Exception $e) {
                        $failed++;
                        $this->command->error("\n❌ Failed to update mata_kuliah ID {$mk->id}: {$e->getMessage()}");
                    }
                    $bar->advance();
                }
            });

            $bar->finish();
            $this->command->line('');
            $this->command->line('');

            // Summary
            $this->command->info('✅ Normalization Complete!');
            $this->command->line("  Total Processed: {$total}");
            $this->command->line("  <fg=green>Updated: {$updated}</>");
            if ($failed > 0) {
                $this->command->line("  <fg=red>Failed: {$failed}</>");
            }
            $this->command->line('');

            // Show breakdown by praktikum
            $breakdown = MataKuliah::selectRaw('praktikum, COUNT(*) as count')
                ->groupBy('praktikum')
                ->orderBy('praktikum')
                ->get();

            $this->command->info('📊 Breakdown by praktikum:');
            foreach ($breakdown as $item) {
                $label = $item->praktikum == 1 ? 'Dengan Praktikum' : 'Tanpa Praktikum';
                $this->command->line("  • {$label}: {$item->count}");
            }
            $this->command->line('');
        } catch (\Exception $e) {
            $this->command->error("❌ Error during seeding: {$e->getMessage()}");
            throw $e;
        }
    }
}
