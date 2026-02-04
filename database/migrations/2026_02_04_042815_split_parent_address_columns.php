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
            // Address fields for Ayah
            $table->text('alamat_ayah')->nullable()->after('agama_ayah');
            $table->string('kota_ayah')->nullable()->after('alamat_ayah');
            $table->string('propinsi_ayah')->nullable()->after('kota_ayah');
            $table->string('desa_ayah')->nullable()->after('propinsi_ayah');
            $table->string('negara_ayah')->nullable()->after('desa_ayah');
            $table->string('handphone_ayah', 20)->nullable()->after('negara_ayah');

            // Address fields for Ibu
            $table->text('alamat_ibu')->nullable()->after('agama_ibu');
            $table->string('kota_ibu')->nullable()->after('alamat_ibu');
            $table->string('propinsi_ibu')->nullable()->after('kota_ibu');
            $table->string('desa_ibu')->nullable()->after('propinsi_ibu');
            $table->string('negara_ibu')->nullable()->after('desa_ibu');
            $table->string('handphone_ibu', 20)->nullable()->after('negara_ibu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->dropColumn([
                'alamat_ayah', 'kota_ayah', 'propinsi_ayah', 'desa_ayah', 'negara_ayah', 'handphone_ayah',
                'alamat_ibu', 'kota_ibu', 'propinsi_ibu', 'desa_ibu', 'negara_ibu', 'handphone_ibu'
            ]);
        });
    }
};
