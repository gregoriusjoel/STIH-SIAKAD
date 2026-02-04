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
        Schema::create('jadwal_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_proposal_id')->constrained('jadwal_proposals')->onDelete('cascade');
            $table->foreignId('approved_by')->constrained('users');
            $table->enum('role', ['dosen', 'admin']);
            $table->enum('action', ['approve', 'reject']);
            $table->text('alasan_penolakan')->nullable();
            
            // Usulan perubahan dari dosen (jika reject)
            $table->enum('hari_pengganti', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'])->nullable();
            $table->time('jam_mulai_pengganti')->nullable();
            $table->time('jam_selesai_pengganti')->nullable();
            $table->string('ruangan_pengganti', 100)->nullable();
            
            $table->timestamp('approved_at')->useCurrent();
            $table->timestamps();
            
            // Index untuk query performa
            $table->index(['jadwal_proposal_id', 'role']);
            $table->index(['approved_by', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_approvals');
    }
};
