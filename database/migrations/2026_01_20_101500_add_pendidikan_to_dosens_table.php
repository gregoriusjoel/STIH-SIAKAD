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
        Schema::table('dosens', function (Blueprint $table) {
            if (!Schema::hasColumn('dosens', 'pendidikan')) {
                $table->string('pendidikan')->nullable()->after('nidn');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            if (Schema::hasColumn('dosens', 'pendidikan')) {
                $table->dropColumn('pendidikan');
            }
        });
    }
};
