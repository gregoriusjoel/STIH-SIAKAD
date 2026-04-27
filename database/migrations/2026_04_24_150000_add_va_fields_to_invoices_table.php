<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add optional Virtual Account fields to invoices table.
     * Non-breaking: both columns are nullable.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('bank_name', 50)->nullable()->after('notes');
            $table->string('va_number', 50)->nullable()->after('bank_name');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'va_number']);
        });
    }
};
