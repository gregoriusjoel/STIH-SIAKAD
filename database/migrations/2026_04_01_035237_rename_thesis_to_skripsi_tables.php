<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop foreign key constraints on child tables first
        Schema::table('thesis_guidances', function (Blueprint $table) {
            $table->dropForeign(['thesis_submission_id']);
        });
        Schema::table('thesis_sidang_registrations', function (Blueprint $table) {
            $table->dropForeign(['thesis_submission_id']);
        });
        Schema::table('thesis_sidang_schedules', function (Blueprint $table) {
            $table->dropForeign(['thesis_submission_id']);
        });
        Schema::table('thesis_revisions', function (Blueprint $table) {
            $table->dropForeign(['thesis_submission_id']);
        });

        // 2. Rename all 6 tables
        Schema::rename('thesis_submissions', 'skripsi_submissions');
        Schema::rename('thesis_guidances', 'skripsi_guidances');
        Schema::rename('thesis_sidang_files', 'skripsi_sidang_files');
        Schema::rename('thesis_sidang_registrations', 'skripsi_sidang_registrations');
        Schema::rename('thesis_sidang_schedules', 'skripsi_sidang_schedules');
        Schema::rename('thesis_revisions', 'skripsi_revisions');

        // 3. Rename FK columns in child tables
        Schema::table('skripsi_guidances', function (Blueprint $table) {
            $table->renameColumn('thesis_submission_id', 'skripsi_submission_id');
        });
        Schema::table('skripsi_sidang_registrations', function (Blueprint $table) {
            $table->renameColumn('thesis_submission_id', 'skripsi_submission_id');
        });
        Schema::table('skripsi_sidang_schedules', function (Blueprint $table) {
            $table->renameColumn('thesis_submission_id', 'skripsi_submission_id');
        });
        Schema::table('skripsi_revisions', function (Blueprint $table) {
            $table->renameColumn('thesis_submission_id', 'skripsi_submission_id');
        });

        // 4. Recreate foreign key constraints
        Schema::table('skripsi_guidances', function (Blueprint $table) {
            $table->foreign('skripsi_submission_id')
                  ->references('id')->on('skripsi_submissions')
                  ->cascadeOnDelete();
        });
        Schema::table('skripsi_sidang_registrations', function (Blueprint $table) {
            $table->foreign('skripsi_submission_id')
                  ->references('id')->on('skripsi_submissions')
                  ->cascadeOnDelete();
        });
        Schema::table('skripsi_sidang_schedules', function (Blueprint $table) {
            $table->foreign('skripsi_submission_id')
                  ->references('id')->on('skripsi_submissions')
                  ->cascadeOnDelete();
        });
        Schema::table('skripsi_revisions', function (Blueprint $table) {
            $table->foreign('skripsi_submission_id')
                  ->references('id')->on('skripsi_submissions')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        // Drop new FK constraints
        Schema::table('skripsi_guidances', function (Blueprint $table) {
            $table->dropForeign(['skripsi_submission_id']);
        });
        Schema::table('skripsi_sidang_registrations', function (Blueprint $table) {
            $table->dropForeign(['skripsi_submission_id']);
        });
        Schema::table('skripsi_sidang_schedules', function (Blueprint $table) {
            $table->dropForeign(['skripsi_submission_id']);
        });
        Schema::table('skripsi_revisions', function (Blueprint $table) {
            $table->dropForeign(['skripsi_submission_id']);
        });

        // Rename columns back
        Schema::table('skripsi_guidances', function (Blueprint $table) {
            $table->renameColumn('skripsi_submission_id', 'thesis_submission_id');
        });
        Schema::table('skripsi_sidang_registrations', function (Blueprint $table) {
            $table->renameColumn('skripsi_submission_id', 'thesis_submission_id');
        });
        Schema::table('skripsi_sidang_schedules', function (Blueprint $table) {
            $table->renameColumn('skripsi_submission_id', 'thesis_submission_id');
        });
        Schema::table('skripsi_revisions', function (Blueprint $table) {
            $table->renameColumn('skripsi_submission_id', 'thesis_submission_id');
        });

        // Rename tables back
        Schema::rename('skripsi_submissions', 'thesis_submissions');
        Schema::rename('skripsi_guidances', 'thesis_guidances');
        Schema::rename('skripsi_sidang_files', 'thesis_sidang_files');
        Schema::rename('skripsi_sidang_registrations', 'thesis_sidang_registrations');
        Schema::rename('skripsi_sidang_schedules', 'thesis_sidang_schedules');
        Schema::rename('skripsi_revisions', 'thesis_revisions');

        // Recreate old FK constraints
        Schema::table('thesis_guidances', function (Blueprint $table) {
            $table->foreign('thesis_submission_id')
                  ->references('id')->on('thesis_submissions')
                  ->cascadeOnDelete();
        });
        Schema::table('thesis_sidang_registrations', function (Blueprint $table) {
            $table->foreign('thesis_submission_id')
                  ->references('id')->on('thesis_submissions')
                  ->cascadeOnDelete();
        });
        Schema::table('thesis_sidang_schedules', function (Blueprint $table) {
            $table->foreign('thesis_submission_id')
                  ->references('id')->on('thesis_submissions')
                  ->cascadeOnDelete();
        });
        Schema::table('thesis_revisions', function (Blueprint $table) {
            $table->foreign('thesis_submission_id')
                  ->references('id')->on('thesis_submissions')
                  ->cascadeOnDelete();
        });
    }
};
