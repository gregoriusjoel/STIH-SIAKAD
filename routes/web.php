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
use App\Http\Controllers\Mahasiswa\PengajuanController;

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
        } elseif (in_array($user->role, ['finance', 'keuangan'])) {
            return redirect()->route('finance.invoices.index');
        }
        return redirect()->route('dosen.dashboard');
    }
    return redirect('/login');
});

// Public QR image and redirect routes
Route::get('/qrcode/kelas/{token}/image', [App\Http\Controllers\QrController::class, 'image'])->name('qrcode.kelas.image');
Route::get('/kelas/qr-redirect/{token}', [App\Http\Controllers\QrController::class, 'redirect'])->name('qrcode.kelas.redirect');

// Public attendance form via QR (legacy) — redirect to login-based absen flow
Route::get('/absensi/kelas/{token}', function ($token) {
    return redirect()->route('absen.login', ['token' => $token]);
})->name('absensi.form');

// If someone tries to POST to the old manual submit endpoint, redirect them to login flow
Route::post('/absensi/kelas/{token}', function ($token) {
    return redirect()->route('absen.login', ['token' => $token]);
})->name('absensi.submit');

// keep the legacy thank-you route mapping (optional)
Route::get('/absensi/terima-kasih', [App\Http\Controllers\AttendanceController::class, 'thanks'])->name('absensi.thanks');

// Absen berbasis login (separate flow)
Route::get('/absen/login', [App\Http\Controllers\Absen\LoginController::class, 'showLoginForm'])->name('absen.login');
Route::post('/absen/login', [App\Http\Controllers\Absen\LoginController::class, 'login'])->middleware('throttle:10,1')->name('absen.login.post');
Route::get('/absen/thank-you', [App\Http\Controllers\Absen\LoginController::class, 'thankYou'])->middleware('auth:mahasiswa_absen')->name('absen.thankyou');

