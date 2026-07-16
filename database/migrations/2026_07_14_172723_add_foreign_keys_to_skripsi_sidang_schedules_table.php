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
        Schema::table('skripsi_sidang_schedules', function (Blueprint $table) {
            $table->foreign(['skripsi_submission_id'])->references(['id'])->on('skripsi_submissions')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['created_by'], 'thesis_sidang_schedules_created_by_foreign')->references(['id'])->on('admins')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['pembimbing_id'], 'thesis_sidang_schedules_pembimbing_id_foreign')->references(['id'])->on('dosens')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['penguji_1_id'], 'thesis_sidang_schedules_penguji_1_id_foreign')->references(['id'])->on('dosens')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['penguji_2_id'], 'thesis_sidang_schedules_penguji_2_id_foreign')->references(['id'])->on('dosens')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['ruangan_id'], 'thesis_sidang_schedules_ruangan_id_foreign')->references(['id'])->on('ruangans')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['sidang_registration_id'], 'thesis_sidang_schedules_sidang_registration_id_foreign')->references(['id'])->on('skripsi_sidang_registrations')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skripsi_sidang_schedules', function (Blueprint $table) {
            $table->dropForeign('skripsi_sidang_schedules_skripsi_submission_id_foreign');
            $table->dropForeign('thesis_sidang_schedules_created_by_foreign');
            $table->dropForeign('thesis_sidang_schedules_pembimbing_id_foreign');
            $table->dropForeign('thesis_sidang_schedules_penguji_1_id_foreign');
            $table->dropForeign('thesis_sidang_schedules_penguji_2_id_foreign');
            $table->dropForeign('thesis_sidang_schedules_ruangan_id_foreign');
            $table->dropForeign('thesis_sidang_schedules_sidang_registration_id_foreign');
        });
    }
};
