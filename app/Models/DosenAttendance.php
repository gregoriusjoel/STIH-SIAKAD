<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DosenAttendance extends Model
{
    protected $fillable = [
        'dosen_id',
        'kelas_mata_kuliah_id',
        'pertemuan_id',
        'metode_pengajaran',
        'jam_kelas_mulai',
        'jam_kelas_selesai',
        'jam_absen_dosen',
        'lokasi_dosen',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'jam_absen_dosen' => 'datetime',
    ];

    /**
     * The dosen who attended.
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    /**
     * The kelas mata kuliah this attendance belongs to.
     */
    public function kelasMataKuliah(): BelongsTo
    {
        return $this->belongsTo(KelasMataKuliah::class);
    }

    /**
     * The specific pertemuan this attendance is for.
     */
    public function pertemuan(): BelongsTo
    {
        return $this->belongsTo(Pertemuan::class);
    }
}
