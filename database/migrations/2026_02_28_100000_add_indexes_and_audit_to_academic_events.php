<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add composite indexes and audit columns to academic_events table.
 * These are minimal additions to support cached period queries + audit trail.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('academic_events', function (Blueprint $table) {
            // Audit trail columns
            $table->unsignedBigInteger('created_by')->nullable()->after('is_active');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');

            // Composite index for period lookups: isActive → type → date range
            $table->index(['is_active', 'event_type', 'start_date', 'end_date'], 'ae_active_type_dates_idx');

            // Index for semester + type queries
            $table->index(['semester_id', 'event_type'], 'ae_semester_type_idx');
        });
    }

    public function down(): void
    {
        Schema::table('academic_events', function (Blueprint $table) {
            $table->dropIndex('ae_active_type_dates_idx');
            $table->dropIndex('ae_semester_type_idx');
            $table->dropColumn(['created_by', 'updated_by']);
        });
    }
};
