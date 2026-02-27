<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\MataKuliah;
use App\Models\MataKuliahSemester;
use App\Models\Semester;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\MataKuliahSemesterService;
use App\Services\SemesterService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MataKuliahSemesterFeatureTest extends TestCase
{
    use RefreshDatabase;

    /* ─────────────────────────────────────────
     * Helpers
     * ───────────────────────────────────────── */

    protected function adminUser(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    protected function createSemester(array $attrs = []): Semester
    {
        return Semester::create(array_merge([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2026/2027',
            'status' => 'aktif',
            'is_active' => true,
            'is_locked' => false,
            'tanggal_mulai' => now()->subDays(30),
            'tanggal_selesai' => now()->addDays(120),
        ], $attrs));
    }

    protected function createMataKuliah(array $attrs = []): MataKuliah
    {
        return MataKuliah::create(array_merge([
            'kode_mk' => 'MK' . rand(1000, 9999),
            'nama_mk' => 'Test Mata Kuliah',
            'sks' => 3,
            'semester' => 1,
            'jenis' => 'wajib_prodi',
            'prodi_id' => 1,
            'fakultas_id' => 1,
        ], $attrs));
    }

    /* ═══════════════════════════════════════════
     *  TEST 1: Activate Semester → relasi lama jadi history
     * ═══════════════════════════════════════════ */

    public function test_activate_semester_marks_old_mk_as_history(): void
    {
        // Arrange
        $oldSemester = $this->createSemester([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2025/2026',
            'status' => 'aktif',
            'is_active' => true,
        ]);

        $mk1 = $this->createMataKuliah(['kode_mk' => 'MK001']);
        $mk2 = $this->createMataKuliah(['kode_mk' => 'MK002']);

        // Attach MK to old semester
        MataKuliahSemester::create([
            'semester_id' => $oldSemester->id,
            'mata_kuliah_id' => $mk1->id,
            'status' => 'active',
            'activated_at' => now()->subDays(10),
        ]);
        MataKuliahSemester::create([
            'semester_id' => $oldSemester->id,
            'mata_kuliah_id' => $mk2->id,
            'status' => 'active',
            'activated_at' => now()->subDays(10),
        ]);

        $newSemester = $this->createSemester([
            'nama_semester' => 'Genap',
            'tahun_ajaran' => '2025/2026',
            'status' => 'non-aktif',
            'is_active' => false,
            'tanggal_mulai' => now()->addDays(1),
            'tanggal_selesai' => now()->addDays(180),
        ]);

        // Act
        $this->actingAs($this->adminUser());
        $semesterService = app(SemesterService::class);
        $result = $semesterService->activateSemester($newSemester);

        // Assert: activate succeeded
        $this->assertTrue($result);

        // Old semester relasi should be history
        $this->assertEquals(2, MataKuliahSemester::where('semester_id', $oldSemester->id)
            ->where('status', 'history')
            ->count());

        // Old semester should be inactive
        $oldSemester->refresh();
        $this->assertFalse((bool) $oldSemester->is_active);

        // New semester should be active
        $newSemester->refresh();
        $this->assertTrue((bool) $newSemester->is_active);

        // Audit log should record the activation
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'activate_semester',
            'auditable_type' => Semester::class,
            'auditable_id' => $newSemester->id,
        ]);
    }

    /* ═══════════════════════════════════════════
     *  TEST 2: Carry Forward → pivot terisi tanpa duplikasi
     * ═══════════════════════════════════════════ */

    public function test_carry_forward_copies_mk_without_duplication(): void
    {
        // Arrange
        $sourceSemester = $this->createSemester([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2025/2026',
            'status' => 'non-aktif',
            'is_active' => false,
        ]);

        $targetSemester = $this->createSemester([
            'nama_semester' => 'Genap',
            'tahun_ajaran' => '2025/2026',
            'status' => 'aktif',
            'is_active' => true,
        ]);

        $mk1 = $this->createMataKuliah(['kode_mk' => 'MK001']);
        $mk2 = $this->createMataKuliah(['kode_mk' => 'MK002']);
        $mk3 = $this->createMataKuliah(['kode_mk' => 'MK003']);

        // Add mk1 & mk2 to source (history)
        MataKuliahSemester::create(['semester_id' => $sourceSemester->id, 'mata_kuliah_id' => $mk1->id, 'status' => 'history']);
        MataKuliahSemester::create(['semester_id' => $sourceSemester->id, 'mata_kuliah_id' => $mk2->id, 'status' => 'history']);

        // mk3 already exists in target (conflict)
        MataKuliahSemester::create(['semester_id' => $targetSemester->id, 'mata_kuliah_id' => $mk3->id, 'status' => 'active', 'activated_at' => now()]);

        // Act
        $this->actingAs($this->adminUser());
        $service = app(MataKuliahSemesterService::class);
        $result = $service->carryForward($sourceSemester->id, $targetSemester->id);

        // Assert: 2 copied, 0 skipped (mk3 was not in source)
        $this->assertEquals(2, $result['copied']);
        $this->assertEquals(0, $result['skipped']);
        $this->assertEmpty($result['errors']);

        // Both should now be active in target
        $this->assertDatabaseHas('mata_kuliah_semesters', [
            'semester_id' => $targetSemester->id,
            'mata_kuliah_id' => $mk1->id,
            'status' => 'active',
            'source_semester_id' => $sourceSemester->id,
        ]);
        $this->assertDatabaseHas('mata_kuliah_semesters', [
            'semester_id' => $targetSemester->id,
            'mata_kuliah_id' => $mk2->id,
            'status' => 'active',
            'source_semester_id' => $sourceSemester->id,
        ]);

        // mk3 still there (not duplicated)
        $this->assertEquals(1, MataKuliahSemester::where('semester_id', $targetSemester->id)
            ->where('mata_kuliah_id', $mk3->id)
            ->count());

        // Total in target = 3 (mk1, mk2, mk3)
        $this->assertEquals(3, MataKuliahSemester::where('semester_id', $targetSemester->id)->count());

        // Audit log should have carry_forward entries
        $this->assertEquals(2, AuditLog::where('action', 'carry_forward')->count());
    }

    /* ═══════════════════════════════════════════
     *  TEST 3: Lock Semester → semua mutasi ditolak
     * ═══════════════════════════════════════════ */

    public function test_locked_semester_rejects_all_mutations(): void
    {
        $semester = $this->createSemester(['is_locked' => true]);
        $mk = $this->createMataKuliah(['kode_mk' => 'MK001']);

        $this->actingAs($this->adminUser());
        $service = app(MataKuliahSemesterService::class);

        // Attempt to attach to locked semester
        $this->expectException(\App\Exceptions\SemesterLockedException::class);
        $service->attachToSemester($semester->id, [$mk->id]);
    }

    public function test_locked_semester_rejects_carry_forward(): void
    {
        $sourceSemester = $this->createSemester([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2025/2026',
            'status' => 'non-aktif',
            'is_active' => false,
        ]);
        $lockedSemester = $this->createSemester(['is_locked' => true]);

        $mk = $this->createMataKuliah(['kode_mk' => 'MK001']);
        MataKuliahSemester::create(['semester_id' => $sourceSemester->id, 'mata_kuliah_id' => $mk->id, 'status' => 'history']);

        $this->actingAs($this->adminUser());
        $service = app(MataKuliahSemesterService::class);

        $this->expectException(\App\Exceptions\SemesterLockedException::class);
        $service->carryForward($sourceSemester->id, $lockedSemester->id);
    }

    public function test_locked_semester_rejects_detach(): void
    {
        $semester = $this->createSemester(['is_locked' => true]);
        $mk = $this->createMataKuliah(['kode_mk' => 'MK001']);
        MataKuliahSemester::create(['semester_id' => $semester->id, 'mata_kuliah_id' => $mk->id, 'status' => 'active', 'activated_at' => now()]);

        $this->actingAs($this->adminUser());
        $service = app(MataKuliahSemesterService::class);

        $this->expectException(\App\Exceptions\SemesterLockedException::class);
        $service->detachFromSemester($semester->id, [$mk->id]);
    }

    /* ═══════════════════════════════════════════
     *  TEST 4: Carry Forward Preview — conflict detection
     * ═══════════════════════════════════════════ */

    public function test_carry_forward_preview_detects_conflicts(): void
    {
        $source = $this->createSemester(['nama_semester' => 'Ganjil', 'is_active' => false, 'status' => 'non-aktif']);
        $target = $this->createSemester(['nama_semester' => 'Genap', 'is_active' => true, 'status' => 'aktif']);

        $mk1 = $this->createMataKuliah(['kode_mk' => 'MK001']);
        $mk2 = $this->createMataKuliah(['kode_mk' => 'MK002']);

        MataKuliahSemester::create(['semester_id' => $source->id, 'mata_kuliah_id' => $mk1->id, 'status' => 'history']);
        MataKuliahSemester::create(['semester_id' => $source->id, 'mata_kuliah_id' => $mk2->id, 'status' => 'history']);

        // mk1 already in target (conflict)
        MataKuliahSemester::create(['semester_id' => $target->id, 'mata_kuliah_id' => $mk1->id, 'status' => 'active', 'activated_at' => now()]);

        $service = app(MataKuliahSemesterService::class);
        $preview = $service->previewCarryForward($source->id, $target->id);

        $this->assertEquals(1, $preview['to_copy']->count());
        $this->assertEquals(1, $preview['conflicts']->count());
        $this->assertEquals(2, $preview['source_total']);
        $this->assertEquals($mk2->id, $preview['to_copy']->first()->id);
        $this->assertEquals($mk1->id, $preview['conflicts']->first()->id);
    }

    /* ═══════════════════════════════════════════
     *  TEST 5: Restore from history
     * ═══════════════════════════════════════════ */

    public function test_restore_from_history_activates_mk(): void
    {
        $historySemester = $this->createSemester(['nama_semester' => 'Ganjil', 'is_active' => false, 'status' => 'non-aktif']);
        $activeSemester = $this->createSemester(['nama_semester' => 'Genap', 'is_active' => true, 'status' => 'aktif']);

        $mk = $this->createMataKuliah(['kode_mk' => 'MK001']);
        MataKuliahSemester::create(['semester_id' => $historySemester->id, 'mata_kuliah_id' => $mk->id, 'status' => 'history', 'deactivated_at' => now()]);

        $this->actingAs($this->adminUser());
        $service = app(MataKuliahSemesterService::class);

        $result = $service->restoreFromSemester($historySemester->id, $activeSemester->id, [$mk->id]);

        $this->assertEquals(1, $result['restored']);
        $this->assertEquals(0, $result['skipped']);

        $this->assertDatabaseHas('mata_kuliah_semesters', [
            'semester_id' => $activeSemester->id,
            'mata_kuliah_id' => $mk->id,
            'status' => 'active',
            'source_semester_id' => $historySemester->id,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'restore_mk',
        ]);
    }

    /* ═══════════════════════════════════════════
     *  TEST 6: Unique constraint — no duplicate pivot
     * ═══════════════════════════════════════════ */

    public function test_attach_does_not_duplicate_existing_active_mk(): void
    {
        $semester = $this->createSemester();
        $mk = $this->createMataKuliah(['kode_mk' => 'MK001']);

        MataKuliahSemester::create(['semester_id' => $semester->id, 'mata_kuliah_id' => $mk->id, 'status' => 'active', 'activated_at' => now()]);

        $this->actingAs($this->adminUser());
        $service = app(MataKuliahSemesterService::class);

        $result = $service->attachToSemester($semester->id, [$mk->id]);

        $this->assertEquals(0, $result['attached']);
        $this->assertEquals(1, $result['skipped']);

        // Only one record in DB
        $this->assertEquals(1, MataKuliahSemester::where('semester_id', $semester->id)
            ->where('mata_kuliah_id', $mk->id)
            ->count());
    }

    /* ═══════════════════════════════════════════
     *  TEST 7: Lock + Unlock via service
     * ═══════════════════════════════════════════ */

    public function test_semester_lock_and_unlock(): void
    {
        $semester = $this->createSemester(['is_locked' => false]);
        $this->actingAs($this->adminUser());

        $semesterService = app(SemesterService::class);

        // Lock
        $this->assertTrue($semesterService->lockSemester($semester));
        $semester->refresh();
        $this->assertTrue((bool) $semester->is_locked);
        $this->assertNotNull($semester->locked_at);

        $this->assertDatabaseHas('audit_logs', ['action' => 'lock_semester']);

        // Unlock
        $this->assertTrue($semesterService->unlockSemester($semester));
        $semester->refresh();
        $this->assertFalse((bool) $semester->is_locked);
        $this->assertNull($semester->locked_at);

        $this->assertDatabaseHas('audit_logs', ['action' => 'unlock_semester']);
    }

    /* ═══════════════════════════════════════════
     *  TEST 8: Audit log — all actions are logged
     * ═══════════════════════════════════════════ */

    public function test_audit_log_records_all_actions(): void
    {
        $semester = $this->createSemester();
        $mk = $this->createMataKuliah(['kode_mk' => 'MK001']);

        $this->actingAs($this->adminUser());
        $service = app(MataKuliahSemesterService::class);

        // Attach
        $service->attachToSemester($semester->id, [$mk->id]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'attach_mk']);

        // Detach
        $service->detachFromSemester($semester->id, [$mk->id]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'detach_mk']);

        $this->assertEquals(2, AuditLog::whereIn('action', ['attach_mk', 'detach_mk'])->count());
    }
}
