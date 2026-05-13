<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Tabel utama prestasi ──
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();

            // Alur: pengajuan (sebelum kegiatan) / pelaporan (setelah kegiatan)
            $table->enum('tipe', ['pengajuan', 'pelaporan'])->default('pengajuan');

            // Polymorphic: mahasiswa / dosen
            $table->string('pengaju_type'); // 'App\Models\Mahasiswa' or 'App\Models\Dosen'
            $table->unsignedBigInteger('pengaju_id');

            // Data kegiatan
            $table->string('nama_kegiatan');
            $table->string('jenis_kegiatan')->default('akademik'); // akademik / non-akademik
            $table->enum('tingkat_kegiatan', ['internal', 'regional', 'nasional', 'internasional'])->default('nasional');
            $table->string('tempat_kegiatan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('penyelenggara');
            $table->text('deskripsi')->nullable();

            // Dosen pendamping
            $table->foreignId('dosen_pendamping_id')->nullable()->constrained('dosens')->nullOnDelete();

            // Khusus pelaporan
            $table->string('jenis_prestasi')->nullable(); // juara 1, peserta, pembicara, dll
            $table->string('nomor_sertifikat')->nullable();
            $table->text('keterangan')->nullable();

            // State machine
            $table->string('status')->default('draft')->index();
            // draft → diajukan → diproses_admin → surat_diterbitkan → selesai / ditolak

            // Approval
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('admin_note')->nullable();

            // Future-proof fields
            $table->json('tags')->nullable(); // untuk BAN-PT/akreditasi tagging
            $table->string('external_ref')->nullable(); // untuk BKD/SISTER

            // Duplicate detection
            $table->string('hash_kegiatan')->nullable()->index();

            $table->timestamps();

            // Indexes
            $table->index(['pengaju_type', 'pengaju_id']);
            $table->index('dosen_pendamping_id');
            $table->index('tingkat_kegiatan');
        });

        // ── Dokumen prestasi (sertifikat, dokumentasi, dll) ──
        Schema::create('prestasi_dokumens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestasi_id')->constrained('prestasis')->cascadeOnDelete();
            $table->enum('jenis', ['sertifikat', 'dokumentasi', 'surat_tugas_lama', 'pendukung'])->default('sertifikat');
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0); // bytes
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('prestasi_id');
        });

        // ── Log audit & timeline prestasi ──
        Schema::create('prestasi_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestasi_id')->constrained('prestasis')->cascadeOnDelete();
            $table->string('action'); // status_change, note_added, surat_generated, dokumen_uploaded, etc.
            $table->string('from_status')->nullable();
            $table->string('to_status')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('prestasi_id');
            $table->index('action');
        });

        // ── Surat prestasi (generated letters) ──
        Schema::create('prestasi_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestasi_id')->constrained('prestasis')->cascadeOnDelete();
            $table->string('jenis_surat'); // surat_tugas, surat_rekomendasi, surat_keterangan, surat_penghargaan, surat_arsip
            $table->string('nomor_surat')->unique();
            $table->date('tanggal_surat');
            $table->string('penandatangan_nama');
            $table->string('penandatangan_jabatan');
            $table->string('penandatangan_nip')->nullable();
            $table->string('file_path')->nullable(); // path PDF di storage
            $table->boolean('is_backdate')->default(false);
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('metadata')->nullable(); // extra data
            $table->timestamps();

            $table->index('prestasi_id');
            $table->index('jenis_surat');
        });

        // ── Pengaturan format nomor surat prestasi ──
        Schema::create('prestasi_surat_settings', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_surat'); // tugas, rekomendasi, keterangan, penghargaan
            $table->string('format_nomor'); // e.g., {counter}/STIH/ST/{month}/{year}
            $table->integer('last_counter')->default(0);
            $table->year('reset_year')->default(date('Y'));
            $table->timestamps();
        });

        // Insert default settings
        $defaultFormats = [
            'tugas'       => '{counter}/STIH/ST/{month}/{year}',
            'rekomendasi' => '{counter}/STIH/SR/{month}/{year}',
            'keterangan'  => '{counter}/STIH/SKP/{month}/{year}',
            'penghargaan' => '{counter}/STIH/PP/{month}/{year}',
        ];

        foreach ($defaultFormats as $jenis => $format) {
            \DB::table('prestasi_surat_settings')->insert([
                'jenis_surat'  => $jenis,
                'format_nomor' => $format,
                'last_counter' => 0,
                'reset_year'   => (int) date('Y'),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasi_surat_settings');
        Schema::dropIfExists('prestasi_surats');
        Schema::dropIfExists('prestasi_logs');
        Schema::dropIfExists('prestasi_dokumens');
        Schema::dropIfExists('prestasis');
    }
};
