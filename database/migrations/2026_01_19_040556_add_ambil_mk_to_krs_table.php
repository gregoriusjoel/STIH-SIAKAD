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
        Schema::table('krs', function (Blueprint $table) {
            // Add 'ambil_mk' column if it does not exist yet. Do not rely on 'after' position
            if (!Schema::hasColumn('krs', 'ambil_mk')) {
                $table->enum('ambil_mk', ['ya', 'tidak'])->default('ya');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('krs', function (Blueprint $table) {
            if (Schema::hasColumn('krs', 'ambil_mk')) {
                $table->dropColumn('ambil_mk');
            }
        });
    }
};
