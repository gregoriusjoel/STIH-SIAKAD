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
        Schema::create('dosen_mata_kuliah', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dosen_id');
            $table->unsignedBigInteger('mata_kuliah_id')->index('dosen_mata_kuliah_mata_kuliah_id_foreign');
            $table->unsignedBigInteger('semester_id')->index('dosen_mata_kuliah_semester_id_foreign');
            $table->unsignedBigInteger('created_by')->nullable()->index('dosen_mata_kuliah_created_by_foreign');
            $table->timestamps();

            $table->index(['dosen_id', 'semester_id'], 'dmk_dosen_semester');
            $table->unique(['dosen_id', 'mata_kuliah_id', 'semester_id'], 'dmk_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_mata_kuliah');
    }
};
