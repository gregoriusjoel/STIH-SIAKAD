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
        Schema::create('email_outboxes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('batch_id')->nullable()->index();
            $table->unsignedBigInteger('mahasiswa_id')->index('email_outboxes_mahasiswa_id_foreign');
            $table->string('target_email');
            $table->string('subject')->nullable();
            $table->string('greeting')->nullable();
            $table->text('message_body')->nullable();
            $table->boolean('is_credentials_mode')->default(false);
            $table->enum('credential_type', ['none', 'student', 'parents', 'both'])->default('none');
            $table->string('status')->default('pending')->index();
            $table->timestamp('scheduled_at')->nullable()->index();
            $table->timestamp('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_outboxes');
    }
};
