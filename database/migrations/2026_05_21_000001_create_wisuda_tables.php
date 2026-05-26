<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. wisuda_batches — created first because wisuda_registrations references it
        Schema::create('wisuda_batches', function (Blueprint $table) {
            $table->id();
            $table->string('nama_batch');
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->string('lokasi');
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });

        // 2. wisuda_registrations — one row per student registration
        Schema::create('wisuda_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('skripsi_submission_id');
            $table->unsignedBigInteger('wisuda_batch_id')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email_aktif')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'scheduled'])->default('pending');
            $table->text('rejection_note')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswas')->cascadeOnDelete();
            $table->foreign('skripsi_submission_id')->references('id')->on('skripsi_submissions')->cascadeOnDelete();
            $table->foreign('wisuda_batch_id')->references('id')->on('wisuda_batches')->nullOnDelete();
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();

            // Index for eligibility checks — one active registration per student
            $table->index(['mahasiswa_id', 'status']);
        });

        // 3. wisuda_documents — uploaded files per registration
        Schema::create('wisuda_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wisuda_registration_id');
            $table->enum('file_type', [
                'surat_penyerahan_skripsi',
                'penyerahan_buku',
                'keterangan_turnitin',
                'pas_foto',
            ]);
            $table->string('file_path');
            $table->string('original_name');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->timestamps();

            $table->foreign('wisuda_registration_id')
                  ->references('id')->on('wisuda_registrations')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wisuda_documents');
        Schema::dropIfExists('wisuda_registrations');
        Schema::dropIfExists('wisuda_batches');
    }
};
