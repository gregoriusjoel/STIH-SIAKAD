<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvincesTable extends Migration
{
    public function up()
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('region_code')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('provinces');
    }
}
