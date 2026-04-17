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
            // Add tipe field if it doesn't exist
            if (!Schema::hasColumn('mata_kuliahs', 'tipe')) {
                $table->enum('tipe', ['teori', 'praktikum', 'sidang', 'lab'])
                    ->default('teori')
                    ->after('praktikum')
                    ->comment('Jenis mata kuliah: teori, praktikum, sidang, atau lab');
                
                // Add index for performance
                $table->index('tipe');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            $table->dropIndex(['tipe']);
            $table->dropColumn('tipe');
        });
    }
};
