<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kuesioner_mahasiswa_baru', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id')->index();
            $table->json('answers')->nullable();
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kuesioner_mahasiswa_baru');
    }
};
