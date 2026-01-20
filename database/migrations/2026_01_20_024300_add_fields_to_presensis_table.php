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
        Schema::table('presensis', function (Blueprint $table) {
            $table->foreignId('mahasiswa_id')->nullable()->after('id')->constrained('mahasiswas')->nullOnDelete();
            $table->foreignId('kelas_mata_kuliah_id')->nullable()->after('mahasiswa_id')->constrained('kelas_mata_kuliahs')->nullOnDelete();
            $table->string('nama')->nullable()->after('kelas_mata_kuliah_id');
            $table->string('kontak')->nullable()->after('nama');
            $table->timestamp('waktu')->nullable()->after('kontak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->dropColumn(['waktu', 'kontak', 'nama']);
            $table->dropForeign(['kelas_mata_kuliah_id']);
            $table->dropColumn('kelas_mata_kuliah_id');
            $table->dropForeign(['mahasiswa_id']);
            $table->dropColumn('mahasiswa_id');
        });
    }
};
