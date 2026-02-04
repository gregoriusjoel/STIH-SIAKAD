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
        Schema::table('kelas_mata_kuliahs', function (Blueprint $table) {
            if (!Schema::hasColumn('kelas_mata_kuliahs', 'qr_current_pertemuan')) {
                $table->integer('qr_current_pertemuan')->nullable()->after('qr_enabled');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_mata_kuliahs', function (Blueprint $table) {
            if (Schema::hasColumn('kelas_mata_kuliahs', 'qr_current_pertemuan')) {
                $table->dropColumn('qr_current_pertemuan');
            }
        });
    }
};
