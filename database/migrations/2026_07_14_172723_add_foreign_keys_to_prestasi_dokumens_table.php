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
        Schema::table('prestasi_dokumens', function (Blueprint $table) {
            $table->foreign(['prestasi_id'])->references(['id'])->on('prestasis')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['uploaded_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestasi_dokumens', function (Blueprint $table) {
            $table->dropForeign('prestasi_dokumens_prestasi_id_foreign');
            $table->dropForeign('prestasi_dokumens_uploaded_by_foreign');
        });
    }
};
