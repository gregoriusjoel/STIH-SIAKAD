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
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('jenis_sekolah')->nullable()->after('negara');
            $table->string('jurusan_sekolah')->nullable()->after('jenis_sekolah');
            $table->string('tahun_lulus')->nullable()->after('jurusan_sekolah');
            $table->decimal('nilai_kelulusan', 5, 2)->nullable()->after('tahun_lulus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_sekolah',
                'jurusan_sekolah',
                'tahun_lulus',
                'nilai_kelulusan'
            ]);
        });
    }
};
