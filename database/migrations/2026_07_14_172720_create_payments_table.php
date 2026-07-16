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
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('installment_id')->nullable()->index();
            $table->unsignedBigInteger('proof_id')->unique();
            $table->unsignedBigInteger('amount_approved');
            $table->date('paid_date');
            $table->date('transfer_date');
            $table->unsignedBigInteger('approved_by')->index('payments_approved_by_foreign');
            $table->timestamps();

            $table->index(['invoice_id', 'paid_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
