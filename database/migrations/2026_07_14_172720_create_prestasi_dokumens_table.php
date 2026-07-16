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
        Schema::create('prestasi_dokumens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('prestasi_id')->index();
            $table->enum('jenis', ['sertifikat', 'dokumentasi', 'surat_tugas_lama', 'pendukung'])->default('sertifikat');
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->unsignedBigInteger('uploaded_by')->nullable()->index('prestasi_dokumens_uploaded_by_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi_dokumens');
    }
};
