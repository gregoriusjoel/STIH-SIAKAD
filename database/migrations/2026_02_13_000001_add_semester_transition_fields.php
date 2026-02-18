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
        // Add is_active to semesters if not exists
        if (!Schema::hasColumn('semesters', 'is_active')) {
            Schema::table('semesters', function (Blueprint $table) {
                $table->boolean('is_active')->default(false)->after('status');
            });
        }

        // Add krs fields to semesters if not exists
        if (!Schema::hasColumn('semesters', 'krs_dapat_diisi')) {
            Schema::table('semesters', function (Blueprint $table) {
                $table->boolean('krs_dapat_diisi')->default(false)->after('is_active');
                $table->date('krs_mulai')->nullable()->after('krs_dapat_diisi');
                $table->date('krs_selesai')->nullable()->after('krs_mulai');
            });
        }

        // Add semester field to mahasiswas if not exists
        if (!Schema::hasColumn('mahasiswas', 'semester')) {
            Schema::table('mahasiswas', function (Blueprint $table) {
                $table->integer('semester')->default(1)->after('angkatan');
            });
        }

        // Add last_semester_id to mahasiswas
        if (!Schema::hasColumn('mahasiswas', 'last_semester_id')) {
            Schema::table('mahasiswas', function (Blueprint $table) {
                $table->foreignId('last_semester_id')->nullable()->after('semester')
                    ->constrained('semesters')->onDelete('set null');
            });
        }

        // Add status_akun to mahasiswas if not exists
        if (!Schema::hasColumn('mahasiswas', 'status_akun')) {
            Schema::table('mahasiswas', function (Blueprint $table) {
                $table->enum('status_akun', ['aktif', 'non-aktif'])->default('aktif')->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (Schema::hasColumn('mahasiswas', 'last_semester_id')) {
                $table->dropForeign(['last_semester_id']);
                $table->dropColumn('last_semester_id');
            }
            if (Schema::hasColumn('mahasiswas', 'status_akun')) {
                $table->dropColumn('status_akun');
            }
            if (Schema::hasColumn('mahasiswas', 'semester')) {
                $table->dropColumn('semester');
            }
        });

        Schema::table('semesters', function (Blueprint $table) {
            if (Schema::hasColumn('semesters', 'krs_selesai')) {
                $table->dropColumn(['krs_dapat_diisi', 'krs_mulai', 'krs_selesai']);
            }
            if (Schema::hasColumn('semesters', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
