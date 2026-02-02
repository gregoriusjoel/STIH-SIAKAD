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
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (!Schema::hasColumn('mahasiswas', 'desa')) {
                $table->string('desa')->nullable()->after('kota');
            }
            if (!Schema::hasColumn('mahasiswas', 'provinsi')) {
                $table->string('provinsi')->nullable()->after('propinsi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (Schema::hasColumn('mahasiswas', 'desa')) {
                $table->dropColumn('desa');
            }
            if (Schema::hasColumn('mahasiswas', 'provinsi')) {
                $table->dropColumn('provinsi');
            }
        });
    }
};
