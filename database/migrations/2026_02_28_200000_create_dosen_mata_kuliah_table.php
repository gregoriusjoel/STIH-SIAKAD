<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosen_mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('dosens')->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semesters')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['dosen_id', 'mata_kuliah_id', 'semester_id'], 'dmk_unique');
            $table->index(['dosen_id', 'semester_id'], 'dmk_dosen_semester');
        });

        // Seed existing data: copy dosens.mata_kuliah_ids JSON into pivot for active semester
        $activeSemester = DB::table('semesters')->where('is_active', true)->first();
        if ($activeSemester) {
            $dosens = DB::table('dosens')->whereNotNull('mata_kuliah_ids')->get();
            foreach ($dosens as $dosen) {
                $mkIds = json_decode($dosen->mata_kuliah_ids, true);
                if (!is_array($mkIds) || empty($mkIds)) continue;

                foreach ($mkIds as $mkId) {
                    $mkId = (int) $mkId;
                    if ($mkId <= 0) continue;

                    // Only insert if mata_kuliah actually exists
                    $exists = DB::table('mata_kuliahs')->where('id', $mkId)->exists();
                    if (!$exists) continue;

                    DB::table('dosen_mata_kuliah')->insertOrIgnore([
                        'dosen_id' => $dosen->id,
                        'mata_kuliah_id' => $mkId,
                        'semester_id' => $activeSemester->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen_mata_kuliah');
    }
};
