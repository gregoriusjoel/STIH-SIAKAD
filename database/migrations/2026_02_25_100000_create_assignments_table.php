<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('dosen_id')->nullable()->constrained('dosens')->nullOnDelete();
            $table->unsignedInteger('minggu_ke')->default(1)->comment('Minggu / pertemuan ke-');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->date('deadline')->nullable();
            $table->unsignedInteger('max_nilai')->default(100);
            $table->decimal('bobot', 5, 2)->nullable()->comment('Bobot dalam % (misal 20.00 = 20%)');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('assignment_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnDelete();
            $table->foreignId('graded_by')->nullable()->constrained('dosens')->nullOnDelete();
            $table->decimal('nilai', 6, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();

            $table->unique(['assignment_id', 'mahasiswa_id'], 'uq_assignment_mahasiswa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_scores');
        Schema::dropIfExists('assignments');
    }
};
