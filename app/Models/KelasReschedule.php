<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KelasReschedule extends Model
{
    protected $fillable = [
        'kelas_mata_kuliah_id',
        'dosen_id',
        'old_hari',
        'old_jam_mulai',
        'old_jam_selesai',
        'new_hari',
        'new_jam_mulai',
        'new_jam_selesai',
        'new_ruang',
        'new_kelas',
        'metode_pengajaran',
        'online_link',
        'asynchronous_tugas',
        'asynchronous_file',
        'week_start',
        'week_end',
        'status',
        'catatan_dosen',
        'catatan_admin',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'week_start' => 'date',
        'week_end' => 'date',
        'approved_at' => 'datetime',
    ];

    public function kelasMataKuliah(): BelongsTo
    {
        return $this->belongsTo(KelasMataKuliah::class);
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
