<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Migrate up - Fix foreign key to reference mahasiswas instead of students
     */
    public function up(): void
    {
        if (Schema::hasTable('installment_requests')) {
            // Get all foreign keys for this table
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_NAME = 'installment_requests'
                AND COLUMN_NAME = 'student_id'
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");

            // Drop existing foreign key if it references 'students' table
            foreach ($foreignKeys as $fk) {
                if ($fk->REFERENCED_TABLE_NAME === 'students') {
                    try {
                        DB::statement("ALTER TABLE installment_requests DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
                    } catch (\Throwable $e) {
                        // Ignore if can't drop
                    }
                }
            }

            // Add foreign key to mahasiswas
            Schema::table('installment_requests', function (Blueprint $table) {
                // Check if constraint already exists to mahasiswas
                $constraints = DB::select("
                    SELECT CONSTRAINT_NAME
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                    WHERE TABLE_NAME = 'installment_requests'
                    AND COLUMN_NAME = 'student_id'
                    AND REFERENCED_TABLE_NAME = 'mahasiswas'
                ");

                if (empty($constraints)) {
                    $table->foreign('student_id')
                        ->references('id')
                        ->on('mahasiswas')
                        ->onDelete('cascade');
                }
            });
        }
    }

    /**
     * Migrate down - Revert to students table
     */
    public function down(): void
    {
        if (Schema::hasTable('installment_requests')) {
            Schema::table('installment_requests', function (Blueprint $table) {
                try {
                    $table->dropForeign(['student_id']);
                } catch (\Throwable $e) {
                    // Ignore if can't drop
                }
            });
        }
    }
};
