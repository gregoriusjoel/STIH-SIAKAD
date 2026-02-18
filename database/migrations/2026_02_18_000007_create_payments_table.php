<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('installment_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('proof_id')->unique()->constrained('payment_proofs')->cascadeOnDelete();
            $table->bigInteger('amount_approved')->unsigned();
            $table->date('paid_date');
            $table->date('transfer_date');
            $table->foreignId('approved_by')->constrained('users');
            $table->timestamps();

            $table->index(['invoice_id', 'paid_date']);
            $table->index('installment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
