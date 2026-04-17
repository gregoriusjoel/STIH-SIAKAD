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
        if (!Schema::hasColumn('dosens', 'fakultas_id')) {
            Schema::table('dosens', function (Blueprint $table) {
                $table->unsignedBigInteger('fakultas_id')->nullable()->after('user_id');
            });
        }

        Schema::table('dosens', function (Blueprint $table) {
            $foreignKeys = collect(Schema::getForeignKeys('dosens'));
            $hasForeign = $foreignKeys->contains(function ($foreign) {
                return in_array('fakultas_id', $foreign['columns'] ?? [], true);
            });

            if (!$hasForeign && Schema::hasColumn('dosens', 'fakultas_id')) {
                $table->foreign('fakultas_id')
                    ->references('id')
                    ->on('fakultas')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('dosens', 'fakultas_id')) {
            return;
        }

        Schema::table('dosens', function (Blueprint $table) {
            $foreignKeys = collect(Schema::getForeignKeys('dosens'));
            $hasForeign = $foreignKeys->contains(function ($foreign) {
                return in_array('fakultas_id', $foreign['columns'] ?? [], true);
            });

            if ($hasForeign) {
                $table->dropForeign(['fakultas_id']);
            }

            $table->dropColumn('fakultas_id');
        });
    }
};
