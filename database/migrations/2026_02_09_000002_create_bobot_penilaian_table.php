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
            $table->id();
            $table->foreignId('kelas_id')->constrained()->onDelete('cascade');
            $table->decimal('bobot_partisipatif', 5, 2)->default(25.00);
            $table->decimal('bobot_proyek', 5, 2)->default(25.00);
            $table->decimal('bobot_quiz', 5, 2)->default(5.00);
            $table->decimal('bobot_tugas', 5, 2)->default(5.00);
            $table->decimal('bobot_uts', 5, 2)->default(20.00);
            $table->decimal('bobot_uas', 5, 2)->default(20.00);
            $table->boolean('is_locked')->default(false);
            $table->timestamp('locked_at')->nullable();
            $table->foreignId('locked_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Ensure only one bobot per kelas
            $table->unique('kelas_id');
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
