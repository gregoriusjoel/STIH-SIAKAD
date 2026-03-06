<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Tambahan kolom di tabel internships ──
        Schema::table('internships', function (Blueprint $table) {
            // Nomor surat resmi (contoh: 099/SK/STIH/III/2026)
            $table->string('nomor_surat')->nullable()->after('admin_note');

            // Admin generate PDF resmi (setelah approve)
            $table->string('admin_final_pdf_path')->nullable()->after('nomor_surat');
            // Admin upload PDF sudah TTD + cap
            $table->string('admin_signed_pdf_path')->nullable()->after('admin_final_pdf_path');

            // Admin kirim ke mahasiswa
            $table->timestamp('sent_to_student_at')->nullable()->after('admin_signed_pdf_path');
            $table->foreignId('sent_by')->nullable()->after('sent_to_student_at')
                  ->constrained('users')->nullOnDelete();

            // Audit trail untuk perubahan tanggal periode
            $table->foreignId('date_changed_by')->nullable()->after('sent_by')
                  ->constrained('users')->nullOnDelete();
            $table->timestamp('date_changed_at')->nullable()->after('date_changed_by');
            $table->text('date_change_reason')->nullable()->after('date_changed_at');
        });

        // ── 2. Tambahan semester_id di tabel krs (untuk groupBy KHS tanpa kelasMataKuliah) ──
        if (!Schema::hasColumn('krs', 'semester_id')) {
            Schema::table('krs', function (Blueprint $table) {
                $table->foreignId('semester_id')->nullable()->after('kelas_id')
                      ->constrained('semesters')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('internships', function (Blueprint $table) {
            $table->dropForeign(['sent_by']);
            $table->dropForeign(['date_changed_by']);
            $table->dropColumn([
                'nomor_surat',
                'admin_final_pdf_path',
                'admin_signed_pdf_path',
                'sent_to_student_at',
                'sent_by',
                'date_changed_by',
                'date_changed_at',
                'date_change_reason',
            ]);
        });

        if (Schema::hasColumn('krs', 'semester_id')) {
            Schema::table('krs', function (Blueprint $table) {
                $table->dropForeign(['semester_id']);
                $table->dropColumn('semester_id');
            });
        }
    }
};
