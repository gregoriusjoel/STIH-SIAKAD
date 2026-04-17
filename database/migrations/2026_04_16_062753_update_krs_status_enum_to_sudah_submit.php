<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = config('database.default');
        if ($driver === 'mysql') {
            // Migrate 'diajukan' to 'sudah submit' and update enum
            DB::statement("UPDATE `krs` SET `status` = 'sudah submit' WHERE `status` = 'diajukan'");
            DB::statement("ALTER TABLE `krs` MODIFY `status` ENUM('draft','sudah submit','approved','rejected') NOT NULL DEFAULT 'draft'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = config('database.default');
        if ($driver === 'mysql') {
            // Revert back
            DB::statement("UPDATE `krs` SET `status` = 'diajukan' WHERE `status` = 'sudah submit'");
            DB::statement("ALTER TABLE `krs` MODIFY `status` ENUM('draft','diajukan','approved','rejected') NOT NULL DEFAULT 'draft'");
        }
    }
};

