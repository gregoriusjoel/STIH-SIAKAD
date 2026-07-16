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
        Schema::create('payment_proofs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->unsignedBigInteger('installment_id')->nullable();
            $table->unsignedBigInteger('uploaded_by')->index('payment_proofs_uploaded_by_foreign');
            $table->date('transfer_date');
            $table->unsignedBigInteger('amount_submitted');
            $table->string('method', 50)->nullable();
            $table->string('file_path');
            $table->enum('status', ['UPLOADED', 'APPROVED', 'REJECTED'])->default('UPLOADED')->index();
            $table->text('finance_notes')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable()->index('payment_proofs_approved_by_foreign');
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->text('student_notes')->nullable();
            $table->timestamps();

            $table->index(['installment_id', 'status']);
            $table->index(['invoice_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_proofs');
    }
};
