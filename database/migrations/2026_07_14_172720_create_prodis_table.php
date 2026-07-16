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
        Schema::create('prodis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_prodi', 10)->unique();
            $table->unsignedBigInteger('fakultas_id')->nullable()->index('prodis_fakultas_id_foreign');
            $table->string('nama_prodi');
            $table->enum('jenjang', ['D3', 'S1', 'S2', 'S3']);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prodis');
    }
};
