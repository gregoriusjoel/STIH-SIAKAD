<?php

namespace App\Services;

use App\Models\KelasPerkuliahan;
use App\Models\Prodi;
use App\Models\Semester;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KelasPerkuliahanService
{
    /**
     * Auto-generate nama_kelas from components.
     * Format: [tingkat][kode_prodi][kode_kelas]
     * Example: 1HK01, 2PRWT02
     */
    public function generateNamaKelas(int $tingkat, string $kodeProdi, string $kodeKelas): string
    {
        return $tingkat . $kodeProdi . $kodeKelas;
    }

    /**
     * Find existing or create new KelasPerkuliahan.
     * Uses firstOrCreate to prevent duplicates.
     */
    public function findOrCreate(array $attributes): KelasPerkuliahan
    {
        // Build the unique key for lookup
        $uniqueKey = [
            'tingkat' => $attributes['tingkat'],
            'kode_prodi' => $attributes['kode_prodi'],
            'kode_kelas' => $attributes['kode_kelas'],
        ];

        // Additional attributes to set on create
        $additional = [
            'prodi_id' => $attributes['prodi_id'] ?? null,
            'tahun_akademik_id' => $attributes['tahun_akademik_id'] ?? null,
        ];

        return KelasPerkuliahan::firstOrCreate($uniqueKey, $additional);
    }

    /**
     * Update an existing KelasPerkuliahan.
     * Re-checks unique constraint before updating.
     */
    public function update(KelasPerkuliahan $kelasPerkuliahan, array $attributes): KelasPerkuliahan
    {
        // Check if the new combination already exists (excluding current record)
        $existing = KelasPerkuliahan::where('tingkat', $attributes['tingkat'])
            ->where('kode_prodi', $attributes['kode_prodi'])
            ->where('kode_kelas', $attributes['kode_kelas'])
            ->where('id', '!=', $kelasPerkuliahan->id)
            ->first();

        if ($existing) {
            throw new \InvalidArgumentException(
                "Kelas Perkuliahan dengan kombinasi Tingkat {$attributes['tingkat']}, " .
                "Prodi {$attributes['kode_prodi']}, Kelas {$attributes['kode_kelas']} sudah ada."
            );
        }

        $kelasPerkuliahan->update($attributes);

        return $kelasPerkuliahan->fresh();
    }

    /**
     * Bulk generate kelas perkuliahan for a specific prodi.
     *
     * @param int $prodiId
     * @param int|null $tahunAkademikId
     * @param int $maxTingkat  Number of tingkat levels to generate (e.g. 4)
     * @param int $kelasPerTingkat  Number of classes per tingkat (e.g. 3 → 01,02,03)
     * @return array{created: Collection, skipped: int}
     */
    public function generateForProdi(
        int $prodiId,
        ?int $tahunAkademikId,
        int $maxTingkat = 4,
        int $kelasPerTingkat = 1
    ): array {
        $prodi = Prodi::findOrFail($prodiId);
        $created = collect();
        $skipped = 0;

        DB::beginTransaction();
        try {
            for ($tingkat = 1; $tingkat <= $maxTingkat; $tingkat++) {
                for ($k = 1; $k <= $kelasPerTingkat; $k++) {
                    $kodeKelas = str_pad($k, 2, '0', STR_PAD_LEFT);

                    $kp = $this->findOrCreate([
                        'tingkat' => $tingkat,
                        'kode_prodi' => $prodi->kode_prodi,
                        'kode_kelas' => $kodeKelas,
                        'prodi_id' => $prodiId,
                        'tahun_akademik_id' => $tahunAkademikId,
                    ]);

                    if ($kp->wasRecentlyCreated) {
                        $created->push($kp);
                    } else {
                        $skipped++;
                    }
                }
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return ['created' => $created, 'skipped' => $skipped];
    }

    /**
     * Generate kelas with overwrite capability.
     * Deletes existing kelas for the prodi/tahun first, then creates new ones.
     */
    public function generateForProdiWithOverwrite(
        int $prodiId,
        ?int $tahunAkademikId,
        int $maxTingkat = 4,
        int $kelasPerTingkat = 1
    ): array {
        $prodi = Prodi::findOrFail($prodiId);
        $deleted = 0;
        $created = collect();

        DB::beginTransaction();
        try {
            // Delete existing kelas for this prodi and tahun_akademik
            $query = KelasPerkuliahan::where('prodi_id', $prodiId);
            if ($tahunAkademikId) {
                $query->where('tahun_akademik_id', $tahunAkademikId);
            }
            $deleted = $query->delete();

            // Now generate new kelas
            for ($tingkat = 1; $tingkat <= $maxTingkat; $tingkat++) {
                for ($k = 1; $k <= $kelasPerTingkat; $k++) {
                    $kodeKelas = str_pad($k, 2, '0', STR_PAD_LEFT);
                    $namaKelas = $this->generateNamaKelas($tingkat, $prodi->kode_prodi, $kodeKelas);

                    $kp = KelasPerkuliahan::create([
                        'tingkat' => $tingkat,
                        'kode_prodi' => $prodi->kode_prodi,
                        'kode_kelas' => $kodeKelas,
                        'nama_kelas' => $namaKelas,
                        'prodi_id' => $prodiId,
                        'tahun_akademik_id' => $tahunAkademikId,
                    ]);

                    $created->push($kp);
                }
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return ['created' => $created, 'deleted' => $deleted];
    }

    /**
     * Generate kelas with different student counts per level.
     * Calculates classes needed based on students/level and max_students_per_class.
     * 
     * @param int $prodiId
     * @param int|null $tahunAkademikId
     * @param array $siswaPerTingkat [1 => 100, 2 => 80, 3 => 90, 4 => 70]
     * @param int $maxStudentsPerClass
     */
    public function generateForProdiPerLevel(
        int $prodiId,
        ?int $tahunAkademikId,
        array $siswaPerTingkat,
        int $maxStudentsPerClass = 40
    ): array {
        $prodi = Prodi::findOrFail($prodiId);
        $created = collect();
        $skipped = 0;

        DB::beginTransaction();
        try {
            foreach ($siswaPerTingkat as $tingkat => $siswa) {
                if ($siswa <= 0) {
                    continue;
                }

                // Calculate classes needed for this level
                $kelasNeeded = ceil($siswa / $maxStudentsPerClass);

                for ($k = 1; $k <= $kelasNeeded; $k++) {
                    $kodeKelas = str_pad($k, 2, '0', STR_PAD_LEFT);

                    $kp = $this->findOrCreate([
                        'tingkat' => $tingkat,
                        'kode_prodi' => $prodi->kode_prodi,
                        'kode_kelas' => $kodeKelas,
                        'prodi_id' => $prodiId,
                        'tahun_akademik_id' => $tahunAkademikId,
                    ]);

                    if ($kp->wasRecentlyCreated) {
                        $created->push($kp);
                    } else {
                        $skipped++;
                    }
                }
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return ['created' => $created, 'skipped' => $skipped];
    }

    /**
     * Generate kelas with overwrite capability and different student counts per level.
     */
    public function generateForProdiWithOverwritePerLevel(
        int $prodiId,
        ?int $tahunAkademikId,
        array $siswaPerTingkat,
        int $maxStudentsPerClass = 40
    ): array {
        $prodi = Prodi::findOrFail($prodiId);
        $deleted = 0;
        $created = collect();

        DB::beginTransaction();
        try {
            // Delete existing kelas for this prodi and tahun_akademik
            $query = KelasPerkuliahan::where('prodi_id', $prodiId);
            if ($tahunAkademikId) {
                $query->where('tahun_akademik_id', $tahunAkademikId);
            }
            $deleted = $query->delete();

            // Now generate new kelas per level
            foreach ($siswaPerTingkat as $tingkat => $siswa) {
                if ($siswa <= 0) {
                    continue;
                }

                // Calculate classes needed for this level
                $kelasNeeded = ceil($siswa / $maxStudentsPerClass);

                for ($k = 1; $k <= $kelasNeeded; $k++) {
                    $kodeKelas = str_pad($k, 2, '0', STR_PAD_LEFT);
                    $namaKelas = $this->generateNamaKelas($tingkat, $prodi->kode_prodi, $kodeKelas);

                    $kp = KelasPerkuliahan::create([
                        'tingkat' => $tingkat,
                        'kode_prodi' => $prodi->kode_prodi,
                        'kode_kelas' => $kodeKelas,
                        'nama_kelas' => $namaKelas,
                        'prodi_id' => $prodiId,
                        'tahun_akademik_id' => $tahunAkademikId,
                    ]);

                    $created->push($kp);
                }
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return ['created' => $created, 'deleted' => $deleted];
    }


    /**
     * Get dropdown options for kelas perkuliahan.
     * Returns collection with id, nama_kelas, display_label.
     */
    public function getDropdownOptions(?int $prodiId = null, ?int $tahunAkademikId = null): Collection
    {
        $query = KelasPerkuliahan::with('prodi')
            ->orderBy('tingkat')
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
}
