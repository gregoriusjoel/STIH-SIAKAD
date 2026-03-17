<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thesis_sidang_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_submission_id')->constrained('thesis_submissions')->cascadeOnDelete();
            $table->foreignId('sidang_registration_id')->nullable()->constrained('thesis_sidang_registrations')->nullOnDelete();
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai')->nullable();
            $table->foreignId('ruangan_id')->nullable()->constrained('ruangans')->nullOnDelete();
            $table->string('ruangan_manual')->nullable(); // fallback if no ruangan_id
            $table->foreignId('pembimbing_id')->constrained('dosens');
            $table->foreignId('penguji_1_id')->constrained('dosens');
            $table->foreignId('penguji_2_id')->nullable()->constrained('dosens')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thesis_sidang_schedules');
    }
};
