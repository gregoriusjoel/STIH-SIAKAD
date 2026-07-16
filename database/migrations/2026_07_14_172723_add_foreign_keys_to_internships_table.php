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
        Schema::table('internships', function (Blueprint $table) {
            $table->foreign(['approved_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['date_changed_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['internship_type_id'])->references(['id'])->on('internship_types')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['mahasiswa_id'])->references(['id'])->on('mahasiswas')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['semester_id'])->references(['id'])->on('semesters')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['sent_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['supervisor_dosen_id'])->references(['id'])->on('dosens')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internships', function (Blueprint $table) {
            $table->dropForeign('internships_approved_by_foreign');
            $table->dropForeign('internships_date_changed_by_foreign');
            $table->dropForeign('internships_internship_type_id_foreign');
            $table->dropForeign('internships_mahasiswa_id_foreign');
            $table->dropForeign('internships_semester_id_foreign');
            $table->dropForeign('internships_sent_by_foreign');
            $table->dropForeign('internships_supervisor_dosen_id_foreign');
        });
    }
};
