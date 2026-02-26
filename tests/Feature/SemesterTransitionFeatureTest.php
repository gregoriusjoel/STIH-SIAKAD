<?php

namespace Tests\Feature;

use App\Models\Semester;
use App\Models\KelasMataKuliah;
use App\Models\MataKuliah;
use App\Models\Dosen;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SemesterTransitionFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that classes from old semester disappear after 14 days grace period
     */
    public function test_old_semester_classes_disappear_after_grace_period()
    {
        // Setup: Create old semester (ended 15 days ago - past grace period)
        $oldSemester = Semester::create([
            'nama_semester' => 'Genap',
            'tahun_ajaran' => '2024/2025',
            'status' => 'non-aktif',
            'is_active' => false,
            'tanggal_mulai' => Carbon::now()->subDays(135),
            'tanggal_selesai' => Carbon::now()->subDays(15),
        ]);

        // Create new active semester
        $newSemester = Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2025/2026',
            'status' => 'aktif',
            'is_active' => true,
            'tanggal_mulai' => Carbon::now()->subDays(10),
            'tanggal_selesai' => Carbon::now()->addDays(100),
        ]);

        // Create test data
        $mataKuliah = MataKuliah::create([
            'kode_mk' => 'MK001',
            'nama_mk' => 'Test MK',
            'sks' => 3,
            'semester' => 1,
        ]);

        $dosen = Dosen::create([
            'user_id' => 1,
            'nidn' => '1234567890',
            'nama' => 'Test Dosen',
        ]);

        // Create class from old semester
        $oldClass = KelasMataKuliah::create([
            'mata_kuliah_id' => $mataKuliah->id,
            'dosen_id' => $dosen->id,
            'semester_id' => $oldSemester->id,
            'kode_kelas' => 'OLD',
            'kapasitas' => 40,
        ]);

        // Create class from new semester
        $newClass = KelasMataKuliah::create([
            'mata_kuliah_id' => $mataKuliah->id,
            'dosen_id' => $dosen->id,
            'semester_id' => $newSemester->id,
            'kode_kelas' => 'NEW',
            'kapasitas' => 40,
        ]);

        // Test: Query active classes
        $activeClasses = KelasMataKuliah::activeClasses()->get();

        // Assert: Only new semester class appears
        $this->assertCount(1, $activeClasses);
        $this->assertEquals($newClass->id, $activeClasses->first()->id);
        $this->assertFalse($activeClasses->contains('id', $oldClass->id));
    }

    /**
     * Test that classes from recently ended semester still appear during grace period
     */
    public function test_recent_semester_classes_visible_during_grace_period()
    {
        // Setup: Create semester that ended 5 days ago (within grace period)
        $recentSemester = Semester::create([
            'nama_semester' => 'Genap',
            'tahun_ajaran' => '2024/2025',
            'status' => 'non-aktif',
            'is_active' => false,
            'tanggal_mulai' => Carbon::now()->subDays(120),
            'tanggal_selesai' => Carbon::now()->subDays(5),
        ]);

        // Create new active semester
        $newSemester = Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2025/2026',
            'status' => 'aktif',
            'is_active' => true,
            'tanggal_mulai' => Carbon::now()->subDays(3),
            'tanggal_selesai' => Carbon::now()->addDays(100),
        ]);

        // Create test data
        $mataKuliah = MataKuliah::create([
            'kode_mk' => 'MK001',
            'nama_mk' => 'Test MK',
            'sks' => 3,
            'semester' => 1,
        ]);

        $dosen = Dosen::create([
            'user_id' => 1,
            'nidn' => '1234567890',
            'nama' => 'Test Dosen',
        ]);

        // Create class from recent semester (within grace period)
        $recentClass = KelasMataKuliah::create([
            'mata_kuliah_id' => $mataKuliah->id,
            'dosen_id' => $dosen->id,
            'semester_id' => $recentSemester->id,
            'kode_kelas' => 'RECENT',
            'kapasitas' => 40,
        ]);

        // Create class from new semester
        $newClass = KelasMataKuliah::create([
            'mata_kuliah_id' => $mataKuliah->id,
            'dosen_id' => $dosen->id,
            'semester_id' => $newSemester->id,
            'kode_kelas' => 'NEW',
            'kapasitas' => 40,
        ]);

        // Test: Query active classes
        $activeClasses = KelasMataKuliah::activeClasses()->get();

        // Assert: Both classes appear (grace period + active)
        $this->assertCount(2, $activeClasses);
        $this->assertTrue($activeClasses->contains('id', $recentClass->id));
        $this->assertTrue($activeClasses->contains('id', $newClass->id));
    }

    /**
     * Test that new semester shows correct schedules
     */
    public function test_new_semester_shows_correct_schedules()
    {
        // Create multiple semesters
        $oldSemester = Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2023/2024',
            'status' => 'non-aktif',
            'is_active' => false,
            'tanggal_mulai' => Carbon::now()->subDays(365),
            'tanggal_selesai' => Carbon::now()->subDays(200),
        ]);

        $currentSemester = Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2025/2026',
            'status' => 'aktif',
            'is_active' => true,
            'tanggal_mulai' => Carbon::now()->subDays(30),
            'tanggal_selesai' => Carbon::now()->addDays(90),
        ]);

        // Create mata kuliah and dosen
        $mataKuliah = MataKuliah::create([
            'kode_mk' => 'MK001',
            'nama_mk' => 'Sistem Informasi',
            'sks' => 3,
            'semester' => 1,
        ]);

        $dosen = Dosen::create([
            'user_id' => 1,
            'nidn' => '1234567890',
            'nama' => 'Dr. Test',
        ]);

        // Create classes
        $oldClass = KelasMataKuliah::create([
            'mata_kuliah_id' => $mataKuliah->id,
            'dosen_id' => $dosen->id,
            'semester_id' => $oldSemester->id,
            'kode_kelas' => 'A-OLD',
            'kapasitas' => 30,
            'hari' => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        $currentClass = KelasMataKuliah::create([
            'mata_kuliah_id' => $mataKuliah->id,
            'dosen_id' => $dosen->id,
            'semester_id' => $currentSemester->id,
            'kode_kelas' => 'A-NEW',
            'kapasitas' => 40,
            'hari' => 'Selasa',
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
        ]);

        // Query using current semester scope
        $currentSemesterClasses = KelasMataKuliah::currentSemester()->get();

        // Assert: Only current semester class
        $this->assertCount(1, $currentSemesterClasses);
        $this->assertEquals('A-NEW', $currentSemesterClasses->first()->kode_kelas);
        $this->assertEquals('Selasa', $currentSemesterClasses->first()->hari);
    }

    /**
     * Test no duplicate active semesters
     */
    public function test_no_duplicate_active_semesters()
    {
        $semester1 = Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2024/2025',
            'status' => 'aktif',
            'is_active' => true,
            'tanggal_mulai' => Carbon::now()->subDays(100),
            'tanggal_selesai' => Carbon::now()->subDays(1),
        ]);

        $semester2 = Semester::create([
            'nama_semester' => 'Genap',
            'tahun_ajaran' => '2025/2026',
            'status' => 'non-aktif',
            'is_active' => false,
            'tanggal_mulai' => Carbon::now(),
            'tanggal_selesai' => Carbon::now()->addDays(100),
        ]);

        // Activate semester2
        $semesterService = app(\App\Services\SemesterService::class);
        $semesterService->activateSemester($semester2);

        // Check that only one is active
        $activeSemesters = Semester::where('is_active', true)->get();
        
        $this->assertCount(1, $activeSemesters);
        $this->assertEquals($semester2->id, $activeSemesters->first()->id);
    }
}
