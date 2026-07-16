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
        Schema::table('dosen_pa', function (Blueprint $table) {
            $table->foreign(['dosen_id'])->references(['id'])->on('dosens')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['mahasiswa_id'])->references(['id'])->on('mahasiswas')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen_pa', function (Blueprint $table) {
            $table->dropForeign('dosen_pa_dosen_id_foreign');
            $table->dropForeign('dosen_pa_mahasiswa_id_foreign');
        });
    }
};
