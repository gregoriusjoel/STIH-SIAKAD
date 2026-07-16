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
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id');
            $table->integer('semester');
            $table->string('tahun_ajaran', 20)->index();
            $table->integer('sks_ambil')->nullable();
            $table->integer('paket_sks_bayar')->nullable();
            $table->unsignedBigInteger('total_tagihan');
            $table->enum('status', ['DRAFT', 'PUBLISHED', 'IN_INSTALLMENT', 'LUNAS', 'CANCELLED'])->default('DRAFT');
            $table->boolean('auto_generated_from_krs')->default(false);
            $table->boolean('allow_partial')->default(false);
            $table->text('notes')->nullable();
            $table->string('bank_name', 50)->nullable();
            $table->string('va_number', 50)->nullable();
            $table->unsignedBigInteger('created_by')->index('invoices_created_by_foreign');
            $table->dateTime('published_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
