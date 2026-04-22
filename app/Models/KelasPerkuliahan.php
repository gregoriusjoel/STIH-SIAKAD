<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Auditable;

class KelasPerkuliahan extends Model
{
    use SoftDeletes, Auditable;

    protected $table = 'kelas_perkuliahans';

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'kode_prodi',
        'kode_kelas',
        'prodi_id',
        'tahun_akademik_id',
    ];

    protected $casts = [
        'tingkat' => 'integer',
    ];

    /* ─── Boot: Auto-generate nama_kelas ─── */

    protected static function booted(): void
    {
        static::saving(function (KelasPerkuliahan $model) {
            $model->nama_kelas = $model->tingkat . $model->kode_prodi . $model->kode_kelas;
        });
    }

    /* ─── Relationships ─── */

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function tahunAkademik(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'tahun_akademik_id');
    }

    public function kelasMataKuliahs(): HasMany
    {
        return $this->hasMany(KelasMataKuliah::class, 'kelas_perkuliahan_id');
    }

    public function kelasLegacy(): HasMany
    {
        return $this->hasMany(Kelas::class, 'kelas_perkuliahan_id');
    }

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'kelas_perkuliahan_id');
    }

    public function mahasiswas(): HasMany
    {
        return $this->hasMany(Mahasiswa::class, 'kelas_perkuliahan_id');
    }

    /* ─── Accessors ─── */

    /**
     * Display label for dropdowns and UI.
     * Format: "1HK01 - Hukum Tingkat 1 Kelas 01"
     */
    public function getDisplayLabelAttribute(): string
    {
        $prodiNama = $this->prodi?->nama_prodi ?? $this->kode_prodi;

        return "{$this->nama_kelas} - {$prodiNama} Tingkat {$this->tingkat} Kelas {$this->kode_kelas}";
    }

    /**
     * Short label for compact display.
     * Format: "1HK01"
     */
    public function getShortLabelAttribute(): string
    {
        return $this->nama_kelas;
    }

    /* ─── Scopes ─── */

    public function scopeByProdi($query, int $prodiId)
    {
        return $query->where('prodi_id', $prodiId);
    }

    public function scopeByTingkat($query, int $tingkat)
    {
        return $query->where('tingkat', $tingkat);
    }

    public function scopeByTahunAkademik($query, int $tahunAkademikId)
    {
        return $query->where('tahun_akademik_id', $tahunAkademikId);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }
}
