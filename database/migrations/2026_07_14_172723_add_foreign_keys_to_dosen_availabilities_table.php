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
        Schema::table('dosen_availabilities', function (Blueprint $table) {
            $table->foreign(['dosen_id'])->references(['id'])->on('dosens')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['jam_perkuliahan_id'])->references(['id'])->on('jam_perkuliahan')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['semester_id'])->references(['id'])->on('semesters')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen_availabilities', function (Blueprint $table) {
            $table->dropForeign('dosen_availabilities_dosen_id_foreign');
            $table->dropForeign('dosen_availabilities_jam_perkuliahan_id_foreign');
            $table->dropForeign('dosen_availabilities_semester_id_foreign');
        });
    }
};
