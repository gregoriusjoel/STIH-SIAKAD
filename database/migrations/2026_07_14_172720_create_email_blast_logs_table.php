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
        Schema::create('email_blast_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('batch_id', 50)->index();
            $table->unsignedBigInteger('mahasiswa_id')->index();
            $table->string('email_sent_to')->nullable();
            $table->string('subject');
            $table->boolean('success')->default(false)->index();
            $table->text('error_message')->nullable();
            $table->enum('recipient_type', ['student', 'parent'])->default('student');
            $table->enum('credential_type', ['none', 'student', 'parents', 'both'])->default('none');
            $table->unsignedBigInteger('sent_by')->nullable();
            $table->timestamp('created_at')->nullable()->index();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_blast_logs');
    }
};
