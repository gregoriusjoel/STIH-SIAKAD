<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thesis_guidances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_submission_id')->constrained('thesis_submissions')->cascadeOnDelete();
            $table->foreignId('dosen_id')->constrained('dosens');
            $table->date('tanggal_bimbingan');
            $table->text('catatan');
            $table->string('file_path')->nullable();
            // Status: pending | reviewed | approved
            $table->string('status')->default('pending');
            $table->text('catatan_dosen')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thesis_guidances');
    }
};
