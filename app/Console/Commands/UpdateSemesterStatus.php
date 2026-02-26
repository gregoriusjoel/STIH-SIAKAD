<?php

namespace App\Console\Commands;

use App\Services\SemesterService;
use Illuminate\Console\Command;

class UpdateSemesterStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'semester:update-status 
                            {--show-status : Show current semester status without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update semester status automatically (activate new, deactivate old after grace period)';

    /**
     * Semester service
     */
    protected SemesterService $semesterService;

    /**
     * Create a new command instance.
     */
    public function __construct(SemesterService $semesterService)
    {
        parent::__construct();
        $this->semesterService = $semesterService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('╔══════════════════════════════════════════╗');
        $this->info('║   UPDATE SEMESTER STATUS - OTOMATIS      ║');
        $this->info('╚══════════════════════════════════════════╝');
        $this->newLine();

        // Show status if requested
        if ($this->option('show-status')) {
            return $this->showStatus();
        }

        // Process automatic status updates
        $this->info('🔄 Memproses update status semester...');
        $this->newLine();

        $report = $this->semesterService->processAutomaticStatusUpdates();

        // Display results
        $this->displayReport($report);

        // Return appropriate exit code
        return empty($report['errors']) ? Command::SUCCESS : Command::FAILURE;
    }

    /**
     * Show current semester status
     */
    protected function showStatus(): int
    {
        $this->info('📊 Status Semester Saat Ini:');
        $this->newLine();

        $activeSemester = $this->semesterService->getActiveSemester();

        if (!$activeSemester) {
            $this->warn('⚠️  Tidak ada semester aktif ditemukan');
            return Command::SUCCESS;
        }

        $status = $this->semesterService->getSemesterStatus($activeSemester);

        // Display active semester
        $this->table(
            ['Field', 'Value'],
            [
                ['Semester', "{$status['nama_semester']} {$status['tahun_ajaran']}"],
                ['Status DB', $activeSemester->status],
                ['Is Active', $status['is_active'] ? '✓ Ya' : '✗ Tidak'],
                ['Status Real', $this->getStatusLabel($status['status'])],
                ['Tanggal Mulai', $status['start_date']],
                ['Tanggal Selesai', $status['end_date']],
                ['Grace Period Berakhir', $status['grace_period_end']],
                ['Hari Hingga Grace Berakhir', $status['days_until_grace_end'] . ' hari'],
                ['In Grace Period', $status['is_in_grace_period'] ? '✓ Ya' : '✗ Tidak'],
                ['Show Classes', $status['should_show_classes'] ? '✓ Ya' : '✗ Tidak'],
            ]
        );

        // Show semesters in grace period
        $gracePeriodsemesters = $this->semesterService->getSemestersInGracePeriod();
        
        if ($gracePeriodsemesters->isNotEmpty()) {
            $this->newLine();
            $this->info('📅 Semester dalam Grace Period (14 hari setelah selesai):');
            
            foreach ($gracePeriodsemesters as $semester) {
                $daysLeft = $semester->getDaysUntilGracePeriodEnds();
                $this->line("  • {$semester->nama_semester} {$semester->tahun_ajaran} - {$daysLeft} hari lagi");
            }
        }

        // Show active semester IDs
        $activeSemesterIds = $this->semesterService->getActiveSemesterIds();
        $this->newLine();
        $this->info('🎯 Semester IDs dengan Kelas Aktif: ' . implode(', ', $activeSemesterIds));

        $this->newLine();
        $this->comment(SemesterService::getGracePeriodDescription());

        return Command::SUCCESS;
    }

    /**
     * Display the update report
     */
    protected function displayReport(array $report): void
    {
        // Activated semesters
        if (!empty($report['activated'])) {
            $this->info('✅ Semester Diaktifkan:');
            foreach ($report['activated'] as $semester) {
                $this->line("   • {$semester}");
            }
            $this->newLine();
        } else {
            $this->comment('• Tidak ada semester yang perlu diaktifkan');
        }

        // Deactivated semesters
        if (!empty($report['deactivated'])) {
            $this->info('🔄 Semester Dinonaktifkan (Grace Period Berakhir):');
            foreach ($report['deactivated'] as $semester) {
                $this->line("   • {$semester}");
            }
            $this->newLine();
        } else {
            $this->comment('• Tidak ada semester yang melewati grace period');
        }

        // Errors
        if (!empty($report['errors'])) {
            $this->error('❌ Error terjadi:');
            foreach ($report['errors'] as $error) {
                $this->line("   • {$error}");
            }
            $this->newLine();
        }

        // Summary
        $totalChanges = count($report['activated']) + count($report['deactivated']);
        
        if ($totalChanges > 0) {
            $this->info("✓ Selesai! {$totalChanges} perubahan status semester.");
        } else {
            $this->info('✓ Tidak ada perubahan status semester diperlukan.');
        }

        // Show current active classes info
        $activeSemesterIds = $this->semesterService->getActiveSemesterIds();
        $classCount = \App\Models\KelasMataKuliah::whereIn('semester_id', $activeSemesterIds)->count();
        
        $this->newLine();
        $this->comment("📚 Jumlah kelas aktif saat ini: {$classCount} kelas");
        $this->comment("🗓️  Dari " . count($activeSemesterIds) . " semester (aktif + grace period)");
    }

    /**
     * Get human-readable status label
     */
    protected function getStatusLabel(string $status): string
    {
        return match($status) {
            'ongoing' => '🟢 Sedang Berjalan',
            'grace_period' => '🟡 Dalam Grace Period',
            'ended' => '🔴 Berakhir',
            'upcoming' => '🔵 Akan Datang',
            default => $status
        };
    }
}
