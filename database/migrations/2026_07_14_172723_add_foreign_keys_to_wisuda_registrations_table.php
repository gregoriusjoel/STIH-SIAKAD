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
        Schema::table('wisuda_registrations', function (Blueprint $table) {
            $table->foreign(['mahasiswa_id'])->references(['id'])->on('mahasiswas')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['reviewed_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['skripsi_submission_id'])->references(['id'])->on('skripsi_submissions')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['wisuda_batch_id'])->references(['id'])->on('wisuda_batches')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wisuda_registrations', function (Blueprint $table) {
            $table->dropForeign('wisuda_registrations_mahasiswa_id_foreign');
            $table->dropForeign('wisuda_registrations_reviewed_by_foreign');
            $table->dropForeign('wisuda_registrations_skripsi_submission_id_foreign');
            $table->dropForeign('wisuda_registrations_wisuda_batch_id_foreign');
        });
    }
};
