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
        Schema::table('jadwal_proposals', function (Blueprint $table) {
            $table->foreign(['dosen_id'])->references(['id'])->on('dosens')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['generated_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['kelas_id'])->references(['id'])->on('kelas')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['mata_kuliah_id'])->references(['id'])->on('mata_kuliahs')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['ruangan_id'])->references(['id'])->on('ruangans')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_proposals', function (Blueprint $table) {
            $table->dropForeign('jadwal_proposals_dosen_id_foreign');
            $table->dropForeign('jadwal_proposals_generated_by_foreign');
            $table->dropForeign('jadwal_proposals_kelas_id_foreign');
            $table->dropForeign('jadwal_proposals_mata_kuliah_id_foreign');
            $table->dropForeign('jadwal_proposals_ruangan_id_foreign');
        });
    }
};
