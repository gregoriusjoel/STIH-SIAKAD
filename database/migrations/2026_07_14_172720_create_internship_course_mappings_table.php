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
        Schema::create('internship_course_mappings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('internship_id');
            $table->unsignedBigInteger('mata_kuliah_id')->index('internship_course_mappings_mata_kuliah_id_foreign');
            $table->unsignedTinyInteger('sks');
            $table->timestamps();

            $table->unique(['internship_id', 'mata_kuliah_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_course_mappings');
    }
};
