<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('actor_id')->nullable()->index();
            $table->string('actor_role', 50)->nullable()->index('idx_audit_actor_role');
            $table->string('action', 100)->index();
            $table->string('module', 100)->nullable()->index('idx_audit_module')->comment('Feature module: akademik, keuangan, magang, skripsi, wisuda, system, auth');
            $table->string('auditable_type');
            $table->unsignedBigInteger('auditable_id');
            $table->longText('meta')->nullable();
            $table->longText('before')->nullable();
            $table->longText('after')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('session_id', 191)->nullable()->comment('Laravel session ID for correlating events within one login session');
            $table->timestamp('created_at')->useCurrentOnUpdate()->useCurrent()->index('idx_audit_created_at');

            $table->index(['auditable_type', 'auditable_id']);
            $table->index(['actor_id', 'action'], 'idx_audit_actor_action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
