<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Semester;
use App\Models\KuesionerAktivasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckSemesterKuesionerTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_student_semester_1_skips_semester_kuesioner()
    {
        // Setup: active semester
        $semester = Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2025/2026',
            'status' => 'aktif',
            'is_active' => true,
            'tanggal_mulai' => now()->subDays(10),
            'tanggal_selesai' => now()->addDays(100),
        ]);

        $user = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswa = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '12345678',
            'status_akun' => 'aktif',
            'semester' => 1,
            'status' => 'aktif',
            'prodi' => 'Ilmu Hukum',
            'prodi_id' => 1,
            'angkatan' => '2025',
        ]);

        // Act & Assert: Should pass middleware (e.g. accessing dashboard redirect isn't blocked by kuesioner)
        $response = $this->actingAs($user)->get(route('mahasiswa.dashboard'));
        
        $this->assertNotEquals(route('mahasiswa.semester-aktivasi.index'), $response->headers->get('Location'));
    }

    public function test_student_semester_2_without_kuesioner_is_redirected()
    {
        $semester = Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2025/2026',
            'status' => 'aktif',
            'is_active' => true,
            'tanggal_mulai' => now()->subDays(10),
            'tanggal_selesai' => now()->addDays(100),
        ]);

        $user = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswa = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '12345678',
            'status_akun' => 'aktif',
            'semester' => 2,
            'status' => 'aktif',
            'prodi' => 'Ilmu Hukum',
            'prodi_id' => 1,
            'angkatan' => '2025',
        ]);

        $response = $this->actingAs($user)->get(route('mahasiswa.dashboard'));
        
        $response->assertRedirect(route('mahasiswa.semester-aktivasi.index'));
    }

    public function test_student_semester_2_with_kuesioner_can_access()
    {
        $semester = Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2025/2026',
            'status' => 'aktif',
            'is_active' => true,
            'tanggal_mulai' => now()->subDays(10),
            'tanggal_selesai' => now()->addDays(100),
        ]);

        $user = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswa = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '12345678',
            'status_akun' => 'aktif',
            'semester' => 2,
            'status' => 'aktif',
            'prodi' => 'Ilmu Hukum',
            'prodi_id' => 1,
            'angkatan' => '2025',
        ]);

        // Fill kuesioner for semester 2
        KuesionerAktivasi::create([
            'mahasiswa_id' => $mahasiswa->id,
            'semester_id' => $semester->id,
            'semester_mahasiswa' => 2,
            'fasilitas_kampus' => 5,
            'sistem_akademik' => 5,
            'kualitas_dosen' => 5,
            'layanan_administrasi' => 5,
            'kepuasan_keseluruhan' => 5,
        ]);

        $response = $this->actingAs($user)->get(route('mahasiswa.dashboard'));
        
        $this->assertNotEquals(route('mahasiswa.semester-aktivasi.index'), $response->headers->get('Location'));
    }

    public function test_student_must_refill_kuesioner_when_rising_semester_within_same_academic_semester()
    {
        $semester = Semester::create([
            'nama_semester' => 'Ganjil',
            'tahun_ajaran' => '2025/2026',
            'status' => 'aktif',
            'is_active' => true,
            'tanggal_mulai' => now()->subDays(10),
            'tanggal_selesai' => now()->addDays(100),
        ]);

        $user = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswa = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '12345678',
            'status_akun' => 'aktif',
            'semester' => 2,
            'status' => 'aktif',
            'prodi' => 'Ilmu Hukum',
            'prodi_id' => 1,
            'angkatan' => '2025',
        ]);

        // Fill kuesioner for semester 2
        KuesionerAktivasi::create([
            'mahasiswa_id' => $mahasiswa->id,
            'semester_id' => $semester->id,
            'semester_mahasiswa' => 2,
            'fasilitas_kampus' => 5,
            'sistem_akademik' => 5,
            'kualitas_dosen' => 5,
            'layanan_administrasi' => 5,
            'kepuasan_keseluruhan' => 5,
        ]);

        // Student rises to Semester 3
        $mahasiswa->update(['semester' => 3]);

        // Act: access dashboard again
        $response = $this->actingAs($user)->get(route('mahasiswa.dashboard'));

        // Assert: Must be redirected to kuesioner again because they haven't filled it for semester 3!
        $response->assertRedirect(route('mahasiswa.semester-aktivasi.index'));
    }
}
