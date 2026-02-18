<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('npm', 50)->unique();
            $table->string('nama', 255);
            $table->string('prodi', 100);
            $table->string('angkatan', 10);
            $table->timestamps();

            $table->index('npm');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
