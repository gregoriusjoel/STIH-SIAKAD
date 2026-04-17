<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'status',
        'kategori_id'
    ];

    protected $casts = [
        'lantai' => 'integer',
        'kapasitas' => 'integer',
    ];

    // Relasi dengan KategoriRuangan
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriRuangan::class, 'kategori_id');
    }

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'ruangan', 'kode_ruangan');
    }

    public function jadwalProposals(): HasMany
    {
        return $this->hasMany(JadwalProposal::class, 'ruangan', 'kode_ruangan');
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

    // Scope untuk filter berdasarkan kategori
    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('kategori_id', $kategoriId);
    }

    // Scope untuk filter berdasarkan nama kategori
    public function scopeByKategoriNama($query, $namKategori)
    {
        return $query->whereHas('kategori', function ($q) use ($namKategori) {
            $q->where('nama_kategori', $namKategori);
        });
    }

    // Get full room name
    public function getFullNameAttribute()
    {
        return $this->nama_ruangan . ' (' . $this->kode_ruangan . ')';
    }

    // Get kategori name atau default
    public function getKategoriNameAttribute()
    {
        return $this->kategori?->nama_kategori ?? 'Tidak Dikategorisasi';
    }
}

