<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kuesioner_mahasiswa_baru', function (Blueprint $table) {
            if (!Schema::hasColumn('kuesioner_mahasiswa_baru', 'q1')) {
                $table->tinyInteger('q1')->nullable()->after('mahasiswa_id');
                $table->tinyInteger('q2')->nullable()->after('q1');
                $table->tinyInteger('q3')->nullable()->after('q2');
                $table->tinyInteger('q4')->nullable()->after('q3');
                $table->tinyInteger('q5')->nullable()->after('q4');
                $table->tinyInteger('q6')->nullable()->after('q5');
                $table->tinyInteger('q7')->nullable()->after('q6');
                $table->text('saran')->nullable()->after('q7');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kuesioner_mahasiswa_baru', function (Blueprint $table) {
            $cols = ['q1','q2','q3','q4','q5','q6','q7','saran'];
            foreach ($cols as $c) {
                if (Schema::hasColumn('kuesioner_mahasiswa_baru', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};
