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
            // Restore fakultas_id column
            $table->unsignedBigInteger('fakultas_id')->nullable()->after('kode_prodi');
            // Restore foreign key constraint
            $table->foreign('fakultas_id')->references('id')->on('fakultas')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prodis', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['fakultas_id']);
            // Drop the column
            $table->dropColumn('fakultas_id');
        });
    }
};
