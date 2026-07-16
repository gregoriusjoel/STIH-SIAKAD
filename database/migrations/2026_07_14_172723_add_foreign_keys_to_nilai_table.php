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
        Schema::table('nilai', function (Blueprint $table) {
            $table->foreign(['kelas_id'])->references(['id'])->on('kelas')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['krs_id'])->references(['id'])->on('krs')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['published_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropForeign('nilai_kelas_id_foreign');
            $table->dropForeign('nilai_krs_id_foreign');
            $table->dropForeign('nilai_published_by_foreign');
        });
    }
};
