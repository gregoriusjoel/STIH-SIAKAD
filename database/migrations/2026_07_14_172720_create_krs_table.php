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
        Schema::create('krs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mahasiswa_id')->index('krs_mahasiswa_id_foreign');
            $table->unsignedBigInteger('mata_kuliah_id')->nullable()->index('krs_mata_kuliah_id_foreign');
            $table->unsignedBigInteger('kelas_id')->nullable()->index('krs_kelas_id_foreign');
            $table->string('tahun_ajaran', 9)->nullable();
            $table->enum('status', ['draft', 'sudah submit', 'approved', 'rejected'])->default('draft');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->enum('ambil_mk', ['ya', 'tidak'])->default('ya');
            $table->unsignedBigInteger('internship_id')->nullable()->index('krs_internship_id_foreign');
            $table->boolean('is_internship_conversion')->default(false);
            $table->unsignedBigInteger('kelas_mata_kuliah_id')->nullable()->index('krs_kelas_mata_kuliah_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('krs');
    }
};
