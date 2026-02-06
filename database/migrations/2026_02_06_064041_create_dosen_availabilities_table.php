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
        Schema::create('dosen_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('dosens')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'])->comment('Hari tersedia');
            $table->foreignId('jam_perkuliahan_id')->constrained('jam_perkuliahan')->onDelete('cascade');
            $table->enum('status', ['available', 'booked', 'blocked'])->default('available')->comment('Status ketersediaan');
            $table->text('notes')->nullable()->comment('Catatan dari dosen');
            $table->timestamps();
            
            // Unique constraint: satu dosen tidak bisa submit slot yang sama 2x untuk semester yang sama
            $table->unique(['dosen_id', 'semester_id', 'hari', 'jam_perkuliahan_id'], 'unique_dosen_slot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_availabilities');
    }
};
