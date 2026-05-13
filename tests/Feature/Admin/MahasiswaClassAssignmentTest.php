<?php

namespace Tests\Feature\Admin;

use App\Models\KelasPerkuliahan;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\Semester;
use App\Models\User;
use App\Services\KelasPerkuliahanService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MahasiswaClassAssignmentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropAllTables();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('user');
            $table->rememberToken()->nullable();
            $table->timestamps();
        });

        Schema::create('fakultas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_fakultas')->unique();
            $table->string('nama_fakultas');
            $table->string('status')->default('aktif');
            $table->timestamps();
        });

        Schema::create('prodis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_prodi')->unique();
            $table->string('nama_prodi');
            $table->foreignId('fakultas_id')->nullable()->constrained('fakultas')->nullOnDelete();
            $table->string('jenjang')->default('S1');
            $table->string('status')->default('aktif');
            $table->timestamps();
        });

        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->string('nama_semester');
            $table->string('tahun_ajaran');
            $table->string('status')->default('nonaktif');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->timestamp('locked_at')->nullable();
            $table->unsignedBigInteger('locked_by')->nullable();
            $table->boolean('krs_dapat_diisi')->default(false);
            $table->date('krs_mulai')->nullable();
            $table->date('krs_selesai')->nullable();
            $table->timestamps();
        });

        Schema::create('kelas_perkuliahans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas', 20)->nullable();
            $table->unsignedTinyInteger('tingkat');
            $table->string('kode_prodi', 10);
            $table->string('kode_kelas', 5);
            $table->foreignId('prodi_id')->nullable()->constrained('prodis')->nullOnDelete();
            $table->foreignId('tahun_akademik_id')->nullable()->constrained('semesters')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nim')->unique();
            $table->string('prodi')->nullable();
            $table->foreignId('prodi_id')->nullable()->constrained('prodis')->nullOnDelete();
            $table->string('angkatan')->nullable();
            $table->unsignedTinyInteger('semester')->nullable();
            $table->foreignId('tahun_akademik_id')->nullable()->constrained('semesters')->nullOnDelete();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('email_pribadi')->nullable()->unique();
            $table->string('email_kampus')->nullable()->unique();
            $table->string('email_aktif')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->enum('status', ['aktif', 'cuti', 'lulus', 'do'])->default('aktif');
            $table->enum('status_akun', ['baru', 'aktif', 'tidak_aktif'])->default('baru');
            $table->boolean('is_dokumen_unlocked')->default(false);
            $table->foreignId('kelas_perkuliahan_id')->nullable()->constrained('kelas_perkuliahans')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('actor_role', 50)->nullable();
            $table->string('action', 100);
            $table->string('auditable_type');
            $table->unsignedBigInteger('auditable_id');
            $table->json('meta')->nullable();
            $table->json('before')->nullable();
            $table->json('after')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at');
        });
    }

    public function test_tingkat_mapping_covers_semester_one_to_eight(): void
    {
        $service = app(KelasPerkuliahanService::class);

        $this->assertSame(1, $service->calculateTingkatFromSemester(1));
        $this->assertSame(1, $service->calculateTingkatFromSemester(2));
        $this->assertSame(2, $service->calculateTingkatFromSemester(3));
        $this->assertSame(2, $service->calculateTingkatFromSemester(4));
        $this->assertSame(3, $service->calculateTingkatFromSemester(5));
        $this->assertSame(3, $service->calculateTingkatFromSemester(6));
        $this->assertSame(4, $service->calculateTingkatFromSemester(7));
        $this->assertSame(4, $service->calculateTingkatFromSemester(8));
    }

    public function test_kelas_options_endpoint_filters_by_prodi_tingkat_and_tahun_akademik(): void
    {
        $admin = $this->createAdmin();
        $prodiHukum = $this->createProdi('HK', 'Ilmu Hukum');
        $prodiLain = $this->createProdi('MI', 'Manajemen');
        $semesterAktif = $this->createSemester('Ganjil', '2025/2026', 'aktif', true);
        $semesterLain = $this->createSemester('Genap', '2024/2025', 'nonaktif', false);

        $expected = KelasPerkuliahan::create([
            'tingkat' => 1,
            'kode_prodi' => $prodiHukum->kode_prodi,
            'kode_kelas' => '01',
            'prodi_id' => $prodiHukum->id,
            'tahun_akademik_id' => $semesterAktif->id,
        ]);

        KelasPerkuliahan::create([
            'tingkat' => 2,
            'kode_prodi' => $prodiHukum->kode_prodi,
            'kode_kelas' => '02',
            'prodi_id' => $prodiHukum->id,
            'tahun_akademik_id' => $semesterAktif->id,
        ]);

        KelasPerkuliahan::create([
            'tingkat' => 1,
            'kode_prodi' => $prodiLain->kode_prodi,
            'kode_kelas' => '01',
            'prodi_id' => $prodiLain->id,
            'tahun_akademik_id' => $semesterAktif->id,
        ]);

        KelasPerkuliahan::create([
            'tingkat' => 1,
            'kode_prodi' => $prodiHukum->kode_prodi,
            'kode_kelas' => '03',
            'prodi_id' => $prodiHukum->id,
            'tahun_akademik_id' => $semesterLain->id,
        ]);

        $response = $this->actingAs($admin)->getJson(route('kelas-perkuliahan.options', [
            'prodi_id' => $prodiHukum->id,
            'semester' => 1,
            'tahun_akademik_id' => $semesterAktif->id,
        ]));

        $response->assertOk()
            ->assertJsonPath('meta.count', 1)
            ->assertJsonPath('data.0.id', $expected->id)
            ->assertJsonPath('data.0.prodi_id', $prodiHukum->id)
            ->assertJsonPath('data.0.tingkat', 1)
            ->assertJsonPath('data.0.tahun_akademik_id', $semesterAktif->id);
    }

    public function test_store_rejects_class_from_different_prodi(): void
    {
        $admin = $this->createAdmin();
        $prodiHukum = $this->createProdi('HK', 'Ilmu Hukum');
        $prodiLain = $this->createProdi('MI', 'Manajemen');
        $semesterAktif = $this->createSemester('Ganjil', '2025/2026', 'aktif', true);

        $kelasProdiLain = KelasPerkuliahan::create([
            'tingkat' => 1,
            'kode_prodi' => $prodiLain->kode_prodi,
            'kode_kelas' => '01',
            'prodi_id' => $prodiLain->id,
            'tahun_akademik_id' => $semesterAktif->id,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.mahasiswa.store'), $this->validPayload([
            'prodi_id' => $prodiHukum->id,
            'kelas_perkuliahan_id' => $kelasProdiLain->id,
            'tahun_akademik_id' => $semesterAktif->id,
        ]));

        $response->assertSessionHasErrors('kelas_perkuliahan_id');
        $this->assertDatabaseCount('mahasiswas', 0);
    }

    public function test_store_requires_class_for_active_student(): void
    {
        $admin = $this->createAdmin();
        $prodiHukum = $this->createProdi('HK', 'Ilmu Hukum');
        $semesterAktif = $this->createSemester('Ganjil', '2025/2026', 'aktif', true);

        $response = $this->actingAs($admin)->post(route('admin.mahasiswa.store'), $this->validPayload([
            'prodi_id' => $prodiHukum->id,
            'kelas_perkuliahan_id' => null,
            'tahun_akademik_id' => $semesterAktif->id,
        ]));

        $response->assertSessionHasErrors('kelas_perkuliahan_id');
        $this->assertDatabaseMissing('mahasiswas', ['nim' => '2025001001']);
    }

    protected function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    protected function createProdi(string $kode, string $nama): Prodi
    {
        $fakultasId = \DB::table('fakultas')->insertGetId([
            'nama_fakultas' => 'Fakultas Hukum ' . $kode,
            'kode_fakultas' => 'FK' . $kode,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Prodi::create([
            'kode_prodi' => $kode,
            'nama_prodi' => $nama,
            'fakultas_id' => $fakultasId,
            'jenjang' => 'S1',
            'status' => 'aktif',
        ]);
    }

    protected function createSemester(string $nama, string $tahunAjaran, string $status, bool $isActive): Semester
    {
        return Semester::create([
            'nama_semester' => $nama,
            'tahun_ajaran' => $tahunAjaran,
            'status' => $status,
            'tanggal_mulai' => now()->startOfMonth(),
            'tanggal_selesai' => now()->addMonths(6)->endOfMonth(),
            'is_active' => $isActive,
        ]);
    }

    protected function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Mahasiswa Uji',
            'email_pribadi' => 'mahasiswa@example.com',
            'email_kampus' => 'mahasiswauji@student.stih.ac.id',
            'password' => 'rahasia123',
            'nim' => '2025001001',
            'angkatan' => '2025',
            'semester' => 1,
            'jenis_kelamin' => 'Laki-Laki',
            'status' => 'aktif',
            'phone' => '081234567890',
            'address' => 'Jl. Pengujian',
        ], $overrides);
    }
}
