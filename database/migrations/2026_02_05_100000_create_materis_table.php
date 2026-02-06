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
        Schema::create('materis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mata_kuliah_id'); // Shared per mata kuliah, bukan per kelas
            $table->unsignedBigInteger('dosen_id');
            $table->integer('pertemuan')->default(1);
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable(); // pdf, ppt, doc, etc
            $table->bigInteger('file_size')->nullable(); // in bytes
            $table->timestamps();

            $table->index(['mata_kuliah_id', 'pertemuan']);
            $table->index('dosen_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materis');
    }
};
