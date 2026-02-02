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
            $table->text('alamat_ktp')->nullable()->after('desa');
            $table->string('rt_ktp')->nullable()->after('alamat_ktp');
            $table->string('rw_ktp')->nullable()->after('rt_ktp');
            $table->string('provinsi_ktp')->nullable()->after('rw_ktp');
            $table->string('kota_ktp')->nullable()->after('provinsi_ktp');
            $table->string('desa_ktp')->nullable()->after('kota_ktp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn([
                'alamat_ktp',
                'rt_ktp',
                'rw_ktp',
                'provinsi_ktp',
                'kota_ktp',
                'desa_ktp',
            ]);
        });
    }
};
