<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\App\Http\Middleware\ConvertEmptyStringsToNull::class);
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        // Override XSRF-TOKEN cookie jadi HttpOnly. Wajib di-append ke grup `web`
        // agar jalan setelah VerifyCsrfToken yang menulis cookie tersebut.
        $middleware->appendToGroup('web', \App\Http\Middleware\SecureCsrfCookie::class);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'dosen' => \App\Http\Middleware\DosenMiddleware::class,
            'parent.role' => \App\Http\Middleware\ParentMiddleware::class,
            'finance' => \App\Http\Middleware\FinanceMiddleware::class,
            'mahasiswa.status' => \App\Http\Middleware\CheckMahasiswaStatus::class,
            'semester.lock' => \App\Http\Middleware\CheckSemesterLock::class,
            'check.semester.kuesioner' => \App\Http\Middleware\CheckSemesterKuesioner::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e) {
            return back()->with('warning', '⚠️ Ukuran total unggahan terlalu besar. Batas maksimal total adalah 15 MB. Silakan kurangi jumlah atau ukuran file yang Anda unggah.');
        });
    })->create();
