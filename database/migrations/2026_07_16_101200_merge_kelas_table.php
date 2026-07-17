<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add tahun_ajaran and semester_type columns to kelas_mata_kuliahs
        Schema::table('kelas_mata_kuliahs', function (Blueprint $table) {
            $table->string('tahun_ajaran')->nullable()->after('semester_id');
            $table->enum('semester_type', ['Ganjil', 'Genap'])->default('Ganjil')->after('tahun_ajaran');
        });

        // 2. Populate these columns based on referenced semesters table
        DB::statement("
            UPDATE kelas_mata_kuliahs kmk
            INNER JOIN semesters s ON s.id = kmk.semester_id
            SET kmk.tahun_ajaran = s.tahun_ajaran,
                kmk.semester_type = s.nama_semester
        ");

        // 3. Map existing foreign keys from kelas.id to kelas_mata_kuliahs.id
        $kelasMapping = DB::table('kelas')
            ->join('kelas_mata_kuliahs', function ($join) {
                $join->on('kelas_mata_kuliahs.mata_kuliah_id', '=', 'kelas.mata_kuliah_id')
                     ->on('kelas_mata_kuliahs.dosen_id', '=', 'kelas.dosen_id');
            })
            ->select('kelas.id as old_id', 'kelas_mata_kuliahs.id as new_id')
            ->get()
            ->pluck('new_id', 'old_id')
            ->toArray();

        Schema::disableForeignKeyConstraints();

        foreach ($kelasMapping as $oldId => $newId) {
            DB::table('nilai')->where('kelas_id', $oldId)->update(['kelas_id' => $newId]);
            DB::table('tugas')->where('kelas_id', $oldId)->update(['kelas_id' => $newId]);
            DB::table('jadwals')->where('kelas_id', $oldId)->update(['kelas_id' => $newId]);
            DB::table('jadwal_proposals')->where('kelas_id', $oldId)->update(['kelas_id' => $newId]);
            DB::table('bobot_penilaian')->where('kelas_id', $oldId)->update(['kelas_id' => $newId]);
            DB::table('dokumen_kelas')->where('kelas_id', $oldId)->update(['kelas_id' => $newId]);
        }

        // 4. Drop old foreign key constraints
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
        });
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
        });
        Schema::table('jadwal_proposals', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
        });
        Schema::table('bobot_penilaian', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
        });
        Schema::table('dokumen_kelas', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
        });
        Schema::table('krs', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
        });

        // 5. Add new foreign key constraints referencing kelas_mata_kuliahs
        Schema::table('nilai', function (Blueprint $table) {
            $table->foreign('kelas_id')->references('id')->on('kelas_mata_kuliahs')->onDelete('cascade');
        });
        Schema::table('jadwals', function (Blueprint $table) {
            $table->foreign('kelas_id')->references('id')->on('kelas_mata_kuliahs')->onDelete('cascade');
        });
        Schema::table('jadwal_proposals', function (Blueprint $table) {
            $table->foreign('kelas_id')->references('id')->on('kelas_mata_kuliahs')->onDelete('cascade');
        });
        Schema::table('bobot_penilaian', function (Blueprint $table) {
            $table->foreign('kelas_id')->references('id')->on('kelas_mata_kuliahs')->onDelete('cascade');
        });
        Schema::table('dokumen_kelas', function (Blueprint $table) {
            $table->foreign('kelas_id')->references('id')->on('kelas_mata_kuliahs')->onDelete('cascade');
        });

        // For KRS table, drop the redundant kelas_id column entirely
        Schema::table('krs', function (Blueprint $table) {
            $table->dropColumn('kelas_id');
        });

        // 6. Drop the redundant kelas table
        Schema::dropIfExists('kelas');

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // Reversal logic stub (not strictly needed for final cleanup migration)
    }
};
