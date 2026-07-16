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
        Schema::table('skripsi_sidang_files', function (Blueprint $table) {
            $table->foreign(['sidang_registration_id'], 'thesis_sidang_files_sidang_registration_id_foreign')->references(['id'])->on('skripsi_sidang_registrations')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skripsi_sidang_files', function (Blueprint $table) {
            $table->dropForeign('thesis_sidang_files_sidang_registration_id_foreign');
        });
    }
};
