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
        // Update tugas table to use mata_kuliah_id instead of kelas_id
        // This allows sharing tugas across all classes of the same mata kuliah
        
        if (Schema::hasTable('tugas')) {
            Schema::table('tugas', function (Blueprint $table) {
                // Add mata_kuliah_id if it doesn't exist
                if (!Schema::hasColumn('tugas', 'mata_kuliah_id')) {
                    $table->unsignedBigInteger('mata_kuliah_id')->nullable()->after('id');
                    $table->index('mata_kuliah_id');
                }
                
                // Make kelas_id nullable (keep for backward compatibility)
                if (Schema::hasColumn('tugas', 'kelas_id')) {
                    $table->unsignedBigInteger('kelas_id')->nullable()->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tugas')) {
            Schema::table('tugas', function (Blueprint $table) {
                if (Schema::hasColumn('tugas', 'mata_kuliah_id')) {
                    $table->dropIndex(['mata_kuliah_id']);
                    $table->dropColumn('mata_kuliah_id');
                }
            });
        }
    }
};
