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
        Schema::table('kelas_reschedules', function (Blueprint $table) {
            $table->string('metode_pengajaran')->nullable()->after('new_kelas');
            $table->string('online_link')->nullable()->after('metode_pengajaran');
            $table->text('asynchronous_tugas')->nullable()->after('online_link');
            $table->string('asynchronous_file')->nullable()->after('asynchronous_tugas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_reschedules', function (Blueprint $table) {
            $table->dropColumn(['metode_pengajaran', 'online_link', 'asynchronous_tugas', 'asynchronous_file']);
        });
    }
};
