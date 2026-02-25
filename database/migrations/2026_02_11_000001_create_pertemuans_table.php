<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pertemuans')) {
            return; // Already exists (e.g. from a previous partial migration)
        }

        Schema::create('pertemuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_mata_kuliah_id')
                  ->nullable()
                  ->constrained('kelas_mata_kuliahs')
                  ->nullOnDelete();
            $table->unsignedInteger('nomor_pertemuan');
            $table->date('tanggal')->nullable();
            $table->string('topik')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('metode_pengajaran', ['offline', 'online', 'asynchronous'])
                  ->default('offline');
            $table->string('online_meeting_link')->nullable();
            $table->string('qr_token', 100)->nullable()->unique();
            $table->boolean('qr_enabled')->default(false);
            $table->dateTime('qr_expires_at')->nullable();
            $table->dateTime('qr_generated_at')->nullable();
            $table->string('status')->default('scheduled');
            $table->timestamps();

            $table->index('kelas_mata_kuliah_id');
            $table->index(['kelas_mata_kuliah_id', 'nomor_pertemuan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertemuans');
    }
};
