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
        Schema::table('kuesioner_aktivasi', function (Blueprint $table) {
            $table->integer('semester_mahasiswa')->nullable()->after('semester_id')->comment('Semester level of the student when filling the questionnaire');
        });

        // Populate existing records with matching student's current semester as fallback
        try {
            \Illuminate\Support\Facades\DB::table('kuesioner_aktivasi')
                ->join('mahasiswas', 'kuesioner_aktivasi.mahasiswa_id', '=', 'mahasiswas.id')
                ->update([
                    'kuesioner_aktivasi.semester_mahasiswa' => \Illuminate\Support\Facades\DB::raw('mahasiswas.semester')
                ]);
        } catch (\Exception $e) {
            // Ignore failure if it's run in an environment with no/different tables during fresh migrations
            logger()->warning('Failed to populate existing kuesioner_aktivasi.semester_mahasiswa: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kuesioner_aktivasi', function (Blueprint $table) {
            $table->dropColumn('semester_mahasiswa');
        });
    }
};
