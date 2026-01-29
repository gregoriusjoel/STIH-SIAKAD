<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kelas_id');
            $table->integer('pertemuan')->default(1);
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->unsignedBigInteger('dosen_id')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('max_score')->nullable();
            $table->timestamps();

            $table->index(['kelas_id', 'pertemuan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
