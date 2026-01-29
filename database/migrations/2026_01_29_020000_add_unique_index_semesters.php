<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove exact duplicate rows, keeping the one with the smallest id
        DB::statement(<<<'SQL'
            DELETE s1 FROM semesters s1
            INNER JOIN semesters s2
              ON s1.nama_semester = s2.nama_semester
             AND s1.tahun_ajaran = s2.tahun_ajaran
             AND s1.tanggal_mulai = s2.tanggal_mulai
            WHERE s1.id > s2.id
        SQL
        );

        // Add composite unique index to enforce uniqueness at the DB level
        Schema::table('semesters', function (Blueprint $table) {
            $table->unique(['nama_semester', 'tahun_ajaran', 'tanggal_mulai'], 'semesters_nama_tahun_tanggal_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('semesters', function (Blueprint $table) {
            $table->dropUnique('semesters_nama_tahun_tanggal_unique');
        });
    }
};
