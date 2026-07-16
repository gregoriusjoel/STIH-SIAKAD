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
        Schema::create('dosen_availabilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dosen_id');
            $table->unsignedBigInteger('semester_id')->index('dosen_availabilities_semester_id_foreign');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'])->comment('Hari tersedia');
            $table->unsignedBigInteger('jam_perkuliahan_id')->index('dosen_availabilities_jam_perkuliahan_id_foreign');
            $table->enum('status', ['available', 'booked', 'blocked'])->default('available')->comment('Status ketersediaan');
            $table->text('notes')->nullable()->comment('Catatan dari dosen');
            $table->timestamps();

            $table->unique(['dosen_id', 'semester_id', 'hari', 'jam_perkuliahan_id'], 'unique_dosen_slot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_availabilities');
    }
};
