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
        'tipe_wali',
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
        'nama_wali',
        'hubungan_wali',
        'pendidikan_wali',
        'pekerjaan_wali',
        'agama_wali',
        'alamat_wali',
        'kota_wali',
        'provinsi_wali',
        'negara_wali',
        'handphone_wali',
        'keluarga',
    ];

    protected $casts = [
        'keluarga' => 'array',
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
