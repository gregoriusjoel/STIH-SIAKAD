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
        Schema::table('skripsi_sidang_registrations', function (Blueprint $table) {
            $table->foreign(['skripsi_submission_id'])->references(['id'])->on('skripsi_submissions')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['verified_by'], 'thesis_sidang_registrations_verified_by_foreign')->references(['id'])->on('admins')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skripsi_sidang_registrations', function (Blueprint $table) {
            $table->dropForeign('skripsi_sidang_registrations_skripsi_submission_id_foreign');
            $table->dropForeign('thesis_sidang_registrations_verified_by_foreign');
        });
    }
};
