<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internships', function (Blueprint $table) {
            $table->id();

            // ── Relasi utama ──
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semesters')->cascadeOnDelete();

            // ── Data instansi ──
            $table->string('instansi');
            $table->text('alamat_instansi');
            $table->string('posisi')->nullable();          // posisi / divisi
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->text('deskripsi')->nullable();          // tujuan / deskripsi singkat

            // ── Pembimbing lapangan (opsional) ──
            $table->string('pembimbing_lapangan_nama')->nullable();
            $table->string('pembimbing_lapangan_email')->nullable();
            $table->string('pembimbing_lapangan_phone')->nullable();

            // ── Dokumen pendukung ──
            $table->string('dokumen_pendukung_path')->nullable();

            // ── State machine ──
            $table->string('status')->default('draft')->index();
            // draft → submitted → waiting_request_letter → request_letter_uploaded
            // → under_review → approved / rejected
            // → supervisor_assigned → acceptance_letter_ready → ongoing
            // → completed → graded → closed

            // ── Dosen pembimbing ──
            $table->foreignId('supervisor_dosen_id')->nullable()->constrained('dosens')->nullOnDelete();
            $table->timestamp('supervisor_assigned_at')->nullable();

            // ── Konversi SKS ──
            $table->unsignedTinyInteger('converted_sks')->default(16);

            // ── Dokumen surat ──
            $table->string('request_letter_generated_path')->nullable();
            $table->string('request_letter_signed_path')->nullable();
            $table->string('acceptance_letter_path')->nullable();

            // ── Approval / rejection ──
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->timestamp('rejected_at')->nullable();

            // ── Revision tracking ──
            $table->unsignedSmallInteger('revision_no')->default(0);

            // ── Catatan ──
            $table->text('admin_note')->nullable();

            $table->timestamps();

            // ── Indexes ──
            $table->index(['mahasiswa_id', 'semester_id']);
            $table->index('supervisor_dosen_id');
        });

        // ── Mapping MK konversi magang ──
        Schema::create('internship_course_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->cascadeOnDelete();
            $table->unsignedTinyInteger('sks');
            $table->timestamps();

            $table->unique(['internship_id', 'mata_kuliah_id']);
        });

        // ── Riwayat revisi magang ──
        Schema::create('internship_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('revision_no');
            $table->string('request_letter_signed_path')->nullable();
            $table->text('note_from_admin')->nullable();
            $table->text('note_from_mahasiswa')->nullable();
            $table->timestamps();

            $table->index('internship_id');
        });

        // ── Log bimbingan magang (opsional, dosen) ──
        Schema::create('internship_logbooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->text('kegiatan');
            $table->text('catatan_dosen')->nullable();
            $table->string('created_by_role')->default('mahasiswa'); // mahasiswa / dosen
            $table->timestamps();

            $table->index('internship_id');
        });

        // ── Flag konversi di KRS ──
        // Menambahkan kolom di tabel krs agar MK konversi magang bisa diidentifikasi
        Schema::table('krs', function (Blueprint $table) {
            $table->foreignId('internship_id')->nullable()->after('ambil_mk')
                  ->constrained()->nullOnDelete();
            $table->boolean('is_internship_conversion')->default(false)->after('internship_id');
        });
    }

    public function down(): void
    {
        Schema::table('krs', function (Blueprint $table) {
            $table->dropForeign(['internship_id']);
            $table->dropColumn(['internship_id', 'is_internship_conversion']);
        });

        Schema::dropIfExists('internship_logbooks');
        Schema::dropIfExists('internship_revisions');
        Schema::dropIfExists('internship_course_mappings');
        Schema::dropIfExists('internships');
    }
};
