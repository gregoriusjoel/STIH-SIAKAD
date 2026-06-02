<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create internship_types table
        Schema::create('internship_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->boolean('is_conversion')->default(false);
            $table->unsignedTinyInteger('max_conversion_sks')->default(16);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Insert the default internship types
        DB::table('internship_types')->insert([
            [
                'id' => 1,
                'code' => 'BERDAMPAK',
                'name' => 'Magang Berdampak (MBKM)',
                'description' => 'Program Magang MBKM dengan konversi SKS mata kuliah (maksimal 20 SKS).',
                'is_conversion' => true,
                'max_conversion_sks' => 20,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'code' => 'MANDIRI',
                'name' => 'Magang Mandiri',
                'description' => 'Program Magang Mandiri pencarian mandiri tanpa konversi SKS.',
                'is_conversion' => false,
                'max_conversion_sks' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 3. Alter internships table adding internship_type_id as NOT NULL with default(1)
        Schema::table('internships', function (Blueprint $table) {
            $table->foreignId('internship_type_id')->default(1)->after('mahasiswa_id')
                  ->constrained('internship_types');
            
            // Add score and grade columns for Mandiri internship evaluation
            $table->decimal('final_score', 5, 2)->nullable()->after('converted_sks');
            $table->string('final_grade', 3)->nullable()->after('final_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internships', function (Blueprint $table) {
            $table->dropForeign(['internship_type_id']);
            $table->dropColumn(['internship_type_id', 'final_score', 'final_grade']);
        });

        Schema::dropIfExists('internship_types');
    }
};
