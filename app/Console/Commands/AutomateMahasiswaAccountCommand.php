<?php

namespace App\Console\Commands;

use App\Models\Mahasiswa;
use App\Services\StudentAccountService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutomateMahasiswaAccountCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'mahasiswa:automate-account 
                            {--dry-run : Tampilkan apa yang akan dijalankan tanpa save}
                            {--force : Regenerate email kampus untuk semua, bahkan yang sudah ada}
                            {--batch-size=100 : Jumlah records per batch}';

    /**
     * The console command description.
     */
    protected $description = 'Otomasi pembuatan akun mahasiswa: generate email kampus & set password default';

    public function handle(StudentAccountService $accountService): int
    {
        $this->info('');
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║   Otomasi Akun Mahasiswa - Email & Password Setup          ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->info('');

        $dryRun = $this->option('dry-run');
        $forceRegenerate = $this->option('force');
        $batchSize = (int) $this->option('batch-size');

        if ($dryRun) {
            $this->warn('🔍 DRY RUN MODE - Tidak ada yang disimpan');
            $this->info('');
        }

        // Hitung berapa mahasiswa yang perlu di-automate
        $query = Mahasiswa::query();

        if (!$forceRegenerate) {
            $query->whereNull('email_kampus'); // Hanya yang belum ada email kampus
        }

        $totalCount = $query->count();

        if ($totalCount === 0) {
            $this->info('✅ Semua mahasiswa sudah ter-automate');
            if (!$forceRegenerate) {
                $this->line('Gunakan --force untuk regenerate email kampus yang sudah ada');
            }
            return self::SUCCESS;
        }

        $this->line("📋 Total mahasiswa yang akan diproses: <fg=cyan>{$totalCount}</>");
        $this->line('');

        if (!$dryRun && !$this->confirm('Lanjutkan otomasi?')) {
            $this->info('Dibatalkan.');
            return self::SUCCESS;
        }

        // Proses per batch
        $successCount = 0;
        $failedCount = 0;
        $errors = [];
        $processedIds = [];

        $query = Mahasiswa::query();
        if (!$forceRegenerate) {
            $query->whereNull('email_kampus');
        }

        $bar = $this->output->createProgressBar($totalCount);
        $bar->start();

        $query->chunk($batchSize, function ($mahasiswas) use (
            $accountService,
            $dryRun,
            $forceRegenerate,
            &$successCount,
            &$failedCount,
            &$errors,
            &$processedIds,
            &$bar
        ) {
            foreach ($mahasiswas as $mahasiswa) {
                try {
                    if ($dryRun) {
                        // Preview saja
                        $status = $accountService->getAccountStatus($mahasiswa);
                        $processedIds[] = $mahasiswa->id;
                        $successCount++;
                    } else {
                        // Sungguhan
                        $accountService->automateStudentAccount(
                            $mahasiswa,
                            $forceRegenerate
                        );
                        $processedIds[] = $mahasiswa->id;
                        $successCount++;
                    }
                } catch (\Throwable $e) {
                    $failedCount++;
                    $errors[] = "NIM {$mahasiswa->nim}: {$e->getMessage()}";
                }

                $bar->advance();
            }
        });

        $bar->finish();
        $this->line('');
        $this->line('');

        // Summary
        $this->info('✅ Otomasi selesai!');
        $this->line("   • Berhasil: <fg=green>{$successCount}</>");
        $this->line("   • Gagal: <fg=red>{$failedCount}</>");

        if (count($errors) > 0) {
            $this->line('');
            $this->warn('⚠️  Error yang terjadi:');
            foreach ($errors as $error) {
                $this->line("   • {$error}");
            }
        }

        $this->line('');

        if ($dryRun) {
            $this->line('<fg=yellow>💡 Ini adalah DRY RUN - data tidak disimpan</>', 1);
            $this->line('   Jalankan tanpa --dry-run untuk menyimpan perubahan', 1);
        } else {
            $this->info('💾 Data sudah disimpan ke database');
        }

        $this->line('');

        return $failedCount > 0 ? self::FAILURE : self::SUCCESS;
    }
}
