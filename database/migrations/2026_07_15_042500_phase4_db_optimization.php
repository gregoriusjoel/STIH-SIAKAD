<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Pembersihan Kolom ─────────────────────────────────────────────
        Schema::table('semesters', function (Blueprint $table) {
            if (Schema::hasColumn('semesters', 'nama_semester_old')) {
                $table->dropColumn('nama_semester_old');
            }
        });

        // ── 2. Modul Keuangan (Pembayaran & Tagihan) ──────────────────────────
        Schema::table('pembayaran', function (Blueprint $table) {
            if (!$this->indexExists('pembayaran', 'idx_pembayaran_mhs_semester')) {
                $table->index(['mahasiswa_id', 'semester_id'], 'idx_pembayaran_mhs_semester');
            }
        });

        Schema::table('invoices', function (Blueprint $table) {
            if (!$this->indexExists('invoices', 'idx_invoices_student_semester')) {
                $table->index(['student_id', 'semester'], 'idx_invoices_student_semester');
            }
        });

        // ── 3. Modul Akademik & Penjadwalan ──────────────────────────────────
        Schema::table('mata_kuliah_semesters', function (Blueprint $table) {
            if (!$this->indexExists('mata_kuliah_semesters', 'idx_mks_matakuliah_status')) {
                $table->index(['mata_kuliah_id', 'status'], 'idx_mks_matakuliah_status');
            }
            if (!$this->indexExists('mata_kuliah_semesters', 'idx_mks_semester_status')) {
                $table->index(['semester_id', 'status'], 'idx_mks_semester_status');
            }
        });

        Schema::table('kelas_mata_kuliahs', function (Blueprint $table) {
            if (!$this->indexExists('kelas_mata_kuliahs', 'idx_kmk_dosen_semester')) {
                $table->index(['dosen_id', 'semester_id'], 'idx_kmk_dosen_semester');
            }
        });

        // ── 4. Modul Penilaian, Tugas & Kuisioner ──────────────────────────────
        Schema::table('tugas_submissions', function (Blueprint $table) {
            if (!$this->indexExists('tugas_submissions', 'idx_tugas_submission_unique')) {
                $table->unique(['tugas_id', 'mahasiswa_id'], 'idx_tugas_submission_unique');
            }
        });

        Schema::table('kuesioner_aktivasi', function (Blueprint $table) {
            if (!$this->indexExists('kuesioner_aktivasi', 'idx_kuesioner_mhs_semester')) {
                $table->index(['mahasiswa_id', 'semester_id'], 'idx_kuesioner_mhs_semester');
            }
        });

        // ── 5. Modul Skripsi & Wisuda ────────────────────────────────────────
        Schema::table('skripsi_submissions', function (Blueprint $table) {
            if (!$this->indexExists('skripsi_submissions', 'idx_skripsi_semester_status')) {
                $table->index(['semester_id', 'status'], 'idx_skripsi_semester_status');
            }
        });

        Schema::table('wisuda_registrations', function (Blueprint $table) {
            if (!$this->indexExists('wisuda_registrations', 'idx_wisuda_batch_status')) {
                $table->index(['wisuda_batch_id', 'status'], 'idx_wisuda_batch_status');
            }
        });
    }

    public function down(): void
    {
        // ── 5. Modul Skripsi & Wisuda ────────────────────────────────────────
        Schema::table('wisuda_registrations', function (Blueprint $table) {
            $table->dropIndex('idx_wisuda_batch_status');
        });

        Schema::table('skripsi_submissions', function (Blueprint $table) {
            $table->dropIndex('idx_skripsi_semester_status');
        });

        // ── 4. Modul Penilaian, Tugas & Kuisioner ──────────────────────────────
        Schema::table('kuesioner_aktivasi', function (Blueprint $table) {
            $table->dropIndex('idx_kuesioner_mhs_semester');
        });

        Schema::table('tugas_submissions', function (Blueprint $table) {
            $table->dropIndex('idx_tugas_submission_unique');
        });

        // ── 3. Modul Akademik & Penjadwalan ──────────────────────────────────
        Schema::table('kelas_mata_kuliahs', function (Blueprint $table) {
            $table->dropIndex('idx_kmk_dosen_semester');
        });

        Schema::table('mata_kuliah_semesters', function (Blueprint $table) {
            $table->dropIndex('idx_mks_semester_status');
            $table->dropIndex('idx_mks_matakuliah_status');
        });

        // ── 2. Modul Keuangan (Pembayaran & Tagihan) ──────────────────────────
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex('idx_invoices_student_semester');
        });

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropIndex('idx_pembayaran_mhs_semester');
        });

        // ── 1. Pembersihan Kolom ─────────────────────────────────────────────
        Schema::table('semesters', function (Blueprint $table) {
            $table->string('nama_semester_old')->nullable()->after('nama_semester');
        });
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }
};
