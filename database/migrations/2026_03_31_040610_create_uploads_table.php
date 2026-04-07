<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Table: uploads
     * Generic file upload table for all file types stored on S3.
     */
    public function up(): void
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();

            // Polymorphic relationship (optional) — link upload to any model
            $table->nullableMorphs('uploadable');

            // Uploader (optional)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // File data
            $table->string('file_path');          // S3 relative path: uploads/images/uuid.jpg
            $table->string('original_name');      // Original client filename
            $table->string('mime_type');          // e.g. image/jpeg
            $table->string('extension', 20);      // e.g. jpg
            $table->string('folder', 50);         // uploads/images | uploads/documents | uploads/others
            $table->unsignedBigInteger('size');   // File size in bytes
            $table->string('disk', 20)->default('s3');

            // Optional label / context
            $table->string('label')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uploads');
    }
};
