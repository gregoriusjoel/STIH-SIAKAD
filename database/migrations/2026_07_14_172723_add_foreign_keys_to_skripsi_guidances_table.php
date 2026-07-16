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
        Schema::table('skripsi_guidances', function (Blueprint $table) {
            $table->foreign(['skripsi_submission_id'])->references(['id'])->on('skripsi_submissions')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['dosen_id'], 'thesis_guidances_dosen_id_foreign')->references(['id'])->on('dosens')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skripsi_guidances', function (Blueprint $table) {
            $table->dropForeign('skripsi_guidances_skripsi_submission_id_foreign');
            $table->dropForeign('thesis_guidances_dosen_id_foreign');
        });
    }
};
