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
        Schema::create('parents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('hubungan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->unsignedBigInteger('user_id')->index('parents_user_id_foreign');
            $table->unsignedBigInteger('mahasiswa_id')->nullable()->index('parents_mahasiswa_id_foreign');
            $table->enum('tipe_wali', ['orang_tua', 'wali'])->default('orang_tua');
            $table->string('nama_ayah')->nullable();
            $table->string('pendidikan_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('agama_ayah')->nullable();
            $table->text('alamat_ayah')->nullable();
            $table->string('kota_ayah')->nullable();
            $table->string('kecamatan_ayah')->nullable();
            $table->string('propinsi_ayah')->nullable();
            $table->string('desa_ayah')->nullable();
            $table->string('handphone_ayah', 20)->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('pendidikan_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('agama_ibu')->nullable();
            $table->text('alamat_ibu')->nullable();
            $table->string('kota_ibu')->nullable();
            $table->string('kecamatan_ibu')->nullable();
            $table->string('propinsi_ibu')->nullable();
            $table->string('desa_ibu')->nullable();
            $table->string('handphone_ibu', 20)->nullable();
            $table->string('phone')->nullable();
            $table->string('nama_wali')->nullable();
            $table->string('hubungan_wali')->nullable();
            $table->string('pendidikan_wali')->nullable();
            $table->string('pekerjaan_wali')->nullable();
            $table->string('agama_wali')->nullable();
            $table->text('alamat_wali')->nullable();
            $table->string('kota_wali')->nullable();
            $table->string('kecamatan_wali')->nullable();
            $table->string('provinsi_wali')->nullable();
            $table->string('handphone_wali', 20)->nullable();
            $table->longText('keluarga')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
            $table->string('desa_wali')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
