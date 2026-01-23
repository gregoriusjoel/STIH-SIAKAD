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
        Schema::table('krs', function (Blueprint $table) {
            // Add mata_kuliah_id as nullable first
            $table->foreignId('mata_kuliah_id')->nullable()->after('mahasiswa_id')->constrained('mata_kuliahs')->onDelete('cascade');
            
            // Make kelas_id nullable since we can have direct mata_kuliah enrollment
            $table->foreignId('kelas_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('krs', function (Blueprint $table) {
            $table->dropForeign(['mata_kuliah_id']);
            $table->dropColumn('mata_kuliah_id');
        });
    }
};
