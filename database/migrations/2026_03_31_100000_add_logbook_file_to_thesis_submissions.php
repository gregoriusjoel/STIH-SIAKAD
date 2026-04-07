<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('thesis_submissions', function (Blueprint $table) {
            $table->string('logbook_file_path')->nullable()->after('total_bimbingan');
            $table->string('logbook_original_name')->nullable()->after('logbook_file_path');
            $table->timestamp('logbook_uploaded_at')->nullable()->after('logbook_original_name');
        });
    }

    public function down(): void
    {
        Schema::table('thesis_submissions', function (Blueprint $table) {
            $table->dropColumn(['logbook_file_path', 'logbook_original_name', 'logbook_uploaded_at']);
        });
    }
};
