<?php

/**
 * Script untuk testing grace period 14 hari
 * 
 * Scenario:
 * 1. Buat 3 semester: Old (15 hari lalu), Grace (5 hari lalu), Active (sekarang)
 * 2. Buat kelas untuk masing-masing semester
 * 3. Test apakah kelas old tidak muncul, grace muncul, active muncul
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Semester;
use App\Models\KelasMataKuliah;
use App\Models\MataKuliah;
use App\Models\Dosen;
use App\Services\SemesterService;
use Carbon\Carbon;

echo "\n";
echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║  TESTING GRACE PERIOD 14 HARI - TRANSITION SEMESTER      ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n";
echo "\n";

// Initialize service
$semesterService = app(SemesterService::class);

// Step 1: Create test semesters
echo "📝 STEP 1: Creating Test Semesters\n";
echo str_repeat("─", 60) . "\n";

try {
    // Old semester (ended 15 days ago - PAST grace period)
    $oldSemester = Semester::create([
        'nama_semester' => 'Test Old',
        'tahun_ajaran' => '2023/2024',
        'status' => 'non-aktif',
        'is_active' => false,
        'tanggal_mulai' => Carbon::now()->subDays(135),
        'tanggal_selesai' => Carbon::now()->subDays(15),
    ]);
    echo "✓ Old Semester created (ID: {$oldSemester->id})\n";
    echo "  Ended: " . $oldSemester->tanggal_selesai->format('Y-m-d') . " (15 days ago)\n";
    echo "  Status: PAST grace period\n\n";

    // Grace semester (ended 5 days ago - WITHIN grace period)
    $graceSemester = Semester::create([
        'nama_semester' => 'Test Grace',
        'tahun_ajaran' => '2024/2025',
        'status' => 'non-aktif',
        'is_active' => false,
        'tanggal_mulai' => Carbon::now()->subDays(125),
        'tanggal_selesai' => Carbon::now()->subDays(5),
    ]);
    echo "✓ Grace Semester created (ID: {$graceSemester->id})\n";
    echo "  Ended: " . $graceSemester->tanggal_selesai->format('Y-m-d') . " (5 days ago)\n";
    echo "  Status: WITHIN grace period (9 days remaining)\n\n";

    // Active semester (current)
    $activeSemester = Semester::create([
        'nama_semester' => 'Test Active',
        'tahun_ajaran' => '2025/2026',
        'status' => 'aktif',
        'is_active' => true,
        'tanggal_mulai' => Carbon::now()->subDays(10),
        'tanggal_selesai' => Carbon::now()->addDays(100),
    ]);
    echo "✓ Active Semester created (ID: {$activeSemester->id})\n";
    echo "  Started: " . $activeSemester->tanggal_mulai->format('Y-m-d') . "\n";
    echo "  Status: ACTIVE\n\n";

} catch (Exception $e) {
    echo "❌ Error creating semesters: " . $e->getMessage() . "\n";
    exit(1);
}

// Step 2: Get test data (mata kuliah & dosen)
echo "\n📝 STEP 2: Getting Test Data\n";
echo str_repeat("─", 60) . "\n";

$mataKuliah = MataKuliah::first();
if (!$mataKuliah) {
    echo "❌ No mata kuliah found. Please seed database first.\n";
    exit(1);
}
echo "✓ Using Mata Kuliah: {$mataKuliah->nama_mk} (ID: {$mataKuliah->id})\n";

$dosen = Dosen::first();
if (!$dosen) {
    echo "❌ No dosen found. Please seed database first.\n";
    exit(1);
}
echo "✓ Using Dosen: ID {$dosen->id}\n\n";

// Step 3: Create test classes
echo "\n📝 STEP 3: Creating Test Classes\n";
echo str_repeat("─", 60) . "\n";

try {
    $oldClass = KelasMataKuliah::create([
        'mata_kuliah_id' => $mataKuliah->id,
        'dosen_id' => $dosen->id,
        'semester_id' => $oldSemester->id,
        'kode_kelas' => 'TEST-OLD-' . time(),
        'kapasitas' => 30,
    ]);
    echo "✓ Old Class created: {$oldClass->kode_kelas} (Semester: {$oldSemester->nama_semester})\n";

    $graceClass = KelasMataKuliah::create([
        'mata_kuliah_id' => $mataKuliah->id,
        'dosen_id' => $dosen->id,
        'semester_id' => $graceSemester->id,
        'kode_kelas' => 'TEST-GRACE-' . time(),
        'kapasitas' => 40,
    ]);
    echo "✓ Grace Class created: {$graceClass->kode_kelas} (Semester: {$graceSemester->nama_semester})\n";

    $activeClass = KelasMataKuliah::create([
        'mata_kuliah_id' => $mataKuliah->id,
        'dosen_id' => $dosen->id,
        'semester_id' => $activeSemester->id,
        'kode_kelas' => 'TEST-ACTIVE-' . time(),
        'kapasitas' => 50,
    ]);
    echo "✓ Active Class created: {$activeClass->kode_kelas} (Semester: {$activeSemester->nama_semester})\n\n";

} catch (Exception $e) {
    echo "❌ Error creating classes: " . $e->getMessage() . "\n";
    exit(1);
}

// Step 4: Test SemesterService
echo "\n📝 STEP 4: Testing SemesterService\n";
echo str_repeat("─", 60) . "\n";

// Test getActiveSemesterIds
$activeSemesterIds = $semesterService->getActiveSemesterIds();
echo "Active Semester IDs (should show classes): " . implode(', ', $activeSemesterIds) . "\n\n";

// Test isInGracePeriod
echo "Grace Period Check:\n";
echo "  • Old Semester: " . ($semesterService->isInGracePeriod($oldSemester) ? "✓ IN" : "✗ NOT IN") . " grace period\n";
echo "  • Grace Semester: " . ($semesterService->isInGracePeriod($graceSemester) ? "✓ IN" : "✗ NOT IN") . " grace period\n";
echo "  • Active Semester: " . ($semesterService->isInGracePeriod($activeSemester) ? "✓ IN" : "✗ NOT IN") . " grace period\n\n";

// Step 5: Test activeClasses scope
echo "\n📝 STEP 5: Testing activeClasses() Scope\n";
echo str_repeat("─", 60) . "\n";

$allClasses = KelasMataKuliah::whereIn('id', [
    $oldClass->id, 
    $graceClass->id, 
    $activeClass->id
])->get();
echo "Total test classes created: " . $allClasses->count() . "\n";

$activeClasses = KelasMataKuliah::whereIn('id', [
    $oldClass->id, 
    $graceClass->id, 
    $activeClass->id
])->activeClasses()->get();

echo "Classes from activeClasses() scope: " . $activeClasses->count() . "\n\n";

echo "Class Visibility:\n";
echo "  • Old Class (TEST-OLD): " . ($activeClasses->contains('id', $oldClass->id) ? "✓ VISIBLE" : "✗ HIDDEN") . " (Expected: HIDDEN)\n";
echo "  • Grace Class (TEST-GRACE): " . ($activeClasses->contains('id', $graceClass->id) ? "✓ VISIBLE" : "✗ HIDDEN") . " (Expected: VISIBLE)\n";
echo "  • Active Class (TEST-ACTIVE): " . ($activeClasses->contains('id', $activeClass->id) ? "✓ VISIBLE" : "✗ HIDDEN") . " (Expected: VISIBLE)\n\n";

// Step 6: Validation
echo "\n📝 STEP 6: Test Results\n";
echo str_repeat("─", 60) . "\n";

$passed = 0;
$failed = 0;

// Test 1: Old class should NOT be visible
if (!$activeClasses->contains('id', $oldClass->id)) {
    echo "✅ PASS: Old class (15 days ago) is HIDDEN ✓\n";
    $passed++;
} else {
    echo "❌ FAIL: Old class should be hidden but is visible ✗\n";
    $failed++;
}

// Test 2: Grace class SHOULD be visible
if ($activeClasses->contains('id', $graceClass->id)) {
    echo "✅ PASS: Grace class (5 days ago) is VISIBLE ✓\n";
    $passed++;
} else {
    echo "❌ FAIL: Grace class should be visible but is hidden ✗\n";
    $failed++;
}

// Test 3: Active class SHOULD be visible
if ($activeClasses->contains('id', $activeClass->id)) {
    echo "✅ PASS: Active class is VISIBLE ✓\n";
    $passed++;
} else {
    echo "❌ FAIL: Active class should be visible but is hidden ✗\n";
    $failed++;
}

// Test 4: Old semester in active IDs?
if (!in_array($oldSemester->id, $activeSemesterIds)) {
    echo "✅ PASS: Old semester NOT in active IDs ✓\n";
    $passed++;
} else {
    echo "❌ FAIL: Old semester should not be in active IDs ✗\n";
    $failed++;
}

// Test 5: Grace semester in active IDs?
if (in_array($graceSemester->id, $activeSemesterIds)) {
    echo "✅ PASS: Grace semester IN active IDs ✓\n";
    $passed++;
} else {
    echo "❌ FAIL: Grace semester should be in active IDs ✗\n";
    $failed++;
}

// Summary
echo "\n";
echo str_repeat("═", 60) . "\n";
echo "TEST SUMMARY\n";
echo str_repeat("═", 60) . "\n";
echo "Total Tests: " . ($passed + $failed) . "\n";
echo "✅ Passed: $passed\n";
echo "❌ Failed: $failed\n";
echo "\n";

if ($failed === 0) {
    echo "🎉 ALL TESTS PASSED! Grace period working correctly.\n";
    echo "\n";
    echo "Conclusion:\n";
    echo "• Kelas dari semester yang berakhir >14 hari lalu: ARCHIVED (tidak tampil)\n";
    echo "• Kelas dari semester dalam grace period (≤14 hari): VISIBLE\n";
    echo "• Kelas dari semester aktif: VISIBLE\n";
} else {
    echo "⚠️  SOME TESTS FAILED. Please check implementation.\n";
}

echo "\n";

// Cleanup
echo "🧹 Cleanup test data...\n";
try {
    $oldClass->delete();
    $graceClass->delete();
    $activeClass->delete();
    $oldSemester->delete();
    $graceSemester->delete();
    $activeSemester->delete();
    echo "✓ Test data cleaned up\n";
} catch (Exception $e) {
    echo "⚠️  Warning: Could not cleanup: " . $e->getMessage() . "\n";
}

echo "\n";
exit($failed > 0 ? 1 : 0);
