<?php

/**
 * Script untuk test profile update tanpa frontend.
 * Jalankan: php artisan tinker < test_profile_update.php
 * Atau: php test_profile_update.php (dari root project)
 */

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Boot the application
$app->boot();

$commit = isset($argv) && in_array('--commit', $argv);
if ($commit) {
    echo "⚠️  COMMIT MODE ENABLED: Changes will be saved to the database.\n\n";
} else {
    echo "ℹ️  DRY RUN MODE: Changes will be rolled back. Use --commit flag to save changes.\n\n";
}

use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\ParentModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

echo "\n" . str_repeat('=', 70) . "\n";
echo "  PROFILE UPDATE TEST — " . date('Y-m-d H:i:s') . "\n";
echo str_repeat('=', 70) . "\n\n";

// ─── Step 1: Find the student ───────────────────────────────────────────
$mahasiswa = Mahasiswa::where('nim', '2024010001')->first();
if (!$mahasiswa) {
    echo "❌ Mahasiswa NIM 2024010001 tidak ditemukan!\n";
    exit(1);
}

$user = User::find($mahasiswa->user_id);
if (!$user) {
    echo "❌ User ID {$mahasiswa->user_id} tidak ditemukan!\n";
    exit(1);
}

echo "✅ Found: {$user->name} (NIM: {$mahasiswa->nim}, User ID: {$user->id})\n";
echo "   Status akun: {$mahasiswa->status_akun}\n";
echo "   Profile complete: " . ($mahasiswa->isProfileComplete() ? 'YES' : 'NO') . "\n\n";

// ─── Step 2: Show current data ──────────────────────────────────────────
$fieldsToCheck = [
    'no_hp', 'alamat', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
    'agama', 'status_sipil', 'kota', 'kecamatan', 'desa', 'provinsi',
    'jenis_sekolah', 'jurusan_sekolah', 'tahun_lulus', 'nilai_kelulusan',
    'kecamatan_ktp',
    'file_ijazah', 'file_transkrip', 'file_kk', 'file_ktp',
];

echo "── Current Field Values ─────────────────────────────────────────────\n";
foreach ($fieldsToCheck as $field) {
    $val = $mahasiswa->$field;
    if (is_array($val)) {
        $val = json_encode($val);
    }
    $status = empty($val) ? '❌ EMPTY' : '✅';
    echo sprintf("  %-20s %s %s\n", $field, $status, $val ?? '(null)');
}
echo "\n";

// ─── Step 3: Try direct model update ────────────────────────────────────
echo "── Test 1: Direct Model Update ────────────────────────────────────\n";

$testData = [
    'no_hp' => '081234567890',
    'alamat' => 'Jl. Test No. 123',
    'tempat_lahir' => 'Jakarta',
    'tanggal_lahir' => '2000-01-15',
    'jenis_kelamin' => 'Laki-Laki',
    'agama' => 'Islam',
    'status_sipil' => 'Belum Menikah',
    'kota' => 'Jakarta Selatan',
    'kecamatan' => 'Kebayoran Baru',
    'desa' => 'Senayan',
    'provinsi' => 'DKI Jakarta',
    'jenis_sekolah' => '1 - Umum',
    'jurusan_sekolah' => 'SMA',
    'tahun_lulus' => '2024',
    'nilai_kelulusan' => '85.50',
];

