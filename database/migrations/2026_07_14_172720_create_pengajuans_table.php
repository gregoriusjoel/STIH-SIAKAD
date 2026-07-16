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
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->string('jenis')->index('idx_pengajuans_jenis');
            $table->text('keterangan')->nullable();
            $table->longText('payload_template')->nullable();
            $table->string('status')->default('pending');
            $table->string('file_path')->nullable();
            $table->string('generated_doc_path')->nullable();
            $table->string('signed_doc_path')->nullable();
            $table->text('admin_note')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->unsignedSmallInteger('revision_no')->default(0);
            $table->unsignedBigInteger('approved_by')->nullable()->index('pengajuans_approved_by_foreign');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->string('nomor_surat')->nullable();
            $table->string('file_surat')->nullable();
            $table->timestamps();

            $table->index(['mahasiswa_id', 'status'], 'idx_pengajuans_mhs_status');
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
