<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thesis_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnDelete();
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->nullOnDelete();

            // Proposal fields
            $table->string('judul');
            $table->text('deskripsi_proposal')->nullable();
            $table->string('proposal_file_path')->nullable();

            // Supervisor
            $table->foreignId('requested_supervisor_id')->nullable()->constrained('dosens')->nullOnDelete();
            $table->foreignId('approved_supervisor_id')->nullable()->constrained('dosens')->nullOnDelete();

            // Status machine
            $table->string('status')->default('PROPOSAL_DRAFT'); // ThesisStatus enum values

            // Counters / timestamps
            $table->unsignedInteger('total_bimbingan')->default(0);
            $table->timestamp('eligible_for_sidang_at')->nullable();
            $table->timestamp('revision_approved_at')->nullable();

            // Admin notes
            $table->text('admin_note')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('admins')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thesis_submissions');
    }
};
