<?php

namespace App\Console\Commands;

use App\Services\KelasPerkuliahanService;
use Illuminate\Console\Command;

class MigrateKelasPerkuliahan extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'kelas:migrate-legacy
                            {--dry-run : Show what would be migrated without actually migrating}';

    /**
     * The console command description.
     */
    protected $description = 'Migrate legacy class data (kelas.section, kelas_mata_kuliahs.kode_kelas) to kelas_perkuliahans';

    public function __construct(
        protected KelasPerkuliahanService $service
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('');
        $this->info('╔══════════════════════════════════════════╗');
        $this->info('║   Migrasi Data Legacy → Kelas Perkuliahan ║');
        $this->info('╚══════════════════════════════════════════╝');
        $this->info('');

        if ($this->option('dry-run')) {
            $this->warn('🔍 DRY RUN MODE — tidak ada data yang diubah.');
            $this->info('');

            // Preview legacy data
            $kelasCount = \App\Models\Kelas::whereNull('kelas_perkuliahan_id')
                ->whereNotNull('section')
                ->where('section', '!=', '')
                ->count();

            $kmkCount = \App\Models\KelasMataKuliah::whereNull('kelas_perkuliahan_id')
                ->whereNotNull('kode_kelas')
                ->where('kode_kelas', '!=', '')
                ->count();

            $this->info("📋 Data yang akan dimigrasi:");
            $this->info("   • Kelas (legacy):         {$kelasCount} record");
            $this->info("   • Kelas Mata Kuliah:      {$kmkCount} record");
            $this->info("   • Total:                  " . ($kelasCount + $kmkCount) . " record");
            $this->info('');

            return self::SUCCESS;
        }

        if (!$this->confirm('Jalankan migrasi data? Data lama TIDAK akan dihapus.')) {
            $this->info('Dibatalkan.');
            return self::SUCCESS;
        }

        $this->info('⏳ Menjalankan migrasi...');
        $this->info('');

        try {
            $result = $this->service->migrateFromLegacy();

            $this->info("✅ Migrasi selesai!");
            $this->info("   • Berhasil dimigrasi:  {$result['migrated']}");
            $this->info("   • Dilewati (sudah ada): {$result['skipped']}");

            if (count($result['failed']) > 0) {
                $this->warn("   • Gagal:                " . count($result['failed']));
                $this->info('');
                $this->warn('⚠ Data yang gagal diproses:');
                foreach ($result['failed'] as $i => $msg) {
                    $this->line("   " . ($i + 1) . ". {$msg}");
                }
            }

            $this->info('');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("❌ Error: {$e->getMessage()}");
            return self::FAILURE;
        }
    }
}