DB::beginTransaction();
try {
    $result = $mahasiswa->update($testData);
    echo "  update() returned: " . var_export($result, true) . "\n";
    echo "  getChanges(): " . json_encode($mahasiswa->getChanges()) . "\n";

    // Refresh and verify
    $mahasiswa->refresh();
    $allSaved = true;
    foreach ($testData as $key => $expected) {
        $actual = $mahasiswa->$key;
        $match = ($actual == $expected);
        if (!$match) {
            echo "  ❌ {$key}: expected '{$expected}' but got '{$actual}'\n";
            $allSaved = false;
        }
    }

    if ($allSaved) {
        echo "  ✅ All test fields saved correctly to database!\n";
    }

    echo "\n  isProfileComplete() after update: " . ($mahasiswa->isProfileComplete() ? 'YES ✅' : 'NO ❌') . "\n";
    
    if (!$mahasiswa->isProfileComplete()) {
        echo "  Still missing:\n";
        $missing = $mahasiswa->getMissingProfileFields();
        foreach ($missing as $f => $info) {
            echo "    - {$info['label']} ({$f}) [tab: {$info['tab']}]\n";
        }
    }

} catch (\Exception $e) {
    echo "  ❌ Exception: " . $e->getMessage() . "\n";
    echo "  File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

// Commit or rollback depending on flag
if ($commit) {
    DB::commit();
    echo "\n  (Transaction COMMITTED — changes saved permanently to database!)\n";
} else {
    DB::rollBack();
    echo "\n  (Transaction rolled back — no permanent changes)\n";
}

// ─── Step 4: Test HTTP request simulation ───────────────────────────────
echo "\n── Test 2: HTTP Request Simulation ──────────────────────────────────\n";

// Login as the student
Auth::login($user);
echo "  Logged in as: " . Auth::user()->name . " (ID: " . Auth::id() . ")\n";

// Create a fake request
$requestData = array_merge($testData, [
    'name' => $user->name,
    'email_pribadi' => '',
    '_method' => 'PUT',
    '_token' => csrf_token(),
    'nama_ayah' => 'Budi Santoso',
    'pendidikan_ayah' => 'S1',
    'pekerjaan_ayah' => 'PNS',
    'agama_ayah' => 'Islam',
    'nama_ibu' => 'Siti Aminah',
    'pendidikan_ibu' => 'S1',
    'pekerjaan_ibu' => 'Guru',
    'agama_ibu' => 'Islam',
    'alamat_ayah' => 'Jl. Ayah No. 1',
    'kota_ayah' => 'Jakarta',
    'kecamatan_ayah' => 'Menteng',
    'desa_ayah' => 'Menteng Atas',
    'propinsi_ayah' => 'DKI Jakarta',
    'handphone_ayah' => '08111222333',
    'alamat_ibu' => 'Jl. Ibu No. 2',
    'kota_ibu' => 'Jakarta',
    'kecamatan_ibu' => 'Menteng',
    'desa_ibu' => 'Menteng Atas',
    'propinsi_ibu' => 'DKI Jakarta',
    'handphone_ibu' => '08222333444',
]);

// Create request object
$request = Illuminate\Http\Request::create(
    '/mahasiswa/profil',
    'PUT',
    $requestData
);

// Set session
$request->setLaravelSession($app['session.store']);

// Run through the ConvertEmptyStringsToNull middleware first
echo "\n  Running through ConvertEmptyStringsToNull middleware...\n";
$middleware = new \App\Http\Middleware\ConvertEmptyStringsToNull();
$middlewareResponse = $middleware->handle($request, function ($req) use ($testData) {
    echo "  After middleware, checking field values:\n";
    $issues = [];
    foreach ($testData as $key => $expected) {
        $actual = $req->input($key);
        if ($actual === null) {
            echo "    ⚠️  {$key}: CONVERTED TO NULL (was '{$expected}')\n";
            $issues[] = $key;
        }
    }
    if (empty($issues)) {
        echo "    ✅ No fields were nullified by middleware\n";
    }
    return new \Illuminate\Http\Response('ok');
});

// Now test the actual validation
echo "\n  Testing ProfilController validation...\n";
DB::beginTransaction();
try {
    $controller = $app->make(\App\Http\Controllers\Mahasiswa\ProfilController::class);
    $response = $controller->update($request);
    
    $statusCode = $response->getStatusCode();
    echo "  Response status: {$statusCode}\n";
    
    if ($statusCode === 302) {
        $redirectUrl = $response->headers->get('Location');
        echo "  Redirect to: {$redirectUrl}\n";
        
        // Check if there are errors in session
        $session = $request->session();
        $errors = $session->get('errors');
        if ($errors && $errors->any()) {
            echo "  ❌ VALIDATION ERRORS:\n";
            foreach ($errors->all() as $error) {
                echo "    - {$error}\n";
            }
        }
        
        $success = $session->get('success');
        if ($success) {
            echo "  ✅ SUCCESS: {$success}\n";
        }
    }
    
    // Verify database
    $mahasiswa->refresh();
    echo "\n  After controller update:\n";
    echo "  no_hp = " . ($mahasiswa->no_hp ?? 'NULL') . "\n";
    echo "  alamat = " . ($mahasiswa->alamat ?? 'NULL') . "\n";
    echo "  jenis_sekolah = " . ($mahasiswa->jenis_sekolah ?? 'NULL') . "\n";
    echo "  isProfileComplete: " . ($mahasiswa->isProfileComplete() ? 'YES' : 'NO') . "\n";

} catch (\Illuminate\Validation\ValidationException $e) {
    echo "  ❌ VALIDATION EXCEPTION:\n";
    foreach ($e->errors() as $field => $messages) {
        foreach ($messages as $msg) {
            echo "    [{$field}] {$msg}\n";
        }
    }
} catch (\Exception $e) {
    echo "  ❌ EXCEPTION: " . get_class($e) . "\n";
    echo "    Message: " . $e->getMessage() . "\n";
    echo "    File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "    Trace:\n";
    // Show first 5 lines of trace
    $trace = explode("\n", $e->getTraceAsString());
    foreach (array_slice($trace, 0, 5) as $line) {
        echo "      {$line}\n";
    }
}

if ($commit) {
    DB::commit();
    echo "\n  (Transaction COMMITTED — changes saved permanently to database!)\n";
} else {
    DB::rollBack();
    echo "\n  (Transaction rolled back — no permanent changes)\n";
}

// ─── Step 5: Check .env session config ──────────────────────────────────
echo "\n── Session & Config Check ───────────────────────────────────────────\n";
echo "  SESSION_DRIVER: " . config('session.driver') . "\n";
echo "  SESSION_DOMAIN: '" . config('session.domain') . "'\n";
echo "  SESSION_SECURE: " . var_export(config('session.secure'), true) . "\n";
echo "  SESSION_SAME_SITE: " . config('session.same_site') . "\n";
echo "  APP_URL: " . config('app.url') . "\n";
echo "  APP_DEBUG: " . var_export(config('app.debug'), true) . "\n";

echo "\n" . str_repeat('=', 70) . "\n";
echo "  TEST COMPLETE\n";
echo str_repeat('=', 70) . "\n\n";
