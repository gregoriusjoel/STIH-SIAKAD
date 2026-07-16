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
        Schema::create('academic_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('event_type', ['krs', 'krs_perubahan', 'perkuliahan', 'uts', 'uas', 'libur_akademik', 'lainnya'])->default('lainnya');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedBigInteger('semester_id')->nullable();
            $table->string('color', 7)->default('#3788d8');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'event_type', 'start_date', 'end_date'], 'ae_active_type_dates_idx');
            $table->index(['semester_id', 'event_type'], 'ae_semester_type_idx');
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
