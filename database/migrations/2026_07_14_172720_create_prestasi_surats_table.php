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
        Schema::create('prestasi_surats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('prestasi_id')->index();
            $table->string('jenis_surat')->index();
            $table->string('nomor_surat')->unique();
            $table->date('tanggal_surat');
            $table->string('penandatangan_nama');
            $table->string('penandatangan_jabatan');
            $table->string('penandatangan_nip')->nullable();
            $table->string('file_path')->nullable();
            $table->boolean('is_backdate')->default(false);
            $table->unsignedBigInteger('generated_by')->nullable()->index('prestasi_surats_generated_by_foreign');
            $table->longText('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi_surats');
    }
};
