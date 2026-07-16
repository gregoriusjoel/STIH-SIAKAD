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
        Schema::create('wisuda_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wisuda_registration_id')->index('wisuda_documents_wisuda_registration_id_foreign');
            $table->enum('file_type', ['surat_penyerahan_skripsi', 'penyerahan_buku', 'keterangan_turnitin', 'pas_foto']);
            $table->string('file_path');
            $table->string('original_name');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wisuda_documents');
    }
};
