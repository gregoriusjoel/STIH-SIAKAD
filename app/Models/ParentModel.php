<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParentModel extends Model
{
    protected $table = 'parents';
    
    protected $fillable = [
        'user_id',
        'mahasiswa_id',
        'hubungan',
        'pekerjaan',
        'phone',
        'address',
        'nama_ayah',
        'pendidikan_ayah',
        'pekerjaan_ayah',
        'agama_ayah',
        'nama_ibu',
        'pendidikan_ibu',
        'pekerjaan_ibu',
        'agama_ibu',
        'alamat_ortu',
        'kota_ortu',
        'propinsi_ortu',
        'negara_ortu',
        'handphone_ortu',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
