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
        Schema::create('presensis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mahasiswa_id')->nullable()->index('presensis_mahasiswa_id_foreign');
            $table->unsignedBigInteger('kelas_mata_kuliah_id')->nullable()->index('presensis_kelas_mata_kuliah_id_foreign');
            $table->unsignedInteger('pertemuan')->nullable();
            $table->string('nama')->nullable();
            $table->string('kontak')->nullable();
            $table->timestamp('waktu')->nullable();
            $table->unsignedBigInteger('krs_id')->index('presensis_krs_id_foreign');
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa']);
            $table->text('keterangan')->nullable();
            $table->decimal('student_lat', 10, 7)->nullable();
            $table->decimal('student_lng', 10, 7)->nullable();
            $table->integer('distance_meters')->nullable();
            $table->enum('presence_mode', ['offline', 'online'])->nullable();
            $table->string('reason_category')->nullable();
            $table->text('reason_detail')->nullable();
            $table->decimal('campus_lat', 10, 7)->default(-6.311252);
            $table->decimal('campus_lng', 10, 7)->default(106.811174);
            $table->integer('radius_meters')->default(100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
