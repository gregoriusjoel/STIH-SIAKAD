<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dosen extends Model
{
    protected $fillable = [
        'user_id',
        'nidn',
        'pendidikan',
        'pendidikan_terakhir',
        'universitas',
        'prodi',
        'phone',
        'address',
        'status',
        'mata_kuliah_ids',
        'dosen_tetap',
        'jabatan_fungsional',
        'kuota',
    ];

    protected $casts = [
        'mata_kuliah_ids' => 'array',
        'prodi' => 'array',
        'pendidikan_terakhir' => 'array',
        'universitas' => 'array',
        'jabatan_fungsional' => 'array',
        'dosen_tetap' => 'boolean',
        'kuota' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getNamaAttribute()
    {
        return $this->user->name ?? 'Unknown';
    }

    public function jadwalProposals()
    {
        return $this->hasMany(JadwalProposal::class);
    }

    public function kelasMataKuliahs(): HasMany
    {
        return $this->hasMany(KelasMataKuliah::class);
    }

    public function mataKuliahs(): BelongsToMany
    {
        return $this->belongsToMany(MataKuliah::class, 'dosen_mata_kuliah');
    }

    public function mahasiswaPa()
    {
        return $this->belongsToMany(Mahasiswa::class, 'dosen_pa', 'dosen_id', 'mahasiswa_id')->withTimestamps();
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(DosenAvailability::class);
    }

    public function dosenAttendances(): HasMany
    {
        return $this->hasMany(DosenAttendance::class);
    }
}
