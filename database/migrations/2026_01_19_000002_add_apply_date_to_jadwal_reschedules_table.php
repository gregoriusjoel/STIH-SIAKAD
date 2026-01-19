<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jadwal_reschedules', function (Blueprint $table) {
            $table->date('apply_date')->nullable()->after('catatan');
            $table->boolean('one_week_only')->default(true)->after('apply_date');
        });
    }

    public function down()
    {
        Schema::table('jadwal_reschedules', function (Blueprint $table) {
            $table->dropColumn(['apply_date', 'one_week_only']);
        });
    }
};
