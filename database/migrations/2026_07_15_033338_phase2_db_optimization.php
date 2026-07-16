<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 2 DB Optimization — migrasi data dan hapus kolom phone/address di mahasiswas.
 *
 * Perubahan:
 * 1. Migrasi data phone -> no_hp dan address -> alamat di tabel mahasiswas jika no_hp/alamat kosong.
 * 2. Drop kolom phone dan address di tabel mahasiswas.
 * 3. Sinkronisasi nilai is_active dengan status di tabel semesters.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Migrasi Data mahasiswas ────────────────────────────────────────
        if (Schema::hasColumn('mahasiswas', 'phone') && Schema::hasColumn('mahasiswas', 'no_hp')) {
            DB::statement("UPDATE mahasiswas SET no_hp = phone WHERE no_hp IS NULL AND phone IS NOT NULL");
        }
        if (Schema::hasColumn('mahasiswas', 'address') && Schema::hasColumn('mahasiswas', 'alamat')) {
            DB::statement("UPDATE mahasiswas SET alamat = address WHERE alamat IS NULL AND address IS NOT NULL");
        }

        // ── 2. Drop kolom phone & address di mahasiswas ───────────────────────
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (Schema::hasColumn('mahasiswas', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('mahasiswas', 'address')) {
                $table->dropColumn('address');
            }
        });

        // ── 3. Sinkronisasi status & is_active di semesters ─────────────────────
        if (Schema::hasColumn('semesters', 'status') && Schema::hasColumn('semesters', 'is_active')) {
            DB::statement("UPDATE semesters SET is_active = CASE WHEN status = 'aktif' THEN 1 ELSE 0 END");
        }
    }

    public function down(): void
    {
        // ── 2. Restore kolom phone & address di mahasiswas ─────────────────────
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('last_semester_id');
            $table->text('address')->nullable()->after('phone');
        });

        // ── 1. Restore data dari no_hp -> phone dan alamat -> address ─────────
        DB::statement("UPDATE mahasiswas SET phone = no_hp, address = alamat");
    }
};
