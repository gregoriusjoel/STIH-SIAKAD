<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Krs;
use App\Models\Nilai;
use App\Models\Invoice;
use App\Models\Internship;
use App\Models\SkripsiSubmission;
use App\Models\AuditLog;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class SuperAdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->seed(RoleAndPermissionSeeder::class);
    }

    /**
     * Test Super Admin can access search page and other roles are forbidden.
     */
    public function test_super_admin_search_access_control(): void
    {
        // 1. Super Admin access
        $superAdmin = User::where('email', 'superadmin@stih.ac.id')->first();
        $response = $this->actingAs($superAdmin)->get(route('super-admin.search'));
        $response->assertStatus(200);

        // 2. Academic / Akademik role access is forbidden
        $akademik = User::factory()->create(['role' => 'akademik']);
        $akademik->assignRole('akademik');
        $response = $this->actingAs($akademik)->get(route('super-admin.search'));
        $response->assertStatus(403);

        // 3. Mahasiswa role access is forbidden
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswa->assignRole('mahasiswa');
        $response = $this->actingAs($mahasiswa)->get(route('super-admin.search'));
        $response->assertStatus(403);
    }

    /**
     * Test Super Admin can impersonate another user and stop.
     */
    public function test_super_admin_impersonate_user(): void
    {
        $superAdmin = User::where('email', 'superadmin@stih.ac.id')->first();
        $targetUser = User::factory()->create(['role' => 'mahasiswa', 'name' => 'Target Student']);
        $targetUser->assignRole('mahasiswa');

        // 1. Start Impersonation
        $response = $this->actingAs($superAdmin)
            ->post(route('super-admin.impersonate', $targetUser->id));

        $response->assertRedirect(route('mahasiswa.dashboard'));
        $this->assertEquals($targetUser->id, Auth::id());
        $this->assertEquals($superAdmin->id, session('impersonator_id'));

        // Assert audit log for start impersonate was written
        $this->assertTrue(AuditLog::where('action', 'user.impersonate_start')->exists());

        // 2. Stop Impersonation
        $response = $this->actingAs($targetUser)
            ->post(route('super-admin.impersonate-stop'));

        $response->assertRedirect(route('super-admin.search'));
        $this->assertEquals($superAdmin->id, Auth::id());
        $this->assertFalse(session()->has('impersonator_id'));

        // Assert audit log for stop impersonate was written
        $this->assertTrue(AuditLog::where('action', 'user.impersonate_stop')->exists());
    }

    /**
     * Test Super Admin can override KRS status.
     */
    public function test_super_admin_override_krs(): void
    {
        $superAdmin = User::where('email', 'superadmin@stih.ac.id')->first();
        
        $user = User::factory()->create(['role' => 'mahasiswa']);
        $mhs = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '12345678',
            'nama' => $user->name,
            'prodi' => 'Ilmu Hukum',
            'angkatan' => '2022',
            'status' => 'aktif',
            'status_akun' => 'baru',
        ]);

        $krs = Krs::create([
            'mahasiswa_id' => $mhs->id,
            'tahun_ajaran' => '2025/2026',
            'status' => 'pending',
            'ambil_mk' => true
        ]);

        $response = $this->actingAs($superAdmin)
            ->post(route('super-admin.override.krs', $krs->id), [
                'status' => 'sudah submit',
                'override_reason' => 'Perbaikan registrasi matakuliah mahasiswa'
            ]);

        $response->assertRedirect();
        
        $krs->refresh();
        $this->assertEquals('sudah submit', $krs->status);
        $this->assertEquals('Perbaikan registrasi matakuliah mahasiswa', $krs->keterangan);

        // Assert audit log
        $this->assertTrue(AuditLog::where('action', 'krs.override')->exists());
    }

    /**
     * Test Super Admin can override Grade.
     */
    public function test_super_admin_override_grade(): void
    {
        $superAdmin = User::where('email', 'superadmin@stih.ac.id')->first();

        $user = User::factory()->create(['role' => 'mahasiswa']);
        $mhs = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '12345678',
            'nama' => $user->name,
            'prodi' => 'Ilmu Hukum',
            'angkatan' => '2022',
            'status' => 'aktif',
            'status_akun' => 'baru',
        ]);

        $krs = Krs::create([
            'mahasiswa_id' => $mhs->id,
            'tahun_ajaran' => '2025/2026',
            'status' => 'sudah submit',
            'ambil_mk' => true
        ]);
        
        $nilai = Nilai::create([
            'krs_id' => $krs->id,
            'nilai_akhir' => 65.00,
            'grade' => 'B-',
            'bobot' => 2.67
        ]);

        $response = $this->actingAs($superAdmin)
            ->post(route('super-admin.override.nilai', $nilai->id), [
                'nilai_akhir' => 85.50,
                'override_reason' => 'Koreksi nilai uas yang salah input'
            ]);

        $response->assertRedirect();

        $nilai->refresh();
        $this->assertEquals(85.50, (float)$nilai->nilai_akhir);
        $this->assertEquals('A', $nilai->grade); // Auto mapped from convertToGrade
        $this->assertEquals(4.00, (float)$nilai->bobot); // Auto mapped from convertToGrade

        // Assert audit log
        $this->assertTrue(AuditLog::where('action', 'nilai.override')->exists());
    }

    /**
     * Test Super Admin can override Invoice status.
     */
    public function test_super_admin_override_invoice(): void
    {
        $superAdmin = User::where('email', 'superadmin@stih.ac.id')->first();

        $user = User::factory()->create(['role' => 'mahasiswa']);
        $mhs = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '12345678',
            'nama' => $user->name,
            'prodi' => 'Ilmu Hukum',
            'angkatan' => '2022',
            'status' => 'aktif',
            'status_akun' => 'baru',
        ]);

        \Illuminate\Support\Facades\DB::table('students')->insert([
            'id' => $mhs->id,
            'user_id' => $user->id,
            'npm' => '12345678',
            'nama' => $user->name,
            'prodi' => 'Ilmu Hukum',
            'angkatan' => '2022',
        ]);

        $invoice = Invoice::create([
            'student_id' => $mhs->id,
            'semester' => 1,
            'tahun_ajaran' => '2025/2026',
            'total_tagihan' => 1500000,
            'status' => 'PUBLISHED',
            'created_by' => $superAdmin->id,
        ]);

        $response = $this->actingAs($superAdmin)
            ->post(route('super-admin.override.invoice', $invoice->id), [
                'status' => 'LUNAS',
                'override_reason' => 'Verifikasi pembayaran offline khusus'
            ]);

        $response->assertRedirect();

        $invoice->refresh();
        $this->assertEquals('LUNAS', $invoice->status);
        $this->assertStringContainsString('Verifikasi pembayaran offline khusus', $invoice->notes);

        // Assert audit log
        $this->assertTrue(AuditLog::where('action', 'invoice.override')->exists());
    }

    /**
     * Test Super Admin can override Internship status.
     */
    public function test_super_admin_override_internship(): void
    {
        $superAdmin = User::where('email', 'superadmin@stih.ac.id')->first();

        $user = User::factory()->create(['role' => 'mahasiswa']);
        $mhs = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '12345678',
            'nama' => $user->name,
            'prodi' => 'Ilmu Hukum',
            'angkatan' => '2022',
            'status' => 'aktif',
            'status_akun' => 'baru',
        ]);

        $semester = \App\Models\Semester::create([
            'tahun_ajaran' => '2025/2026',
            'nama_semester' => 'Ganjil',
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(6)->toDateString(),
            'is_active' => true,
        ]);

        $internship = Internship::create([
            'mahasiswa_id' => $mhs->id,
            'semester_id' => $semester->id,
            'instansi' => 'Kejaksaan Negeri',
            'alamat_instansi' => 'Jl. Salemba Raya No. 1',
            'periode_mulai' => now()->toDateString(),
            'periode_selesai' => now()->addMonths(3)->toDateString(),
            'posisi' => 'Staf Hukum',
            'status' => 'under_review',
        ]);

        $response = $this->actingAs($superAdmin)
            ->post(route('super-admin.override.internship', $internship->id), [
                'status' => 'approved',
                'override_reason' => 'Persetujuan instansi khusus'
            ]);

        $response->assertRedirect();

        $internship->refresh();
        $this->assertEquals('approved', $internship->status);
        $this->assertEquals('Persetujuan instansi khusus', $internship->admin_note);

        // Assert audit log
        $this->assertTrue(AuditLog::where('action', 'internship.override')->exists());
    }

    /**
     * Test Super Admin can override Skripsi status.
     */
    public function test_super_admin_override_skripsi(): void
    {
        $superAdmin = User::where('email', 'superadmin@stih.ac.id')->first();

        $user = User::factory()->create(['role' => 'mahasiswa']);
        $mhs = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '12345678',
            'nama' => $user->name,
            'prodi' => 'Ilmu Hukum',
            'angkatan' => '2022',
            'status' => 'aktif',
            'status_akun' => 'baru',
        ]);

        $skripsi = SkripsiSubmission::create([
            'mahasiswa_id' => $mhs->id,
            'judul' => 'Analisis Hukum Pidana',
            'status' => 'PROPOSAL_SUBMITTED',
        ]);

        $response = $this->actingAs($superAdmin)
            ->post(route('super-admin.override.skripsi', $skripsi->id), [
                'status' => 'PROPOSAL_APPROVED',
                'override_reason' => 'Judul skripsi disetujui langsung oleh Senat'
            ]);

        $response->assertRedirect();

        $skripsi->refresh();
        $this->assertEquals('PROPOSAL_APPROVED', $skripsi->status->value);
        $this->assertEquals('Judul skripsi disetujui langsung oleh Senat', $skripsi->keterangan);

        // Assert audit log
        $this->assertTrue(AuditLog::where('action', 'skripsi.override')->exists());
    }
}
