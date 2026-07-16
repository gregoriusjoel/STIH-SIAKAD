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
        Schema::table('presensis', function (Blueprint $table) {
            $table->foreign(['kelas_mata_kuliah_id'])->references(['id'])->on('kelas_mata_kuliahs')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['krs_id'])->references(['id'])->on('krs')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['mahasiswa_id'])->references(['id'])->on('mahasiswas')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->dropForeign('presensis_kelas_mata_kuliah_id_foreign');
            $table->dropForeign('presensis_krs_id_foreign');
            $table->dropForeign('presensis_mahasiswa_id_foreign');
        });
    }
};
