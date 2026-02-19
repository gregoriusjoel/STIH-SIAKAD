<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosen_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('dosens')->cascadeOnDelete();
            $table->foreignId('kelas_mata_kuliah_id')->constrained('kelas_mata_kuliahs')->cascadeOnDelete();
            $table->foreignId('pertemuan_id')->constrained('pertemuans')->cascadeOnDelete();
            $table->enum('metode_pengajaran', ['offline', 'online', 'asynchronous'])->default('offline');
            $table->time('jam_kelas_mulai')->nullable()->comment('Scheduled class start time');
            $table->time('jam_kelas_selesai')->nullable()->comment('Scheduled class end time');
            $table->dateTime('jam_absen_dosen')->comment('When dosen tapped activate QR');
            $table->string('lokasi_dosen', 500)->nullable()->comment('GPS coords or address');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->unique(['dosen_id', 'pertemuan_id'], 'dosen_attendance_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen_attendances');
    }
};
