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
        Schema::table('jadwals', function (Blueprint $table) {
            $table->boolean('is_outside_availability')->default(false)->after('ruangan')
                ->comment('True jika jadwal dibuat di luar ketersediaan waktu dosen');
            $table->string('outside_reason')->nullable()->after('is_outside_availability')
                ->comment('Alasan jadwal di luar availability: tidak mengisi / tidak cukup / bentrok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropColumn(['is_outside_availability', 'outside_reason']);
        });
    }
};
