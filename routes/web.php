<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dosen\LecturerController;
use App\Http\Controllers\Dosen\JadwalController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\AktivasiController;
use App\Http\Controllers\Mahasiswa\KRSController;
use App\Http\Controllers\Mahasiswa\NilaiController;
use App\Http\Controllers\Mahasiswa\JadwalController as MahasiswaJadwalController;
use App\Http\Controllers\Mahasiswa\PembayaranController;
use App\Http\Controllers\Mahasiswa\ProfilController;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/test-500', function () {
    abort(500);
});

// Root redirect
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'mahasiswa') {
            return redirect()->route('mahasiswa.dashboard');
        } elseif ($user->role === 'parent') {
            return redirect()->route('parent.dashboard');
        }
        return redirect()->route('dosen.dashboard');
    }
    return redirect('/login');
});

// Public QR image and redirect routes
Route::get('/qrcode/kelas/{token}/image', [App\Http\Controllers\QrController::class, 'image'])->name('qrcode.kelas.image');
Route::get('/kelas/qr-redirect/{token}', [App\Http\Controllers\QrController::class, 'redirect'])->name('qrcode.kelas.redirect');

// Public attendance form via QR
Route::get('/absensi/kelas/{token}', [App\Http\Controllers\AttendanceController::class, 'showForm'])->name('absensi.form');
Route::post('/absensi/kelas/{token}', [App\Http\Controllers\AttendanceController::class, 'store'])->name('absensi.submit');
Route::get('/absensi/terima-kasih', [App\Http\Controllers\AttendanceController::class, 'thanks'])->name('absensi.thanks');

// Dosen / Lecturer Portal Routes
Route::prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [LecturerController::class, 'dashboard'])->name('dashboard');
    Route::get('/kelas', [LecturerController::class, 'classes'])->name('kelas');
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal')->middleware('auth');
    Route::post('/jadwal/{jadwal}/reschedule', [JadwalController::class, 'reschedule'])->name('jadwal.reschedule')->middleware('auth');
    Route::post('/jadwal/reschedule', [JadwalController::class, 'rescheduleGeneric'])->name('jadwal.reschedule.generic')->middleware('auth');
    Route::post('/kelas/reschedule', [JadwalController::class, 'kelasReschedule'])->name('kelas.reschedule')->middleware('auth');
    Route::get('/kelas/{id}/absensi', [LecturerController::class, 'absensi'])->name('kelas.absensi');
    Route::get('/kelas/{id}/detail', [LecturerController::class, 'detail'])->name('kelas.detail');
    Route::get('/kelas/{id}/pertemuan/{pertemuan}', [LecturerController::class, 'meetingDetail'])->name('kelas.pertemuan.detail');
    Route::get('/kelas/{id}/pertemuan/{pertemuan}/materi', [LecturerController::class, 'meetingMaterials'])->name('kelas.pertemuan.materi');
    Route::post('/kelas/{id}/generate-qr', [LecturerController::class, 'generateQr'])->name('kelas.generate_qr');
    Route::get('/krs', [LecturerController::class, 'krs'])->name('krs');
    Route::get('/input-nilai', [LecturerController::class, 'inputNilai'])->name('input-nilai');
    Route::get('/mahasiswa', [LecturerController::class, 'students'])->name('mahasiswa');
});

// Mahasiswa Portal Routes
Route::prefix('mahasiswa')->name('mahasiswa.')->middleware(['auth'])->group(function () {
    // Aktivasi (tidak perlu middleware status check)
    Route::get('/aktivasi', [AktivasiController::class, 'index'])->name('aktivasi.index');
    Route::post('/aktivasi', [AktivasiController::class, 'store'])->name('aktivasi.store');

    // Routes yang memerlukan status check
    Route::middleware(['mahasiswa.status'])->group(function () {
        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');

        // KRS
        Route::get('/krs', [KRSController::class, 'index'])->name('krs.index');
        Route::get('/krs/confirm', [KRSController::class, 'confirm'])->name('krs.confirm');
        Route::post('/krs', [KRSController::class, 'store'])->name('krs.store');
        Route::get('/krs/submit', [KRSController::class, 'submit'])->name('krs.submit');
        Route::get('/krs/print', [KRSController::class, 'print'])->name('krs.print');
        Route::get('/krs/review', [KRSController::class, 'review'])->name('krs.review');

        // Nilai (Akademik)
        Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
        Route::get('/khs', [NilaiController::class, 'khs'])->name('khs.index');
        Route::get('/nilai/print', [NilaiController::class, 'print'])->name('nilai.print');

        // Jadwal Kuliah
        Route::get('/jadwal', [MahasiswaJadwalController::class, 'index'])->name('jadwal.index');

        // Pembayaran
        Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');

        // Profil
        Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
         Route::get('/manajemen-profil', [ProfilController::class, 'manajemen'])->name('profil.manajemen');
        Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
        Route::put('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.update-password');
    });
});

