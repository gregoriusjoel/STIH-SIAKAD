<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            // Dynamic fields JSON per jenis
            $table->json('payload_template')->nullable()->after('keterangan');

            // Generated DOCX path (sebelum TTD)
            $table->string('generated_doc_path')->nullable()->after('file_path');

            // Signed document path (upload mahasiswa)
            $table->string('signed_doc_path')->nullable()->after('generated_doc_path');

            // Alasan penolakan admin (terpisah dari admin_note umum)
            $table->text('rejected_reason')->nullable()->after('admin_note');

            // Berapa kali revisi
            $table->unsignedSmallInteger('revision_no')->default(0)->after('rejected_reason');

            // Timestamps workflow
            $table->timestamp('submitted_at')->nullable()->after('approved_at');
            $table->timestamp('rejected_at')->nullable()->after('submitted_at');
        });

        // Migrate existing status values ke naming baru
        DB::table('pengajuans')->where('status', 'pending')->update(['status' => 'submitted']);
        DB::table('pengajuans')->where('status', 'disetujui')->update(['status' => 'approved']);
        DB::table('pengajuans')->where('status', 'ditolak')->update(['status' => 'rejected']);

        // Index tambahan untuk performance
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->index(['mahasiswa_id', 'status'], 'idx_pengajuans_mhs_status');
            $table->index('jenis', 'idx_pengajuans_jenis');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->dropIndex('idx_pengajuans_mhs_status');
            $table->dropIndex('idx_pengajuans_jenis');
            $table->dropColumn([
                'payload_template',
                'generated_doc_path',
                'signed_doc_path',
                'rejected_reason',
                'revision_no',
                'submitted_at',
                'rejected_at',
            ]);
        });

        DB::table('pengajuans')->where('status', 'submitted')->update(['status' => 'pending']);
        DB::table('pengajuans')->where('status', 'approved')->update(['status' => 'disetujui']);
        DB::table('pengajuans')->where('status', 'rejected')->update(['status' => 'ditolak']);
    }
};
