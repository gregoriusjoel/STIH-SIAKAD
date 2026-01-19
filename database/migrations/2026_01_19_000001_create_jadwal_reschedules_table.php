<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jadwal_reschedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwals')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->string('old_hari');
            $table->time('old_jam_mulai')->nullable();
            $table->time('old_jam_selesai')->nullable();
            $table->string('new_hari');
            $table->time('new_jam_mulai');
            $table->time('new_jam_selesai');
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal_reschedules');
    }
};
