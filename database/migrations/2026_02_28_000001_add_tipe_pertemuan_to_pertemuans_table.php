<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pertemuans', 'tipe_pertemuan')) {
            Schema::table('pertemuans', function (Blueprint $table) {
                $table->enum('tipe_pertemuan', ['kuliah', 'uts', 'uas'])
                      ->default('kuliah')
                      ->after('nomor_pertemuan')
                      ->comment('Meeting type: kuliah (regular), uts (midterm), uas (final)');
            });
        }

        // Add composite index for efficient queries
        // Check if index already exists first
        $indexExists = collect(DB::select("SHOW INDEX FROM pertemuans WHERE Key_name = 'pertemuans_kmk_tipe_nomor_index'"))->isNotEmpty();
        if (!$indexExists) {
            Schema::table('pertemuans', function (Blueprint $table) {
                $table->index(
                    ['kelas_mata_kuliah_id', 'tipe_pertemuan', 'nomor_pertemuan'],
                    'pertemuans_kmk_tipe_nomor_index'
                );
            });
        }
    }

    public function down(): void
    {
        Schema::table('pertemuans', function (Blueprint $table) {
            $table->dropIndex('pertemuans_kmk_tipe_nomor_index');
            $table->dropColumn('tipe_pertemuan');
        });
    }
};
