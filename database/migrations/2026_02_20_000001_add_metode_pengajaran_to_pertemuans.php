<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pertemuans')) {
            Schema::table('pertemuans', function (Blueprint $table) {
                if (!Schema::hasColumn('pertemuans', 'metode_pengajaran')) {
                    $table->enum('metode_pengajaran', ['offline', 'online', 'asynchronous'])
                          ->default('offline')
                          ->after('topik');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pertemuans')) {
            Schema::table('pertemuans', function (Blueprint $table) {
                if (Schema::hasColumn('pertemuans', 'metode_pengajaran')) {
                    $table->dropColumn('metode_pengajaran');
                }
            });
        }
    }
};
