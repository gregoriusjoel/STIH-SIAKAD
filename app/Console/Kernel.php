<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\ImportLocationsCommand::class,
        \App\Console\Commands\ProcessSemesterTransition::class,
        \App\Console\Commands\SyncInternshipStatusCommand::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run semester transition check daily at 00:01 AM
        $schedule->command('semester:process-transition')
            ->dailyAt('00:01')
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/semester-transition.log'));

        // Auto-sync internship status based on start/end dates — runs daily at 00:05
        $schedule->command('magang:sync-status')
            ->dailyAt('00:05')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/internship-sync.log'));

        // Optional: Send reminder 7 days before semester ends
        // $schedule->command('semester:process-transition --status')
        //     ->dailyAt('08:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
