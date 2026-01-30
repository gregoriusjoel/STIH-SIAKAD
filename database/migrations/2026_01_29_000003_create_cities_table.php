<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('province_id')->nullable();
            $table->string('name');
            $table->string('code')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
