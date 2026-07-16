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
        Schema::table('jadwal_approvals', function (Blueprint $table) {
            $table->foreign(['approved_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['jadwal_proposal_id'])->references(['id'])->on('jadwal_proposals')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_approvals', function (Blueprint $table) {
            $table->dropForeign('jadwal_approvals_approved_by_foreign');
            $table->dropForeign('jadwal_approvals_jadwal_proposal_id_foreign');
        });
    }
};
