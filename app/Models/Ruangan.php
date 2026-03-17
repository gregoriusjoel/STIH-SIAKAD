<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\Auditable;

class Ruangan extends Model
{
    use Auditable;

    protected $fillable = [
        'kode_ruangan',
        'nama_ruangan', 
        'gedung',
        'lantai',
        'kapasitas',
        'status'
    ];

    protected $casts = [
        'lantai' => 'integer',
        'kapasitas' => 'integer',
    ];

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }

    public function jadwalProposals(): HasMany
    {
        return $this->hasMany(JadwalProposal::class);
    }

    public function kelasMataKuliahs(): HasMany
    {
        return $this->hasMany(KelasMataKuliah::class);
    }

    // Scope for active rooms
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Get full room name
    public function getFullNameAttribute()
    {
        return $this->nama_ruangan . ' (' . $this->kode_ruangan . ')';
    }
}
