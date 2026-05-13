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
        Schema::table('invoices', function (Blueprint $table) {
            // Add flag to track if invoice was auto-generated from KRS submission
            if (!Schema::hasColumn('invoices', 'auto_generated_from_krs')) {
                $table->boolean('auto_generated_from_krs')->default(false)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'auto_generated_from_krs')) {
                $table->dropColumn('auto_generated_from_krs');
            }
        });
    }
};
