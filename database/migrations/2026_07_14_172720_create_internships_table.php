<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('internships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('internship_type_id')->default(1)->index('internships_internship_type_id_foreign');
            $table->unsignedBigInteger('semester_id')->index('internships_semester_id_foreign');
            $table->integer('semester_mahasiswa')->nullable()->comment('Semester mahasiswa saat mendaftar magang');
            $table->string('instansi');
            $table->text('alamat_instansi');
            $table->string('posisi')->nullable();
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->text('deskripsi')->nullable();
            $table->string('pembimbing_lapangan_nama')->nullable();
            $table->string('pembimbing_lapangan_email')->nullable();
            $table->string('pembimbing_lapangan_phone')->nullable();
            $table->string('dokumen_pendukung_path')->nullable();
            $table->string('status')->default('draft')->index();
            $table->unsignedBigInteger('supervisor_dosen_id')->nullable()->index();
            $table->timestamp('supervisor_assigned_at')->nullable();
            $table->unsignedTinyInteger('converted_sks')->default(16);
            $table->decimal('final_score', 5)->nullable();
            $table->string('final_grade', 3)->nullable();
            $table->string('request_letter_generated_path')->nullable();
            $table->string('request_letter_signed_path')->nullable();
            $table->string('acceptance_letter_path')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable()->index('internships_approved_by_foreign');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->unsignedSmallInteger('revision_no')->default(0);
            $table->text('admin_note')->nullable();
            $table->string('nomor_surat')->nullable();
            $table->string('admin_final_pdf_path')->nullable();
            $table->string('admin_signed_pdf_path')->nullable();
            $table->timestamp('sent_to_student_at')->nullable();
            $table->unsignedBigInteger('sent_by')->nullable()->index('internships_sent_by_foreign');
            $table->unsignedBigInteger('date_changed_by')->nullable()->index('internships_date_changed_by_foreign');
            $table->timestamp('date_changed_at')->nullable();
            $table->text('date_change_reason')->nullable();
            $table->timestamps();

            $table->index(['mahasiswa_id', 'semester_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internships');
    }
};
