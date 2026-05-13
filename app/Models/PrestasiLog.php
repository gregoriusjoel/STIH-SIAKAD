<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrestasiLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'prestasi_id',
        'action',
        'from_status',
        'to_status',
        'user_id',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'metadata'   => 'array',
        'created_at' => 'datetime',
    ];

    const ACTION_LABELS = [
        'created'           => 'Pengajuan dibuat',
        'submitted'         => 'Diajukan ke admin',
        'status_change'     => 'Status berubah',
        'approved'          => 'Disetujui',
        'rejected'          => 'Ditolak',
        'note_added'        => 'Catatan ditambahkan',
        'surat_generated'   => 'Surat digenerate',
        'dokumen_uploaded'  => 'Dokumen diupload',
        'sertifikat_verified' => 'Sertifikat diverifikasi',
        'data_updated'      => 'Data diperbarui',
    ];

    const ACTION_ICONS = [
        'created'           => 'add_circle',
        'submitted'         => 'send',
        'status_change'     => 'swap_horiz',
        'approved'          => 'check_circle',
        'rejected'          => 'cancel',
        'note_added'        => 'sticky_note_2',
        'surat_generated'   => 'description',
        'dokumen_uploaded'  => 'upload_file',
        'sertifikat_verified' => 'verified',
        'data_updated'      => 'edit',
    ];

    const ACTION_COLORS = [
        'created'           => 'blue',
        'submitted'         => 'indigo',
        'status_change'     => 'amber',
        'approved'          => 'green',
        'rejected'          => 'red',
        'note_added'        => 'gray',
        'surat_generated'   => 'teal',
        'dokumen_uploaded'  => 'sky',
        'sertifikat_verified' => 'emerald',
        'data_updated'      => 'orange',
    ];

    public function prestasi(): BelongsTo
    {
        return $this->belongsTo(Prestasi::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActionLabelAttribute(): string
    {
        return self::ACTION_LABELS[$this->action] ?? ucfirst(str_replace('_', ' ', $this->action));
    }

    public function getActionIconAttribute(): string
    {
        return self::ACTION_ICONS[$this->action] ?? 'info';
    }

    public function getActionColorAttribute(): string
    {
        return self::ACTION_COLORS[$this->action] ?? 'gray';
    }
}
