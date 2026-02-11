<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class KelasMataKuliah extends Model
{
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
        'asynchronous_tugas',
        'asynchronous_file',
        'qr_token',
        'qr_enabled',
        'qr_expires_at',
        'meeting_count',
        'qr_current_pertemuan',
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
    
    // Accessor for backward compatibility
    public function getNamaKelasAttribute()
    {
        return $this->kode_kelas;
    }
}
