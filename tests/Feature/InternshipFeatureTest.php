<?php

namespace Tests\Feature;

use App\Models\Internship;
use App\Models\InternshipType;
use App\Models\Mahasiswa;
use App\Models\Semester;
use App\Models\User;
use App\Models\MataKuliah;
use App\Models\Krs;
use App\Models\Nilai;
use App\Models\InternshipCourseMapping;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternshipFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function createStudent(): Mahasiswa
    {
        $user = User::factory()->create(['role' => 'mahasiswa']);
        return Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '12345678',
            'nama' => $user->name,
            'prodi' => 'Ilmu Hukum',
            'angkatan' => '2022',
            'semester' => 5,
            'status' => 'aktif',
            'status_akun' => 'baru',
        ]);
    }

    protected function createAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    protected function createSemester(): Semester
    {
        return Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2025/2026',
            'status' => 'aktif',
            'is_active' => true,
            'tanggal_mulai' => '2026-05-21',
            'tanggal_selesai' => '2026-11-21',
        ]);
    }

    public function test_student_can_create_mandiri_internship_draft(): void
    {
        $student = $this->createStudent();
        $semester = $this->createSemester();

        // 2 is MANDIRI type seeded in migration
        $response = $this->actingAs($student->user)
            ->post(route('mahasiswa.magang.store'), [
                'internship_type_id' => 2,
                'instansi' => 'PT. Adhyaksa Corp',
                'alamat_instansi' => 'Jl. Salemba Raya No. 1',
                'posisi' => 'Legal Officer Intern',
                'periode_mulai' => '2026-06-01',
                'periode_selesai' => '2026-09-01',
                'deskripsi' => 'Belajar hukum praktis',
                'pembimbing_lapangan_nama' => 'Budi',
                'pembimbing_lapangan_telp' => '081234567890',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('internships', [
            'mahasiswa_id' => $student->id,
            'internship_type_id' => 2,
            'instansi' => 'PT. Adhyaksa Corp',
            'pembimbing_lapangan_phone' => '081234567890',
        ]);
    }

    public function test_admin_can_grade_mandiri_internship_directly(): void
    {
        $student = $this->createStudent();
        $semester = $this->createSemester();
        $admin = $this->createAdmin();

        $internship = Internship::create([
            'mahasiswa_id' => $student->id,
            'internship_type_id' => 2, // MANDIRI
            'semester_id' => $semester->id,
            'semester_mahasiswa' => 5,
            'instansi' => 'PT. Adhyaksa Corp',
            'alamat_instansi' => 'Jl. Salemba Raya No. 1',
            'periode_mulai' => '2026-06-01',
            'periode_selesai' => '2026-09-01',
            'status' => Internship::STATUS_COMPLETED,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.magang.grades', $internship), [
                'grades' => [
                    'final_score' => [
                        'nilai_akhir' => 85
                    ]
                ]
            ]);

        $response->assertRedirect();
        $internship->refresh();
        $this->assertEquals(85, $internship->final_score);
        $this->assertEquals('A', $internship->final_grade);
        $this->assertEquals(Internship::STATUS_CLOSED, $internship->status);
    }

    public function test_admin_can_start_mbkm_internship_and_inject_krs_with_tahun_ajaran(): void
    {
        $student = $this->createStudent();
        $semester = $this->createSemester();
        $admin = $this->createAdmin();

        $mk = MataKuliah::create([
            'kode_mk' => 'MK001',
            'nama_mk' => 'Hukum Perdata',
            'sks' => 4,
            'jenis' => 'wajib',
            'semester' => 5,
            'prodi_id' => 1,
            'fakultas_id' => 1,
        ]);

        $internship = Internship::create([
            'mahasiswa_id' => $student->id,
            'internship_type_id' => 1, // BERDAMPAK (MBKM)
            'semester_id' => $semester->id,
            'semester_mahasiswa' => 5,
            'instansi' => 'PT. Adhyaksa Corp',
            'alamat_instansi' => 'Jl. Salemba Raya No. 1',
            'periode_mulai' => '2026-06-01',
            'periode_selesai' => '2026-09-01',
            'status' => Internship::STATUS_ACCEPTANCE_LETTER_READY,
        ]);

        // Map course to internship
        InternshipCourseMapping::create([
            'internship_id' => $internship->id,
            'mata_kuliah_id' => $mk->id,
            'sks' => 4,
        ]);

        // Start internship
        $response = $this->actingAs($admin)
            ->post(route('admin.magang.start', $internship));

        $response->assertRedirect();
        $internship->refresh();
        $this->assertEquals(Internship::STATUS_ONGOING, $internship->status);

        // Check KRS is injected with correct active semester tahun_ajaran
        $this->assertDatabaseHas('krs', [
            'mahasiswa_id' => $student->id,
            'mata_kuliah_id' => $mk->id,
            'internship_id' => $internship->id,
            'is_internship_conversion' => true,
            'tahun_ajaran' => $semester->tahun_ajaran,
        ]);
    }

    public function test_student_cannot_create_internship_exceeding_6_months(): void
    {
        $student = $this->createStudent();
        $semester = $this->createSemester();

        $response = $this->actingAs($student->user)
            ->post(route('mahasiswa.magang.store'), [
                'internship_type_id' => 2,
                'instansi' => 'PT. Adhyaksa Corp',
                'alamat_instansi' => 'Jl. Salemba Raya No. 1',
                'posisi' => 'Legal Officer Intern',
                'periode_mulai' => '2026-06-01',
                'periode_selesai' => '2026-12-02', // 6 months + 1 day
                'deskripsi' => 'Belajar hukum praktis',
                'pembimbing_lapangan_nama' => 'Budi',
                'pembimbing_lapangan_telp' => '081234567890',
            ]);

        $response->assertSessionHasErrors(['periode_selesai']);
    }

    public function test_student_cannot_update_internship_exceeding_6_months(): void
    {
        $student = $this->createStudent();
        $semester = $this->createSemester();

        $internship = Internship::create([
            'mahasiswa_id' => $student->id,
            'internship_type_id' => 2,
            'semester_id' => $semester->id,
            'semester_mahasiswa' => 5,
            'instansi' => 'PT. Adhyaksa Corp',
            'alamat_instansi' => 'Jl. Salemba Raya No. 1',
            'periode_mulai' => '2026-06-01',
            'periode_selesai' => '2026-09-01',
            'status' => Internship::STATUS_DRAFT,
        ]);

        $response = $this->actingAs($student->user)
            ->put(route('mahasiswa.magang.update', $internship), [
                'internship_type_id' => 2,
                'instansi' => 'PT. Adhyaksa Corp',
                'alamat_instansi' => 'Jl. Salemba Raya No. 1',
                'posisi' => 'Legal Officer Intern',
                'periode_mulai' => '2026-06-01',
                'periode_selesai' => '2026-12-02', // 6 months + 1 day
            ]);

        $response->assertSessionHasErrors(['periode_selesai']);
    }

    public function test_admin_cannot_update_dates_exceeding_6_months(): void
    {
        $student = $this->createStudent();
        $semester = $this->createSemester();
        $admin = $this->createAdmin();

        $internship = Internship::create([
            'mahasiswa_id' => $student->id,
            'internship_type_id' => 2,
            'semester_id' => $semester->id,
            'semester_mahasiswa' => 5,
            'instansi' => 'PT. Adhyaksa Corp',
            'alamat_instansi' => 'Jl. Salemba Raya No. 1',
            'periode_mulai' => '2026-06-01',
            'periode_selesai' => '2026-09-01',
            'status' => Internship::STATUS_APPROVED,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.magang.update-dates', $internship), [
                'periode_mulai' => '2026-06-01',
                'periode_selesai' => '2026-12-02', // 6 months + 1 day
                'date_change_reason' => 'Perpanjang',
            ]);

        $response->assertSessionHasErrors(['periode_selesai']);
    }
}
