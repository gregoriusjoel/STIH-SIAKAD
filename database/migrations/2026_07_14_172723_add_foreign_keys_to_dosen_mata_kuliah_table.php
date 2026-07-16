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
        Schema::table('dosen_mata_kuliah', function (Blueprint $table) {
            $table->foreign(['created_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['dosen_id'])->references(['id'])->on('dosens')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['mata_kuliah_id'])->references(['id'])->on('mata_kuliahs')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['semester_id'])->references(['id'])->on('semesters')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen_mata_kuliah', function (Blueprint $table) {
            $table->dropForeign('dosen_mata_kuliah_created_by_foreign');
            $table->dropForeign('dosen_mata_kuliah_dosen_id_foreign');
            $table->dropForeign('dosen_mata_kuliah_mata_kuliah_id_foreign');
            $table->dropForeign('dosen_mata_kuliah_semester_id_foreign');
        });
    }
};
