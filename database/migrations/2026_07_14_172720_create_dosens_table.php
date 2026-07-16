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
        Schema::create('dosens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('dosens_user_id_foreign');
            $table->unsignedBigInteger('fakultas_id')->nullable()->index('dosens_fakultas_id_foreign');
            $table->string('nidn')->unique();
            $table->longText('pendidikan_terakhir')->nullable()->comment('Multiple education levels: S1, S2, S3');
            $table->longText('universitas')->nullable()->comment('Array of universities for each education level');
            $table->boolean('dosen_tetap')->default(false)->comment('Is permanent lecturer');
            $table->longText('jabatan_fungsional')->nullable()->comment('Functional positions');
            $table->string('pendidikan')->nullable();
            $table->string('prodi');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->enum('status', ['aktif', 'non-aktif'])->default('aktif');
            $table->timestamps();
            $table->integer('kuota')->default(6);
            $table->string('absen_password_hash')->nullable()->comment('Bcrypt hash for dosen QR attendance activation password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosens');
    }
};
