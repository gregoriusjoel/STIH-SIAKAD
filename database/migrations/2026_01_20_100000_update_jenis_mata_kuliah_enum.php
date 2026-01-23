<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First expand the enum to include all values
        DB::statement("ALTER TABLE mata_kuliahs MODIFY COLUMN jenis ENUM('wajib', 'pilihan', 'wajib_nasional', 'wajib_prodi', 'peminatan') NOT NULL");
        
        // Update existing 'wajib' to 'wajib_prodi'
        DB::statement("UPDATE mata_kuliahs SET jenis = 'wajib_prodi' WHERE jenis = 'wajib'");
        
        // Remove old 'wajib' option from enum
        DB::statement("ALTER TABLE mata_kuliahs MODIFY COLUMN jenis ENUM('wajib_nasional', 'wajib_prodi', 'pilihan', 'peminatan') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE mata_kuliahs MODIFY COLUMN jenis ENUM('wajib', 'pilihan') NOT NULL");
    }
};
