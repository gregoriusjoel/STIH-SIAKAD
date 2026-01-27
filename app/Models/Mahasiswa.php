<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Model
{
    protected $fillable = [
        'user_id',
        'npm',
        'prodi',
        'angkatan',
        'semester',
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
        'propinsi',
        'negara',
        'jenis_sekolah',
        'jurusan_sekolah',
        'tahun_lulus',
        'nilai_kelulusan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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

    public function dosenPa()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_pa', 'mahasiswa_id', 'dosen_id')->withTimestamps();
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
            'propinsi',
            'negara',
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

        // Check if parent data exists and is complete
        $parent = $this->parents()->first();
        if (!$parent) {
            return false;
        }

        $requiredParentFields = [
            'nama_ayah',
            'pendidikan_ayah',
            'pekerjaan_ayah',
            'agama_ayah',
            'nama_ibu',
            'pendidikan_ibu',
            'pekerjaan_ibu',
            'agama_ibu',
            'alamat_ortu',
            'kota_ortu',
            'propinsi_ortu',
            'negara_ortu',
            'handphone_ortu',
        ];

        foreach ($requiredParentFields as $field) {
            if (empty($parent->$field)) {
                return false;
            }
        }

        return true;
    }
}
