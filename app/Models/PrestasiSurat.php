<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrestasiSurat extends Model
{
    protected $fillable = [
        'prestasi_id',
        'jenis_surat',
        'nomor_surat',
        'tanggal_surat',
        'penandatangan_nama',
        'penandatangan_jabatan',
        'penandatangan_nip',
        'file_path',
        'is_backdate',
        'generated_by',
        'metadata',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'is_backdate'   => 'boolean',
        'metadata'      => 'array',
    ];

    public function prestasi(): BelongsTo
    {
        return $this->belongsTo(Prestasi::class);
    }

    public function generator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function getJenisSuratLabelAttribute(): string
    {
        return Prestasi::JENIS_SURAT_LABELS[$this->jenis_surat] ?? ucfirst(str_replace('_', ' ', $this->jenis_surat));
    }

    public function getTanggalSuratFormattedAttribute(): string
    {
        return $this->tanggal_surat?->locale('id')->isoFormat('D MMMM YYYY') ?? '-';
    }
}
