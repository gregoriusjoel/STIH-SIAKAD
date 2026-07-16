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
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            $table->foreign(['fakultas_id'])->references(['id'])->on('fakultas')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['prodi_id'])->references(['id'])->on('prodis')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            $table->dropForeign('mata_kuliahs_fakultas_id_foreign');
            $table->dropForeign('mata_kuliahs_prodi_id_foreign');
        });
    }
};
