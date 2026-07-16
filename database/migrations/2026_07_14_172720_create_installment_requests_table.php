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
        Schema::create('installment_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('student_id')->index('installment_requests_student_id_foreign');
            $table->integer('requested_terms');
            $table->integer('approved_terms')->nullable();
            $table->text('alasan')->nullable();
            $table->enum('status', ['SUBMITTED', 'APPROVED', 'REJECTED', 'CANCELLED'])->default('SUBMITTED')->index();
            $table->unsignedBigInteger('reviewed_by')->nullable()->index('installment_requests_reviewed_by_foreign');
            $table->dateTime('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->index(['invoice_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installment_requests');
    }
};
