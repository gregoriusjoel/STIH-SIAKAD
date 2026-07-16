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
        Schema::create('prestasi_surat_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('jenis_surat');
            $table->string('format_nomor');
            $table->integer('last_counter')->default(0);
            $table->year('reset_year')->default('2026');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi_surat_settings');
    }
};
