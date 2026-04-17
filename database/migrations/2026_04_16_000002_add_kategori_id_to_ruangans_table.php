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
        Schema::table('ruangans', function (Blueprint $table) {
            if (!Schema::hasColumn('ruangans', 'kategori_id')) {
                $table->foreignId('kategori_id')
                    ->nullable()
                    ->after('status')
                    ->constrained('kategori_ruangans')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ruangans', function (Blueprint $table) {
            if (Schema::hasColumn('ruangans', 'kategori_id')) {
                $table->dropForeignKeyIfExists(['kategori_id']);
                $table->dropColumn('kategori_id');
            }
        });
    }
};
