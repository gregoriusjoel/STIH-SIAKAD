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
            $table->string('nama_ayah')->nullable()->after('mahasiswa_id');
            $table->string('pendidikan_ayah')->nullable()->after('nama_ayah');
            $table->string('pekerjaan_ayah')->nullable()->after('pendidikan_ayah');
            $table->string('agama_ayah')->nullable()->after('pekerjaan_ayah');
            $table->string('nama_ibu')->nullable()->after('agama_ayah');
            $table->string('pendidikan_ibu')->nullable()->after('nama_ibu');
            $table->string('pekerjaan_ibu')->nullable()->after('pendidikan_ibu');
            $table->string('agama_ibu')->nullable()->after('pekerjaan_ibu');
            $table->text('alamat_ortu')->nullable()->after('address');
            $table->string('kota_ortu')->nullable()->after('alamat_ortu');
            $table->string('provinsi_ortu')->nullable()->after('kota_ortu');
            $table->string('negara_ortu')->nullable()->after('provinsi_ortu');
            $table->string('handphone_ortu')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->dropColumn([
                'nama_ayah',
                'pendidikan_ayah',
                'pekerjaan_ayah',
                'agama_ayah',
                'nama_ibu',
                'pendidikan_ibu',
                'pekerjaan_ibu',
                'agama_ibu',
                'alamat_ortu',
                'kota_ortu',
                'provinsi_ortu',
                'negara_ortu',
                'handphone_ortu'
            ]);
        });
    }
};
