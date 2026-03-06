<?php

namespace App\Console\Commands;

use App\Models\Internship;
use App\Services\InternshipWorkflowService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Artisan command: magang:sync-status
 *
 * Otomatis memperbarui status magang berdasarkan tanggal:
 * - ACCEPTANCE_LETTER_READY → ONGOING   (jika periode_mulai <= hari ini)
 * - ONGOING                → COMPLETED  (jika periode_selesai <  hari ini)
 *
 * Jalankan setiap hari via cron (lihat Console/Kernel.php):
 *   php artisan magang:sync-status
 */
class SyncInternshipStatusCommand extends Command
{
    protected $signature   = 'magang:sync-status {--dry-run : Preview changes without saving}';
    protected $description = 'Auto-transition internship status based on start/end dates.';

    public function __construct(private InternshipWorkflowService $workflow)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $today  = now()->startOfDay();

        $this->info("[magang:sync-status] Date: {$today->toDateString()}");
        $changed = 0;

        // ── 1. ACCEPTANCE_LETTER_READY → ONGOING ─────────────────────────────
        $readyToStart = Internship::where('status', Internship::STATUS_ACCEPTANCE_LETTER_READY)
            ->whereNotNull('periode_mulai')
            ->whereDate('periode_mulai', '<=', $today)
            ->get();

        foreach ($readyToStart as $internship) {
            if ($dryRun) {
                $name = $internship->mahasiswa?->user?->name ?? '?';
                $this->line("  [DRY] #{$internship->id} {$name} → ONGOING");
                continue;
            }
            try {
                $this->workflow->startInternship($internship);
                $this->line("  ✓ #{$internship->id} → ONGOING");
                Log::info("magang:sync-status: internship #{$internship->id} auto-started.");
                $changed++;
            } catch (\Throwable $e) {
                $this->warn("  ✗ #{$internship->id} gagal: " . $e->getMessage());
                Log::error("magang:sync-status: gagal start #{$internship->id}: " . $e->getMessage());
            }
        }

        // ── 2. ONGOING → COMPLETED ────────────────────────────────────────────
        $readyToComplete = Internship::where('status', Internship::STATUS_ONGOING)
            ->whereNotNull('periode_selesai')
            ->whereDate('periode_selesai', '<', $today)
            ->get();

        foreach ($readyToComplete as $internship) {
            if ($dryRun) {
                $name = $internship->mahasiswa?->user?->name ?? '?';
                $this->line("  [DRY] #{$internship->id} {$name} → COMPLETED");
                continue;
            }
            try {
                $this->workflow->markCompleted($internship);
                $this->line("  ✓ #{$internship->id} → COMPLETED");
                Log::info("magang:sync-status: internship #{$internship->id} auto-completed.");
                $changed++;
            } catch (\Throwable $e) {
                $this->warn("  ✗ #{$internship->id} gagal: " . $e->getMessage());
                Log::error("magang:sync-status: gagal complete #{$internship->id}: " . $e->getMessage());
            }
        }

        $this->info("[magang:sync-status] Selesai. {$changed} status diperbarui.");

        return self::SUCCESS;
    }
}
