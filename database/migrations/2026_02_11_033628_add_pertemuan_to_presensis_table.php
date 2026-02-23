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
        if (!Schema::hasColumn('presensis', 'pertemuan')) {
            Schema::table('presensis', function (Blueprint $table) {
                $table->integer('pertemuan')->nullable()->after('kelas_mata_kuliah_id');
                $table->index('pertemuan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('presensis', 'pertemuan')) {
            Schema::table('presensis', function (Blueprint $table) {
                // Attempt to drop index and column only if they exist
                $table->dropIndex(['pertemuan']);
                $table->dropColumn('pertemuan');
            });
        }
    }
};
