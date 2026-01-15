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

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    
    // Mahasiswa Management
    Route::resource('mahasiswa', App\Http\Controllers\Admin\MahasiswaController::class);
    
    // Dosen Management
    Route::resource('dosen', App\Http\Controllers\Admin\DosenController::class);
    
    // Parent Management
    Route::resource('parents', App\Http\Controllers\Admin\ParentController::class);
    
    // Mata Kuliah Management
    Route::resource('mata-kuliah', App\Http\Controllers\Admin\MataKuliahController::class);
    
    // Kelas Mata Kuliah Management
    Route::resource('kelas-mata-kuliah', App\Http\Controllers\Admin\KelasMataKuliahController::class);
    
    // Jadwal Management
    Route::resource('jadwal', App\Http\Controllers\Admin\JadwalController::class);
    
    // Semester Management
    Route::resource('semester', App\Http\Controllers\Admin\SemesterController::class);
    // Global search
    Route::get('search', [App\Http\Controllers\Admin\SearchController::class, 'index'])->name('search');
    
    // KRS Management
    Route::get('krs', [App\Http\Controllers\Admin\KrsController::class, 'index'])->name('krs.index');
    Route::get('krs/{kr}', [App\Http\Controllers\Admin\KrsController::class, 'show'])->name('krs.show');
    Route::put('krs/{kr}/status', [App\Http\Controllers\Admin\KrsController::class, 'updateStatus'])->name('krs.updateStatus');
    Route::delete('krs/{kr}', [App\Http\Controllers\Admin\KrsController::class, 'destroy'])->name('krs.destroy');
});
