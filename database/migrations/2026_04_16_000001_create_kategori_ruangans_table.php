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
        if (!Schema::hasTable('kategori_ruangans')) {
            Schema::create('kategori_ruangans', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kategori', 50)->unique();
                $table->string('deskripsi', 255)->nullable();
                $table->string('warna_badge', 20)->default('gray')->comment('Warna untuk badge di UI (blue, yellow, purple, green, gray)');
                $table->integer('urutan')->default(0);
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
                $table->timestamps();
                $table->index('urutan');
                $table->index('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_ruangans');
    }
};
