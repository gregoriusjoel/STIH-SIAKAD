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
        Schema::table('mata_kuliah_semesters', function (Blueprint $table) {
            $table->foreign(['mata_kuliah_id'])->references(['id'])->on('mata_kuliahs')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['semester_id'])->references(['id'])->on('semesters')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['source_semester_id'])->references(['id'])->on('semesters')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mata_kuliah_semesters', function (Blueprint $table) {
            $table->dropForeign('mata_kuliah_semesters_mata_kuliah_id_foreign');
            $table->dropForeign('mata_kuliah_semesters_semester_id_foreign');
            $table->dropForeign('mata_kuliah_semesters_source_semester_id_foreign');
        });
    }
};
