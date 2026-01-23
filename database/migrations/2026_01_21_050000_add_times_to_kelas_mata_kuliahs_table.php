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
        if (Schema::hasTable('kelas_mata_kuliahs')) {
            Schema::table('kelas_mata_kuliahs', function (Blueprint $table) {
                if (!Schema::hasColumn('kelas_mata_kuliahs', 'jam_mulai')) {
                    $table->time('jam_mulai')->nullable()->after('hari');
                }
                if (!Schema::hasColumn('kelas_mata_kuliahs', 'jam_selesai')) {
                    $table->time('jam_selesai')->nullable()->after('jam_mulai');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('kelas_mata_kuliahs')) {
            Schema::table('kelas_mata_kuliahs', function (Blueprint $table) {
                if (Schema::hasColumn('kelas_mata_kuliahs', 'jam_selesai')) {
                    $table->dropColumn('jam_selesai');
                }
                if (Schema::hasColumn('kelas_mata_kuliahs', 'jam_mulai')) {
                    $table->dropColumn('jam_mulai');
                }
            });
        }
    }
};
