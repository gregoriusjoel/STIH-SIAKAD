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
        Schema::table('kelas_reschedules', function (Blueprint $table) {
            $table->foreign(['approved_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['dosen_id'])->references(['id'])->on('dosens')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['kelas_mata_kuliah_id'])->references(['id'])->on('kelas_mata_kuliahs')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_reschedules', function (Blueprint $table) {
            $table->dropForeign('kelas_reschedules_approved_by_foreign');
            $table->dropForeign('kelas_reschedules_dosen_id_foreign');
            $table->dropForeign('kelas_reschedules_kelas_mata_kuliah_id_foreign');
        });
    }
};
