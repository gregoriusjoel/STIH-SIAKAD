<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixInvoicesStudentForeignToMahasiswas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            // remove existing foreign key if present
            try {
                $table->dropForeign(['student_id']);
            } catch (\Throwable $e) {
                // ignore if not exists
            }

            // add foreign key to mahasiswas table
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
        Schema::table('invoices', function (Blueprint $table) {
            try {
                $table->dropForeign(['student_id']);
            } catch (\Throwable $e) {
                // ignore
            }

            // restore original foreign key to students table (if present)
            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');
        });
    }
}
