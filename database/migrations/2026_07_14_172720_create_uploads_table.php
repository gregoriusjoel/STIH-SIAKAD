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
        Schema::create('uploads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uploadable_type')->nullable();
            $table->unsignedBigInteger('uploadable_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index('uploads_user_id_foreign');
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type');
            $table->string('extension', 20);
            $table->string('folder', 50);
            $table->unsignedBigInteger('size');
            $table->string('disk', 20)->default('s3');
            $table->string('label')->nullable();
            $table->timestamps();

            $table->index(['uploadable_type', 'uploadable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploads');
    }
};
