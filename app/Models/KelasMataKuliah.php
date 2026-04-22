<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\KelasPerkuliahan;
use App\Traits\Auditable;

class KelasMataKuliah extends Model
{
    use Auditable;

    protected $fillable = [
        'mata_kuliah_id',
        'dosen_id',
        'semester_id',
        'kode_kelas',
        'kapasitas',
        'ruang',
        'ruangan_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'metode_pengajaran',
        'online_link',
        'online_meeting_link',
        'asynchronous_tugas',
        'asynchronous_file',
        'qr_token',
        'qr_enabled',
        'qr_expires_at',
        'meeting_count',
        'qr_current_pertemuan',
        'kelas_perkuliahan_id',
    ];

    protected $casts = [
        'qr_enabled' => 'boolean',
        'qr_expires_at' => 'datetime',
    ];

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function jadwal(): HasOne
    {
        // jadwals table uses `kelas_id` as foreign key; map it here
        return $this->hasOne(Jadwal::class, 'kelas_id', 'id');
    }

    public function krs(): HasMany
    {
        return $this->hasMany(Krs::class);
    }

    public function pertemuans(): HasMany
    {
        return $this->hasMany(Pertemuan::class);
    }
    
    /**
     * Scope to filter classes by active semester (including grace period)
     * This ensures only current semester + grace period classes are shown
     */
    public function scopeActiveClasses($query)
    {
        $semesterService = app(\App\Services\SemesterService::class);
        $activeSemesterIds = $semesterService->getActiveSemesterIds();
        
        return $query->whereIn('semester_id', $activeSemesterIds);
    }

    /**
     * Scope to filter by specific semester
     */
    public function scopeForSemester($query, $semesterId)
    {
        return $query->where('semester_id', $semesterId);
    }

    /**
     * Scope to get classes for current active semester only (no grace period)
     */
    public function scopeCurrentSemester($query)
    {
        $semesterService = app(\App\Services\SemesterService::class);
        $activeSemester = $semesterService->getActiveSemester();
        
        if ($activeSemester) {
            return $query->where('semester_id', $activeSemester->id);
        }
        
        return $query->whereNull('semester_id'); // Return nothing if no active semester
    }

    /**
     * Check if this class is from an active semester
     */
    public function isFromActiveSemester(): bool
    {
        if (!$this->semester_id) {
            return false;
        }

        $semesterService = app(\App\Services\SemesterService::class);
        return in_array($this->semester_id, $semesterService->getActiveSemesterIds());
    }
    /**
     * Get the Kelas Perkuliahan master data (new dynamic system)
     */
    public function kelasPerkuliahan(): BelongsTo
    {
        return $this->belongsTo(KelasPerkuliahan::class);
    }

    /**
     * Accessor for backward compatibility.
     * Prioritizes the new KelasPerkuliahan relation, falls back to legacy kode_kelas.
     */
    public function getNamaKelasAttribute()
    {
        if ($this->kelas_perkuliahan_id && $this->relationLoaded('kelasPerkuliahan') && $this->kelasPerkuliahan) {
            return $this->kelasPerkuliahan->nama_kelas;
        }

        return $this->kode_kelas;
    }

    /**
     * Check if this record uses legacy data (no KelasPerkuliahan relation).
     */
    public function getIsLegacyAttribute(): bool
    {
        return empty($this->kelas_perkuliahan_id);
    }
}
