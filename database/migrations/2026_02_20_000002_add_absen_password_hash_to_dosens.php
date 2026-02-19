<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            if (!Schema::hasColumn('dosens', 'absen_password_hash')) {
                $table->string('absen_password_hash')->nullable()->after('kuota')
                      ->comment('Bcrypt hash for dosen QR attendance activation password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            if (Schema::hasColumn('dosens', 'absen_password_hash')) {
                $table->dropColumn('absen_password_hash');
            }
        });
    }
};
