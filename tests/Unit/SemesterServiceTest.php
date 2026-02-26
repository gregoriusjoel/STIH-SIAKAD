<?php

namespace Tests\Unit\Services;

use App\Models\Semester;
use App\Models\KelasMataKuliah;
use App\Models\MataKuliah;
use App\Models\Dosen;
use App\Services\SemesterService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SemesterServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SemesterService $semesterService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->semesterService = app(SemesterService::class);
    }

    /**
     * Test that active semester is retrieved correctly
     */
    public function test_get_active_semester_returns_current_active_semester()
    {
        // Create an active semester
        $activeSemester = Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2025/2026',
            'status' => 'aktif',
            'is_active' => true,
            'tanggal_mulai' => Carbon::now()->subDays(30),
            'tanggal_selesai' => Carbon::now()->addDays(30),
        ]);

        // Create an inactive semester
        Semester::create([
            'nama_semester' => 'Genap',
            'tahun_ajaran' => '2024/2025',
            'status' => 'non-aktif',
            'is_active' => false,
            'tanggal_mulai' => Carbon::now()->subDays(200),
            'tanggal_selesai' => Carbon::now()->subDays(100),
        ]);

        $result = $this->semesterService->getActiveSemester();

        $this->assertNotNull($result);
        $this->assertEquals($activeSemester->id, $result->id);
        $this->assertTrue($result->is_active);
    }

    /**
     * Test that semesters within grace period are identified correctly
     */
    public function test_get_semesters_in_grace_period()
    {
        // Semester that ended 7 days ago (within grace period)
        $recentlyEnded = Semester::create([
            'nama_semester' => 'Genap',
            'tahun_ajaran' => '2024/2025',
            'status' => 'non-aktif',
            'is_active' => false,
            'tanggal_mulai' => Carbon::now()->subDays(120),
            'tanggal_selesai' => Carbon::now()->subDays(7),
        ]);

        // Semester that ended 20 days ago (outside grace period)
        Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2024/2025',
            'status' => 'non-aktif',
            'is_active' => false,
            'tanggal_mulai' => Carbon::now()->subDays(200),
            'tanggal_selesai' => Carbon::now()->subDays(20),
        ]);

        // Current active semester
        Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2025/2026',
            'status' => 'aktif',
            'is_active' => true,
            'tanggal_mulai' => Carbon::now()->subDays(10),
            'tanggal_selesai' => Carbon::now()->addDays(100),
        ]);

        $gracePeriodSemesters = $this->semesterService->getSemestersInGracePeriod();

        $this->assertCount(1, $gracePeriodSemesters);
        $this->assertEquals($recentlyEnded->id, $gracePeriodSemesters->first()->id);
    }

    /**
     * Test that active semester IDs include both active and grace period semesters
     */
    public function test_get_active_semester_ids_includes_grace_period()
    {
        // Current active semester
        $activeSemester = Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2025/2026',
            'status' => 'aktif',
            'is_active' => true,
            'tanggal_mulai' => Carbon::now()->subDays(10),
            'tanggal_selesai' => Carbon::now()->addDays(100),
        ]);

        // Semester in grace period (ended 5 days ago)
        $gracePeriodSemester = Semester::create([
            'nama_semester' => 'Genap',
            'tahun_ajaran' => '2024/2025',
            'status' => 'non-aktif',
            'is_active' => false,
            'tanggal_mulai' => Carbon::now()->subDays(120),
            'tanggal_selesai' => Carbon::now()->subDays(5),
        ]);

        // Semester ended long ago (outside grace period)
        Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2023/2024',
            'status' => 'non-aktif',
            'is_active' => false,
            'tanggal_mulai' => Carbon::now()->subDays(365),
            'tanggal_selesai' => Carbon::now()->subDays(200),
        ]);

        $activeSemesterIds = $this->semesterService->getActiveSemesterIds();

        $this->assertCount(2, $activeSemesterIds);
        $this->assertContains($activeSemester->id, $activeSemesterIds);
        $this->assertContains($gracePeriodSemester->id, $activeSemesterIds);
    }

    /**
     * Test that semester should deactivate after grace period
     */
    public function test_should_deactivate_after_grace_period()
    {
        // Semester that ended 15 days ago (past grace period)
        $oldSemester = Semester::create([
            'nama_semester' => 'Genap',
            'tahun_ajaran' => '2024/2025',
            'status' => 'aktif',
            'is_active' => true,
            'tanggal_mulai' => Carbon::now()->subDays(120),
            'tanggal_selesai' => Carbon::now()->subDays(15),
        ]);

        $this->assertTrue($this->semesterService->shouldDeactivate($oldSemester));
    }

    /**
     * Test that semester should NOT deactivate during grace period
     */
    public function test_should_not_deactivate_during_grace_period()
    {
        // Semester that ended 5 days ago (within grace period)
        $recentSemester = Semester::create([
            'nama_semester' => 'Genap',
            'tahun_ajaran' => '2024/2025',
            'status' => 'aktif',
            'is_active' => true,
            'tanggal_mulai' => Carbon::now()->subDays(120),
            'tanggal_selesai' => Carbon::now()->subDays(5),
        ]);

        $this->assertFalse($this->semesterService->shouldDeactivate($recentSemester));
    }

    /**
     * Test semester activation
     */
    public function test_activate_semester_ensures_only_one_active()
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

        $result = $this->semesterService->activateSemester($semester2);

        $this->assertTrue($result);

        // Refresh from database
        $semester1->refresh();
        $semester2->refresh();

        $this->assertFalse($semester1->is_active);
        $this->assertEquals('non-aktif', $semester1->status);
        
        $this->assertTrue($semester2->is_active);
        $this->assertEquals('aktif', $semester2->status);
    }

    /**
     * Test that classes from active semester are filtered correctly
     */
    public function test_active_classes_scope_filters_by_semester()
    {
        // Create semesters
        $activeSemester = Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2025/2026',
            'status' => 'aktif',
            'is_active' => true,
            'tanggal_mulai' => Carbon::now()->subDays(10),
            'tanggal_selesai' => Carbon::now()->addDays(100),
        ]);

        $gracePeriodSemester = Semester::create([
            'nama_semester' => 'Genap',
            'tahun_ajaran' => '2024/2025',
            'status' => 'non-aktif',
            'is_active' => false,
            'tanggal_mulai' => Carbon::now()->subDays(120),
            'tanggal_selesai' => Carbon::now()->subDays(5),
        ]);

        $oldSemester = Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2023/2024',
            'status' => 'non-aktif',
            'is_active' => false,
            'tanggal_mulai' => Carbon::now()->subDays(365),
            'tanggal_selesai' => Carbon::now()->subDays(200),
        ]);

        // Create mata kuliah and dosen
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

        // Create classes for each semester
        $activeClass = KelasMataKuliah::create([
            'mata_kuliah_id' => $mataKuliah->id,
            'dosen_id' => $dosen->id,
            'semester_id' => $activeSemester->id,
            'kode_kelas' => 'A',
            'kapasitas' => 40,
        ]);

        $graceClass = KelasMataKuliah::create([
            'mata_kuliah_id' => $mataKuliah->id,
            'dosen_id' => $dosen->id,
            'semester_id' => $gracePeriodSemester->id,
            'kode_kelas' => 'B',
            'kapasitas' => 40,
        ]);

        $oldClass = KelasMataKuliah::create([
            'mata_kuliah_id' => $mataKuliah->id,
            'dosen_id' => $dosen->id,
            'semester_id' => $oldSemester->id,
            'kode_kelas' => 'C',
            'kapasitas' => 40,
        ]);

        // Test activeClasses scope
        $activeClasses = KelasMataKuliah::activeClasses()->get();

        $this->assertCount(2, $activeClasses);
        $this->assertTrue($activeClasses->contains('id', $activeClass->id));
        $this->assertTrue($activeClasses->contains('id', $graceClass->id));
        $this->assertFalse($activeClasses->contains('id', $oldClass->id));
    }

    /**
     * Test automatic status updates process
     */
    public function test_process_automatic_status_updates()
    {
        // Semester to activate (start date has passed)
        $toActivate = Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2026/2027',
            'status' => 'non-aktif',
            'is_active' => false,
            'tanggal_mulai' => Carbon::now()->subDays(1),
            'tanggal_selesai' => Carbon::now()->addDays(100),
        ]);

        // Semester to deactivate (grace period has ended)
        $toDeactivate = Semester::create([
            'nama_semester' => 'Genap',
            'tahun_ajaran' => '2024/2025',
            'status' => 'aktif',
            'is_active' => true,
            'tanggal_mulai' => Carbon::now()->subDays(150),
            'tanggal_selesai' => Carbon::now()->subDays(15),
        ]);

        $report = $this->semesterService->processAutomaticStatusUpdates();

        $this->assertArrayHasKey('activated', $report);
        $this->assertArrayHasKey('deactivated', $report);
        $this->assertArrayHasKey('errors', $report);

        $this->assertCount(1, $report['activated']);
        $this->assertCount(1, $report['deactivated']);
        $this->assertEmpty($report['errors']);

        // Verify database changes
        $toActivate->refresh();
        $toDeactivate->refresh();

        $this->assertTrue($toActivate->is_active);
        $this->assertFalse($toDeactivate->is_active);
    }

    /**
     * Test getSemesterStatus method returns correct information
     */
    public function test_get_semester_status_returns_correct_info()
    {
        $semester = Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2025/2026',
            'status' => 'aktif',
            'is_active' => true,
            'tanggal_mulai' => Carbon::now()->subDays(10),
            'tanggal_selesai' => Carbon::now()->addDays(100),
        ]);

        $status = $this->semesterService->getSemesterStatus($semester);

        $this->assertArrayHasKey('status', $status);
        $this->assertArrayHasKey('is_in_grace_period', $status);
        $this->assertArrayHasKey('should_show_classes', $status);
        $this->assertArrayHasKey('grace_period_end', $status);
        
        $this->assertEquals('ongoing', $status['status']);
        $this->assertTrue($status['should_show_classes']);
    }
}
