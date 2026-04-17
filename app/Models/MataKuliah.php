<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\Auditable;

class MataKuliah extends Model
{
    use Auditable;

    protected $fillable = [
        'kode_id',
        'kode_mk',
        'praktikum',
        'nama_mk',
        'sks',
        'semester',
        'jenis',
        'tipe',
        'prodi_id',
        'fakultas_id',
        'deskripsi',
    ];

    /**
     * Type enumeration for mata kuliah
     * Used for automatic jadwal generation room category matching
     */
    public const TIPE_TEORI = 'teori';
    public const TIPE_PRAKTIKUM = 'praktikum';
    public const TIPE_SIDANG = 'sidang';
    public const TIPE_LAB = 'lab';

    public static function getTipeOptions(): array
    {
        return [
            self::TIPE_TEORI => 'Teori',
            self::TIPE_PRAKTIKUM => 'Praktikum',
            self::TIPE_SIDANG => 'Sidang',
            self::TIPE_LAB => 'Laboratorium',
        ];
    }

    public function kelasMataKuliahs(): HasMany
    {
        return $this->hasMany(KelasMataKuliah::class);
    }

    /* ─── Semester Pivot ─── */

    public function semesters(): BelongsToMany
    {
        return $this->belongsToMany(Semester::class, 'mata_kuliah_semesters')
            ->withPivot(['status', 'source_semester_id', 'activated_at', 'deactivated_at', 'meta'])
            ->withTimestamps();
    }

    public function activeSemesters(): BelongsToMany
    {
        return $this->semesters()->wherePivot('status', 'active');
    }

    public function mataKuliahSemesters(): HasMany
    {
        return $this->hasMany(MataKuliahSemester::class);
    }

    /* ─── Scopes ─── */

    /**
     * Scope: get MK that are active in a given semester
     */
    public function scopeActiveBySemester($query, int $semesterId)
    {
        return $query->whereHas('mataKuliahSemesters', function ($q) use ($semesterId) {
            $q->where('semester_id', $semesterId)->where('status', 'active');
        });
    }

    /**
     * Scope: get MK that are history in a given semester
     */
    public function scopeHistoryBySemester($query, int $semesterId)
    {
        return $query->whereHas('mataKuliahSemesters', function ($q) use ($semesterId) {
            $q->where('semester_id', $semesterId)->where('status', 'history');
        });
    }

    /**
     * Scope: filter by tipe
     */
    public function scopeByTipe($query, string $tipe)
    {
        return $query->where('tipe', $tipe);
    }

    /**
     * Scope: get teori courses
     */
    public function scopeTeori($query)
    {
        return $query->where('tipe', self::TIPE_TEORI);
    }

    /**
     * Scope: get praktikum courses
     */
    public function scopePraktikum($query)
    {
        return $query->where('tipe', self::TIPE_PRAKTIKUM);
    }

    /**
     * Scope: get sidang courses
     */
    public function scopeSidang($query)
    {
        return $query->where('tipe', self::TIPE_SIDANG);
    }

    /**
     * Scope: get lab courses
     */
    public function scopeLab($query)
    {
        return $query->where('tipe', self::TIPE_LAB);
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }
}
