<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('installment_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->date('transfer_date');
            $table->bigInteger('amount_submitted')->unsigned();
            $table->string('method', 50)->nullable();
            $table->string('file_path', 255);
            $table->enum('status', ['UPLOADED', 'APPROVED', 'REJECTED'])->default('UPLOADED');
            $table->text('finance_notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->text('student_notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index(['installment_id', 'status']);
            $table->index(['invoice_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_proofs');
    }
};
