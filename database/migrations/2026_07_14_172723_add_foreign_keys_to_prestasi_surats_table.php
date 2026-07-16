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
        Schema::table('prestasi_surats', function (Blueprint $table) {
            $table->foreign(['generated_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['prestasi_id'])->references(['id'])->on('prestasis')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestasi_surats', function (Blueprint $table) {
            $table->dropForeign('prestasi_surats_generated_by_foreign');
            $table->dropForeign('prestasi_surats_prestasi_id_foreign');
        });
    }
};
