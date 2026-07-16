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
        Schema::create('kelas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mata_kuliah_id')->index('kelas_mata_kuliah_id_foreign');
            $table->unsignedBigInteger('dosen_id')->index('kelas_dosen_id_foreign');
            $table->integer('kapasitas')->default(40);
            $table->string('tahun_ajaran', 20);
            $table->enum('semester_type', ['Ganjil', 'Genap'])->default('Ganjil');
            $table->unsignedBigInteger('kelas_perkuliahan_id')->nullable()->index('kelas_kelas_perkuliahan_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
