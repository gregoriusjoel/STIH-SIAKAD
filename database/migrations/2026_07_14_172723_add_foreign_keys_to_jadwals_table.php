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
            $table->foreign(['approved_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['kelas_id'])->references(['id'])->on('kelas')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['kelas_perkuliahan_id'])->references(['id'])->on('kelas_perkuliahans')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['ruangan_id'])->references(['id'])->on('ruangans')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropForeign('jadwals_approved_by_foreign');
            $table->dropForeign('jadwals_kelas_id_foreign');
            $table->dropForeign('jadwals_kelas_perkuliahan_id_foreign');
            $table->dropForeign('jadwals_ruangan_id_foreign');
        });
    }
};
