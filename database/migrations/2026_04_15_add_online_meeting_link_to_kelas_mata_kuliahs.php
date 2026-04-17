<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas_mata_kuliahs', function (Blueprint $table) {
            if (!Schema::hasColumn('kelas_mata_kuliahs', 'online_meeting_link')) {
                $table->string('online_meeting_link')->nullable()->after('metode_pengajaran');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kelas_mata_kuliahs', function (Blueprint $table) {
            if (Schema::hasColumn('kelas_mata_kuliahs', 'online_meeting_link')) {
                $table->dropColumn('online_meeting_link');
            }
        });
    }
};
