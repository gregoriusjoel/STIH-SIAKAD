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
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->json('file_ijazah')->nullable()->after('negara');
            $table->json('file_transkrip')->nullable()->after('file_ijazah');
            $table->json('file_kk')->nullable()->after('file_transkrip');
            $table->json('file_ktp')->nullable()->after('file_kk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn(['file_ijazah', 'file_transkrip', 'file_kk', 'file_ktp']);
        });
    }
};
