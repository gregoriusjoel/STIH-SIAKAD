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
            $table->string('new_kelas', 50)->nullable()->after('new_ruang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_reschedules', function (Blueprint $table) {
            $table->dropColumn('new_kelas');
        });
    }
};
