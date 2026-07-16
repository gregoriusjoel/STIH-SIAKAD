<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Penambahan Indeks Unik Baru ───────────────────────────────────
        
        // Tabel nilai
        Schema::table('nilai', function (Blueprint $table) {
            if (!$this->indexExists('nilai', 'idx_nilai_krs_unique')) {
                $table->unique('krs_id', 'idx_nilai_krs_unique');
            }
        });

        // Tabel presensis
        Schema::table('presensis', function (Blueprint $table) {
            if (!$this->indexExists('presensis', 'idx_presensi_pertemuan_unique')) {
                $table->unique(['kelas_mata_kuliah_id', 'mahasiswa_id', 'pertemuan'], 'idx_presensi_pertemuan_unique');
            }
        });

        // ── 2. Pembersihan Indeks Redundan / Duplikat ─────────────────────────
        
        // Tabel mahasiswas
        Schema::table('mahasiswas', function (Blueprint $table) {
            if ($this->indexExists('mahasiswas', 'idx_nim')) {
                $table->dropIndex('idx_nim');
            }
            if ($this->indexExists('mahasiswas', 'idx_mahasiswas_user_id')) {
                $table->dropIndex('idx_mahasiswas_user_id');
            }
        });

        // Tabel tugas_submissions
        Schema::table('tugas_submissions', function (Blueprint $table) {
            if ($this->indexExists('tugas_submissions', 'tugas_submissions_tugas_id_mahasiswa_id_index')) {
                $table->dropIndex('tugas_submissions_tugas_id_mahasiswa_id_index');
            }
        });

        // Tabel krs
        Schema::table('krs', function (Blueprint $table) {
            if ($this->indexExists('krs', 'krs_mahasiswa_id_foreign')) {
                $table->dropIndex('krs_mahasiswa_id_foreign');
            }
        });

        // Tabel kuesioner_aktivasi
        Schema::table('kuesioner_aktivasi', function (Blueprint $table) {
            if ($this->indexExists('kuesioner_aktivasi', 'kuesioner_aktivasi_mahasiswa_id_foreign')) {
                $table->dropIndex('kuesioner_aktivasi_mahasiswa_id_foreign');
            }
        });

        // Tabel kelas_mata_kuliahs
        Schema::table('kelas_mata_kuliahs', function (Blueprint $table) {
            if ($this->indexExists('kelas_mata_kuliahs', 'kelas_mata_kuliahs_dosen_id_foreign')) {
                $table->dropIndex('kelas_mata_kuliahs_dosen_id_foreign');
            }
        });

        // Tabel pembayaran
        Schema::table('pembayaran', function (Blueprint $table) {
            if ($this->indexExists('pembayaran', 'pembayaran_mahasiswa_id_foreign')) {
                $table->dropIndex('pembayaran_mahasiswa_id_foreign');
            }
        });

        // Tabel mata_kuliah_semesters
        Schema::table('mata_kuliah_semesters', function (Blueprint $table) {
            if ($this->indexExists('mata_kuliah_semesters', 'mata_kuliah_semesters_mata_kuliah_id_foreign')) {
                $table->dropIndex('mata_kuliah_semesters_mata_kuliah_id_foreign');
            }
        });

        // Tabel skripsi_submissions
        Schema::table('skripsi_submissions', function (Blueprint $table) {
            if ($this->indexExists('skripsi_submissions', 'thesis_submissions_semester_id_foreign')) {
                $table->dropIndex('thesis_submissions_semester_id_foreign');
            }
        });

        // Tabel wisuda_registrations
        Schema::table('wisuda_registrations', function (Blueprint $table) {
            if ($this->indexExists('wisuda_registrations', 'wisuda_registrations_wisuda_batch_id_foreign')) {
                $table->dropIndex('wisuda_registrations_wisuda_batch_id_foreign');
            }
        });
    }

    public function down(): void
    {
        // ── Recreate Redundant Indexes ──
        
        Schema::table('wisuda_registrations', function (Blueprint $table) {
            $table->index('wisuda_batch_id', 'wisuda_registrations_wisuda_batch_id_foreign');
        });

        Schema::table('skripsi_submissions', function (Blueprint $table) {
            $table->index('semester_id', 'thesis_submissions_semester_id_foreign');
        });

        Schema::table('mata_kuliah_semesters', function (Blueprint $table) {
            $table->index('mata_kuliah_id', 'mata_kuliah_semesters_mata_kuliah_id_foreign');
        });

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->index('mahasiswa_id', 'pembayaran_mahasiswa_id_foreign');
        });

        Schema::table('kelas_mata_kuliahs', function (Blueprint $table) {
            $table->index('dosen_id', 'kelas_mata_kuliahs_dosen_id_foreign');
        });

        Schema::table('kuesioner_aktivasi', function (Blueprint $table) {
            $table->index('mahasiswa_id', 'kuesioner_aktivasi_mahasiswa_id_foreign');
        });

        Schema::table('krs', function (Blueprint $table) {
            $table->index('mahasiswa_id', 'krs_mahasiswa_id_foreign');
        });

        Schema::table('tugas_submissions', function (Blueprint $table) {
            $table->index(['tugas_id', 'mahasiswa_id'], 'tugas_submissions_tugas_id_mahasiswa_id_index');
        });

        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->index('user_id', 'idx_mahasiswas_user_id');
            $table->index('nim', 'idx_nim');
        });

        // ── Drop Unique Indexes ──
        
        Schema::table('presensis', function (Blueprint $table) {
            $table->dropIndex('idx_presensi_pertemuan_unique');
        });

        Schema::table('nilai', function (Blueprint $table) {
            $table->dropIndex('idx_nilai_krs_unique');
        });
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }
};
