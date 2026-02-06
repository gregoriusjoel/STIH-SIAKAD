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
        Schema::table('parents', function (Blueprint $table) {
            // Add desa_ortu if not exists (no positioning to avoid missing-column errors)
            if (!Schema::hasColumn('parents', 'desa_ortu')) {
                $table->string('desa_ortu')->nullable();
            }
            // Add negara_ortu if not exists
            if (!Schema::hasColumn('parents', 'negara_ortu')) {
                $table->string('negara_ortu')->nullable();
            }
            // Add desa_wali if not exists
            if (!Schema::hasColumn('parents', 'desa_wali')) {
                $table->string('desa_wali')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parents', function (Blueprint $table) {
            if (Schema::hasColumn('parents', 'desa_ortu')) {
                $table->dropColumn('desa_ortu');
            }
            if (Schema::hasColumn('parents', 'negara_ortu')) {
                $table->dropColumn('negara_ortu');
            }
            if (Schema::hasColumn('parents', 'desa_wali')) {
                $table->dropColumn('desa_wali');
            }
        });
    }
};
