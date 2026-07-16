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
        Schema::table('skripsi_submissions', function (Blueprint $table) {
            $table->foreign(['approved_supervisor_id'], 'thesis_submissions_approved_supervisor_id_foreign')->references(['id'])->on('dosens')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['mahasiswa_id'], 'thesis_submissions_mahasiswa_id_foreign')->references(['id'])->on('mahasiswas')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['requested_supervisor_id'], 'thesis_submissions_requested_supervisor_id_foreign')->references(['id'])->on('dosens')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['reviewed_by'], 'thesis_submissions_reviewed_by_foreign')->references(['id'])->on('admins')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['semester_id'], 'thesis_submissions_semester_id_foreign')->references(['id'])->on('semesters')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skripsi_submissions', function (Blueprint $table) {
            $table->dropForeign('thesis_submissions_approved_supervisor_id_foreign');
            $table->dropForeign('thesis_submissions_mahasiswa_id_foreign');
            $table->dropForeign('thesis_submissions_requested_supervisor_id_foreign');
            $table->dropForeign('thesis_submissions_reviewed_by_foreign');
            $table->dropForeign('thesis_submissions_semester_id_foreign');
        });
    }
};
