<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 1 DB Optimization — aman, tidak ada breaking change ke kode.
 *
 * Perubahan:
 * 1. Fix audit_logs.created_at — hapus ON UPDATE (bug: timestamp berubah saat row di-update)
 * 2. Tambah missing indexes (nim, user_id, status, krs composite, nilai, presensis, audit_logs)
 * 3. Drop mahasiswas.kabupaten — kolom NULL semua, 0 referensi di kode
 * 4. Drop tabel students — tabel kosong, Student model extend Mahasiswa (pakai tabel mahasiswas)
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Fix audit_logs.created_at (hapus ON UPDATE) ───────────────────
        // MariaDB tidak bisa ubah ON UPDATE via Blueprint, pakai raw SQL.
        DB::statement("
            ALTER TABLE `audit_logs`
            MODIFY `created_at` timestamp NOT NULL DEFAULT current_timestamp()
        ");

        // ── 2. Missing indexes ────────────────────────────────────────────────
        Schema::table('mahasiswas', function (Blueprint $table) {
            // NIM sering dicari exact + LIKE di banyak controller
            if (!$this->indexExists('mahasiswas', 'idx_nim')) {
                $table->index('nim', 'idx_nim');
            }
            // user_id FK tapi belum ada index
            if (!$this->indexExists('mahasiswas', 'idx_mahasiswas_user_id')) {
                $table->index('user_id', 'idx_mahasiswas_user_id');
            }
            // Filter by status (aktif/cuti/lulus/do)
            if (!$this->indexExists('mahasiswas', 'idx_mahasiswas_status')) {
                $table->index('status', 'idx_mahasiswas_status');
            }
        });

        Schema::table('krs', function (Blueprint $table) {
            // Composite — query KRS by mahasiswa + kelas_mata_kuliah
            // (krs tidak punya semester_id, pakai kelas_mata_kuliah_id)
            if (!$this->indexExists('krs', 'idx_krs_mahasiswa_kelas')) {
                $table->index(['mahasiswa_id', 'kelas_mata_kuliah_id'], 'idx_krs_mahasiswa_kelas');
            }
            // Index status — filter draft/approved/rejected
            if (!$this->indexExists('krs', 'idx_krs_status')) {
                $table->index('status', 'idx_krs_status');
            }
        });

        Schema::table('nilai', function (Blueprint $table) {
            if (!$this->indexExists('nilai', 'idx_nilai_krs_id')) {
                $table->index('krs_id', 'idx_nilai_krs_id');
            }
        });

        Schema::table('presensis', function (Blueprint $table) {
            // Absensi lookup by mahasiswa + kelas
            if (!$this->indexExists('presensis', 'idx_presensis_lookup')) {
                $table->index(['mahasiswa_id', 'kelas_mata_kuliah_id'], 'idx_presensis_lookup');
            }
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            // Filter by date — dipakai intensif di SuperAdminController
            if (!$this->indexExists('audit_logs', 'idx_audit_created_at')) {
                $table->index('created_at', 'idx_audit_created_at');
            }
        });

        // ── 3. Drop mahasiswas.kabupaten ──────────────────────────────────────
        // Kolom ini NULL di semua row dan tidak ada referensi di kode PHP.
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn('kabupaten');
        });

        // ── 4. Drop tabel students ────────────────────────────────────────────
        // Student model adalah alias (extend Mahasiswa), pakai tabel mahasiswas.
        // Tabel students di DB tidak pernah diwrite/diread secara aktif.
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign('students_user_id_foreign');
        });
        Schema::dropIfExists('students');
    }

    public function down(): void
    {
        // ── 4. Restore students tabel ─────────────────────────────────────────
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index('students_user_id_foreign');
            $table->timestamps();
            $table->foreign(['user_id'])->references(['id'])->on('users')
                ->onUpdate('no action')->onDelete('cascade');
        });

        // ── 3. Restore mahasiswas.kabupaten ───────────────────────────────────
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('kabupaten')->nullable()->after('provinsi');
        });

        // ── 2. Drop indexes ───────────────────────────────────────────────────
        Schema::table('presensis', function (Blueprint $table) {
            $table->dropIndex('idx_presensis_lookup');
        });
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropIndex('idx_nilai_krs_id');
        });
        Schema::table('krs', function (Blueprint $table) {
            $table->dropIndex('idx_krs_status');
            $table->dropIndex('idx_krs_mahasiswa_kelas');
        });
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropIndex('idx_mahasiswas_status');
            $table->dropIndex('idx_mahasiswas_user_id');
            $table->dropIndex('idx_nim');
        });
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex('idx_audit_created_at');
        });

        // ── 1. Restore audit_logs.created_at dengan ON UPDATE ─────────────────
        DB::statement("
            ALTER TABLE `audit_logs`
            MODIFY `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
        ");
    }

    /**
     * Cek apakah index sudah ada agar tidak error saat di-run ulang.
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }
};
