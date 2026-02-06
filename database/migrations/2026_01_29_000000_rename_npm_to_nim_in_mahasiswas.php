<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Correctly rename `npm` -> `nim`:
        // 1. add `nim` if missing
        // 2. copy values from `npm` to `nim`
        // 3. add unique index on `nim` only if it does not already exist
        // 4. drop `npm`

        Schema::table('mahasiswas', function (Blueprint $table) {
            if (!Schema::hasColumn('mahasiswas', 'nim')) {
                $table->string('nim')->nullable()->after('user_id');
            }
        });

        // Copy from npm -> nim if npm exists
        if (Schema::hasColumn('mahasiswas', 'npm')) {
            DB::table('mahasiswas')->whereNotNull('npm')->update(['nim' => DB::raw('npm')]);
        }

        // Add unique index only if it doesn't already exist
        $hasUnique = false;
        try {
            // MySQL: check for a non-unique = 0 index on column `nim`
            $indexes = DB::select("SHOW INDEX FROM mahasiswas WHERE Column_name = 'nim'");
            foreach ($indexes as $idx) {
                // Non_unique = 0 indicates unique index
                if (isset($idx->Non_unique) && intval($idx->Non_unique) === 0) {
                    $hasUnique = true;
                    break;
                }
                // older PDO may present array
                if (is_array((array)$idx) && array_key_exists('Non_unique', (array)$idx) && intval(((array)$idx)['Non_unique']) === 0) {
                    $hasUnique = true;
                    break;
                }
            }
        } catch (\Exception $e) {
            // ignore inspection errors — we'll attempt to create and catch duplicate key errors
            $hasUnique = false;
        }

        if (!$hasUnique) {
            try {
                Schema::table('mahasiswas', function (Blueprint $table) {
                    $table->unique('nim');
                });
            } catch (\Illuminate\Database\QueryException $e) {
                // If index already exists (race/previous run), ignore
            }
        }

        // Drop old column `npm` if present
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (Schema::hasColumn('mahasiswas', 'npm')) {
                $table->dropColumn('npm');
            }
        });
    }

    public function down(): void
    {
        // Reverse: re-create `npm`, copy back from `nim`, re-add unique index on `npm` if needed, drop `nim`
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (!Schema::hasColumn('mahasiswas', 'npm')) {
                $table->string('npm')->nullable()->after('user_id');
            }
        });

        if (Schema::hasColumn('mahasiswas', 'nim')) {
            DB::table('mahasiswas')->whereNotNull('nim')->update(['npm' => DB::raw('nim')]);
        }

        // Add unique on npm if missing
        $hasUniqueNpm = false;
        try {
            $indexes = DB::select("SHOW INDEX FROM mahasiswas WHERE Column_name = 'npm'");
            foreach ($indexes as $idx) {
                if (isset($idx->Non_unique) && intval($idx->Non_unique) === 0) {
                    $hasUniqueNpm = true;
                    break;
                }
                if (is_array((array)$idx) && array_key_exists('Non_unique', (array)$idx) && intval(((array)$idx)['Non_unique']) === 0) {
                    $hasUniqueNpm = true;
                    break;
                }
            }
        } catch (\Exception $e) {
            $hasUniqueNpm = false;
        }

        if (!$hasUniqueNpm) {
            try {
                Schema::table('mahasiswas', function (Blueprint $table) {
                    $table->unique('npm');
                });
            } catch (\Illuminate\Database\QueryException $e) {
                // ignore duplicate index error
            }
        }

        Schema::table('mahasiswas', function (Blueprint $table) {
            if (Schema::hasColumn('mahasiswas', 'nim')) {
                $table->dropColumn('nim');
            }
        });
    }
};
