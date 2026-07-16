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
        Schema::table('wisuda_documents', function (Blueprint $table) {
            $table->foreign(['wisuda_registration_id'])->references(['id'])->on('wisuda_registrations')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wisuda_documents', function (Blueprint $table) {
            $table->dropForeign('wisuda_documents_wisuda_registration_id_foreign');
        });
    }
};
