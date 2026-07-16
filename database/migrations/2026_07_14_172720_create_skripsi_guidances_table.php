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
        Schema::create('skripsi_guidances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('skripsi_submission_id')->index('skripsi_guidances_skripsi_submission_id_foreign');
            $table->unsignedBigInteger('dosen_id')->index('thesis_guidances_dosen_id_foreign');
            $table->date('tanggal_bimbingan');
            $table->text('catatan');
            $table->string('file_path')->nullable();
            $table->string('status')->default('pending');
            $table->text('catatan_dosen')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skripsi_guidances');
    }
};
