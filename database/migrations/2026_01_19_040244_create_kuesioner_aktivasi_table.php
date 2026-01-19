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
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('set null');
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
