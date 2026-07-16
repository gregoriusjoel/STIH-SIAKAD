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
        Schema::create('tugas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mata_kuliah_id')->nullable()->index();
            $table->unsignedBigInteger('kelas_id')->nullable();
            $table->integer('pertemuan')->default(1);
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->unsignedBigInteger('dosen_id')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('max_score')->nullable();
            $table->enum('submission_type', ['pdf', 'word', 'excel', 'text', 'any'])->default('any');
            $table->timestamps();

            $table->index(['kelas_id', 'pertemuan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
