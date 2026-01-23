<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHubunganPekerjaanToParents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('parents', 'hubungan')) {
            Schema::table('parents', function (Blueprint $table) {
                $table->string('hubungan')->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('parents', 'pekerjaan')) {
            Schema::table('parents', function (Blueprint $table) {
                $table->string('pekerjaan')->nullable()->after('hubungan');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('parents', 'pekerjaan')) {
            Schema::table('parents', function (Blueprint $table) {
                $table->dropColumn('pekerjaan');
            });
        }

        if (Schema::hasColumn('parents', 'hubungan')) {
            Schema::table('parents', function (Blueprint $table) {
                $table->dropColumn('hubungan');
            });
        }
    }
}
