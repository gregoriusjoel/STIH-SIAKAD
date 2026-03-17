<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thesis_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_submission_id')->constrained('thesis_submissions')->cascadeOnDelete();
            $table->string('revision_file_path');
            $table->string('original_name')->nullable();
            $table->text('notes')->nullable(); // catatan dari mahasiswa
            $table->text('dosen_notes')->nullable(); // catatan dari dosen pembimbing
            $table->foreignId('approved_by_dosen_id')->nullable()->constrained('dosens')->nullOnDelete();
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thesis_revisions');
    }
};
