<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'mata_kuliah_id',
        'dosen_id',
        'section',
        'kapasitas',
        'tahun_ajaran',
        'semester_type',
    ];

    /**
     * Get the course for this class
     */
    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }

    /**
     * Get the dosen (lecturer) for this class
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    /**
     * Get all schedules for this class
     */
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }
    public function krs(): HasMany
    {
        return $this->hasMany(Krs::class);
    }

    public function mahasiswas()
    {
        // KRS status values have changed over time (indonesian: 'disetujui', english: 'approved').
        // Accept either so students with approved KRS are included in the class participant list.
        return $this->belongsToMany(Mahasiswa::class, 'krs', 'kelas_id', 'mahasiswa_id')
            ->withPivot('status')
            ->wherePivotIn('status', ['approved', 'disetujui']);
    }
}
