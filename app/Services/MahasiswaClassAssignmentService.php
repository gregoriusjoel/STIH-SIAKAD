<?php

namespace App\Services;

use App\Models\KelasPerkuliahan;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\Semester;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MahasiswaClassAssignmentService
{
    public function __construct(
        protected KelasPerkuliahanService $kelasPerkuliahanService,
        protected AuditLogService $auditLogService
    ) {
    }

    public function getActiveAcademicYear(): ?Semester
    {
        return Semester::where('status', 'aktif')->first()
            ?? Semester::where('is_active', true)->first()
            ?? Semester::latest('id')->first();
    }

    public function resolveProdi(?int $prodiId = null, ?string $prodiName = null): ?Prodi
    {
        if ($prodiId) {
            return Prodi::find($prodiId);
        }

        if (!$prodiName) {
            return null;
        }

        $normalized = strtolower(trim($prodiName));

        return Prodi::query()
            ->whereRaw('LOWER(TRIM(nama_prodi)) = ?', [$normalized])
            ->orWhereRaw('LOWER(TRIM(kode_prodi)) = ?', [$normalized])
            ->first();
    }

    public function resolveAcademicYear(?int $tahunAkademikId = null): ?Semester
    {
        if ($tahunAkademikId) {
            return Semester::find($tahunAkademikId);
        }

        return $this->getActiveAcademicYear();
    }

    public function prepareMahasiswaPayload(array $validated, ?Mahasiswa $existing = null): array
    {
        $angkatan = $this->kelasPerkuliahanService->normalizeAngkatan($validated['angkatan'] ?? $existing?->angkatan ?? '');
        $prodi = $this->resolveProdi(
            isset($validated['prodi_id']) ? (int) $validated['prodi_id'] : $existing?->prodi_id,
            $validated['prodi'] ?? $existing?->prodi
        );

        if (!$prodi) {
            throw ValidationException::withMessages([
                'prodi_id' => 'Program studi tidak ditemukan.',
            ]);
        }

        $status = $validated['status'] ?? $existing?->status ?? 'aktif';
        $tahunAkademik = $this->resolveAcademicYear(
            isset($validated['tahun_akademik_id']) ? (int) $validated['tahun_akademik_id'] : $existing?->tahun_akademik_id
        );

        $selectedClass = $this->validateSelectedClass(
            isset($validated['kelas_perkuliahan_id']) ? (int) $validated['kelas_perkuliahan_id'] : null,
            $prodi,
            $angkatan,
            $tahunAkademik,
            false
        );

        return [
            'nim' => $validated['nim'],
            'prodi' => $prodi->nama_prodi,
            'prodi_id' => $prodi->id,
            'angkatan' => $angkatan,
            'semester' => (int) $validated['semester'],
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? $existing?->jenis_kelamin,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'email_pribadi' => $validated['email_pribadi'] ?? null,
            'email_kampus' => $validated['email_kampus'],
            'email_aktif' => 'kampus',
            'status' => $status,
            'tahun_akademik_id' => $selectedClass?->tahun_akademik_id ?? $tahunAkademik?->id,
            'kelas_perkuliahan_id' => $selectedClass?->id,
        ];
    }

    public function validateSelectedClass(
        ?int $kelasPerkuliahanId,
        Prodi $prodi,
        string $angkatan,
        ?Semester $tahunAkademik,
        bool $required = false
    ): ?KelasPerkuliahan {
        if ($required && !$kelasPerkuliahanId) {
            throw ValidationException::withMessages([
                'kelas_perkuliahan_id' => 'Kelas wajib dipilih untuk mahasiswa aktif.',
            ]);
        }

        if (!$kelasPerkuliahanId) {
            return null;
        }

        if (!$tahunAkademik) {
            throw ValidationException::withMessages([
                'kelas_perkuliahan_id' => 'Tahun akademik aktif tidak ditemukan.',
            ]);
        }

        $kelas = KelasPerkuliahan::with(['prodi', 'tahunAkademik'])->find($kelasPerkuliahanId);

        if (!$kelas) {
            throw ValidationException::withMessages([
                'kelas_perkuliahan_id' => 'Kelas tidak ditemukan.',
            ]);
        }

        $validator = Validator::make(
            [
                'prodi_id' => $kelas->prodi_id,
                'angkatan' => $kelas->angkatan,
                'tahun_akademik_id' => $kelas->tahun_akademik_id,
            ],
            [
                'prodi_id' => ['required', 'in:' . $prodi->id],
                'angkatan' => ['required', 'in:' . $angkatan],
                'tahun_akademik_id' => ['required', 'in:' . ($tahunAkademik?->id ?? '0')],
            ],
            [
                'prodi_id.in' => 'Kelas tidak sesuai prodi mahasiswa.',
                'angkatan.in' => 'Kelas tidak sesuai angkatan mahasiswa.',
                'tahun_akademik_id.in' => 'Kelas tidak sesuai tahun akademik aktif.',
            ]
        );

        if ($validator->fails()) {
            throw ValidationException::withMessages([
                'kelas_perkuliahan_id' => $validator->errors()->all(),
            ]);
        }

        return $kelas;
    }

    public function findLegacyMatchingClass(Mahasiswa $mahasiswa, ?string $legacySection = null, ?Semester $tahunAkademik = null): ?KelasPerkuliahan
    {
        $prodi = $this->resolveProdi($mahasiswa->prodi_id, $mahasiswa->prodi);

        if (!$prodi || !$mahasiswa->semester) {
            return null;
        }

        $tahunAkademik ??= $this->resolveAcademicYear($mahasiswa->tahun_akademik_id);
        $query = KelasPerkuliahan::query()
            ->where('prodi_id', $prodi->id);

        if ($mahasiswa->angkatan) {
            $query->where('angkatan', $this->kelasPerkuliahanService->normalizeAngkatan($mahasiswa->angkatan));
        } else {
            $query->where('tingkat', $this->kelasPerkuliahanService->calculateTingkatFromSemester((int) $mahasiswa->semester));
        }

        if ($tahunAkademik) {
            $query->where('tahun_akademik_id', $tahunAkademik->id);
        }

        $normalizedLegacySection = $this->normalizeLegacySection($legacySection);

        if ($normalizedLegacySection) {
            $query->where(function ($builder) use ($normalizedLegacySection) {
                $builder->where('kode_kelas', $normalizedLegacySection)
                    ->orWhere('nama_kelas', $normalizedLegacySection);
            });
        }

        return $query
            ->orderBy('kode_kelas')
            ->first();
    }

    public function logClassAssignmentChange(Mahasiswa $mahasiswa, ?KelasPerkuliahan $before, ?KelasPerkuliahan $after, string $action = 'mahasiswa.class_assignment_updated'): void
    {
        if (($before?->id ?? null) === ($after?->id ?? null)) {
            return;
        }

        $this->auditLogService->log(
            $action,
            Mahasiswa::class,
            $mahasiswa->id,
            $this->snapshotClass($before),
            $this->snapshotClass($after),
            [
                'mahasiswa_id' => $mahasiswa->id,
                'mahasiswa_nim' => $mahasiswa->nim,
                'mahasiswa_nama' => $mahasiswa->user?->name,
            ]
        );
    }

    public function normalizeLegacySection(?string $section): ?string
    {
        if (!$section) {
            return null;
        }

        $normalized = strtoupper(trim($section));

        return $normalized === '' ? null : $normalized;
    }

    protected function snapshotClass(?KelasPerkuliahan $kelas): ?array
    {
        if (!$kelas) {
            return null;
        }

        return [
            'id' => $kelas->id,
            'nama_kelas' => $kelas->nama_kelas,
            'display_label' => $kelas->display_label,
            'prodi_id' => $kelas->prodi_id,
            'prodi' => $kelas->prodi?->nama_prodi,
            'angkatan' => $kelas->angkatan,
            'tingkat' => $kelas->tingkat,
            'tahun_akademik_id' => $kelas->tahun_akademik_id,
            'tahun_akademik' => $kelas->tahunAkademik?->display_label,
        ];
    }
}
