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
        Schema::table('pertemuans', function (Blueprint $table) {
            $table->foreign(['kelas_mata_kuliah_id'])->references(['id'])->on('kelas_mata_kuliahs')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pertemuans', function (Blueprint $table) {
            $table->dropForeign('pertemuans_kelas_mata_kuliah_id_foreign');
        });
    }
};
