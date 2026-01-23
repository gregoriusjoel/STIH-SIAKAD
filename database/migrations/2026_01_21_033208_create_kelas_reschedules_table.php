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
            $table->id();
            $table->foreignId('kelas_mata_kuliah_id')->constrained('kelas_mata_kuliahs')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('dosens')->onDelete('cascade');

            // Original schedule
            $table->string('old_hari');
            $table->time('old_jam_mulai')->nullable();
            $table->time('old_jam_selesai')->nullable();
            
            // New schedule request
            $table->string('new_hari');
            $table->time('new_jam_mulai');
            $table->time('new_jam_selesai');
            $table->string('new_ruang')->nullable();
            
            // Week info - reschedule applies only to this specific week
            $table->date('week_start'); // The Monday of the week this reschedule applies to
            $table->date('week_end');   // The Saturday of the week
            
            // Status: pending, approved, rejected, room_assigned
            $table->enum('status', ['pending', 'approved', 'room_assigned', 'rejected'])->default('pending');
            
            $table->text('catatan_dosen')->nullable();
            $table->text('catatan_admin')->nullable();
            
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
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