// Dosen / Lecturer Portal Routes
Route::prefix('dosen')->name('dosen.')->where(['pertemuan' => '[a-z0-9:]+'])->group(function () {
    Route::get('/dashboard', [LecturerController::class, 'dashboard'])->name('dashboard');
    Route::get('/kelas', [LecturerController::class, 'classes'])->name('kelas');
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal')->middleware('auth');
    Route::post('/jadwal/{jadwal}/reschedule', [JadwalController::class, 'reschedule'])->name('jadwal.reschedule')->middleware('auth');
    Route::post('/jadwal/reschedule', [JadwalController::class, 'rescheduleGeneric'])->name('jadwal.reschedule.generic')->middleware('auth');
    Route::post('/kelas/reschedule', [JadwalController::class, 'kelasReschedule'])->name('kelas.reschedule')->middleware('auth');
    Route::post('/jadwal/check-availability', [JadwalController::class, 'checkAvailability'])->name('jadwal.check_availability')->middleware('auth');
    Route::get('/kelas/{id}/detail', [LecturerController::class, 'detail'])->name('kelas.detail');
    Route::get('/kelas/{id}/export-berita-acara', [LecturerController::class, 'exportBeritaAcara'])->name('kelas.export-berita-acara');
    Route::get('/kelas/{id}/pertemuan/{pertemuan}', [LecturerController::class, 'meetingDetail'])->name('kelas.pertemuan.detail');
    Route::get('/kelas/{id}/pertemuan/{pertemuan}/materi', [LecturerController::class, 'meetingMaterials'])->name('kelas.pertemuan.materi');
    Route::post('/kelas/{id}/pertemuan/{pertemuan}/presensi', [LecturerController::class, 'updateAttendance'])->name('kelas.pertemuan.presensi');

    // Materi (Dosen)
    Route::get('/kelas/{id}/pertemuan/{pertemuan}/materi/list', [App\Http\Controllers\Dosen\MateriController::class, 'index'])->name('kelas.pertemuan.materi.list');
    Route::post('/kelas/{id}/pertemuan/{pertemuan}/materi', [App\Http\Controllers\Dosen\MateriController::class, 'store'])->name('kelas.pertemuan.materi.store');
    Route::delete('/kelas/{id}/pertemuan/{pertemuan}/materi/{materi}', [App\Http\Controllers\Dosen\MateriController::class, 'destroy'])->name('kelas.pertemuan.materi.destroy');
    Route::get('/materi/{materi}/download', [App\Http\Controllers\Dosen\MateriController::class, 'download'])->name('materi.download');

    // Tugas (Dosen)
    Route::post('/kelas/{id}/pertemuan/{pertemuan}/tugas', [App\Http\Controllers\Dosen\TugasController::class, 'store'])->name('kelas.pertemuan.tugas.store');
    Route::get('/kelas/{id}/pertemuan/{pertemuan}/tugas', [App\Http\Controllers\Dosen\TugasController::class, 'index'])->name('kelas.pertemuan.tugas.index');
    Route::delete('/kelas/{id}/pertemuan/{pertemuan}/tugas/{tugas}', [App\Http\Controllers\Dosen\TugasController::class, 'destroy'])->name('kelas.pertemuan.tugas.destroy');
    Route::post('/kelas/{id}/generate-qr', [LecturerController::class, 'generateQr'])->name('kelas.generate_qr');
    Route::post('/kelas/{id}/activate-qr', [LecturerController::class, 'activateQr'])->name('kelas.activate_qr');
    Route::post('/kelas/{id}/deactivate-qr', [LecturerController::class, 'deactivateQr'])->name('kelas.deactivate_qr');

    // Dosen Attendance (metode pengajaran + password QR activation)
    Route::patch('/kelas/{id}/pertemuan/{pertemuan}/metode', [App\Http\Controllers\Dosen\DosenAttendanceController::class, 'updateMetode'])->name('kelas.pertemuan.metode.update');
    Route::post('/kelas/{id}/pertemuan/{pertemuan}/activate-qr-password', [App\Http\Controllers\Dosen\DosenAttendanceController::class, 'activateQrWithPassword'])->name('kelas.pertemuan.activate_qr_password');
    Route::get('/kelas/{id}/attendance-data', [LecturerController::class, 'getAttendanceData'])->name('kelas.attendance_data');
    Route::get('/krs', [LecturerController::class, 'krs'])->name('krs');
    Route::get('/input-nilai', [LecturerController::class, 'inputNilai'])->name('input-nilai');
    Route::get('/mahasiswa', [LecturerController::class, 'students'])->name('mahasiswa');

    // Input Nilai per Kelas
    Route::get('/kelas/{id}/input-nilai', [LecturerController::class, 'inputNilaiKelas'])->name('kelas.input-nilai');
    Route::post('/kelas/{id}/bobot-penilaian', [LecturerController::class, 'saveBobotPenilaian'])->name('kelas.bobot-penilaian.save');
    Route::post('/kelas/{id}/simpan-nilai', [LecturerController::class, 'simpanNilai'])->name('kelas.simpan-nilai');
    Route::post('/kelas/{id}/tarik-nilai', [LecturerController::class, 'tarikNilai'])->name('kelas.tarik-nilai');
    Route::get('/kelas/{id}/get-bobot', [LecturerController::class, 'getBobotPenilaian'])->name('kelas.get-bobot');
    Route::get('/kelas/{id}/nilai-template', [LecturerController::class, 'downloadNilaiTemplate'])->name('kelas.nilai-template');
    Route::post('/kelas/{id}/import-nilai', [LecturerController::class, 'importNilai'])->name('kelas.import-nilai');

    // Dokumen (Silabus & RPS)
    Route::post('/kelas/{id}/dokumen/upload', [LecturerController::class, 'uploadDokumen'])->name('kelas.dokumen.upload')->middleware('auth');
    Route::get('/kelas/{id}/dokumen/{tipe}/download', [LecturerController::class, 'downloadDokumen'])->name('kelas.dokumen.download')->middleware('auth');
    Route::delete('/kelas/{id}/dokumen/{tipe}', [LecturerController::class, 'deleteDokumen'])->name('kelas.dokumen.delete')->middleware('auth');

    // Jadwal Approval Routes
    Route::prefix('jadwal-approval')->name('jadwal_approval.')->group(function () {
        Route::get('/', [App\Http\Controllers\Dosen\JadwalApprovalController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Dosen\JadwalApprovalController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [App\Http\Controllers\Dosen\JadwalApprovalController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [App\Http\Controllers\Dosen\JadwalApprovalController::class, 'reject'])->name('reject');
        Route::get('/available-slots/{hari}', [App\Http\Controllers\Dosen\JadwalApprovalController::class, 'getAvailableSlots'])->name('available_slots');
    });

    // Availability Management for Dosen
    Route::resource('availability', App\Http\Controllers\Dosen\DosenAvailabilityController::class)->only(['index', 'create', 'store', 'destroy']);

    // ── Nilai Tugas (Assignment Grades) ──────────────────────────────────
    Route::prefix('kelas/{kelasId}/nilai-tugas')->name('nilai-tugas.')->group(function () {
        Route::get('/', [App\Http\Controllers\Dosen\NilaiTugasController::class, 'index'])->name('index');
        Route::get('/tugas/{tugasId}/input', [App\Http\Controllers\Dosen\NilaiTugasController::class, 'inputNilai'])->name('input');
        Route::post('/tugas/{tugasId}/simpan', [App\Http\Controllers\Dosen\NilaiTugasController::class, 'simpanNilai'])->name('simpan');
        Route::delete('/tugas/{tugasId}/reset', [App\Http\Controllers\Dosen\NilaiTugasController::class, 'resetNilai'])->name('reset');
        Route::get('/rekap', [App\Http\Controllers\Dosen\NilaiTugasController::class, 'rekap'])->name('rekap');
        Route::get('/export', [App\Http\Controllers\Dosen\NilaiTugasController::class, 'export'])->name('export');
    });

    // Pengumuman untuk dosen
    Route::get('/pengumuman', [App\Http\Controllers\Page\PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::get('/pengumuman/{pengumuman}', [App\Http\Controllers\Page\PengumumanController::class, 'show'])->name('pengumuman.show');

    // ── Bimbingan Magang (Dosen) ──────────────────────────────────
    Route::prefix('magang')->name('magang.')->group(function () {
        Route::get('/', [App\Http\Controllers\Dosen\InternshipController::class, 'index'])->name('index');
        Route::get('/{internship}', [App\Http\Controllers\Dosen\InternshipController::class, 'show'])->name('show');
        Route::post('/{internship}/logbook', [App\Http\Controllers\Dosen\InternshipController::class, 'storeLogbook'])->name('logbook.store');
        Route::put('/{internship}/logbook/{logbook}', [App\Http\Controllers\Dosen\InternshipController::class, 'updateLogbook'])->name('logbook.update');
    });
});

// Mahasiswa Portal Routes
Route::prefix('mahasiswa')->name('mahasiswa.')->middleware(['auth'])->group(function () {
    // Aktivasi (tidak perlu middleware status check)
    Route::get('/aktivasi', [AktivasiController::class, 'index'])->name('aktivasi.index');
    Route::post('/aktivasi', [AktivasiController::class, 'store'])->name('aktivasi.store');

    // New student survey (must be accessible before filling it)
    Route::get('/survey-new', [App\Http\Controllers\Mahasiswa\NewStudentSurveyController::class, 'index'])->name('survey_new.index');
    Route::post('/survey-new', [App\Http\Controllers\Mahasiswa\NewStudentSurveyController::class, 'store'])->name('survey_new.store');

    // Routes yang memerlukan status check
    Route::middleware(['mahasiswa.status'])->group(function () {
        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');

        // Pengumuman untuk mahasiswa
        Route::get('/pengumuman', [App\Http\Controllers\Page\PengumumanController::class, 'index'])->name('pengumuman.index');
        Route::get('/pengumuman/{pengumuman}', [App\Http\Controllers\Page\PengumumanController::class, 'show'])->name('pengumuman.show');

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

        // Kelas Saya
        Route::get('/kelas', [App\Http\Controllers\Mahasiswa\KelasController::class, 'index'])->name('kelas.index');
        Route::get('/kelas/{id}', [App\Http\Controllers\Mahasiswa\KelasController::class, 'show'])->name('kelas.show');

        // Materi download
        Route::get('/materi/{id}/download', [App\Http\Controllers\Mahasiswa\MateriController::class, 'download'])->name('materi.download');

        // Tugas download and submit
        Route::get('/tugas/{id}/download', [App\Http\Controllers\Mahasiswa\TugasController::class, 'download'])->name('tugas.download');
        Route::post('/kelas/{id}/pertemuan/{pertemuan}/tugas/{tugas}/submit', [App\Http\Controllers\Mahasiswa\TugasController::class, 'submit'])->name('kelas.pertemuan.tugas.submit');

        // Profil
        Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
        Route::get('/manajemen-profil', [ProfilController::class, 'manajemen'])->name('profil.manajemen');
        Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
        Route::put('/profil/foto', [ProfilController::class, 'updateFoto'])->name('profil.update-foto');
        Route::put('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.update-password');

        // Menu Pengajuan
        Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
            Route::get('/', [PengajuanController::class, 'index'])->name('index');
            // Step 1: Buat draft
            Route::post('/', [PengajuanController::class, 'store'])->name('store');
            // Step 2: Generate dokumen (dispatch queue)
            Route::post('/{pengajuan}/generate', [PengajuanController::class, 'generate'])->name('generate');
            // Polling status
            Route::get('/{pengajuan}/status', [PengajuanController::class, 'statusCheck'])->name('status');
            // Step 3: Download generated doc
            Route::get('/{pengajuan}/download-generated', [PengajuanController::class, 'downloadGenerated'])->name('download-generated');
            // Step 4: Upload signed doc
            Route::post('/{pengajuan}/upload-signed', [PengajuanController::class, 'uploadSigned'])->name('upload-signed');
            // Step 5: Submit ke admin
            Route::post('/{pengajuan}/submit', [PengajuanController::class, 'submit'])->name('submit');
            // Download final surat approved
            Route::get('/{pengajuan}/download', [PengajuanController::class, 'download'])->name('download');
            Route::get('/{pengajuan}/preview', [PengajuanController::class, 'preview'])->name('preview');
            // Delete pengajuan (mahasiswa)
            Route::delete('/{pengajuan}', [PengajuanController::class, 'destroy'])->name('destroy');
            // AJAX: config fields per jenis
            Route::get('/jenis-config/{jenis}', [PengajuanController::class, 'jenisConfig'])->name('jenis-config');
            Route::view('/sidang', 'errors.503')->name('sidang.index');
            Route::get('/surat', [PengajuanController::class, 'index'])->name('surat.index');
            Route::view('/yudisium', 'errors.503')->name('yudisium.index');
        });
    });

    // Menu Akademik Tambahan
    Route::view('/perpustakaan', 'errors.503')->name('perpustakaan.index');
    Route::view('/prestasi', 'errors.503')->name('prestasi.index');

    // ── Magang (Internship) ──────────────────────────────────────
    Route::prefix('magang')->name('magang.')->group(function () {
        Route::get('/', [App\Http\Controllers\Mahasiswa\InternshipController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Mahasiswa\InternshipController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Mahasiswa\InternshipController::class, 'store'])->name('store');
        Route::get('/{internship}', [App\Http\Controllers\Mahasiswa\InternshipController::class, 'show'])->name('show');
        Route::get('/{internship}/edit', [App\Http\Controllers\Mahasiswa\InternshipController::class, 'edit'])->name('edit');
        Route::put('/{internship}', [App\Http\Controllers\Mahasiswa\InternshipController::class, 'update'])->name('update');
        Route::post('/{internship}/submit', [App\Http\Controllers\Mahasiswa\InternshipController::class, 'submit'])->name('submit');
        Route::get('/{internship}/generate-letter', [App\Http\Controllers\Mahasiswa\InternshipController::class, 'generateLetter'])->name('generate-letter');
        Route::post('/{internship}/upload-signed', [App\Http\Controllers\Mahasiswa\InternshipController::class, 'uploadSigned'])->name('upload-signed');
        Route::post('/{internship}/upload-acceptance', [App\Http\Controllers\Mahasiswa\InternshipController::class, 'uploadAcceptance'])->name('upload-acceptance');
        Route::post('/{internship}/submit-review', [App\Http\Controllers\Mahasiswa\InternshipController::class, 'submitForReview'])->name('submit-review');
        Route::post('/{internship}/logbook', [App\Http\Controllers\Mahasiswa\InternshipController::class, 'storeLogbook'])->name('logbook.store');
        Route::get('/{internship}/download-acceptance', [App\Http\Controllers\Mahasiswa\InternshipController::class, 'downloadAcceptanceLetter'])->name('download-acceptance');
        Route::get('/{internship}/download-official', [App\Http\Controllers\Mahasiswa\InternshipController::class, 'downloadOfficial'])->name('download-official');
        Route::delete('/{internship}', [App\Http\Controllers\Mahasiswa\InternshipController::class, 'destroy'])->name('destroy');
    });

});

Route::prefix('parent')->name('parent.')->middleware(['auth', 'parent.role'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Parent\ParentController::class, 'dashboard'])->name('dashboard');
    Route::get('/nilai', [App\Http\Controllers\Parent\ParentController::class, 'nilai'])->name('nilai');
    Route::get('/jadwal', [App\Http\Controllers\Parent\ParentController::class, 'jadwal'])->name('jadwal');
    Route::get('/presensi', [App\Http\Controllers\Parent\ParentController::class, 'presensi'])->name('presensi');
    Route::get('/pembayaran', [App\Http\Controllers\Parent\ParentController::class, 'pembayaran'])->name('pembayaran');
});

