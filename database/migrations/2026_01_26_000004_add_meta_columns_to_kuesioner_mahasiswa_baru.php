<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kuesioner_mahasiswa_baru', function (Blueprint $table) {
            if (!Schema::hasColumn('kuesioner_mahasiswa_baru', 'email')) {
                $table->string('email')->nullable()->after('mahasiswa_id');
            }
            if (!Schema::hasColumn('kuesioner_mahasiswa_baru', 'prodi')) {
                $table->string('prodi')->nullable()->after('email');
            }
            if (!Schema::hasColumn('kuesioner_mahasiswa_baru', 'jenis_kelamin')) {
                $table->string('jenis_kelamin')->nullable()->after('prodi');
            }
            if (!Schema::hasColumn('kuesioner_mahasiswa_baru', 'angkatan')) {
                $table->smallInteger('angkatan')->nullable()->after('jenis_kelamin');
            }
        });
    }

    public function down()
    {
        Schema::table('kuesioner_mahasiswa_baru', function (Blueprint $table) {
            foreach (['email','prodi','jenis_kelamin','angkatan'] as $col) {
                if (Schema::hasColumn('kuesioner_mahasiswa_baru', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
