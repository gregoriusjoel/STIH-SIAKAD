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
        Schema::create('dosen_pa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dosen_id')->index('dosen_pa_dosen_id_foreign');
            $table->unsignedBigInteger('mahasiswa_id')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_pa');
    }
};
