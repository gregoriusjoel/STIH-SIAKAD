<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengajuan_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')
                ->constrained('pengajuans')
                ->cascadeOnDelete();
            $table->unsignedSmallInteger('revision_no');
            $table->string('signed_doc_path');         // dokumen yang di-upload mahasiswa
            $table->text('note_from_admin')->nullable(); // catatan admin saat reject
            $table->text('note_from_mahasiswa')->nullable(); // keterangan revisi mahasiswa
            $table->timestamps();

            $table->index(['pengajuan_id', 'revision_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_revisions');
    }
};
