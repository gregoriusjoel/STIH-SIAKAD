<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrestasiDokumen extends Model
{
    protected $fillable = [
        'prestasi_id',
        'jenis',
        'file_path',
        'original_name',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    const JENIS_LABELS = [
        'sertifikat'       => 'Sertifikat',
        'dokumentasi'      => 'Dokumentasi',
        'surat_tugas_lama' => 'Surat Tugas Lama',
        'pendukung'        => 'Dokumen Pendukung',
    ];

    public function prestasi(): BelongsTo
    {
        return $this->belongsTo(Prestasi::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getJenisLabelAttribute(): string
    {
        return self::JENIS_LABELS[$this->jenis] ?? ucfirst($this->jenis);
    }

    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }

    public function getIconAttribute(): string
    {
        return match ($this->jenis) {
            'sertifikat'       => 'workspace_premium',
            'dokumentasi'      => 'photo_library',
            'surat_tugas_lama' => 'description',
            'pendukung'        => 'attach_file',
            default            => 'insert_drive_file',
        };
    }
}
