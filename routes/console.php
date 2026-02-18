<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Semester Transition Scheduler
Schedule::command('semester:process-transition')
    ->dailyAt('00:01') // PRODUCTION MODE: Runs daily at 00:01 AM
    // ->everyMinute() // TEST MODE: Uncomment for testing
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/semester-transition.log'));
