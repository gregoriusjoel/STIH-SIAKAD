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
        'alamat_ayah',
        'kota_ayah',
        'kecamatan_ayah',
        'propinsi_ayah',
        'desa_ayah',
        'handphone_ayah',
        'alamat_ibu',
        'kota_ibu',
        'kecamatan_ibu',
        'propinsi_ibu',
        'desa_ibu',
        'handphone_ibu',
        'nama_wali',
        'hubungan_wali',
        'pendidikan_wali',
        'pekerjaan_wali',
        'agama_wali',
        'alamat_wali',
        'kota_wali',
        'kecamatan_wali',
        'provinsi_wali',
        'desa_wali',
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
