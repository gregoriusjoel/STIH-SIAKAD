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
        Schema::table('dosen_attendances', function (Blueprint $table) {
            $table->foreign(['dosen_id'])->references(['id'])->on('dosens')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['kelas_mata_kuliah_id'])->references(['id'])->on('kelas_mata_kuliahs')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['pertemuan_id'])->references(['id'])->on('pertemuans')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen_attendances', function (Blueprint $table) {
            $table->dropForeign('dosen_attendances_dosen_id_foreign');
            $table->dropForeign('dosen_attendances_kelas_mata_kuliah_id_foreign');
            $table->dropForeign('dosen_attendances_pertemuan_id_foreign');
        });
    }
};
