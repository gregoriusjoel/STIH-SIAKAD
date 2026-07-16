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
        Schema::create('skripsi_submissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mahasiswa_id')->index('thesis_submissions_mahasiswa_id_foreign');
            $table->unsignedBigInteger('semester_id')->nullable()->index('thesis_submissions_semester_id_foreign');
            $table->string('judul');
            $table->text('deskripsi_proposal')->nullable();
            $table->string('proposal_file_path')->nullable();
            $table->unsignedBigInteger('requested_supervisor_id')->nullable()->index('thesis_submissions_requested_supervisor_id_foreign');
            $table->unsignedBigInteger('approved_supervisor_id')->nullable()->index('thesis_submissions_approved_supervisor_id_foreign');
            $table->string('status')->default('PROPOSAL_DRAFT');
            $table->unsignedInteger('total_bimbingan')->default(0);
            $table->string('logbook_file_path')->nullable();
            $table->string('logbook_original_name')->nullable();
            $table->timestamp('logbook_uploaded_at')->nullable();
            $table->timestamp('eligible_for_sidang_at')->nullable();
            $table->timestamp('revision_approved_at')->nullable();
            $table->text('admin_note')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable()->index('thesis_submissions_reviewed_by_foreign');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skripsi_submissions');
    }
};
