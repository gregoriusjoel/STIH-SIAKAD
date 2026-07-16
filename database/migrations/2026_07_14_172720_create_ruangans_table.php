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
        Schema::create('ruangans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_ruangan', 20)->unique();
            $table->string('nama_ruangan');
            $table->string('gedung', 50)->nullable();
            $table->integer('lantai')->nullable();
            $table->integer('kapasitas')->default(30);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->unsignedBigInteger('kategori_id')->nullable()->index('ruangans_kategori_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangans');
    }
};
