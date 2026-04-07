<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\Auditable;

class Mahasiswa extends Model
{
    use Auditable;

    /**
     * Get the S3 public URL for the student's photo, or null if not set.
     */
    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->foto) {
            return null;
        }

        return \Illuminate\Support\Facades\Storage::disk('s3')->url($this->foto);
    }

    /**
     * Get the S3 public URL for an arbitrary file path.
     * Usage in Blade: {{ $mahasiswa->s3Url($file) }}
     */
    public function s3Url(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return \Illuminate\Support\Facades\Storage::disk('s3')->url($path);
    }


    protected $table = 'mahasiswas';

    protected $fillable = [
        'user_id',
        'nim',
        'nim',
        'prodi',
        'angkatan',
        'semester',
        'last_semester_id',
        'phone',
        'address',
        'status',
        'status_akun',
        'foto',
        'no_hp',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'status_sipil',
        'rt',
        'rw',
        'kota',
        'kecamatan',
        'desa',
        'provinsi',
        'negara',
        'jenis_sekolah',
        'jurusan_sekolah',
        'tahun_lulus',
        'nilai_kelulusan',
        'file_ijazah',
        'file_transkrip',
        'file_kk',
        'file_ktp',
        'alamat_ktp',
        'rt_ktp',
        'rw_ktp',
        'provinsi_ktp',
        'kota_ktp',
        'kecamatan_ktp',
        'desa_ktp',
        'is_dokumen_unlocked',
    ];

    protected $casts = [
        'file_ijazah' => 'array',
        'file_transkrip' => 'array',
        'file_kk' => 'array',
        'file_ktp' => 'array',
        'is_dokumen_unlocked' => 'boolean',
    ];

    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Payment System Relationships
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'student_id');
    }

    public function installmentRequests(): HasMany
    {
        return $this->hasMany(InstallmentRequest::class, 'student_id');
    }

    // Payment System Accessors
    public function getNpmAttribute()
    {
        return $this->nim;
    }

    public function getNamaAttribute()
    {
        return $this->user?->name ?? '';
    }

    public function parents(): HasMany
    {
        return $this->hasMany(ParentModel::class);
    }

    public function krs(): HasMany
    {
        return $this->hasMany(Krs::class);
    }

    public function kuesionerAktivasi(): HasMany
    {
        return $this->hasMany(KuesionerAktivasi::class);
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function lastSemester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'last_semester_id');
    }

    public function isAktif(): bool
    {
        return $this->status_akun === 'aktif' || $this->status_akun === 'baru';
    }

    /**
     * Calculate the current semester based on angkatan and current semester period
     */
    public function getCurrentSemester(): int
    {
        // If the semester is explicitly stored on the mahasiswa record, prefer it.
        if ($this->semester && is_numeric($this->semester)) {
            return max(1, min(8, (int) $this->semester));
        }

        if (!$this->angkatan) {
            return 1;
        }

        // Get active semester to determine which academic year and period we're in
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();

        if (!$activeSemester) {
            return 1;
        }

        // Parse tahun ajaran (format: "2025/2026")
        $tahunParts = explode('/', $activeSemester->tahun_ajaran);
        $currentYear = (int) ($tahunParts[0] ?? date('Y'));

        // Calculate years since enrollment
        $angkatanYear = (int) $this->angkatan;
        $yearsDiff = $currentYear - $angkatanYear;

        // Calculate semester: 2 semesters per year
        $baseSemester = ($yearsDiff * 2);

        // Add 1 if currently in Genap semester, 0 if Ganjil
        if ($activeSemester->nama_semester === 'Genap') {
            $baseSemester += 2;
        } else {
            $baseSemester += 1;
        }

        // Limit to reasonable range (1-8 for undergraduate)
        return max(1, min(8, $baseSemester));
    }

    /**
     * Get current semester information as an object
     * Returns an object containing semester details
     */
    public function getCurrentSemesterInfo(): object
    {
        $semesterNumber = $this->getCurrentSemester();

        // Try to get the active semester record for additional metadata
        // Priority: status='aktif' -> is_active=true -> latest
        $activeSemester = \App\Models\Semester::where('status', 'aktif')->first() 
            ?? \App\Models\Semester::where('is_active', true)->first()
            ?? \App\Models\Semester::latest()->first();

        $tahunAjaran = $activeSemester->tahun_ajaran ?? null;
        $namaSemester = $activeSemester->nama_semester ?? (($semesterNumber % 2 === 1) ? 'Ganjil' : 'Genap');

        return (object) [
            'semester_number' => $semesterNumber,
            'semester' => $semesterNumber,
            'angkatan' => $this->angkatan,
            'tahun_ajaran' => $tahunAjaran,
            'nama_semester' => $namaSemester,
            'semester_obj' => $activeSemester,
        ];
    }

    /**
     * Get past semesters for which the student has submitted KRS
     * Returns a collection of objects with keys: semester_number, semester_display, tahun_ajaran, nama_semester
     */
    public function getPastSemesters()
    {
        // Find submitted KRS entries (not draft) and collect distinct semester numbers
        // We also want to get the tahun_ajaran associated with that semester's KRS
        // This is tricky because KRS -> Kelas -> tahun_ajaran

        $history = \App\Models\Krs::where('mahasiswa_id', $this->id)
            ->where('status', '!=', 'draft')
            ->with(['mataKuliah', 'kelas'])
            ->get()
            ->groupBy(function ($krs) {
                // Group by semester code from mata kuliah (e.g. sms1, sms2)
                return $krs->mataKuliah->kode_id ?? 'unknown';
            });

        $collection = collect();

        foreach ($history as $kodeId => $krsItems) {
            // Check if valid code
            if (!preg_match('/sms(\d+)/', $kodeId, $matches)) {
                continue;
            }
            $semNum = (int) $matches[1];

            // Try to find a tahun_ajaran from the kelas of these KRS items
            // We take the most frequent or first one found
            $tahunAjaran = null;
            foreach ($krsItems as $krs) {
                if ($krs->kelas && $krs->kelas->tahun_ajaran) {
                    $tahunAjaran = $krs->kelas->tahun_ajaran;
                    break;
                }
            }

            // Fallback: calculate tahun_ajaran based on angkatan and semester number
            if (!$tahunAjaran) {
                $baseYear = (int) $this->angkatan;
                // Calculate which academic year this semester falls into
                // Semester 1-2: First year, Semester 3-4: Second year, etc.
                $yearOffset = floor(($semNum - 1) / 2);
                $academicStartYear = $baseYear + $yearOffset;
                $academicEndYear = $academicStartYear + 1;
                $tahunAjaran = $academicStartYear . '/' . $academicEndYear;
            }

            $collection->push((object) [
                'semester_number' => $semNum,
                'semester_display' => 'Semester ' . $semNum,
                'tahun_ajaran' => $tahunAjaran,
                'nama_semester' => ($semNum % 2 === 1) ? 'Ganjil' : 'Genap',
            ]);
        }

        return $collection->sortByDesc('semester_number')->values();
    }

    public function dosenPa()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_pa', 'mahasiswa_id', 'dosen_id')->withTimestamps();
    }

    public function internships(): HasMany
    {
        return $this->hasMany(Internship::class);
    }

    /**
     * Get active/ongoing internship for this mahasiswa (if any).
     */
    public function activeInternship()
    {
        return $this->internships()->whereIn('status', [
            Internship::STATUS_ONGOING,
            Internship::STATUS_SUPERVISOR_ASSIGNED,
            Internship::STATUS_ACCEPTANCE_LETTER_READY,
        ])->latest()->first();
    }

    /**
     * Check if the student profile is complete
     * Profile is complete when all required fields are filled
     */
    public function isProfileComplete(): bool
    {
        // Check mahasiswa personal data
        $requiredMahasiswaFields = [
            'no_hp',
            'alamat',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'agama',
            'status_sipil',
            'kota',
            'kecamatan',
            'kecamatan_ktp',
            'provinsi',
            'desa',
            'jenis_sekolah',
            'jurusan_sekolah',
            'tahun_lulus',
            'nilai_kelulusan',
        ];

        foreach ($requiredMahasiswaFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }

        // Check required documents (Dokumen Pribadi)
        $requiredDocuments = [
            'file_ijazah',
            'file_transkrip',
            'file_kk',
            'file_ktp',
        ];

        foreach ($requiredDocuments as $docField) {
            if (empty($this->$docField) || !is_array($this->$docField) || count($this->$docField) === 0) {
                return false;
            }
        }

        // Check if parent data exists and is complete
        $parent = $this->parents()->first();
        if (!$parent) {
            return false;
        }

        // Required Parent (Orang Tua) fields
        $requiredParentFields = [
            'nama_ayah',
            'pendidikan_ayah',
            'pekerjaan_ayah',
            'agama_ayah',
            'nama_ibu',
            'pendidikan_ibu',
            'pekerjaan_ibu',
            'agama_ibu',
            'alamat_ayah',
            'kota_ayah',
            'kecamatan_ayah',
            'propinsi_ayah',
            'desa_ayah',
            'handphone_ayah',
            'alamat_ibu',
            'kota_ibu',
            'kecamatan_ibu',
            'propinsi_ibu',
            'desa_ibu',
            'handphone_ibu',
        ];

        // Required Wali (Guardian) fields
        $requiredWaliFields = [
            'nama_wali',
            'hubungan_wali',
            'pendidikan_wali',
            'pekerjaan_wali',
            'agama_wali',
            'alamat_wali',
            'kota_wali',
            'kecamatan_wali',
            'provinsi_wali',
            'desa_wali',
            'handphone_wali',
        ];

        // Check if Parent has ANY data filled
        $parentHasAnyData = false;
        $parentComplete = true;
        foreach ($requiredParentFields as $field) {
            if (!empty($parent->$field)) {
                $parentHasAnyData = true;
            } else {
                $parentComplete = false;
            }
        }

        // Check if Wali has ANY data filled (at least one field)
        $waliHasAnyData = false;
        $waliComplete = true;
        foreach ($requiredWaliFields as $field) {
            if (!empty($parent->$field)) {
                $waliHasAnyData = true;
            } else {
                $waliComplete = false;
            }
        }

        // Independent validation logic:
        // - If Parent started but incomplete -> False
        // - If Wali started but incomplete -> False
        // - If neither started -> False (must fill at least one)
        // - If (Parent complete OR empty) AND (Wali complete OR empty) AND (at least one is complete) -> True

        if ($parentHasAnyData && !$parentComplete)
            return false;
        if ($waliHasAnyData && !$waliComplete)
            return false;

        if (!$parentHasAnyData && !$waliHasAnyData)
            return false;

        return true;
    }

    /**
     * Get list of missing profile fields
     * Returns array with field names and their tab locations
     */
    public function getMissingProfileFields(): array
    {
        $missing = [];

        // Required mahasiswa fields with their tab locations
        $requiredMahasiswaFields = [
            'no_hp' => ['label' => 'No. HP', 'tab' => 'akademik'],
            'alamat' => ['label' => 'Alamat Domisili', 'tab' => 'data_pribadi'],
            'rt' => ['label' => 'RT', 'tab' => 'data_pribadi'],
            'rw' => ['label' => 'RW', 'tab' => 'data_pribadi'],
            'tempat_lahir' => ['label' => 'Tempat Lahir', 'tab' => 'data_pribadi'],
            'tanggal_lahir' => ['label' => 'Tanggal Lahir', 'tab' => 'data_pribadi'],
            'jenis_kelamin' => ['label' => 'Jenis Kelamin', 'tab' => 'data_pribadi'],
            'agama' => ['label' => 'Agama', 'tab' => 'data_pribadi'],
            'status_sipil' => ['label' => 'Status Sipil', 'tab' => 'data_pribadi'],
            'kota' => ['label' => 'Kota/Kabupaten', 'tab' => 'data_pribadi'],
            'provinsi' => ['label' => 'Provinsi', 'tab' => 'data_pribadi'],
            'kecamatan' => ['label' => 'Kecamatan', 'tab' => 'data_pribadi'],
            'desa' => ['label' => 'Desa/Kelurahan', 'tab' => 'data_pribadi'],
            // Alamat Sesuai KTP fields
            'alamat_ktp' => ['label' => 'Alamat KTP', 'tab' => 'data_pribadi'],
            'rt_ktp' => ['label' => 'RT KTP', 'tab' => 'data_pribadi'],
            'rw_ktp' => ['label' => 'RW KTP', 'tab' => 'data_pribadi'],
            'provinsi_ktp' => ['label' => 'Provinsi KTP', 'tab' => 'data_pribadi'],
            'kota_ktp' => ['label' => 'Kota/Kab. KTP', 'tab' => 'data_pribadi'],
            'kecamatan_ktp' => ['label' => 'Kecamatan KTP', 'tab' => 'data_pribadi'],
            'desa_ktp' => ['label' => 'Desa KTP', 'tab' => 'data_pribadi'],
            // Asal Sekolah fields
            'jenis_sekolah' => ['label' => 'Jenis Sekolah', 'tab' => 'asal_sekolah'],
            'jurusan_sekolah' => ['label' => 'Jurusan Sekolah', 'tab' => 'asal_sekolah'],
            'tahun_lulus' => ['label' => 'Tahun Lulus', 'tab' => 'asal_sekolah'],
            'nilai_kelulusan' => ['label' => 'Nilai Kelulusan', 'tab' => 'asal_sekolah'],
        ];

        foreach ($requiredMahasiswaFields as $field => $info) {
            if (empty($this->$field)) {
                $missing[$field] = $info;
            }
        }

        // Required documents
        $requiredDocuments = [
            'file_ijazah' => ['label' => 'Ijazah', 'tab' => 'data_pribadi'],
            'file_transkrip' => ['label' => 'Transkrip Nilai', 'tab' => 'data_pribadi'],
            'file_kk' => ['label' => 'Kartu Keluarga (KK)', 'tab' => 'data_pribadi'],
            'file_ktp' => ['label' => 'KTP', 'tab' => 'data_pribadi'],
        ];

        foreach ($requiredDocuments as $field => $info) {
            if (empty($this->$field) || !is_array($this->$field) || count($this->$field) === 0) {
                $missing[$field] = $info;
            }
        }

        // Required parent/wali fields - conditional validation
        $parent = $this->parents()->first();

        // Define required fields for Parent (Orang Tua)
        $requiredParentFields = [
            'nama_ayah' => ['label' => 'Nama Ayah', 'tab' => 'orang_tua'],
            'pendidikan_ayah' => ['label' => 'Pendidikan Ayah', 'tab' => 'orang_tua'],
            'pekerjaan_ayah' => ['label' => 'Pekerjaan Ayah', 'tab' => 'orang_tua'],
            'agama_ayah' => ['label' => 'Agama Ayah', 'tab' => 'orang_tua'],
            'nama_ibu' => ['label' => 'Nama Ibu', 'tab' => 'orang_tua'],
            'pendidikan_ibu' => ['label' => 'Pendidikan Ibu', 'tab' => 'orang_tua'],
            'pekerjaan_ibu' => ['label' => 'Pekerjaan Ibu', 'tab' => 'orang_tua'],
            'agama_ibu' => ['label' => 'Agama Ibu', 'tab' => 'orang_tua'],
            'alamat_ayah' => ['label' => 'Alamat Ayah', 'tab' => 'orang_tua'],
            'kota_ayah' => ['label' => 'Kota Ayah', 'tab' => 'orang_tua'],
            'propinsi_ayah' => ['label' => 'Provinsi Ayah', 'tab' => 'orang_tua'],
            'kecamatan_ayah' => ['label' => 'Kecamatan Ayah', 'tab' => 'orang_tua'],
            'desa_ayah' => ['label' => 'Desa Ayah', 'tab' => 'orang_tua'],
            'handphone_ayah' => ['label' => 'Handphone Ayah', 'tab' => 'orang_tua'],
            'alamat_ibu' => ['label' => 'Alamat Ibu', 'tab' => 'orang_tua'],
            'kota_ibu' => ['label' => 'Kota Ibu', 'tab' => 'orang_tua'],
            'propinsi_ibu' => ['label' => 'Provinsi Ibu', 'tab' => 'orang_tua'],
            'kecamatan_ibu' => ['label' => 'Kecamatan Ibu', 'tab' => 'orang_tua'],
            'desa_ibu' => ['label' => 'Desa Ibu', 'tab' => 'orang_tua'],
            'handphone_ibu' => ['label' => 'Handphone Ibu', 'tab' => 'orang_tua'],
        ];

        // Define required fields for Wali (Guardian)
        $requiredWaliFields = [
            'nama_wali' => ['label' => 'Nama Wali', 'tab' => 'orang_tua'],
            'hubungan_wali' => ['label' => 'Hubungan Wali', 'tab' => 'orang_tua'],
            'pendidikan_wali' => ['label' => 'Pendidikan Wali', 'tab' => 'orang_tua'],
            'pekerjaan_wali' => ['label' => 'Pekerjaan Wali', 'tab' => 'orang_tua'],
            'agama_wali' => ['label' => 'Agama Wali', 'tab' => 'orang_tua'],
            'alamat_wali' => ['label' => 'Alamat Wali', 'tab' => 'orang_tua'],
            'kota_wali' => ['label' => 'Kota Wali', 'tab' => 'orang_tua'],
            'provinsi_wali' => ['label' => 'Provinsi Wali', 'tab' => 'orang_tua'],
            'kecamatan_wali' => ['label' => 'Kecamatan Wali', 'tab' => 'orang_tua'],
            'desa_wali' => ['label' => 'Desa Wali', 'tab' => 'orang_tua'],
            'handphone_wali' => ['label' => 'Handphone Wali', 'tab' => 'orang_tua'],
        ];

        if (!$parent) {
            // No parent record exists - show Parent fields as missing (default)
            foreach ($requiredParentFields as $field => $info) {
                $missing[$field] = $info;
            }
        } else {
            // Check if Parent data is complete
            $parentComplete = true;
            $parentMissing = [];
            foreach ($requiredParentFields as $field => $info) {
                if (empty($parent->$field)) {
                    $parentComplete = false;
                    $parentMissing[$field] = $info;
                }
            }

            // Check if Parent has ANY data filled (at least one field)
            $parentHasAnyData = false;
            foreach (array_keys($requiredParentFields) as $field) {
                if (!empty($parent->$field)) {
                    $parentHasAnyData = true;
                    break;
                }
            }

            // Check if Wali has ANY data filled (at least one field) and collect missing
            $waliHasAnyData = false;
            $waliMissing = [];
            foreach ($requiredWaliFields as $field => $info) {
                if (!empty($parent->$field)) {
                    $waliHasAnyData = true;
                } else {
                    $waliMissing[$field] = $info;
                }
            }

            // Independent validation logic:
            // - If Parent has ANY data filled → show Parent missing fields
            // - If Wali has ANY data filled → show Wali missing fields
            // - If NEITHER has data → show Parent missing (default)
            if ($parentHasAnyData) {
                foreach ($parentMissing as $field => $info) {
                    $missing[$field] = $info;
                }
            }

            if ($waliHasAnyData) {
                foreach ($waliMissing as $field => $info) {
                    $missing[$field] = $info;
                }
            }

            // If neither section has been started, show Parent missing as default
            if (!$parentHasAnyData && !$waliHasAnyData) {
                foreach ($parentMissing as $field => $info) {
                    $missing[$field] = $info;
                }
            }

            // Check for missing fields in Data Keluarga Lainnya
            if (!empty($parent->keluarga) && is_array($parent->keluarga)) {
                $requiredFamilyFields = ['nama', 'hubungan', 'pendidikan', 'pekerjaan', 'agama'];
                foreach ($parent->keluarga as $index => $member) {
                    foreach ($requiredFamilyFields as $f) {
                        if (empty($member[$f])) {
                            $missing["keluarga.{$index}.{$f}"] = ['label' => "Keluarga {$index} " . ucfirst($f), 'tab' => 'orang_tua'];
                        }
                    }
                }
            }
        }

        return $missing;
    }
}
