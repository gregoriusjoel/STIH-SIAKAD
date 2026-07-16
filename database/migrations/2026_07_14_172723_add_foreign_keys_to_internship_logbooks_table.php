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
        Schema::table('internship_logbooks', function (Blueprint $table) {
            $table->foreign(['internship_id'])->references(['id'])->on('internships')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internship_logbooks', function (Blueprint $table) {
            $table->dropForeign('internship_logbooks_internship_id_foreign');
        });
    }
};
