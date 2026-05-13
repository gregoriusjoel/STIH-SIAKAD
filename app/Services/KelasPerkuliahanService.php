<?php

namespace App\Services;

use App\Models\KelasPerkuliahan;
use App\Models\Prodi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class KelasPerkuliahanService
{
    public function calculateTingkatFromSemester(int $semester): int
    {
        return (int) ceil(max(1, $semester) / 2);
    }

    public function normalizeAngkatan(int|string $angkatan): string
    {
        return preg_replace('/\D/', '', (string) $angkatan) ?: '';
    }

    public function getTwoDigitAngkatan(int|string $angkatan): string
    {
        return substr($this->normalizeAngkatan($angkatan), -2);
    }

    /**
     * Format: [2 digit angkatan][kode_prodi][kode_kelas]
     * Example: 25HK01, 26PRWT02
     */
    public function generateNamaKelas(int|string $angkatan, string $kodeProdi, string $kodeKelas): string
    {
        return $this->getTwoDigitAngkatan($angkatan) . strtoupper($kodeProdi) . $kodeKelas;
    }

    public function findOrCreate(array $attributes): KelasPerkuliahan
    {
        $existing = $this->findExistingByIdentity($attributes);

        if ($existing) {
            return $existing;
        }

        return KelasPerkuliahan::create($this->normalizeAttributes($attributes));
    }

    public function update(KelasPerkuliahan $kelasPerkuliahan, array $attributes): KelasPerkuliahan
    {
        $normalized = $this->normalizeAttributes($attributes, $kelasPerkuliahan);
        $existing = $this->findExistingByIdentity($normalized, $kelasPerkuliahan->id);

        if ($existing) {
            throw new \InvalidArgumentException(
                "Kelas Perkuliahan dengan kombinasi Angkatan {$normalized['angkatan']}, " .
                "Prodi {$normalized['kode_prodi']}, Kelas {$normalized['kode_kelas']} sudah ada."
            );
        }

        $kelasPerkuliahan->update($normalized);

        return $kelasPerkuliahan->fresh();
    }

    public function generateForAngkatan(
        int $prodiId,
        string $angkatan,
        ?int $tahunAkademikId,
        int $kelasPerAngkatan = 1
    ): array {
        $prodi = Prodi::findOrFail($prodiId);
        $created = collect();
        $skipped = 0;
        $angkatan = $this->normalizeAngkatan($angkatan);

        DB::beginTransaction();
        try {
            for ($k = 1; $k <= $kelasPerAngkatan; $k++) {
                $kodeKelas = str_pad((string) $k, 2, '0', STR_PAD_LEFT);

                $kelas = $this->findOrCreate([
                    'angkatan' => $angkatan,
                    'tingkat' => 0,
                    'kode_prodi' => $prodi->kode_prodi,
                    'kode_kelas' => $kodeKelas,
                    'prodi_id' => $prodiId,
                    'tahun_akademik_id' => $tahunAkademikId,
                ]);

                if ($kelas->wasRecentlyCreated) {
                    $created->push($kelas);
                } else {
                    $skipped++;
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return ['created' => $created, 'skipped' => $skipped];
    }

    public function generateForAngkatanWithOverwrite(
        int $prodiId,
        string $angkatan,
        ?int $tahunAkademikId,
        int $kelasPerAngkatan = 1
    ): array {
        $deleted = 0;
        $created = collect();
        $angkatan = $this->normalizeAngkatan($angkatan);

        DB::beginTransaction();
        try {
            $query = KelasPerkuliahan::where('prodi_id', $prodiId)
                ->where('angkatan', $angkatan);

            if ($tahunAkademikId) {
                $query->where('tahun_akademik_id', $tahunAkademikId);
            }

            $deleted = $query->delete();
            $result = $this->generateForAngkatan($prodiId, $angkatan, $tahunAkademikId, $kelasPerAngkatan);
            $created = $result['created'];

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return ['created' => $created, 'deleted' => $deleted];
    }

    public function getDropdownOptions(?int $prodiId = null, ?int $tahunAkademikId = null): Collection
    {
        $query = KelasPerkuliahan::with('prodi')
            ->orderByDesc('angkatan')
            ->orderBy('kode_prodi')
            ->orderBy('kode_kelas');

        if ($prodiId) {
            $query->where('prodi_id', $prodiId);
        }

        if ($tahunAkademikId) {
            $query->where('tahun_akademik_id', $tahunAkademikId);
        }

        return $query->get()->map(function (KelasPerkuliahan $kp) {
            return [
                'id' => $kp->id,
                'nama_kelas' => $kp->nama_kelas,
                'display_label' => $kp->display_label,
            ];
        });
    }

    public function getStudentDropdownOptions(int $prodiId, string $angkatan, ?int $tahunAkademikId = null): Collection
    {
        $angkatan = $this->normalizeAngkatan($angkatan);

        return KelasPerkuliahan::with(['prodi', 'tahunAkademik'])
            ->where('prodi_id', $prodiId)
            ->where('angkatan', $angkatan)
            ->when($tahunAkademikId, fn ($query) => $query->where('tahun_akademik_id', $tahunAkademikId))
            ->orderBy('kode_kelas')
            ->get()
            ->map(function (KelasPerkuliahan $kp) {
                return [
                    'id' => $kp->id,
                    'nama_kelas' => $kp->nama_kelas,
                    'display_label' => $kp->display_label,
                    'angkatan' => $kp->angkatan,
                    'prodi_id' => $kp->prodi_id,
                    'tahun_akademik_id' => $kp->tahun_akademik_id,
                    'tahun_akademik_label' => $kp->tahunAkademik?->display_label,
                ];
            });
    }

    protected function normalizeAttributes(array $attributes, ?KelasPerkuliahan $existing = null): array
    {
        $angkatan = $this->normalizeAngkatan($attributes['angkatan'] ?? $existing?->angkatan ?? '');
        $kodeProdi = strtoupper(trim((string) ($attributes['kode_prodi'] ?? $existing?->kode_prodi ?? '')));
        $kodeKelas = str_pad(trim((string) ($attributes['kode_kelas'] ?? $existing?->kode_kelas ?? '')), 2, '0', STR_PAD_LEFT);

        return [
            'angkatan' => $angkatan,
            'tingkat' => (int) ($attributes['tingkat'] ?? $existing?->tingkat ?? 0),
            'kode_prodi' => $kodeProdi,
            'kode_kelas' => $kodeKelas,
            'prodi_id' => $attributes['prodi_id'] ?? $existing?->prodi_id,
            'tahun_akademik_id' => $attributes['tahun_akademik_id'] ?? $existing?->tahun_akademik_id,
            'nama_kelas' => $this->generateNamaKelas($angkatan, $kodeProdi, $kodeKelas),
        ];
    }

    protected function findExistingByIdentity(array $attributes, ?int $ignoreId = null): ?KelasPerkuliahan
    {
        $normalized = $this->normalizeAttributes($attributes);

        return KelasPerkuliahan::query()
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->where('angkatan', $normalized['angkatan'])
            ->where('prodi_id', $normalized['prodi_id'])
            ->where('kode_kelas', $normalized['kode_kelas'])
            ->where(function ($query) use ($normalized) {
                if ($normalized['tahun_akademik_id']) {
                    $query->where('tahun_akademik_id', $normalized['tahun_akademik_id']);
                    return;
                }

                $query->whereNull('tahun_akademik_id');
            })
            ->first();
    }
}
