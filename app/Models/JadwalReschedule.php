<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalReschedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'jadwal_id', 'dosen_id', 'old_hari', 'old_jam_mulai', 'old_jam_selesai',
        'new_hari', 'new_jam_mulai', 'new_jam_selesai', 'catatan', 'status', 'apply_date', 'one_week_only'
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }
}
