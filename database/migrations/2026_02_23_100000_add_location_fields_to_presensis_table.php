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
        Schema::table('presensis', function (Blueprint $table) {
            // Student's GPS coordinates when submitting attendance
            $table->decimal('student_lat', 10, 7)->nullable()->after('keterangan');
            $table->decimal('student_lng', 10, 7)->nullable()->after('student_lat');
            
            // Calculated distance from campus in meters
            $table->integer('distance_meters')->nullable()->after('student_lng');
            
            // Attendance mode result: offline (within radius) or online (outside radius/from home)
            $table->enum('presence_mode', ['offline', 'online'])->nullable()->after('distance_meters');
            
            // Reason for being outside radius (required when offline meeting but student is outside radius)
            $table->string('reason_category')->nullable()->after('presence_mode');
            $table->text('reason_detail')->nullable()->after('reason_category');
            
            // Campus location snapshot (optional, for audit trail)
            $table->decimal('campus_lat', 10, 7)->default(-6.311252)->after('reason_detail');
            $table->decimal('campus_lng', 10, 7)->default(106.811174)->after('campus_lat');
            $table->integer('radius_meters')->default(100)->after('campus_lng');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->dropColumn([
                'student_lat',
                'student_lng',
                'distance_meters',
                'presence_mode',
                'reason_category',
                'reason_detail',
                'campus_lat',
                'campus_lng',
                'radius_meters',
            ]);
        });
    }
};
