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
        Schema::create('kuesioner_aktivasi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mahasiswa_id')->index('kuesioner_aktivasi_mahasiswa_id_foreign');
            $table->unsignedBigInteger('semester_id')->nullable()->index('kuesioner_aktivasi_semester_id_foreign');
            $table->integer('semester_mahasiswa')->nullable()->comment('Semester level of the student when filling the questionnaire');
            $table->integer('fasilitas_kampus')->comment('1-5');
            $table->integer('sistem_akademik')->comment('1-5');
            $table->integer('kualitas_dosen')->comment('1-5');
            $table->integer('layanan_administrasi')->comment('1-5');
            $table->integer('kepuasan_keseluruhan')->comment('1-5');
            $table->text('saran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuesioner_aktivasi');
    }
};
