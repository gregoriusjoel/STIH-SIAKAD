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
            if (!Schema::hasColumn('ruangans', 'kode_ruangan')) {
                $table->string('kode_ruangan', 20)->unique()->after('id');
            }
            if (!Schema::hasColumn('ruangans', 'nama_ruangan')) {
                $table->string('nama_ruangan')->after('kode_ruangan');
            }
            if (!Schema::hasColumn('ruangans', 'gedung')) {
                $table->string('gedung', 50)->nullable()->after('nama_ruangan');
            }
            if (!Schema::hasColumn('ruangans', 'lantai')) {
                $table->integer('lantai')->nullable()->after('gedung');
            }
            if (!Schema::hasColumn('ruangans', 'kapasitas')) {
                $table->integer('kapasitas')->default(30)->after('lantai');
            }
            if (!Schema::hasColumn('ruangans', 'status')) {
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('kapasitas');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ruangans', function (Blueprint $table) {
            $table->dropColumn(['kode_ruangan', 'nama_ruangan', 'gedung', 'lantai', 'kapasitas', 'status']);
        });
    }
};
