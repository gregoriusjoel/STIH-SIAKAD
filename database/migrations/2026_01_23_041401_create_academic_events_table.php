<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('academic_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('event_type', ['krs', 'krs_perubahan', 'perkuliahan', 'uts', 'uas', 'libur_akademik', 'lainnya'])->default('lainnya');
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('cascade');
            $table->string('color', 7)->default('#3788d8'); // Hex color for calendar display
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_events');
    }
};
