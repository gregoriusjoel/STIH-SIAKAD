<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Semester Status Update (runs daily to check grace period and activate/deactivate semesters)
Schedule::command('semester:update-status')
    ->dailyAt('00:00') // Runs at midnight to update semester status
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/semester-status-update.log'));

// Semester Transition (triggers when semester ends and increments mahasiswa semester)
Schedule::command('semester:process-transition')
    ->dailyAt('00:01') // Runs after status update
    // ->everyMinute() // TEST MODE: Uncomment for testing
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/semester-transition.log'));

// Process Email Outbox
Schedule::command('email:process-outbox')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/email-outbox.log'));
