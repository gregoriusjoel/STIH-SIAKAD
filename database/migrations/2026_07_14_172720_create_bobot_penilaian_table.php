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
        Schema::create('bobot_penilaian', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kelas_id')->unique();
            $table->decimal('bobot_partisipatif', 5)->default(25);
            $table->decimal('bobot_proyek', 5)->default(25);
            $table->decimal('bobot_quiz', 5)->default(5);
            $table->decimal('bobot_tugas', 5)->default(5);
            $table->decimal('bobot_uts', 5)->default(20);
            $table->decimal('bobot_uas', 5)->default(20);
            $table->boolean('is_locked')->default(false);
            $table->timestamp('locked_at')->nullable();
            $table->unsignedBigInteger('locked_by')->nullable()->index('bobot_penilaian_locked_by_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bobot_penilaian');
    }
};
