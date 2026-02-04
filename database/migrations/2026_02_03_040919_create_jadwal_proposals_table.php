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
            $table->id();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('dosens')->onDelete('cascade');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruangan', 100)->nullable();
            $table->enum('status', [
                'pending_dosen',
                'approved_dosen', 
                'rejected_dosen',
                'pending_admin',
                'approved_admin',
                'rejected_admin'
            ])->default('pending_dosen');
            $table->text('catatan_generate')->nullable()->comment('Catatan dari sistem auto generate');
            $table->foreignId('generated_by')->constrained('users')->comment('Admin yang melakukan auto generate');
            $table->timestamp('generated_at')->useCurrent();
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index(['status', 'dosen_id']);
            $table->index(['mata_kuliah_id', 'hari', 'jam_mulai']);
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
