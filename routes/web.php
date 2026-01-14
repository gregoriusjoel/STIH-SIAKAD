<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Root redirect
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect('/login');
});

// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Dosen / Lecturer Portal Routes
Route::prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Dosen\LecturerController::class, 'dashboard'])->name('dashboard');
    Route::get('/kelas', [App\Http\Controllers\Dosen\LecturerController::class, 'classes'])->name('kelas');
    Route::get('/kelas/{id}/absensi', [App\Http\Controllers\Dosen\LecturerController::class, 'absensi'])->name('kelas.absensi');
    Route::get('/kelas/{id}/detail', [App\Http\Controllers\Dosen\LecturerController::class, 'detail'])->name('kelas.detail');
    Route::get('/krs', [App\Http\Controllers\Dosen\LecturerController::class, 'krs'])->name('krs');
    Route::get('/input-nilai', [App\Http\Controllers\Dosen\LecturerController::class, 'inputNilai'])->name('input-nilai');
    Route::get('/mahasiswa', [App\Http\Controllers\Dosen\LecturerController::class, 'students'])->name('mahasiswa');
});
