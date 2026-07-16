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
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('mahasiswas_user_id_foreign');
            $table->string('nim')->unique();
            $table->string('prodi');
            $table->unsignedBigInteger('prodi_id')->nullable()->index('mahasiswas_prodi_id_idx');
            $table->string('angkatan');
            $table->unsignedTinyInteger('semester')->default(1)->index('mahasiswas_semester_idx');
            $table->unsignedBigInteger('tahun_akademik_id')->nullable()->index('mahasiswas_tahun_akademik_id_idx');
            $table->unsignedBigInteger('last_semester_id')->nullable()->index('mahasiswas_last_semester_id_foreign');
            $table->string('phone')->nullable();
            $table->string('no_hp')->nullable();
            $table->text('address')->nullable();
            $table->text('alamat')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('kota')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('desa')->nullable();
            $table->text('alamat_ktp')->nullable();
            $table->string('rt_ktp')->nullable();
            $table->string('rw_ktp')->nullable();
            $table->string('provinsi_ktp')->nullable();
            $table->string('kota_ktp')->nullable();
            $table->string('kecamatan_ktp')->nullable();
            $table->string('desa_ktp')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('jenis_sekolah')->nullable();
            $table->string('jurusan_sekolah')->nullable();
            $table->string('tahun_lulus')->nullable();
            $table->decimal('nilai_kelulusan', 5)->nullable();
            $table->string('foto')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan'])->nullable();
            $table->string('agama')->nullable();
            $table->enum('status_sipil', ['Belum Menikah', 'Menikah', 'Cerai'])->nullable();
            $table->enum('status', ['aktif', 'cuti', 'lulus', 'do'])->default('aktif');
            $table->enum('status_akun', ['baru', 'aktif', 'tidak_aktif'])->default('baru');
            $table->boolean('is_dokumen_unlocked')->default(false);
            $table->unsignedBigInteger('kelas_perkuliahan_id')->nullable()->index('mahasiswas_kelas_perkuliahan_id_idx');
            $table->boolean('new_survey_completed')->default(false);
            $table->timestamps();
            $table->longText('file_ijazah')->nullable();
            $table->longText('file_transkrip')->nullable();
            $table->longText('file_kk')->nullable();
            $table->longText('file_ktp')->nullable();
            $table->string('email_pribadi')->nullable()->index()->comment('Email pribadi mahasiswa untuk login & notifikasi alternatif');
            $table->string('email_kampus')->nullable()->unique()->comment('Email kampus otomatis: [nama_tanpa_spasi]@student.stih.ac.id');
            $table->enum('email_aktif', ['pribadi', 'kampus'])->default('pribadi')->comment('Email aktif untuk login & notifikasi: pribadi | kampus');
            $table->timestamp('email_pribadi_verified_at')->nullable()->comment('Timestamp saat email pribadi diverifikasi');
            $table->string('password_reset_token')->nullable()->comment('Token untuk force reset password (opsional)');
            $table->boolean('is_default_password')->default(true)->comment('true = password masih default (NIM), false = sudah diganti');
            $table->timestamp('account_automation_at')->nullable()->comment('Timestamp saat akun otomasi dijalankan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
