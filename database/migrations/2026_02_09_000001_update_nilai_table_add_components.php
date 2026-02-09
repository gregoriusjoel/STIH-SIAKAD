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
        Schema::table('nilai', function (Blueprint $table) {
            // Add new component columns
            $table->decimal('nilai_partisipatif', 5, 2)->nullable()->after('krs_id');
            $table->decimal('nilai_proyek', 5, 2)->nullable()->after('nilai_partisipatif');
            $table->decimal('nilai_quiz', 5, 2)->nullable()->after('nilai_proyek');
            
            // Rename nilai_tugas to be more specific
            // Keep nilai_tugas, nilai_uts, nilai_uas as they exist
            
            // Add bobot (grade point) column
            $table->decimal('bobot', 4, 2)->nullable()->after('grade');
            
            // Add reference to kelas for bobot penilaian
            $table->foreignId('kelas_id')->nullable()->after('krs_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropColumn([
                'nilai_partisipatif',
                'nilai_proyek',
                'nilai_quiz',
                'bobot',
                'kelas_id'
            ]);
        });
    }
};
