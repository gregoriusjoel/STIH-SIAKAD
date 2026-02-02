<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas_mata_kuliahs', function (Blueprint $table) {
            if (! Schema::hasColumn('kelas_mata_kuliahs', 'metode_pengajaran')) {
                $table->enum('metode_pengajaran', ['offline','online','asynchronous'])->nullable()->after('jam_selesai');
            }
            if (! Schema::hasColumn('kelas_mata_kuliahs', 'online_link')) {
                $table->string('online_link')->nullable()->after('metode_pengajaran');
            }
            if (! Schema::hasColumn('kelas_mata_kuliahs', 'asynchronous_tugas')) {
                $table->text('asynchronous_tugas')->nullable()->after('online_link');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kelas_mata_kuliahs', function (Blueprint $table) {
            if (Schema::hasColumn('kelas_mata_kuliahs', 'asynchronous_tugas')) {
                $table->dropColumn('asynchronous_tugas');
            }
            if (Schema::hasColumn('kelas_mata_kuliahs', 'online_link')) {
                $table->dropColumn('online_link');
            }
            if (Schema::hasColumn('kelas_mata_kuliahs', 'metode_pengajaran')) {
                $table->dropColumn('metode_pengajaran');
            }
        });
    }
};
