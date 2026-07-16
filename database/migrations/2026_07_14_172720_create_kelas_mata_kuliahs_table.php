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
        Schema::create('kelas_mata_kuliahs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mata_kuliah_id')->index('kelas_mata_kuliahs_mata_kuliah_id_foreign');
            $table->unsignedBigInteger('dosen_id')->index('kelas_mata_kuliahs_dosen_id_foreign');
            $table->unsignedBigInteger('semester_id')->index('kelas_mata_kuliahs_semester_id_foreign');
            $table->string('kode_kelas');
            $table->integer('kapasitas');
            $table->string('ruang');
            $table->unsignedBigInteger('ruangan_id')->nullable()->index();
            $table->string('hari')->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->enum('metode_pengajaran', ['offline', 'online', 'asynchronous'])->nullable();
            $table->string('online_meeting_link')->nullable();
            $table->string('online_link')->nullable();
            $table->text('asynchronous_tugas')->nullable();
            $table->string('asynchronous_file')->nullable();
            $table->string('qr_token')->nullable()->unique();
            $table->boolean('qr_enabled')->default(false);
            $table->integer('qr_current_pertemuan')->nullable();
            $table->unsignedBigInteger('kelas_perkuliahan_id')->nullable()->index('kelas_mata_kuliahs_kelas_perkuliahan_id_foreign');
            $table->timestamp('qr_expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_mata_kuliahs');
    }
};
