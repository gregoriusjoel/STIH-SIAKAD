<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas_mata_kuliahs', function (Blueprint $table) {
            $table->string('asynchronous_file')->nullable()->after('asynchronous_tugas');
        });
    }

    public function down(): void
    {
        Schema::table('kelas_mata_kuliahs', function (Blueprint $table) {
            $table->dropColumn('asynchronous_file');
        });
    }
};
