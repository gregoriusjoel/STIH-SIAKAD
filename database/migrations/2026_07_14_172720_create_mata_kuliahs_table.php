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
        Schema::create('mata_kuliahs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_mk', 20)->unique();
            $table->string('kode_id', 50)->nullable()->index()->comment('master kode like sms1, sms2');
            $table->string('nama_mk');
            $table->tinyInteger('praktikum')->nullable()->comment('jumlah sks praktikum');
            $table->enum('tipe', ['teori', 'praktikum', 'sidang', 'lab'])->default('teori')->index()->comment('Jenis mata kuliah: teori, praktikum, sidang, atau lab');
            $table->integer('sks');
            $table->integer('semester');
            $table->enum('jenis', ['wajib_nasional', 'wajib_prodi', 'pilihan', 'peminatan']);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('prodi_id')->nullable()->index('mata_kuliahs_prodi_id_foreign');
            $table->unsignedBigInteger('fakultas_id')->index('mata_kuliahs_fakultas_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliahs');
    }
};
