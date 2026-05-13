<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add credential_type column to email_outboxes for tracking credential blast type
     */
    public function up(): void
    {
        if (Schema::hasTable('email_outboxes')) {
            Schema::table('email_outboxes', function (Blueprint $table) {
                // Add credential_type column if not exists
                if (!Schema::hasColumn('email_outboxes', 'credential_type')) {
                    $table->enum('credential_type', ['none', 'student', 'parents', 'both'])->default('none')->after('is_credentials_mode');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('email_outboxes')) {
            Schema::table('email_outboxes', function (Blueprint $table) {
                $table->dropColumn('credential_type');
            });
        }
    }
};
