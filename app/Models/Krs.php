<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Krs extends Model
{
    protected $table = 'krs';

    protected $fillable = [
        'mahasiswa_id',
        'kelas_id',
        'status',
        'keterangan',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function nilai(): HasOne
    {
        return $this->hasOne(Nilai::class);
    }

    public function presensis(): HasMany
    {
        return $this->hasMany(Presensi::class);
    }
}
