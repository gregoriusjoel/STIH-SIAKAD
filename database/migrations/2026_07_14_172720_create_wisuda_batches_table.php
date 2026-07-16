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
        Schema::create('wisuda_batches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_batch');
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->string('lokasi');
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index('wisuda_batches_created_by_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wisuda_batches');
    }
};
