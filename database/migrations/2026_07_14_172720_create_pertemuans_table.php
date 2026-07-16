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
        Schema::create('pertemuans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kelas_mata_kuliah_id')->nullable()->index();
            $table->unsignedInteger('nomor_pertemuan');
            $table->enum('tipe_pertemuan', ['kuliah', 'uts', 'uas'])->default('kuliah')->comment('Meeting type: kuliah (regular), uts (midterm), uas (final)');
            $table->date('tanggal')->nullable();
            $table->string('topik')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('metode_pengajaran', ['offline', 'online', 'asynchronous'])->default('offline');
            $table->string('online_meeting_link')->nullable();
            $table->string('qr_token', 100)->nullable()->unique();
            $table->boolean('qr_enabled')->default(false);
            $table->dateTime('qr_expires_at')->nullable();
            $table->dateTime('qr_generated_at')->nullable();
            $table->string('status')->default('scheduled');
            $table->timestamps();

            $table->index(['kelas_mata_kuliah_id', 'nomor_pertemuan']);
            $table->index(['kelas_mata_kuliah_id', 'tipe_pertemuan', 'nomor_pertemuan'], 'pertemuans_kmk_tipe_nomor_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertemuans');
    }
};
