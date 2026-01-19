<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalException extends Model
{
    use HasFactory;

    protected $fillable = [
        'jadwal_id', 'date', 'hari', 'jam_mulai', 'jam_selesai', 'ruangan', 'catatan'
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
}
