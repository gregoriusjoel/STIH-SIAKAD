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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kelas_id')->index('jadwals_kelas_id_foreign');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruangan', 100)->nullable();
            $table->boolean('is_outside_availability')->default(false)->comment('True jika jadwal dibuat di luar ketersediaan waktu dosen');
            $table->string('outside_reason')->nullable()->comment('Alasan jadwal di luar availability: tidak mengisi / tidak cukup / bentrok');
            $table->unsignedBigInteger('kelas_perkuliahan_id')->nullable()->index('jadwals_kelas_perkuliahan_id_foreign');
            $table->unsignedBigInteger('ruangan_id')->nullable()->index();
            $table->enum('status', ['pending', 'approved', 'rejected', 'active'])->default('pending');
            $table->text('catatan_dosen')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable()->index('jadwals_approved_by_foreign');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
