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
        Schema::create('kuesioner_mahasiswa_baru', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mahasiswa_id')->index();
            $table->string('email')->nullable();
            $table->string('prodi')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->smallInteger('angkatan')->nullable();
            $table->tinyInteger('q1')->nullable();
            $table->tinyInteger('q2')->nullable();
            $table->tinyInteger('q3')->nullable();
            $table->tinyInteger('q4')->nullable();
            $table->tinyInteger('q5')->nullable();
            $table->tinyInteger('q6')->nullable();
            $table->tinyInteger('q7')->nullable();
            $table->text('saran')->nullable();
            $table->longText('answers')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuesioner_mahasiswa_baru');
    }
};
