<?php

namespace App\Console\Commands;

use App\Models\Mahasiswa;
use App\Services\MahasiswaClassAssignmentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateMahasiswaKelasPerkuliahan extends Command
{
    protected $signature = 'mahasiswa:migrate-kelas-perkuliahan
                            {--dry-run : Tampilkan hasil mapping tanpa menyimpan perubahan}
                            {--force : Lewati prompt konfirmasi}';

    protected $description = 'Mapping mahasiswa lama ke master kelas perkuliahan dengan fallback aman';

    public function __construct(
        protected MahasiswaClassAssignmentService $assignmentService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->warn('Backup database WAJIB dilakukan sebelum menjalankan migration ini.');

        if (!$this->option('dry-run') && !$this->option('force')) {
            if (!$this->confirm('Lanjutkan proses mapping mahasiswa ke kelas perkuliahan?')) {
                $this->info('Migration dibatalkan.');
                return self::SUCCESS;
            }
        }

        $mahasiswas = Mahasiswa::with(['latestSubmittedKrs.kelas', 'user'])->orderBy('id')->get();
        $mapped = 0;
        $unmapped = 0;
        $rows = [];

        DB::beginTransaction();

        try {
            foreach ($mahasiswas as $mahasiswa) {
                $legacySection = $mahasiswa->latestSubmittedKrs?->kelas?->section;
                $kelas = $this->assignmentService->findLegacyMatchingClass($mahasiswa, $legacySection);
                $status = $kelas ? 'mapped' : 'belum punya kelas';

                $rows[] = [
                    $mahasiswa->id,
                    $mahasiswa->nim,
                    $mahasiswa->user?->name ?? '-',
                    $legacySection ?: '-',
                    $kelas?->nama_kelas ?? '-',
                    $status,
                ];

                if ($kelas) {
                    $mapped++;

                    if (!$this->option('dry-run')) {
                        $mahasiswa->forceFill([
                            'kelas_perkuliahan_id' => $kelas->id,
                            'prodi_id' => $kelas->prodi_id,
                            'tahun_akademik_id' => $kelas->tahun_akademik_id,
                        ])->save();
                    }
                } else {
                    $unmapped++;

                    if (!$this->option('dry-run')) {
                        $mahasiswa->forceFill([
                            'kelas_perkuliahan_id' => null,
                        ])->save();
                    }
                }
            }

            if ($this->option('dry-run')) {
                DB::rollBack();
            } else {
                DB::commit();
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Migration gagal: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->table(
            ['ID', 'NIM', 'Nama', 'Legacy Section', 'Kelas Baru', 'Status'],
            collect($rows)->take(25)->toArray()
        );

        if (count($rows) > 25) {
            $this->line('Menampilkan 25 baris pertama dari total ' . count($rows) . ' mahasiswa.');
        }

        $this->info("Berhasil dipetakan: {$mapped}");
        $this->warn("Belum punya kelas: {$unmapped}");

        if ($this->option('dry-run')) {
            $this->info('Dry run selesai. Tidak ada data yang disimpan.');
        } else {
            $this->info('Migration mahasiswa ke kelas perkuliahan selesai.');
        }

        return self::SUCCESS;
    }
}
