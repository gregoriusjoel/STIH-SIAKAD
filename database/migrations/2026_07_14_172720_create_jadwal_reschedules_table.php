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
        Schema::create('jadwal_reschedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('jadwal_id')->index('jadwal_reschedules_jadwal_id_foreign');
            $table->unsignedBigInteger('dosen_id')->index('jadwal_reschedules_dosen_id_foreign');
            $table->string('old_hari');
            $table->time('old_jam_mulai')->nullable();
            $table->time('old_jam_selesai')->nullable();
            $table->string('new_hari');
            $table->time('new_jam_mulai');
            $table->time('new_jam_selesai');
            $table->text('catatan')->nullable();
            $table->date('apply_date')->nullable();
            $table->boolean('one_week_only')->default(true);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_reschedules');
    }
};
