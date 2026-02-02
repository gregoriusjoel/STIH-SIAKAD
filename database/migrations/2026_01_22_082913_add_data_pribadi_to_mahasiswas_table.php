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
        Schema::table('mahasiswas', function (Blueprint $table) {
            // Add alamat and no_hp if not exists
            if (!Schema::hasColumn('mahasiswas', 'alamat')) {
                $table->text('alamat')->nullable()->after('address');
            }
            if (!Schema::hasColumn('mahasiswas', 'no_hp')) {
                $table->string('no_hp')->nullable()->after('phone');
            }
            
            // Add data pribadi columns
            $table->string('tempat_lahir')->nullable()->after('foto');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan'])->nullable()->after('tanggal_lahir');
            $table->string('agama')->nullable()->after('jenis_kelamin');
            $table->enum('status_sipil', ['Belum Menikah', 'Menikah', 'Cerai'])->nullable()->after('agama');
            $table->string('rt')->nullable()->after('alamat');
            $table->string('rw')->nullable()->after('rt');
            $table->string('kota')->nullable()->after('rw');
            $table->string('provinsi')->nullable()->after('kota');
            $table->string('kabupaten')->nullable()->after('provinsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn([
                'tempat_lahir',
                'tanggal_lahir',
                'jenis_kelamin',
                'agama',
                'status_sipil',
                'rt',
                'rw',
                'kota',
                'provinsi',
                'kabupaten'
            ]);
        });
    }
};
