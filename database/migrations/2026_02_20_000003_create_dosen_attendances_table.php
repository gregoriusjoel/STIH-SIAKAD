<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('dosen_attendances')) {
            Schema::create('dosen_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('dosens')->cascadeOnDelete();
            $table->foreignId('kelas_mata_kuliah_id')->constrained('kelas_mata_kuliahs')->cascadeOnDelete();
            // create column without FK constraint to avoid ordering issues
            $table->unsignedBigInteger('pertemuan_id')->nullable();
            $table->enum('metode_pengajaran', ['offline', 'online', 'asynchronous'])->default('offline');
            $table->time('jam_kelas_mulai')->nullable()->comment('Scheduled class start time');
            $table->time('jam_kelas_selesai')->nullable()->comment('Scheduled class end time');
            $table->dateTime('jam_absen_dosen')->comment('When dosen tapped activate QR');
            $table->string('lokasi_dosen', 500)->nullable()->comment('GPS coords or address');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->unique(['dosen_id', 'pertemuan_id'], 'dosen_attendance_unique');
        });
        }

        // Add foreign key to pertemuans only if that table exists
        if (Schema::hasTable('dosen_attendances') && Schema::hasTable('pertemuans')) {
            Schema::table('dosen_attendances', function (Blueprint $table) {
                $table->foreign('pertemuan_id')->references('id')->on('pertemuans')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen_attendances');
    }
};
