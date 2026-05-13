<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (!Schema::hasColumn('mahasiswas', 'prodi_id')) {
                $table->foreignId('prodi_id')
                    ->nullable()
                    ->after('prodi')
                    ->constrained('prodis')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('mahasiswas', 'tahun_akademik_id')) {
                $table->foreignId('tahun_akademik_id')
                    ->nullable()
                    ->after('semester')
                    ->constrained('semesters')
                    ->nullOnDelete();
            }
        });

        $this->backfillProdiReferences();
        $this->backfillAcademicYearReferences();

        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->index('kelas_perkuliahan_id', 'mahasiswas_kelas_perkuliahan_id_idx');
            $table->index('prodi_id', 'mahasiswas_prodi_id_idx');
            $table->index('tahun_akademik_id', 'mahasiswas_tahun_akademik_id_idx');
            $table->index('semester', 'mahasiswas_semester_idx');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropIndex('mahasiswas_kelas_perkuliahan_id_idx');
            $table->dropIndex('mahasiswas_prodi_id_idx');
            $table->dropIndex('mahasiswas_tahun_akademik_id_idx');
            $table->dropIndex('mahasiswas_semester_idx');
        });

        Schema::table('mahasiswas', function (Blueprint $table) {
            if (Schema::hasColumn('mahasiswas', 'prodi_id')) {
                $table->dropConstrainedForeignId('prodi_id');
            }

            if (Schema::hasColumn('mahasiswas', 'tahun_akademik_id')) {
                $table->dropConstrainedForeignId('tahun_akademik_id');
            }
        });
    }

    protected function backfillProdiReferences(): void
    {
        $prodis = DB::table('prodis')
            ->select(['id', 'nama_prodi', 'kode_prodi'])
            ->get();

        foreach ($prodis as $prodi) {
            DB::table('mahasiswas')
                ->whereNull('prodi_id')
                ->where(function ($query) use ($prodi) {
                    $query->whereRaw('LOWER(TRIM(prodi)) = ?', [strtolower(trim((string) $prodi->nama_prodi))])
                        ->orWhereRaw('LOWER(TRIM(prodi)) = ?', [strtolower(trim((string) $prodi->kode_prodi))]);
                })
                ->update(['prodi_id' => $prodi->id]);
        }
    }

    protected function backfillAcademicYearReferences(): void
    {
        $activeSemesterId = DB::table('semesters')
            ->where('status', 'aktif')
            ->value('id')
            ?? DB::table('semesters')->where('is_active', true)->value('id')
            ?? DB::table('semesters')->max('id');

        if (!$activeSemesterId) {
            return;
        }

        DB::table('mahasiswas')
            ->whereNull('tahun_akademik_id')
            ->update(['tahun_akademik_id' => $activeSemesterId]);
    }
};
