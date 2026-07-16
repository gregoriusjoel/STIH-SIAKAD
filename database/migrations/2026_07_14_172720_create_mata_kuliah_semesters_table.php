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
        Schema::create('mata_kuliah_semesters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('semester_id');
            $table->unsignedBigInteger('mata_kuliah_id')->index('mata_kuliah_semesters_mata_kuliah_id_foreign');
            $table->enum('status', ['active', 'history', 'archived'])->default('active')->index();
            $table->unsignedBigInteger('source_semester_id')->nullable()->index();
            $table->dateTime('activated_at')->nullable();
            $table->dateTime('deactivated_at')->nullable();
            $table->longText('meta')->nullable();
            $table->timestamps();

            $table->unique(['semester_id', 'mata_kuliah_id'], 'mk_semester_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah_semesters');
    }
};
