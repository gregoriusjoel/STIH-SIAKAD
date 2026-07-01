<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the impersonation_logs table for tracking Super Admin impersonation sessions.
     */
    public function up(): void
    {
        Schema::create('impersonation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('impersonator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('target_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('target_role', 50)->nullable()
                ->comment('Role of the target user at time of impersonation');
            $table->text('reason')->nullable()
                ->comment('Reason provided by Super Admin for impersonation');
            $table->timestamp('started_at')
                ->comment('When impersonation session began');
            $table->timestamp('ended_at')->nullable()
                ->comment('When impersonation session ended (null if still active)');
            $table->unsignedInteger('duration_seconds')->nullable()
                ->comment('Duration in seconds (calculated on stop)');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();

            // Indexes
            $table->index('impersonator_id');
            $table->index('target_user_id');
            $table->index('started_at');
            $table->index(['ended_at', 'started_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('impersonation_logs');
    }
};
