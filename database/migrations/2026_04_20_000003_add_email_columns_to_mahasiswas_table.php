<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * NON-DESTRUCTIVE: Hanya menambah kolom, tidak menghapus data lama.
     * Ini memungkinkan backward compatibility dengan sistem yang sudah berjalan.
     */
    public function up(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            // Email pribadi (nullable, dapat diverifikasi)
            if (!Schema::hasColumn('mahasiswas', 'email_pribadi')) {
                $table->string('email_pribadi', 255)->nullable()
                    ->index()
                    ->comment('Email pribadi mahasiswa untuk login & notifikasi alternatif');
            }

            // Email kampus (nullable, unique, auto-generated)
            if (!Schema::hasColumn('mahasiswas', 'email_kampus')) {
                $table->string('email_kampus', 255)->nullable()
                    ->unique('mahasiswas_email_kampus_unique')
                    ->index()
                    ->comment('Email kampus otomatis: [nama_tanpa_spasi]@student.stih.ac.id');
            }

            // Enum untuk menentukan email aktif (pribadi atau kampus)
            if (!Schema::hasColumn('mahasiswas', 'email_aktif')) {
                $table->enum('email_aktif', ['pribadi', 'kampus'])
                    ->default('pribadi')
                    ->comment('Email aktif untuk login & notifikasi: pribadi | kampus');
            }

            // Timestamp untuk verifikasi email pribadi
            if (!Schema::hasColumn('mahasiswas', 'email_pribadi_verified_at')) {
                $table->timestamp('email_pribadi_verified_at')->nullable()
                    ->comment('Timestamp saat email pribadi diverifikasi');
            }

            // Password reset token untuk force reset password login pertama
            if (!Schema::hasColumn('mahasiswas', 'password_reset_token')) {
                $table->string('password_reset_token')->nullable()
                    ->after('email_pribadi_verified_at')
                    ->comment('Token untuk force reset password (opsional)');
            }

            // Flag untuk menandai apakah password default sudah di-set
            if (!Schema::hasColumn('mahasiswas', 'is_default_password')) {
                $table->boolean('is_default_password')->default(true)
                    ->after('password_reset_token')
                    ->comment('true = password masih default (NIM), false = sudah diganti');
            }

            // Kolom untuk tracking automation
            if (!Schema::hasColumn('mahasiswas', 'account_automation_at')) {
                $table->timestamp('account_automation_at')->nullable()
                    ->after('is_default_password')
                    ->comment('Timestamp saat akun otomasi dijalankan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            // Hapus kolom jika rollback
            $columnsToDelete = [
                'email_pribadi',
                'email_kampus',
                'email_aktif',
                'email_pribadi_verified_at',
                'password_reset_token',
                'is_default_password',
                'account_automation_at',
            ];

            foreach ($columnsToDelete as $column) {
                if (Schema::hasColumn('mahasiswas', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
