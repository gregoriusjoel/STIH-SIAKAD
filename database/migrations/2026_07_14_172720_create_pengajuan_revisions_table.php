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
        Schema::create('pengajuan_revisions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pengajuan_id');
            $table->unsignedSmallInteger('revision_no');
            $table->string('signed_doc_path');
            $table->text('note_from_admin')->nullable();
            $table->text('note_from_mahasiswa')->nullable();
            $table->timestamps();

            $table->index(['pengajuan_id', 'revision_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_revisions');
    }
};
