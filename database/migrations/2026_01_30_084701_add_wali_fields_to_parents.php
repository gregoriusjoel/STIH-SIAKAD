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
        Schema::table('parents', function (Blueprint $table) {
            $table->enum('tipe_wali', ['orang_tua', 'wali'])->default('orang_tua')->after('mahasiswa_id');
            $table->string('nama_wali')->nullable()->after('handphone_ortu');
            $table->string('hubungan_wali')->nullable()->after('nama_wali');
            $table->string('pendidikan_wali')->nullable()->after('hubungan_wali');
            $table->string('pekerjaan_wali')->nullable()->after('pendidikan_wali');
            $table->string('agama_wali')->nullable()->after('pekerjaan_wali');
            $table->text('alamat_wali')->nullable()->after('agama_wali');
            $table->string('kota_wali')->nullable()->after('alamat_wali');
            $table->string('provinsi_wali')->nullable()->after('kota_wali');
            $table->string('negara_wali')->nullable()->after('provinsi_wali');
            $table->string('handphone_wali', 20)->nullable()->after('negara_wali');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->dropColumn([
                'tipe_wali',
                'nama_wali',
                'hubungan_wali',
                'pendidikan_wali',
                'pekerjaan_wali',
                'agama_wali',
                'alamat_wali',
                'kota_wali',
                'provinsi_wali',
                'negara_wali',
                'handphone_wali',
            ]);
        });
    }
};
