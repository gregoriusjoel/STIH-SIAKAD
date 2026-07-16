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
        Schema::create('nilai', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('krs_id')->index('nilai_krs_id_foreign');
            $table->unsignedBigInteger('kelas_id')->nullable()->index('nilai_kelas_id_foreign');
            $table->decimal('nilai_partisipatif', 5)->nullable();
            $table->decimal('nilai_proyek', 5)->nullable();
            $table->decimal('nilai_quiz', 5)->nullable();
            $table->decimal('nilai_tugas', 5)->nullable();
            $table->decimal('nilai_uts', 5)->nullable();
            $table->decimal('nilai_uas', 5)->nullable();
            $table->decimal('nilai_akhir', 5)->nullable();
            $table->char('grade', 2)->nullable();
            $table->decimal('bobot', 4)->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('published_by')->nullable()->index('nilai_published_by_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};
