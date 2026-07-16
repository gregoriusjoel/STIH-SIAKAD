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
        Schema::table('kuesioner_aktivasi', function (Blueprint $table) {
            $table->foreign(['mahasiswa_id'])->references(['id'])->on('mahasiswas')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['semester_id'])->references(['id'])->on('semesters')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kuesioner_aktivasi', function (Blueprint $table) {
            $table->dropForeign('kuesioner_aktivasi_mahasiswa_id_foreign');
            $table->dropForeign('kuesioner_aktivasi_semester_id_foreign');
        });
    }
};
