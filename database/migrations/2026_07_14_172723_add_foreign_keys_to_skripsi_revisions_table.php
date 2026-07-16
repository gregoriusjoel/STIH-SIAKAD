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
        Schema::table('skripsi_revisions', function (Blueprint $table) {
            $table->foreign(['skripsi_submission_id'])->references(['id'])->on('skripsi_submissions')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['approved_by_dosen_id'], 'thesis_revisions_approved_by_dosen_id_foreign')->references(['id'])->on('dosens')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skripsi_revisions', function (Blueprint $table) {
            $table->dropForeign('skripsi_revisions_skripsi_submission_id_foreign');
            $table->dropForeign('thesis_revisions_approved_by_dosen_id_foreign');
        });
    }
};