Route::prefix('parent')->name('parent.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Parent\ParentController::class, 'dashboard'])->name('dashboard');
    Route::get('/nilai', [App\Http\Controllers\Parent\ParentController::class, 'nilai'])->name('nilai');
    Route::get('/jadwal', [App\Http\Controllers\Parent\ParentController::class, 'jadwal'])->name('jadwal');
    Route::get('/presensi', [App\Http\Controllers\Parent\ParentController::class, 'presensi'])->name('presensi');
    Route::get('/pembayaran', [App\Http\Controllers\Parent\ParentController::class, 'pembayaran'])->name('pembayaran');
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
    Route::resource('dosen-pa', App\Http\Controllers\Admin\DosenPaController::class);
    Route::get('dosen-pa/{id}/mahasiswa', [App\Http\Controllers\Admin\DosenPaController::class, 'getMahasiswa'])->name('dosen-pa.mahasiswa');

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
    // Reschedule requests
    Route::get('jadwal-reschedules', [App\Http\Controllers\Admin\JadwalController::class, 'reschedules'])->name('jadwal.reschedules');
    Route::post('jadwal-reschedules/{reschedule}/approve', [App\Http\Controllers\Admin\JadwalController::class, 'approveReschedule'])->name('jadwal.reschedules.approve');
    Route::post('jadwal-reschedules/{reschedule}/reject', [App\Http\Controllers\Admin\JadwalController::class, 'rejectReschedule'])->name('jadwal.reschedules.reject');

    // Kelas Reschedule requests (weekly)
    Route::post('kelas-reschedules/{reschedule}/approve', [App\Http\Controllers\Admin\JadwalController::class, 'approveKelasReschedule'])->name('kelas.reschedules.approve');
    Route::post('kelas-reschedules/{reschedule}/reject', [App\Http\Controllers\Admin\JadwalController::class, 'rejectKelasReschedule'])->name('kelas.reschedules.reject');
    Route::post('kelas-reschedules/{reschedule}/assign-room', [App\Http\Controllers\Admin\JadwalController::class, 'assignRoomKelasReschedule'])->name('kelas.reschedules.assignRoom');

    // Semester Management
    Route::resource('semester', App\Http\Controllers\Admin\SemesterController::class);
    Route::get('semester-manage', [App\Http\Controllers\Admin\SemesterController::class, 'manage'])->name('semester.manage');
    Route::post('semester/set-active', [App\Http\Controllers\Admin\SemesterController::class, 'setActive'])->name('semester.set-active');
    Route::put('semester/{semester}/krs-settings', [App\Http\Controllers\Admin\SemesterController::class, 'updateKrsSettings'])->name('semester.update-krs-settings');
    // Global search
    Route::get('search', [App\Http\Controllers\Admin\SearchController::class, 'index'])->name('search');

    // KRS Management
    Route::get('krs', [App\Http\Controllers\Admin\KrsController::class, 'index'])->name('krs.index');
    Route::get('krs/{kr}', [App\Http\Controllers\Admin\KrsController::class, 'show'])->name('krs.show');
    Route::put('krs/{kr}/status', [App\Http\Controllers\Admin\KrsController::class, 'updateStatus'])->name('krs.updateStatus');
    Route::delete('krs/{kr}', [App\Http\Controllers\Admin\KrsController::class, 'destroy'])->name('krs.destroy');

    // QR management (optional admin toggles could be added later)
});
