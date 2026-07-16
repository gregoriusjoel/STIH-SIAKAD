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
        Schema::create('kelas_perkuliahans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_kelas', 20);
            $table->unsignedTinyInteger('tingkat');
            $table->string('angkatan', 4)->index('idx_kelas_angkatan');
            $table->string('kode_prodi', 10);
            $table->string('kode_kelas', 5)->index('idx_kelas_kode');
            $table->unsignedBigInteger('prodi_id')->nullable()->index('idx_kelas_prodi');
            $table->unsignedBigInteger('tahun_akademik_id')->nullable()->index('kelas_perkuliahans_tahun_akademik_id_foreign');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['angkatan', 'prodi_id', 'kode_kelas'], 'idx_kelas_angkatan_prodi_kode');
            $table->unique(['angkatan', 'prodi_id', 'kode_kelas', 'tahun_akademik_id'], 'kp_unique_angkatan_combo');
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
