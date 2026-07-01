<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds columns needed for enhanced audit trail and performance indexes.
     */
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            // Add 'module' column for grouping audit events by feature area
            if (!Schema::hasColumn('audit_logs', 'module')) {
                $table->string('module', 100)->nullable()->after('action')
                    ->comment('Feature module: akademik, keuangan, magang, skripsi, wisuda, system, auth');
            }

            // Add 'session_id' for correlating all actions within a single login session
            if (!Schema::hasColumn('audit_logs', 'session_id')) {
                $table->string('session_id', 191)->nullable()->after('user_agent')
                    ->comment('Laravel session ID for correlating events within one login session');
            }
        });

        // Add performance indexes
        Schema::table('audit_logs', function (Blueprint $table) {
            // Index for filtering by date range (used heavily in Audit Trail page)
            if (!$this->indexExists('audit_logs', 'idx_audit_created_at')) {
                $table->index('created_at', 'idx_audit_created_at');
            }

            // Composite index for actor + action filtering
            if (!$this->indexExists('audit_logs', 'idx_audit_actor_action')) {
                $table->index(['actor_id', 'action'], 'idx_audit_actor_action');
            }

            // Index for module-based filtering
            if (!$this->indexExists('audit_logs', 'idx_audit_module')) {
                $table->index('module', 'idx_audit_module');
            }

            // Index for actor_role filtering
            if (!$this->indexExists('audit_logs', 'idx_audit_actor_role')) {
                $table->index('actor_role', 'idx_audit_actor_role');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndexIfExists('idx_audit_created_at');
            $table->dropIndexIfExists('idx_audit_actor_action');
            $table->dropIndexIfExists('idx_audit_module');
            $table->dropIndexIfExists('idx_audit_actor_role');

            // Drop columns
            if (Schema::hasColumn('audit_logs', 'session_id')) {
                $table->dropColumn('session_id');
            }
            if (Schema::hasColumn('audit_logs', 'module')) {
                $table->dropColumn('module');
            }
        });
    }

    /**
     * Check if an index exists on a table.
     */
    private function indexExists(string $table, string $indexName): bool
    {
        if (\Illuminate\Support\Facades\DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
            $indexes = \DB::select(
                "SELECT name FROM sqlite_master WHERE type = 'index' AND tbl_name = ? AND name = ?",
                [$table, $indexName]
            );
            return count($indexes) > 0;
        }

        $indexes = \DB::select(
            "SHOW INDEX FROM `{$table}` WHERE Key_name = ?",
            [$indexName]
        );
        return count($indexes) > 0;
    }
};
