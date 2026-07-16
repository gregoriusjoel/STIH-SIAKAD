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
        Schema::create('kelas_reschedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kelas_mata_kuliah_id')->index('kelas_reschedules_kelas_mata_kuliah_id_foreign');
            $table->unsignedBigInteger('dosen_id')->index('kelas_reschedules_dosen_id_foreign');
            $table->string('old_hari');
            $table->time('old_jam_mulai')->nullable();
            $table->time('old_jam_selesai')->nullable();
            $table->string('new_hari');
            $table->time('new_jam_mulai');
            $table->time('new_jam_selesai');
            $table->string('new_ruang')->nullable();
            $table->string('new_kelas', 50)->nullable();
            $table->string('metode_pengajaran')->nullable();
            $table->string('online_link')->nullable();
            $table->text('asynchronous_tugas')->nullable();
            $table->string('asynchronous_file')->nullable();
            $table->date('week_start');
            $table->date('week_end');
            $table->enum('status', ['pending', 'approved', 'room_assigned', 'rejected'])->default('pending');
            $table->text('catatan_dosen')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable()->index('kelas_reschedules_approved_by_foreign');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_reschedules');
    }
};
