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
        Schema::create('skripsi_sidang_registrations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('skripsi_submission_id')->index('skripsi_sidang_registrations_skripsi_submission_id_foreign');
            $table->string('status')->default('draft');
            $table->text('notes')->nullable();
            $table->text('admin_note')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable()->index('thesis_sidang_registrations_verified_by_foreign');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skripsi_sidang_registrations');
    }
};
