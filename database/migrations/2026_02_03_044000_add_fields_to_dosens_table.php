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
        Schema::table('dosens', function (Blueprint $table) {
            // Add pendidikan_terakhir as JSON array
            $table->json('pendidikan_terakhir')->nullable()->after('nidn')->comment('Multiple education levels: S1, S2, S3');
            
            // Add dosen_tetap (true/false)
            $table->boolean('dosen_tetap')->default(false)->after('pendidikan_terakhir')->comment('Is permanent lecturer');
            
            // Add jabatan_fungsional as JSON array
            $table->json('jabatan_fungsional')->nullable()->after('dosen_tetap')->comment('Functional positions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            $table->dropColumn('pendidikan_terakhir');
            $table->dropColumn('dosen_tetap');
            $table->dropColumn('jabatan_fungsional');
        });
    }
};
