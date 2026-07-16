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
        Schema::create('dokumen_kelas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kelas_id');
            $table->enum('tipe_dokumen', ['silabus', 'rps']);
            $table->string('nama_file');
            $table->string('path_file');
            $table->unsignedBigInteger('uploaded_by')->index('dokumen_kelas_uploaded_by_foreign');
            $table->timestamps();

            $table->unique(['kelas_id', 'tipe_dokumen']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_kelas');
    }
};
