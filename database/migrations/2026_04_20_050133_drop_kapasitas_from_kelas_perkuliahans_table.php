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
        Schema::table('kelas_perkuliahans', function (Blueprint $table) {
            $table->dropColumn('kapasitas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_perkuliahans', function (Blueprint $table) {
            $table->unsignedInteger('kapasitas')->nullable()->after('tahun_akademik_id');
        });
    }
};
