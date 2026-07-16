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
        Schema::create('jadwal_proposals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mata_kuliah_id');
            $table->unsignedBigInteger('kelas_id')->index('jadwal_proposals_kelas_id_foreign');
            $table->unsignedBigInteger('dosen_id')->index('jadwal_proposals_dosen_id_foreign');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruangan', 100)->nullable();
            $table->boolean('is_outside_availability')->default(false)->comment('True jika jadwal dibuat di luar ketersediaan waktu dosen');
            $table->string('outside_reason')->nullable()->comment('Alasan jadwal di luar availability: tidak mengisi / tidak cukup / bentrok');
            $table->unsignedBigInteger('ruangan_id')->nullable()->index();
            $table->enum('status', ['pending_dosen', 'approved_dosen', 'rejected_dosen', 'pending_admin', 'approved_admin', 'rejected_admin'])->default('pending_dosen');
            $table->text('catatan_generate')->nullable()->comment('Catatan dari sistem auto generate');
            $table->unsignedBigInteger('generated_by')->index('jadwal_proposals_generated_by_foreign');
            $table->timestamp('generated_at')->useCurrent();
            $table->timestamps();

            $table->index(['mata_kuliah_id', 'hari', 'jam_mulai']);
            $table->index(['status', 'dosen_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_proposals');
    }
};
