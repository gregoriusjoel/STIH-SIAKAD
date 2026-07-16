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
        Schema::table('krs', function (Blueprint $table) {
            $table->foreign(['internship_id'])->references(['id'])->on('internships')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['kelas_id'])->references(['id'])->on('kelas')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['kelas_mata_kuliah_id'])->references(['id'])->on('kelas_mata_kuliahs')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['mahasiswa_id'])->references(['id'])->on('mahasiswas')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['mata_kuliah_id'])->references(['id'])->on('mata_kuliahs')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('krs', function (Blueprint $table) {
            $table->dropForeign('krs_internship_id_foreign');
            $table->dropForeign('krs_kelas_id_foreign');
            $table->dropForeign('krs_kelas_mata_kuliah_id_foreign');
            $table->dropForeign('krs_mahasiswa_id_foreign');
            $table->dropForeign('krs_mata_kuliah_id_foreign');
        });
    }
};