// Admin Routes
// Public template download for Dosen import (no auth required)
Route::get('/admin/dosen/import-template', [App\Http\Controllers\Admin\DosenController::class, 'downloadTemplate'])
    ->name('admin.dosen.import-template');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Pengumuman (Admin CRUD)
    Route::resource('pengumuman', App\Http\Controllers\Admin\PengumumanController::class);

    // User Management
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);

    // Mahasiswa Management
    Route::resource('mahasiswa', App\Http\Controllers\Admin\MahasiswaController::class);
    Route::post('mahasiswa/{mahasiswa}/toggle-dokumen', [App\Http\Controllers\Admin\MahasiswaController::class, 'toggleDokumen'])->name('mahasiswa.toggle-dokumen');

    // Dosen Management
    Route::resource('dosen', App\Http\Controllers\Admin\DosenController::class);
    Route::post('dosen/import', [App\Http\Controllers\Admin\DosenController::class, 'import'])->name('dosen.import');
    Route::post('dosen/carry-forward-all', [App\Http\Controllers\Admin\DosenController::class, 'carryForwardAll'])->name('dosen.carry-forward-all');
    Route::post('dosen/{dosen}/toggle-status', [App\Http\Controllers\Admin\DosenController::class, 'toggleStatus'])->name('dosen.toggle-status');

    // Teaching Assignment (Penugasan Mengajar)
    Route::post('dosen/{dosen}/assignments', [App\Http\Controllers\Admin\DosenController::class, 'storeAssignments'])->name('dosen.assignments.store');
    Route::delete('dosen/{dosen}/assignments/{mataKuliah}', [App\Http\Controllers\Admin\DosenController::class, 'destroyAssignment'])->name('dosen.assignments.destroy');
    Route::post('dosen/{dosen}/assignments/copy', [App\Http\Controllers\Admin\DosenController::class, 'copyAssignments'])->name('dosen.assignments.copy');
    Route::get('dosen/{dosen}/assignments/history/{semester}', [App\Http\Controllers\Admin\DosenController::class, 'getHistoryAssignments'])->name('dosen.assignments.history');
    Route::get('dosen/{dosen}/quick-assign', [App\Http\Controllers\Admin\DosenController::class, 'quickAssignData'])->name('dosen.quick-assign.data');

    Route::resource('dosen-pa', App\Http\Controllers\Admin\DosenPaController::class);
    Route::get('dosen-pa/{id}/mahasiswa', [App\Http\Controllers\Admin\DosenPaController::class, 'getMahasiswa'])->name('dosen-pa.mahasiswa');

    // Admin Password Verification (for sensitive actions)
    Route::post('verify-password', function (\Illuminate\Http\Request $request) {
        $request->validate(['password' => 'required|string']);
        if (\Illuminate\Support\Facades\Hash::check($request->password, $request->user()->password)) {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Password salah.'], 422);
    })->name('verify-password');

    // Parent Management
    Route::get('parents/existing/{mahasiswa_id}', [App\Http\Controllers\Admin\ParentController::class, 'getExistingData'])->name('parents.existing');
    Route::resource('parents', App\Http\Controllers\Admin\ParentController::class);

    // Master Data Management
    Route::resource('prodi', App\Http\Controllers\Admin\ProdiController::class);
    Route::resource('fakultas', App\Http\Controllers\Admin\FakultasController::class)->parameters([
        'fakultas' => 'fakultas'
    ]);

    // Mata Kuliah Management
    Route::get('mata-kuliah/download-template', [App\Http\Controllers\Admin\MataKuliahController::class, 'downloadTemplate'])->name('mata-kuliah.download-template');
    Route::post('mata-kuliah/import', [App\Http\Controllers\Admin\MataKuliahController::class, 'import'])->name('mata-kuliah.import');
    Route::resource('mata-kuliah', App\Http\Controllers\Admin\MataKuliahController::class);

    // ── Mata Kuliah per Semester (Best Practice) ──────────────────────────
    Route::prefix('mata-kuliah-semester')->name('mata-kuliah-semester.')->middleware('semester.lock')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\MataKuliahSemesterController::class, 'index'])->name('index')->withoutMiddleware('semester.lock');
        Route::get('/histori', [App\Http\Controllers\Admin\MataKuliahSemesterController::class, 'histori'])->name('histori')->withoutMiddleware('semester.lock');
        Route::post('/attach', [App\Http\Controllers\Admin\MataKuliahSemesterController::class, 'attach'])->name('attach');
        Route::post('/attach-by-codes', [App\Http\Controllers\Admin\MataKuliahSemesterController::class, 'attachByCodes'])->name('attach-by-codes');
        Route::post('/detach', [App\Http\Controllers\Admin\MataKuliahSemesterController::class, 'detach'])->name('detach');
        Route::get('/carry-forward/preview', [App\Http\Controllers\Admin\MataKuliahSemesterController::class, 'carryForwardPreview'])->name('carry-forward-preview')->withoutMiddleware('semester.lock');
        Route::post('/carry-forward', [App\Http\Controllers\Admin\MataKuliahSemesterController::class, 'carryForward'])->name('carry-forward');
        Route::post('/restore', [App\Http\Controllers\Admin\MataKuliahSemesterController::class, 'restore'])->name('restore');
        Route::patch('/semesters/{semester}/activate', [App\Http\Controllers\Admin\MataKuliahSemesterController::class, 'activateSemester'])->name('activate-semester')->withoutMiddleware('semester.lock');
        Route::patch('/semesters/{semester}/lock', [App\Http\Controllers\Admin\MataKuliahSemesterController::class, 'lockSemester'])->name('lock-semester')->withoutMiddleware('semester.lock');
        Route::patch('/semesters/{semester}/unlock', [App\Http\Controllers\Admin\MataKuliahSemesterController::class, 'unlockSemester'])->name('unlock-semester')->withoutMiddleware('semester.lock');
    });
    Route::get('audit-logs', [App\Http\Controllers\Admin\MataKuliahSemesterController::class, 'auditLogs'])->name('mata-kuliah-semester.audit-logs');
    // ─────────────────────────────────────────────────────────────────────

    // Ruangan Management
    Route::resource('ruangan', App\Http\Controllers\Admin\RuanganController::class);

    // Jam Perkuliahan Management
    Route::resource('jam-perkuliahan', App\Http\Controllers\Admin\JamPerkuliahanController::class);

    // Kelas Mata Kuliah Management
    Route::resource('kelas-mata-kuliah', App\Http\Controllers\Admin\KelasMataKuliahController::class);
    Route::get('kelas-mata-kuliah/{kelasId}/attendance', [App\Http\Controllers\Admin\KelasMataKuliahController::class, 'getAttendanceData'])->name('kelas-mata-kuliah.attendance');

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
    Route::get('semester/previous-end', [App\Http\Controllers\Admin\SemesterController::class, 'previousEnd'])->name('semester.previous_end');
    Route::resource('semester', App\Http\Controllers\Admin\SemesterController::class);
    Route::get('semester-manage', [App\Http\Controllers\Admin\SemesterController::class, 'manage'])->name('semester.manage');
    Route::post('semester/set-active', [App\Http\Controllers\Admin\SemesterController::class, 'setActive'])->name('semester.set-active');
    Route::put('semester/{semester}/krs-settings', [App\Http\Controllers\Admin\SemesterController::class, 'updateKrsSettings'])->name('semester.update-krs-settings');
    
    // Semester Transition (Auto Increment)
    Route::prefix('semester-transition')->name('semester-transition.')->group(function () {
        Route::get('/status', [App\Http\Controllers\Admin\SemesterTransitionController::class, 'status'])->name('status');
        Route::get('/preview', [App\Http\Controllers\Admin\SemesterTransitionController::class, 'preview'])->name('preview');
        Route::post('/process', [App\Http\Controllers\Admin\SemesterTransitionController::class, 'process'])->name('process');
    });
    
    // Global search
    Route::get('search', [App\Http\Controllers\Admin\SearchController::class, 'index'])->name('search');

    // Auto Generate Jadwal System
    Route::prefix('jadwal-generator')->name('jadwal_generator.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\JadwalGeneratorController::class, 'index'])->name('index');
        Route::post('/auto-generate', [App\Http\Controllers\Admin\JadwalGeneratorController::class, 'autoGenerate'])->name('auto_generate');
        Route::delete('/{id}', [App\Http\Controllers\Admin\JadwalGeneratorController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [App\Http\Controllers\Admin\JadwalGeneratorController::class, 'bulkDelete'])->name('bulk_delete');
    });

    // Jadwal Generate Logs System
    Route::prefix('jadwal-generate-logs')->name('jadwal-generate-logs.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\JadwalGenerateLogController::class, 'index'])->name('index');
        Route::get('/{log}', [App\Http\Controllers\Admin\JadwalGenerateLogController::class, 'show'])->name('show');
        Route::get('/{log}/export', [App\Http\Controllers\Admin\JadwalGenerateLogController::class, 'export'])->name('export');
    });

    // Admin Jadwal Approval System
    Route::prefix('jadwal-admin-approval')->name('jadwal_admin_approval.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\JadwalAdminApprovalController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Admin\JadwalAdminApprovalController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [App\Http\Controllers\Admin\JadwalAdminApprovalController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [App\Http\Controllers\Admin\JadwalAdminApprovalController::class, 'reject'])->name('reject');
        Route::post('/{id}/approve-with-changes', [App\Http\Controllers\Admin\JadwalAdminApprovalController::class, 'approveWithChanges'])->name('approve_with_changes');
        Route::post('/{id}/process-dosen-request', [App\Http\Controllers\Admin\JadwalAdminApprovalController::class, 'processDosenRequest'])->name('process_dosen_request');
        Route::post('/{id}/process', [App\Http\Controllers\Admin\JadwalAdminApprovalController::class, 'process'])->name('process');
    });

    // Dosen Availability Management (Admin)
    Route::get('availability', [App\Http\Controllers\Admin\DosenAvailabilityController::class, 'index'])->name('availability.index');
    Route::get('availability/{dosen}', [App\Http\Controllers\Admin\DosenAvailabilityController::class, 'show'])->name('availability.show');

    // Academic Calendar Management
    Route::get('kalender-akademik', [App\Http\Controllers\Admin\AcademicCalendarController::class, 'index'])->name('kalender.index');
    Route::get('kalender-akademik/data', [App\Http\Controllers\Admin\AcademicCalendarController::class, 'getData'])->name('kalender.data');
    Route::post('kalender-akademik/event', [App\Http\Controllers\Admin\AcademicCalendarController::class, 'storeEvent'])->name('kalender.event.store');
    Route::put('kalender-akademik/event/{id}', [App\Http\Controllers\Admin\AcademicCalendarController::class, 'updateEvent'])->name('kalender.event.update');
    Route::delete('kalender-akademik/event/{id}', [App\Http\Controllers\Admin\AcademicCalendarController::class, 'deleteEvent'])->name('kalender.event.delete');
    Route::put('kalender-akademik/semester/{id}', [App\Http\Controllers\Admin\AcademicCalendarController::class, 'updateSemester'])->name('kalender.semester.update');
    Route::put('kalender-akademik/event/{id}/date', [App\Http\Controllers\Admin\AcademicCalendarController::class, 'updateDate'])->name('kalender.event.updateDate');
    Route::post('kalender-akademik/import', [App\Http\Controllers\Admin\AcademicCalendarController::class, 'import'])->name('kalender.import');
    Route::get('kalender-akademik/import-template', [App\Http\Controllers\Admin\AcademicCalendarController::class, 'downloadTemplate'])->name('kalender.import-template');
    Route::get('kalender-akademik/export', [App\Http\Controllers\Admin\AcademicCalendarController::class, 'export'])->name('kalender.export');


    // Maintenance
    Route::view('maintenance', 'errors.503')->name('maintenance');

    // KRS Management
    Route::get('krs', [App\Http\Controllers\Admin\KrsController::class, 'index'])->name('krs.index');
    Route::put('krs/{kr}/status', [App\Http\Controllers\Admin\KrsController::class, 'updateStatus'])->name('krs.updateStatus');
    Route::post('krs/mahasiswa/{mahasiswa}/reopen', [App\Http\Controllers\Admin\KrsController::class, 'reopenForStudent'])->name('krs.reopen');
    Route::delete('krs/{kr}', [App\Http\Controllers\Admin\KrsController::class, 'destroy'])->name('krs.destroy');

    // Pengajuan Management (Surat Mahasiswa)
    Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\PengajuanController::class, 'index'])->name('index');
        Route::get('/{pengajuan}', [App\Http\Controllers\Admin\PengajuanController::class, 'show'])->name('show');
        Route::post('/{pengajuan}/approve', [App\Http\Controllers\Admin\PengajuanController::class, 'approve'])->name('approve');
        Route::post('/{pengajuan}/reject', [App\Http\Controllers\Admin\PengajuanController::class, 'reject'])->name('reject');
        // Download berbagai jenis dokumen
        Route::get('/{pengajuan}/download', [App\Http\Controllers\Admin\PengajuanController::class, 'download'])->name('download');
        Route::get('/{pengajuan}/download-signed', [App\Http\Controllers\Admin\PengajuanController::class, 'downloadSigned'])->name('download-signed');
        Route::get('/{pengajuan}/download-generated', [App\Http\Controllers\Admin\PengajuanController::class, 'downloadGenerated'])->name('download-generated');
    });

    // ── Magang Management (Admin) ──────────────────────────────────
    Route::prefix('magang')->name('magang.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\InternshipController::class, 'index'])->name('index');
        Route::get('/{internship}', [App\Http\Controllers\Admin\InternshipController::class, 'show'])->name('show');
        Route::post('/{internship}/approve', [App\Http\Controllers\Admin\InternshipController::class, 'approve'])->name('approve');
        Route::post('/{internship}/reject', [App\Http\Controllers\Admin\InternshipController::class, 'reject'])->name('reject');
        Route::post('/{internship}/assign-supervisor', [App\Http\Controllers\Admin\InternshipController::class, 'assignSupervisor'])->name('assign-supervisor');
        Route::post('/{internship}/upload-acceptance', [App\Http\Controllers\Admin\InternshipController::class, 'uploadAcceptanceLetter'])->name('upload-acceptance');
        Route::post('/{internship}/start', [App\Http\Controllers\Admin\InternshipController::class, 'startInternship'])->name('start');
        Route::post('/{internship}/complete', [App\Http\Controllers\Admin\InternshipController::class, 'markCompleted'])->name('complete');
        Route::post('/{internship}/course-mappings', [App\Http\Controllers\Admin\InternshipController::class, 'saveCourseMappings'])->name('course-mappings');
        Route::post('/{internship}/grades', [App\Http\Controllers\Admin\InternshipController::class, 'inputGrades'])->name('grades');
        Route::post('/{internship}/close', [App\Http\Controllers\Admin\InternshipController::class, 'close'])->name('close');
        // ── NEW: PDF generation, sign, send, date update, grade preview ──
        Route::post('/{internship}/generate-official-pdf', [App\Http\Controllers\Admin\InternshipController::class, 'generateOfficialPdf'])->name('generate-official-pdf');
        Route::get('/{internship}/download-official-pdf', [App\Http\Controllers\Admin\InternshipController::class, 'downloadOfficialPdf'])->name('download-official-pdf');
        Route::post('/{internship}/upload-signed-pdf', [App\Http\Controllers\Admin\InternshipController::class, 'uploadSignedPdf'])->name('upload-signed-pdf');
        Route::get('/{internship}/download-signed-pdf', [App\Http\Controllers\Admin\InternshipController::class, 'downloadSignedPdf'])->name('download-signed-pdf');
        Route::post('/{internship}/send-to-student', [App\Http\Controllers\Admin\InternshipController::class, 'sendToStudent'])->name('send-to-student');
        Route::post('/{internship}/update-dates', [App\Http\Controllers\Admin\InternshipController::class, 'updateDates'])->name('update-dates');
        Route::post('/{internship}/preview-grade', [App\Http\Controllers\Admin\InternshipController::class, 'previewGrade'])->name('preview-grade');
    });

    // Import Data Management
    Route::prefix('import')->name('import.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ImportController::class, 'index'])->name('index');
        Route::get('/history', [App\Http\Controllers\Admin\ImportController::class, 'history'])->name('history');
        Route::get('/log/{log}', [App\Http\Controllers\Admin\ImportController::class, 'showLog'])->name('log');
        Route::get('/{type}', [App\Http\Controllers\Admin\ImportController::class, 'show'])->name('show');
        Route::post('/{type}/preview', [App\Http\Controllers\Admin\ImportController::class, 'preview'])->name('preview');
        Route::post('/{type}/import', [App\Http\Controllers\Admin\ImportController::class, 'import'])->name('import');
        Route::get('/{type}/template', [App\Http\Controllers\Admin\ImportController::class, 'downloadTemplate'])->name('template');
    });

    // QR management (optional admin toggles could be added later)

    // Absensi Dosen
    Route::prefix('absensi-dosen')->name('absensi_dosen.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AbsensiDosenController::class, 'index'])->name('index');
        Route::get('/{dosen}/{kelasMataKuliah}', [App\Http\Controllers\Admin\AbsensiDosenController::class, 'show'])->name('show');
    });
});

// Payment System Routes (Finance & Mahasiswa)
require __DIR__.'/payment_routes.php';
