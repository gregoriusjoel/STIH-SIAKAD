<?php

namespace Tests\Feature;

use App\Models\Semester;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SemesterControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function adminUser(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    /**
     * Test previousEnd returns correct date when Genap requests preceding Ganjil (same year).
     */
    public function test_previous_end_for_genap_same_year(): void
    {
        $admin = $this->adminUser();

        Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2025/2026',
            'status' => 'aktif',
            'is_active' => true,
            'tanggal_mulai' => '2026-05-21',
            'tanggal_selesai' => '2026-11-21',
        ]);

        $response = $this->actingAs($admin)
            ->getJson(route('admin.semester.previous_end', [
                'tahun_ajaran' => '2025/2026',
                'nama_semester' => 'Genap'
            ]));

        $response->assertStatus(200);
        $response->assertJsonPath('tanggal_selesai', '2026-11-21');
    }

    /**
     * Test previousEnd returns correct date when Ganjil requests preceding Genap (previous year).
     */
    public function test_previous_end_for_ganjil_previous_year(): void
    {
        $admin = $this->adminUser();

        // Previous year's Genap
        Semester::create([
            'nama_semester' => 'Genap',
            'tahun_ajaran' => '2025/2026',
            'status' => 'non-aktif',
            'is_active' => false,
            'tanggal_mulai' => '2026-11-22',
            'tanggal_selesai' => '2027-05-22',
        ]);

        // Requesting for Ganjil 2026/2027
        $response = $this->actingAs($admin)
            ->getJson(route('admin.semester.previous_end', [
                'tahun_ajaran' => '2026/2027',
                'nama_semester' => 'Ganjil'
            ]));

        $response->assertStatus(200);
        $response->assertJsonPath('tanggal_selesai', '2027-05-22');
    }

    /**
     * Test previousEnd falls back to the latest overall semester when exact preceding is not found.
     */
    public function test_previous_end_fallback_to_latest_overall(): void
    {
        $admin = $this->adminUser();

        Semester::create([
            'nama_semester' => 'Genap',
            'tahun_ajaran' => '2024/2025',
            'status' => 'non-aktif',
            'is_active' => false,
            'tanggal_mulai' => '2025-11-22',
            'tanggal_selesai' => '2026-05-22', // latest overall
        ]);

        // Requesting Ganjil 2027/2028 (missing logical predecessor 2026/2027 Genap)
        $response = $this->actingAs($admin)
            ->getJson(route('admin.semester.previous_end', [
                'tahun_ajaran' => '2027/2028',
                'nama_semester' => 'Ganjil'
            ]));

        $response->assertStatus(200);
        $response->assertJsonPath('tanggal_selesai', '2026-05-22');
    }
}
