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
        Schema::table('prodis', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['fakultas_id']);
            // Drop the column
            $table->dropColumn('fakultas_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prodis', function (Blueprint $table) {
            // Restore the column
            $table->unsignedBigInteger('fakultas_id')->after('kode_prodi');
            // Restore the foreign key
            $table->foreign('fakultas_id')->references('id')->on('fakultas')->onDelete('restrict');
        });
    }
};
