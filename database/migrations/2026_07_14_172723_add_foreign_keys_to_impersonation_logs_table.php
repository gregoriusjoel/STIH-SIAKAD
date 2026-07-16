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
        Schema::table('impersonation_logs', function (Blueprint $table) {
            $table->foreign(['impersonator_id'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['target_user_id'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('impersonation_logs', function (Blueprint $table) {
            $table->dropForeign('impersonation_logs_impersonator_id_foreign');
            $table->dropForeign('impersonation_logs_target_user_id_foreign');
        });
    }
};
