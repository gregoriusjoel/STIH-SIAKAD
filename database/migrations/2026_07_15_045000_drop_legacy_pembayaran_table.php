<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('pembayaran');
    }

    public function down(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('semester_id')->nullable();
            $table->string('jenis');
            $table->decimal('jumlah', 15, 2);
            $table->decimal('dibayar', 15, 2)->default(0.00);
            $table->enum('status', ['belum_bayar', 'sebagian', 'lunas'])->default('belum_bayar');
            $table->date('tanggal_bayar')->nullable();
            $table->string('bukti_bayar')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('mahasiswa_id', 'pembayaran_mahasiswa_id_foreign');
            $table->index('semester_id', 'idx_pembayaran_mhs_semester');
        });
    }
};
