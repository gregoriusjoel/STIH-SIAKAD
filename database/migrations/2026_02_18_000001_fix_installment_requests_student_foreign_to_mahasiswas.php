<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixInstallmentRequestsStudentForeignToMahasiswas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('installment_requests', function (Blueprint $table) {
            try {
                $table->dropForeign(['student_id']);
            } catch (\Throwable $e) {
                // ignore if foreign key doesn't exist
            }

            $table->foreign('student_id')
                ->references('id')
                ->on('mahasiswas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('installment_requests', function (Blueprint $table) {
            try {
                $table->dropForeign(['student_id']);
            } catch (\Throwable $e) {
                // ignore
            }

            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');
        });
    }
}
