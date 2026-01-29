<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add new nullable 'nim' column, copy values from 'nim', add unique index, then drop 'nim'
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (!Schema::hasColumn('mahasiswas', 'nim')) {
                $table->string('nim')->nullable()->after('user_id');
            }
        });

        // Copy data from nim to nim
        DB::table('mahasiswas')->whereNotNull('nim')->update(['nim' => DB::raw('nim')]);

        // Make nim unique and not nullable
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (!Schema::hasColumn('mahasiswas', 'nim_unique_added')) {
                // add unique index
                $table->unique('nim');
            }
        });

        // Drop nim column
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (Schema::hasColumn('mahasiswas', 'nim')) {
                $table->dropColumn('nim');
            }
        });
    }

    public function down(): void
    {
        // Reverse: add nim, copy from nim, drop nim
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (!Schema::hasColumn('mahasiswas', 'nim')) {
                $table->string('nim')->nullable()->after('user_id');
            }
        });

        DB::table('mahasiswas')->whereNotNull('nim')->update(['nim' => DB::raw('nim')]);

        Schema::table('mahasiswas', function (Blueprint $table) {
            if (Schema::hasColumn('mahasiswas', 'nim') && !Schema::hasColumn('mahasiswas', 'nim_unique_added')) {
                $table->unique('nim');
            }
        });

        Schema::table('mahasiswas', function (Blueprint $table) {
            if (Schema::hasColumn('mahasiswas', 'nim')) {
                $table->dropColumn('nim');
            }
        });
    }
};
