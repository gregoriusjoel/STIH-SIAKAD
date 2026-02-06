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
            if (!Schema::hasColumn('mahasiswas', 'file_ijazah')) {
                $table->json('file_ijazah')->nullable();
            }
            if (!Schema::hasColumn('mahasiswas', 'file_transkrip')) {
                $table->json('file_transkrip')->nullable();
            }
            if (!Schema::hasColumn('mahasiswas', 'file_kk')) {
                $table->json('file_kk')->nullable();
            }
            if (!Schema::hasColumn('mahasiswas', 'file_ktp')) {
                $table->json('file_ktp')->nullable();
            }
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
