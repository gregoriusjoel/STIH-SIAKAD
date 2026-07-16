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
        Schema::create('skripsi_sidang_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('skripsi_submission_id')->index('skripsi_sidang_schedules_skripsi_submission_id_foreign');
            $table->unsignedBigInteger('sidang_registration_id')->nullable()->index('thesis_sidang_schedules_sidang_registration_id_foreign');
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai')->nullable();
            $table->unsignedBigInteger('ruangan_id')->nullable()->index('thesis_sidang_schedules_ruangan_id_foreign');
            $table->string('ruangan_manual')->nullable();
            $table->unsignedBigInteger('pembimbing_id')->index('thesis_sidang_schedules_pembimbing_id_foreign');
            $table->unsignedBigInteger('penguji_1_id')->index('thesis_sidang_schedules_penguji_1_id_foreign');
            $table->unsignedBigInteger('penguji_2_id')->nullable()->index('thesis_sidang_schedules_penguji_2_id_foreign');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index('thesis_sidang_schedules_created_by_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skripsi_sidang_schedules');
    }
};
