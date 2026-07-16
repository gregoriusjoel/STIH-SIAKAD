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
        Schema::create('skripsi_sidang_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sidang_registration_id')->index('thesis_sidang_files_sidang_registration_id_foreign');
            $table->string('file_type');
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
        Schema::dropIfExists('skripsi_sidang_files');
    }
};
