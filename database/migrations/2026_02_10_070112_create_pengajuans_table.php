<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->string('jenis'); // e.g., 'cuti', 'surat_aktif'
            $table->text('keterangan')->nullable();
            $table->string('status')->default('pending'); // pending, disetujui, ditolak
            $table->string('file_path')->nullable(); // For supporting documents
            $table->text('admin_note')->nullable(); // Feedback from admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};
