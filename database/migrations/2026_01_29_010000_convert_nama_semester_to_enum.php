<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Preserve original values in a temporary column
        if (!Schema::hasColumn('semesters', 'nama_semester_old')) {
            Schema::table('semesters', function (Blueprint $table) {
                $table->string('nama_semester_old')->nullable()->after('nama_semester');
            });
        }

        // Copy original values
        DB::table('semesters')->update(['nama_semester_old' => DB::raw('nama_semester')]);

        // Normalize existing values to either 'Ganjil' or 'Genap'
        DB::table('semesters')->update([
            'nama_semester' => DB::raw("CASE
                WHEN LOWER(nama_semester) LIKE '%ganjil%' THEN 'Ganjil'
                WHEN LOWER(nama_semester) LIKE '%genap%' THEN 'Genap'
                ELSE 'Ganjil' END")
        ]);

        // Alter column to ENUM('Ganjil','Genap') NOT NULL
        DB::statement("ALTER TABLE `semesters` MODIFY `nama_semester` ENUM('Ganjil','Genap') NOT NULL");
    }

    public function down(): void
    {
        // Revert to varchar and restore original values if present
        DB::statement("ALTER TABLE `semesters` MODIFY `nama_semester` VARCHAR(255) NOT NULL");

        if (Schema::hasColumn('semesters', 'nama_semester_old')) {
            DB::table('semesters')->whereNotNull('nama_semester_old')->update(['nama_semester' => DB::raw('nama_semester_old')]);
            Schema::table('semesters', function (Blueprint $table) {
                $table->dropColumn('nama_semester_old');
            });
        }
    }
};
