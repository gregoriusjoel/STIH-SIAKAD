<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new columns if not present
        Schema::table('krs', function (Blueprint $table) {
            if (!Schema::hasColumn('krs', 'mata_kuliah_id')) {
                $table->foreignId('mata_kuliah_id')->nullable()->constrained('mata_kuliahs')->onDelete('cascade');
            }
            if (!Schema::hasColumn('krs', 'kelas_mata_kuliah_id')) {
                if (Schema::hasTable('kelas_mata_kuliahs')) {
                    $table->foreignId('kelas_mata_kuliah_id')->nullable()->constrained('kelas_mata_kuliahs')->onDelete('cascade');
                } else {
                    $table->unsignedBigInteger('kelas_mata_kuliah_id')->nullable();
                }
            }
            if (!Schema::hasColumn('krs', 'ambil_mk')) {
                $table->enum('ambil_mk', ['ya','tidak'])->default('tidak')->after('keterangan');
            }
            // Make kelas_id nullable if not already
            if (Schema::hasColumn('krs', 'kelas_id')) {
                $table->unsignedBigInteger('kelas_id')->nullable()->change();
            }
        });

        // Update status enum to match current system values
        // MySQL requires raw statement to change enum types
        $driver = config('database.default');
        if ($driver === 'mysql') {
            // Step 1: temporarily make `status` a varchar so we can migrate values safely
            DB::statement("ALTER TABLE `krs` MODIFY `status` VARCHAR(50) NOT NULL DEFAULT 'draft'");

            // Step 2: normalize/migrate old values to the new set
            // Map older values to new ones where applicable
            DB::statement("UPDATE `krs` SET `status` = 'draft' WHERE `status` = 'pending'");
            DB::statement("UPDATE `krs` SET `status` = 'approved' WHERE `status` = 'disetujui'");
            DB::statement("UPDATE `krs` SET `status` = 'rejected' WHERE `status` = 'ditolak'");
            // leave values like 'diajukan','draft','approved','rejected' untouched

            // Step 3: enforce new enum type
            DB::statement("ALTER TABLE `krs` MODIFY `status` ENUM('draft','sudah submit','approved','rejected') NOT NULL DEFAULT 'draft'");
        } else {
            // For other DBs, attempt a safe fallback: add column if missing
            if (!Schema::hasColumn('krs', 'status')) {
                Schema::table('krs', function (Blueprint $table) {
                    $table->string('status')->default('draft');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('krs', function (Blueprint $table) {
            if (Schema::hasColumn('krs', 'mata_kuliah_id')) {
                $table->dropConstrainedForeignId('mata_kuliah_id');
            }
            if (Schema::hasColumn('krs', 'kelas_mata_kuliah_id')) {
                // drop foreign if constraint exists; otherwise drop column
                try {
                    $table->dropConstrainedForeignId('kelas_mata_kuliah_id');
                } catch (\Throwable $e) {
                    $table->dropColumn('kelas_mata_kuliah_id');
                }
            }
            if (Schema::hasColumn('krs', 'ambil_mk')) {
                $table->dropColumn('ambil_mk');
            }
            // revert kelas_id nullable change is non-trivial; leave as-is
        });

        // revert status change: best-effort set back to original enum
        $driver = config('database.default');
        if ($driver === 'mysql') {
            try {
                DB::statement("ALTER TABLE `krs` MODIFY `status` ENUM('pending','disetujui','ditolak') NOT NULL DEFAULT 'pending'");
            } catch (\Throwable $e) {
                // ignore
            }
        }
    }
};
