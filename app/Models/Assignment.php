<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    protected $fillable = [
        'kelas_id', 'dosen_id', 'minggu_ke', 'judul', 'deskripsi',
        'deadline', 'max_nilai', 'bobot', 'is_active',
    ];

    protected $casts = [
        'deadline' => 'date',
        'is_active' => 'boolean',
        'bobot' => 'decimal:2',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(AssignmentScore::class);
    }

    /** Jumlah mahasiswa yang sudah diberi nilai */
    public function jumlahDinilai(): int
    {
        return $this->scores()->whereNotNull('nilai')->count();
    }
}
