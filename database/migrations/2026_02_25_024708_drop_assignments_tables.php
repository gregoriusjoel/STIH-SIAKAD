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
        Schema::dropIfExists('assignment_scores');
        Schema::dropIfExists('assignments');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not needed - these tables are replaced by 'tugas' and 'tugas_submissions'
    }
};
