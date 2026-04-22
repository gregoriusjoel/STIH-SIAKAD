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
            $table->id();
            $table->string('batch_id', 50);
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnDelete();
            $table->string('email_sent_to', 255)->nullable();
            $table->string('subject', 255);
            $table->boolean('success')->default(false);
            $table->text('error_message')->nullable();
            $table->unsignedBigInteger('sent_by')->nullable();
            $table->timestamps();

            // Indexes untuk query lebih cepat
            $table->index('batch_id');
            $table->index('mahasiswa_id');
            $table->index('success');
            $table->index('created_at');
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
