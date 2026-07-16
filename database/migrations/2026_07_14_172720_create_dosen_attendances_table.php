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
        Schema::create('dosen_attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dosen_id');
            $table->unsignedBigInteger('kelas_mata_kuliah_id')->index('dosen_attendances_kelas_mata_kuliah_id_foreign');
            $table->unsignedBigInteger('pertemuan_id')->nullable()->index('dosen_attendances_pertemuan_id_foreign');
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_attendances');
    }
};
