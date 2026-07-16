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
        Schema::create('installments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id');
            $table->integer('installment_no');
            $table->unsignedBigInteger('amount');
            $table->date('due_date')->nullable();
            $table->enum('status', ['UNPAID', 'WAITING_VERIFICATION', 'PAID', 'REJECTED_PAYMENT'])->default('UNPAID');
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();

            $table->unique(['invoice_id', 'installment_no']);
            $table->index(['invoice_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
