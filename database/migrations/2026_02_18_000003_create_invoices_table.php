<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->integer('semester');
            $table->string('tahun_ajaran', 20);
            $table->integer('sks_ambil')->nullable();
            $table->integer('paket_sks_bayar')->nullable();
            $table->bigInteger('total_tagihan')->unsigned();
            $table->enum('status', ['DRAFT', 'PUBLISHED', 'IN_INSTALLMENT', 'LUNAS', 'CANCELLED'])->default('DRAFT');
            $table->boolean('allow_partial')->default(false);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->dateTime('published_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index('tahun_ajaran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
