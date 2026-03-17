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
        Schema::table('parents', function (Blueprint $table) {
            $columnsToDrop = [];

            $columns = [
                'negara_ayah',
                'negara_ibu',
                'negara_wali',
                'alamat_ortu',
                'kota_ortu',
                'provinsi_ortu',
                'kabupaten_ortu',
                'desa_ortu',
                'negara_ortu',
                'handphone_ortu',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('parents', $column)) {
                    $columnsToDrop[] = $column;
                }
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->string('negara_ayah')->nullable();
            $table->string('negara_ibu')->nullable();
            $table->string('negara_wali')->nullable();
            $table->text('alamat_ortu')->nullable();
            $table->string('kota_ortu')->nullable();
            $table->string('provinsi_ortu')->nullable();
            $table->string('kabupaten_ortu')->nullable();
            $table->string('desa_ortu')->nullable();
            $table->string('negara_ortu')->nullable();
            $table->string('handphone_ortu')->nullable();
        });
    }
};
