<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Refactor Kelas Perkuliahan from tingkat-based to angkatan-based system
     * 
     * Changes:
     * 1. Add angkatan column (nullable first)
     * 2. Populate angkatan from tingkat or default to 2000
     * 3. Make angkatan NOT NULL
     * 4. Drop old unique constraint (tingkat-based)
     * 5. Add new unique constraint (angkatan-based)
     * 6. Add performance indexes
     */
    public function up(): void
    {
        Schema::table('kelas_perkuliahans', function (Blueprint $table) {
            // Step 1: Add angkatan column (nullable initially) - only if it doesn't exist
            if (!Schema::hasColumn('kelas_perkuliahans', 'angkatan')) {
                $table->string('angkatan', 4)->nullable()->after('tingkat');
            }
        });

        // Step 2: Populate angkatan values with default '2000'
        DB::statement('UPDATE kelas_perkuliahans SET angkatan = "2000" WHERE angkatan IS NULL OR angkatan = ""');

        // Step 3: Handle duplicate entries (keep the one with highest ID, delete others)
        $sql = <<<SQL
        DELETE kp1 FROM kelas_perkuliahans kp1
        INNER JOIN (
            SELECT angkatan, prodi_id, kode_kelas, tahun_akademik_id, MAX(id) as max_id
            FROM kelas_perkuliahans
            WHERE tahun_akademik_id IS NOT NULL
            GROUP BY angkatan, prodi_id, kode_kelas, tahun_akademik_id
            HAVING COUNT(*) > 1
        ) kp2
        ON kp1.angkatan = kp2.angkatan 
            AND kp1.prodi_id = kp2.prodi_id 
            AND kp1.kode_kelas = kp2.kode_kelas
            AND kp1.tahun_akademik_id = kp2.tahun_akademik_id
        WHERE kp1.id < kp2.max_id
        SQL;
        
        try {
            DB::statement($sql);
        } catch (\Exception $e) {
            // If there are duplicate entries without tahun_akademik_id, that's OK - they won't have unique constraint
            Log::warning('Could not delete duplicate kelas entries: ' . $e->getMessage());
        }

        Schema::table('kelas_perkuliahans', function (Blueprint $table) {
            // Step 4: Make angkatan NOT NULL
            $table->string('angkatan', 4)->nullable(false)->change();

            // Step 5: Drop old unique constraint if exists (using raw SQL with IF EXISTS)
            // This prevents error if constraint doesn't exist
            DB::statement('ALTER TABLE kelas_perkuliahans DROP INDEX IF EXISTS kp_unique_combo');

            // Step 6: Add new unique constraint (only for records with tahun_akademik_id)
            // Note: Partial unique constraints require MySQL 5.7.8+, so we use a regular unique constraint
            // Duplicates with NULL tahun_akademik_id are allowed (multiple NULLs are different in UNIQUE constraints)
            $table->unique(['angkatan', 'prodi_id', 'kode_kelas', 'tahun_akademik_id'], 
                'kp_unique_angkatan_combo');

            // Step 7: Add performance indexes
            $table->index('angkatan', 'idx_kelas_angkatan');
            $table->index('prodi_id', 'idx_kelas_prodi');
            $table->index('kode_kelas', 'idx_kelas_kode');
            $table->index(['angkatan', 'prodi_id', 'kode_kelas'], 'idx_kelas_angkatan_prodi_kode');
        });
    }

    /**
     * Reverse the migrations.
     * Rollback to tingkat-based system
     */
    public function down(): void
    {
        Schema::table('kelas_perkuliahans', function (Blueprint $table) {
            // Remove new indexes if they exist
            $indexes = DB::select("SELECT INDEX_NAME FROM INFORMATION_SCHEMA.STATISTICS 
                WHERE TABLE_NAME = 'kelas_perkuliahans' AND TABLE_SCHEMA = DATABASE()");
            
            $indexNames = collect($indexes)->pluck('INDEX_NAME')->toArray();
            
            if (in_array('idx_kelas_angkatan', $indexNames)) {
                DB::statement('ALTER TABLE kelas_perkuliahans DROP INDEX idx_kelas_angkatan');
            }
            if (in_array('idx_kelas_prodi', $indexNames)) {
                DB::statement('ALTER TABLE kelas_perkuliahans DROP INDEX idx_kelas_prodi');
            }
            if (in_array('idx_kelas_kode', $indexNames)) {
                DB::statement('ALTER TABLE kelas_perkuliahans DROP INDEX idx_kelas_kode');
            }
            if (in_array('idx_kelas_angkatan_prodi_kode', $indexNames)) {
                DB::statement('ALTER TABLE kelas_perkuliahans DROP INDEX idx_kelas_angkatan_prodi_kode');
            }

            // Remove new unique constraint
            DB::statement('ALTER TABLE kelas_perkuliahans DROP INDEX IF EXISTS kp_unique_angkatan_combo');

            // Drop angkatan column
            if (Schema::hasColumn('kelas_perkuliahans', 'angkatan')) {
                $table->dropColumn('angkatan');
            }

            // Restore old unique constraint
            DB::statement('ALTER TABLE kelas_perkuliahans ADD UNIQUE KEY kp_unique_combo 
                (tingkat, kode_prodi, kode_kelas, tahun_akademik_id)');
        });
    }
};
