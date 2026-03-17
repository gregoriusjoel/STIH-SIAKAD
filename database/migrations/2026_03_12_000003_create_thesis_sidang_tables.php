<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thesis_sidang_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_submission_id')->constrained('thesis_submissions')->cascadeOnDelete();
            // Status: draft | submitted | rejected | verified
            $table->string('status')->default('draft');
            $table->text('notes')->nullable();
            $table->text('admin_note')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
        });

        Schema::create('thesis_sidang_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sidang_registration_id')->constrained('thesis_sidang_registrations')->cascadeOnDelete();
            // file_type: form_sidang|bebas_pustaka|transkrip|form_bimbingan|file_skripsi|file_ppt|lainnya
            $table->string('file_type');
            $table->string('file_path');
            $table->string('original_name');
            $table->unsignedBigInteger('file_size')->nullable(); // bytes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thesis_sidang_files');
        Schema::dropIfExists('thesis_sidang_registrations');
    }
};
