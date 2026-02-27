<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string('actor_role', 50)->nullable()->after('actor_id');
            $table->json('before')->nullable()->after('meta');
            $table->json('after')->nullable()->after('before');
            $table->string('ip_address', 45)->nullable()->after('after');
            $table->string('user_agent')->nullable()->after('ip_address');
        });

        // Make actor_id nullable for system-triggered actions
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('actor_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn(['actor_role', 'before', 'after', 'ip_address', 'user_agent']);
        });
    }
};
