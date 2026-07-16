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
        Schema::create('wisuda_registrations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('skripsi_submission_id')->index('wisuda_registrations_skripsi_submission_id_foreign');
            $table->unsignedBigInteger('wisuda_batch_id')->nullable()->index('wisuda_registrations_wisuda_batch_id_foreign');
            $table->string('no_hp')->nullable();
            $table->string('email_aktif')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'scheduled'])->default('pending');
            $table->text('rejection_note')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable()->index('wisuda_registrations_reviewed_by_foreign');
            $table->timestamps();

            $table->index(['mahasiswa_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wisuda_registrations');
    }
};
