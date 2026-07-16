<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Tabel nilai ───────────────────────────────────────────────────
        Schema::table('nilai', function (Blueprint $table) {
            if ($this->indexExists('nilai', 'idx_nilai_krs_id')) {
                $table->dropIndex('idx_nilai_krs_id');
            }
            if ($this->indexExists('nilai', 'nilai_krs_id_foreign')) {
                $table->dropIndex('nilai_krs_id_foreign');
            }
        });

        // ── 2. Tabel audit_logs ──────────────────────────────────────────────
        Schema::table('audit_logs', function (Blueprint $table) {
            if ($this->indexExists('audit_logs', 'audit_logs_actor_id_index')) {
                $table->dropIndex('audit_logs_actor_id_index');
            }
        });

        // ── 3. Tabel kelas_perkuliahans ──────────────────────────────────────
        Schema::table('kelas_perkuliahans', function (Blueprint $table) {
            if ($this->indexExists('kelas_perkuliahans', 'idx_kelas_angkatan')) {
                $table->dropIndex('idx_kelas_angkatan');
            }
            if ($this->indexExists('kelas_perkuliahans', 'idx_kelas_angkatan_prodi_kode')) {
                $table->dropIndex('idx_kelas_angkatan_prodi_kode');
            }
        });

        // ── 4. Tabel pertemuans ──────────────────────────────────────────────
        Schema::table('pertemuans', function (Blueprint $table) {
            if ($this->indexExists('pertemuans', 'pertemuans_kelas_mata_kuliah_id_index')) {
                $table->dropIndex('pertemuans_kelas_mata_kuliah_id_index');
            }
        });

        // ── 5. Tabel presensis ───────────────────────────────────────────────
        Schema::table('presensis', function (Blueprint $table) {
            if ($this->indexExists('presensis', 'presensis_mahasiswa_id_foreign')) {
                $table->dropIndex('presensis_mahasiswa_id_foreign');
            }
            if ($this->indexExists('presensis', 'presensis_kelas_mata_kuliah_id_foreign')) {
                $table->dropIndex('presensis_kelas_mata_kuliah_id_foreign');
            }
        });
    }

    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->index('mahasiswa_id', 'presensis_mahasiswa_id_foreign');
            $table->index('kelas_mata_kuliah_id', 'presensis_kelas_mata_kuliah_id_foreign');
        });

        Schema::table('pertemuans', function (Blueprint $table) {
            $table->index('kelas_mata_kuliah_id', 'pertemuans_kelas_mata_kuliah_id_index');
        });

        Schema::table('kelas_perkuliahans', function (Blueprint $table) {
            $table->index(['angkatan', 'prodi_id', 'kode_kelas'], 'idx_kelas_angkatan_prodi_kode');
            $table->index('angkatan', 'idx_kelas_angkatan');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index('actor_id', 'audit_logs_actor_id_index');
        });

        Schema::table('nilai', function (Blueprint $table) {
            $table->index('krs_id', 'nilai_krs_id_foreign');
            $table->index('krs_id', 'idx_nilai_krs_id');
        });
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }
};
