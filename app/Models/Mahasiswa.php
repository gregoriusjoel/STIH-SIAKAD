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
        'phone',
        'address',
        'status',
        'status_akun',
        'foto',
        'no_hp',
        'alamat',
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
}
