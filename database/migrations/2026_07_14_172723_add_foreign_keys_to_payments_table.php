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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreign(['approved_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['installment_id'])->references(['id'])->on('installments')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['invoice_id'])->references(['id'])->on('invoices')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['proof_id'])->references(['id'])->on('payment_proofs')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign('payments_approved_by_foreign');
            $table->dropForeign('payments_installment_id_foreign');
            $table->dropForeign('payments_invoice_id_foreign');
            $table->dropForeign('payments_proof_id_foreign');
        });
    }
};
