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
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->foreign(['kelas_perkuliahan_id'])->references(['id'])->on('kelas_perkuliahans')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['last_semester_id'])->references(['id'])->on('semesters')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['prodi_id'])->references(['id'])->on('prodis')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['tahun_akademik_id'])->references(['id'])->on('semesters')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropForeign('mahasiswas_kelas_perkuliahan_id_foreign');
            $table->dropForeign('mahasiswas_last_semester_id_foreign');
            $table->dropForeign('mahasiswas_prodi_id_foreign');
            $table->dropForeign('mahasiswas_tahun_akademik_id_foreign');
            $table->dropForeign('mahasiswas_user_id_foreign');
        });
    }
};
