<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dosen\LecturerController;
use App\Http\Controllers\Dosen\JadwalController;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Root redirect
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dosen.dashboard');
    }
    return redirect('/login');
});

// Dosen / Lecturer Portal Routes
Route::prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [LecturerController::class, 'dashboard'])->name('dashboard');
    Route::get('/kelas', [LecturerController::class, 'classes'])->name('kelas');
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal')->middleware('auth');
    Route::get('/jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create')->middleware('auth');
    Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store')->middleware('auth');
    Route::get('/kelas/{id}/absensi', [LecturerController::class, 'absensi'])->name('kelas.absensi');
    Route::get('/kelas/{id}/detail', [LecturerController::class, 'detail'])->name('kelas.detail');
    Route::get('/krs', [LecturerController::class, 'krs'])->name('krs');
    Route::get('/input-nilai', [LecturerController::class, 'inputNilai'])->name('input-nilai');
    Route::get('/mahasiswa', [LecturerController::class, 'students'])->name('mahasiswa');
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
    Route::post('jadwal/{jadwal}/approve', [App\Http\Controllers\Admin\JadwalController::class, 'approve'])->name('jadwal.approve');
    Route::post('jadwal/{jadwal}/reject', [App\Http\Controllers\Admin\JadwalController::class, 'reject'])->name('jadwal.reject');
    Route::post('jadwal/{jadwal}/assign-room', [App\Http\Controllers\Admin\JadwalController::class, 'assignRoom'])->name('jadwal.assignRoom');
    
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
