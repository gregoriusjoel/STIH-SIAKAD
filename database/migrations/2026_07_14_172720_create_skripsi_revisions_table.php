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
        Schema::create('skripsi_revisions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('skripsi_submission_id')->index('skripsi_revisions_skripsi_submission_id_foreign');
            $table->string('revision_file_path');
            $table->string('original_name')->nullable();
            $table->text('notes')->nullable();
            $table->text('dosen_notes')->nullable();
            $table->unsignedBigInteger('approved_by_dosen_id')->nullable()->index('thesis_revisions_approved_by_dosen_id_foreign');
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skripsi_revisions');
    }
};
