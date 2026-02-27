<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mata_kuliah_semesters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->enum('status', ['active', 'history', 'archived'])->default('active');
            $table->foreignId('source_semester_id')->nullable()->constrained('semesters')->onDelete('set null');
            $table->datetime('activated_at')->nullable();
            $table->datetime('deactivated_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['semester_id', 'mata_kuliah_id'], 'mk_semester_unique');
            $table->index('status');
            $table->index('source_semester_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah_semesters');
    }
};
