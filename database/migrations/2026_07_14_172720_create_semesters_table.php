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
        Schema::create('semesters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('nama_semester', ['Ganjil', 'Genap']);
            $table->string('nama_semester_old')->nullable();
            $table->string('tahun_ajaran');
            $table->enum('status', ['aktif', 'non-aktif'])->default('non-aktif');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->dateTime('locked_at')->nullable();
            $table->unsignedBigInteger('locked_by')->nullable()->index('semesters_locked_by_foreign');
            $table->boolean('krs_dapat_diisi')->default(false);
            $table->integer('max_sks_rendah')->default(20)->comment('Max SKS untuk IPK < 3.0');
            $table->integer('max_sks_tinggi')->default(24)->comment('Max SKS untuk IPK >= 3.0');
            $table->date('krs_mulai')->nullable();
            $table->date('krs_selesai')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->timestamps();

            $table->unique(['nama_semester', 'tahun_ajaran', 'tanggal_mulai'], 'semesters_nama_tahun_tanggal_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semesters');
    }
};
