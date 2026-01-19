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
        Schema::table('semesters', function (Blueprint $table) {
            $table->boolean('krs_dapat_diisi')->default(false)->after('is_active');
            $table->integer('max_sks_rendah')->default(20)->after('krs_dapat_diisi')->comment('Max SKS untuk IPK < 3.0');
            $table->integer('max_sks_tinggi')->default(24)->after('max_sks_rendah')->comment('Max SKS untuk IPK >= 3.0');
            $table->date('krs_mulai')->nullable()->after('max_sks_tinggi');
            $table->date('krs_selesai')->nullable()->after('krs_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('semesters', function (Blueprint $table) {
            $table->dropColumn(['krs_dapat_diisi', 'max_sks_rendah', 'max_sks_tinggi', 'krs_mulai', 'krs_selesai']);
        });
    }
};
