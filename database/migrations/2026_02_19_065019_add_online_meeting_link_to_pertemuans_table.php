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
        if (Schema::hasTable('pertemuans')) {
            if (!Schema::hasColumn('pertemuans', 'online_meeting_link')) {
                Schema::table('pertemuans', function (Blueprint $table) {
                    $table->string('online_meeting_link')->nullable()->after('metode_pengajaran');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pertemuans')) {
            if (Schema::hasColumn('pertemuans', 'online_meeting_link')) {
                Schema::table('pertemuans', function (Blueprint $table) {
                    $table->dropColumn('online_meeting_link');
                });
            }
        }
    }
};
