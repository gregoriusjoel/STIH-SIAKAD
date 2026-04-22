<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * NON-DESTRUCTIVE: Creates new master table for Kelas Perkuliahan.
     * Format nama_kelas: [tingkat][kode_prodi][kode_kelas] e.g. 1HK01
     */
    public function up(): void
    {
        Schema::create('kelas_perkuliahans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas', 20);            // Auto-generated: "1HK01", "2PRWT02"
            $table->unsignedTinyInteger('tingkat');        // 1, 2, 3, 4 (tahun kuliah)
            $table->string('kode_prodi', 10);             // "HK", "PRWT" — panjang menyesuaikan
            $table->string('kode_kelas', 5);              // "01", "02", "03"
            $table->foreignId('prodi_id')->nullable()->constrained('prodis')->nullOnDelete();
            $table->foreignId('tahun_akademik_id')->nullable()->constrained('semesters')->nullOnDelete();
            $table->unsignedInteger('kapasitas')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Unique constraint: prevent duplicate class combinations
            $table->unique(
                ['tingkat', 'kode_prodi', 'kode_kelas', 'tahun_akademik_id'],
                'kp_unique_combo'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_perkuliahans');
    }
};
