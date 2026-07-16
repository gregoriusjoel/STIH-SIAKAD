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
        Schema::create('prestasi_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('prestasi_id')->index();
            $table->string('action')->index();
            $table->string('from_status')->nullable();
            $table->string('to_status')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index('prestasi_logs_user_id_foreign');
            $table->longText('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi_logs');
    }
};
