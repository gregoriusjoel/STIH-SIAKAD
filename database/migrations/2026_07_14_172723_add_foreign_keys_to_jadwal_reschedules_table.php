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
        Schema::table('jadwal_reschedules', function (Blueprint $table) {
            $table->foreign(['dosen_id'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['jadwal_id'])->references(['id'])->on('jadwals')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_reschedules', function (Blueprint $table) {
            $table->dropForeign('jadwal_reschedules_dosen_id_foreign');
            $table->dropForeign('jadwal_reschedules_jadwal_id_foreign');
        });
    }
};
