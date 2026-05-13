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
        Schema::table('krs', function (Blueprint $table) {
            // Drop the foreign key constraint if it exists
            $table->dropForeign(['semester_id']);
            // Drop the column
            $table->dropColumn('semester_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('krs', function (Blueprint $table) {
            // Restore the column and foreign key
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('cascade');
        });
    }
};
