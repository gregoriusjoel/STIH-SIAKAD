<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kelas_mata_kuliahs', function (Blueprint $table) {
            $table->string('qr_token')->nullable()->unique()->after('ruang');
            $table->boolean('qr_enabled')->default(false)->after('qr_token');
            $table->timestamp('qr_expires_at')->nullable()->after('qr_enabled');
        });
    }

    public function down()
    {
        Schema::table('kelas_mata_kuliahs', function (Blueprint $table) {
            $table->dropColumn(['qr_token', 'qr_enabled', 'qr_expires_at']);
        });
    }
};
