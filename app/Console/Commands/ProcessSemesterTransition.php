<?php

namespace App\Console\Commands;

use App\Services\SemesterTransitionService;
use Illuminate\Console\Command;

class ProcessSemesterTransition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'semester:process-transition 
                            {--force : Force transition even if checks fail}
                            {--status : Show current transition status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process automatic semester transition for all active students';

    /**
     * Semester transition service
     *
     * @var SemesterTransitionService
     */
    protected $transitionService;

    /**
     * Create a new command instance.
     */
    public function __construct(SemesterTransitionService $transitionService)
    {
        parent::__construct();
        $this->transitionService = $transitionService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=====================================');
        $this->info('  SISTEM TRANSISI SEMESTER OTOMATIS');
        $this->info('=====================================');
        $this->newLine();

        // Show status if requested
        if ($this->option('status')) {
            return $this->showStatus();
        }

        // Process transition
        $this->info('Memproses transisi semester...');
        $this->newLine();

        $result = $this->transitionService->processTransition();

        if ($result['success']) {
            $this->info('✓ ' . $result['message']);
            
            if ($result['data']) {
                $this->newLine();
                $this->table(
                    ['Informasi', 'Detail'],
                    $this->formatResultData($result['data'])
                );
            }
            
            return Command::SUCCESS;
        } else {
            $this->error('✗ ' . $result['message']);
            return Command::FAILURE;
        }
    }

    /**
     * Show current transition status
     */
    protected function showStatus()
    {
        $this->info('Status Transisi Semester Saat Ini:');
        $this->newLine();

        $status = $this->transitionService->getTransitionStatus();

        if (!$status['has_active_semester']) {
            $this->warn('⚠ ' . $status['message']);
            return Command::SUCCESS;
        }

        // Display current semester info
        $current = $status['current_semester'];
        $this->info("Semester Aktif:");
        $this->line("  • Nama: {$current['nama_semester']} {$current['tahun_ajaran']}");
        $this->line("  • Mulai: {$current['tanggal_mulai']}");
        $this->line("  • Selesai: {$current['tanggal_selesai']}");
        $this->newLine();

        // Display status
        if ($status['is_ended']) {
            $this->warn("✓ Semester telah berakhir ({$status['days_remaining']} hari yang lalu)");
        } else {
            $this->info("⧗ Semester masih berjalan ({$status['days_remaining']} hari lagi)");
        }
        $this->newLine();

        // Display next semester if available
        if ($status['next_semester']) {
            $next = $status['next_semester'];
            $this->info("Semester Berikutnya:");
            $this->line("  • Nama: {$next['nama_semester']} {$next['tahun_ajaran']}");
            $this->line("  • Mulai: {$next['tanggal_mulai']}");
        } else {
            $this->warn("⚠ Semester berikutnya belum tersedia");
        }
        $this->newLine();

        // Transition readiness
        if ($status['ready_for_transition']) {
            $this->info('✓ Sistem siap melakukan transisi semester');
        } else {
            $this->comment('○ Sistem belum siap untuk transisi');
        }

        return Command::SUCCESS;
    }

    /**
     * Format result data for table display
     */
    protected function formatResultData(array $data): array
    {
        $formatted = [];

        foreach ($data as $key => $value) {
            $label = ucwords(str_replace('_', ' ', $key));
            $formatted[] = [$label, $value];
        }

        return $formatted;
    }
}
