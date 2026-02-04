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
        Schema::create('jam_perkuliahan', function (Blueprint $table) {
            $table->id();
            $table->integer('jam_ke')->unique()->comment('Jam ke berapa (1-14)');
            $table->time('jam_mulai')->comment('Waktu mulai');
            $table->time('jam_selesai')->comment('Waktu selesai');
            $table->boolean('is_active')->default(true)->comment('Status aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jam_perkuliahan');
    }
};
