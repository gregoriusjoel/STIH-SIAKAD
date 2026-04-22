<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * NON-DESTRUCTIVE: Only ADDs nullable columns. No existing columns are
     * altered or dropped. Old fields (section, kode_kelas, etc.) remain intact.
     */
    public function up(): void
    {
        // 1. kelas table — legacy class table
        if (!Schema::hasColumn('kelas', 'kelas_perkuliahan_id')) {
            Schema::table('kelas', function (Blueprint $table) {
                $table->foreignId('kelas_perkuliahan_id')
                    ->nullable()
                    ->after('semester_type')
                    ->constrained('kelas_perkuliahans')
                    ->nullOnDelete();
            });
        }

        // 2. kelas_mata_kuliahs table — primary active class table
        if (!Schema::hasColumn('kelas_mata_kuliahs', 'kelas_perkuliahan_id')) {
            Schema::table('kelas_mata_kuliahs', function (Blueprint $table) {
                $table->foreignId('kelas_perkuliahan_id')
                    ->nullable()
                    ->after('qr_current_pertemuan')
                    ->constrained('kelas_perkuliahans')
                    ->nullOnDelete();
            });
        }

        // 3. jadwals table — schedule table
        if (!Schema::hasColumn('jadwals', 'kelas_perkuliahan_id')) {
            Schema::table('jadwals', function (Blueprint $table) {
                $table->foreignId('kelas_perkuliahan_id')
                    ->nullable()
                    ->after('outside_reason')
                    ->constrained('kelas_perkuliahans')
                    ->nullOnDelete();
            });
        }

        // 4. mahasiswas table — student table
        if (!Schema::hasColumn('mahasiswas', 'kelas_perkuliahan_id')) {
            Schema::table('mahasiswas', function (Blueprint $table) {
                $table->foreignId('kelas_perkuliahan_id')
                    ->nullable()
                    ->after('is_dokumen_unlocked')
                    ->constrained('kelas_perkuliahans')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['kelas', 'kelas_mata_kuliahs', 'jadwals', 'mahasiswas'];

        foreach ($tables as $tableName) {
            if (Schema::hasColumn($tableName, 'kelas_perkuliahan_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropConstrainedForeignId('kelas_perkuliahan_id');
                });
            }
        }
    }
};
