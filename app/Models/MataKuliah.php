<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataKuliah extends Model
{
    protected $fillable = [
        'kode_id',
        'kode_mk',
        'praktikum',
        'nama_mk',
        'sks',
        'semester',
        'jenis',
        'prodi_id',
        'fakultas_id',
        'deskripsi',
    ];

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

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }
}
