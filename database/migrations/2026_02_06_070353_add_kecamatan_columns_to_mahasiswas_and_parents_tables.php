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
        // Add kecamatan columns to mahasiswas table
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('kecamatan')->nullable()->after('kota');
            $table->string('kecamatan_ktp')->nullable()->after('kota_ktp');
        });

        // Add kecamatan columns to parents table
        Schema::table('parents', function (Blueprint $table) {
            $table->string('kecamatan_ayah')->nullable()->after('kota_ayah');
            $table->string('kecamatan_ibu')->nullable()->after('kota_ibu');
            $table->string('kecamatan_wali')->nullable()->after('kota_wali');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn(['kecamatan', 'kecamatan_ktp']);
        });

        Schema::table('parents', function (Blueprint $table) {
            $table->dropColumn(['kecamatan_ayah', 'kecamatan_ibu', 'kecamatan_wali']);
        });
    }
};
