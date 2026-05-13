<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add recipient_type and credential_type columns to email_blast_logs for credential blast tracking
     */
    public function up(): void
    {
        if (Schema::hasTable('email_blast_logs')) {
            Schema::table('email_blast_logs', function (Blueprint $table) {
                // Add recipient_type column if not exists
                if (!Schema::hasColumn('email_blast_logs', 'recipient_type')) {
                    $table->enum('recipient_type', ['student', 'parent'])->default('student')->after('error_message');
                }
                
                // Add credential_type column if not exists  
                if (!Schema::hasColumn('email_blast_logs', 'credential_type')) {
                    $table->enum('credential_type', ['none', 'student', 'parents', 'both'])->default('none')->after('recipient_type');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('email_blast_logs')) {
            Schema::table('email_blast_logs', function (Blueprint $table) {
                $table->dropColumn(['recipient_type', 'credential_type']);
            });
        }
    }
};
