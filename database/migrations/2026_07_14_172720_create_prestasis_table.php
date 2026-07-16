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
        Schema::create('prestasis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('tipe', ['pengajuan', 'pelaporan'])->default('pengajuan');
            $table->string('pengaju_type');
            $table->unsignedBigInteger('pengaju_id');
            $table->string('nama_kegiatan');
            $table->string('jenis_kegiatan')->default('akademik');
            $table->enum('tingkat_kegiatan', ['internal', 'regional', 'nasional', 'internasional'])->default('nasional')->index();
            $table->string('tempat_kegiatan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('penyelenggara');
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('dosen_pendamping_id')->nullable()->index();
            $table->string('jenis_prestasi')->nullable();
            $table->string('nomor_sertifikat')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status')->default('draft')->index();
            $table->unsignedBigInteger('approved_by')->nullable()->index('prestasis_approved_by_foreign');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('admin_note')->nullable();
            $table->longText('tags')->nullable();
            $table->string('external_ref')->nullable();
            $table->string('hash_kegiatan')->nullable()->index();
            $table->timestamps();

            $table->index(['pengaju_type', 'pengaju_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasis');
    }
};
