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
        Schema::table('jadwal_proposals', function (Blueprint $table) {
            // Add ruangan_id foreign key
            $table->unsignedBigInteger('ruangan_id')->nullable()->after('ruangan');
            $table->foreign('ruangan_id')->references('id')->on('ruangans')->onDelete('set null');
            
            // Index for performance
            $table->index('ruangan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_proposals', function (Blueprint $table) {
            $table->dropForeign(['ruangan_id']);
            $table->dropIndex(['ruangan_id']);
            $table->dropColumn('ruangan_id');
        });
    }
};
