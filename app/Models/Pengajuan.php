<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    // ── Status Constants ──────────────────────────────────────────
    const STATUS_DRAFT              = 'draft';
    const STATUS_GENERATED          = 'generated';
    const STATUS_SUBMITTED          = 'submitted';
    const STATUS_APPROVED           = 'approved';
    const STATUS_REJECTED           = 'rejected';

    protected $fillable = [
        'mahasiswa_id',
        'jenis',
        'keterangan',
        'payload_template',
        'status',
        'file_path',
        'generated_doc_path',
        'signed_doc_path',
        'admin_note',
        'rejected_reason',
        'revision_no',
        'approved_by',
        'approved_at',
        'submitted_at',
        'rejected_at',
        'nomor_surat',
        'file_surat',
    ];

    protected $casts = [
        'payload_template' => 'array',
        'approved_at'      => 'datetime',
        'submitted_at'     => 'datetime',
        'rejected_at'      => 'datetime',
    ];

    // ── Relations ─────────────────────────────────────────────────

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function revisions()
    {
        return $this->hasMany(PengajuanRevision::class)->orderBy('revision_no');
    }

    // ── Accessors ─────────────────────────────────────────────────

    public function getJenisLabelAttribute(): string
    {
        return match($this->jenis) {
            'surat_aktif'    => 'Surat Keterangan Aktif Kuliah',
            'cuti'           => 'Cuti Akademik',
            'dispensasi'     => 'Dispensasi Perkuliahan',
            'izin_penelitian'=> 'Izin Penelitian',
            default          => ucfirst(str_replace('_', ' ', $this->jenis)),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft'     => 'Draft',
            'generated' => 'Surat Diproses',
            'submitted' => 'Menunggu Review',
            'approved'  => 'Disetujui',
            'rejected'  => 'Ditolak',
            default     => ucfirst($this->status),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft'     => '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 ring-1 ring-inset ring-gray-500/20"><i class="fas fa-pencil-alt text-[10px]"></i>Draft</span>',
            'generated' => '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20"><i class="fas fa-file-download text-[10px]"></i>Siap Diunduh</span>',
            'submitted' => '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20"><span class="w-1.5 h-1.5 rounded-full bg-yellow-600 animate-pulse"></span>Menunggu Review</span>',
            'approved'  => '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20"><i class="fas fa-check-circle text-[10px]"></i>Disetujui</span>',
            'rejected'  => '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20"><i class="fas fa-times-circle text-[10px]"></i>Ditolak</span>',
            default     => $this->status,
        };
    }

    // ── State helpers ─────────────────────────────────────────────

    public function canGenerateDoc(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function canDownloadGenerated(): bool
    {
        return in_array($this->status, [self::STATUS_GENERATED, self::STATUS_SUBMITTED])
            && $this->generated_doc_path;
    }

    public function canUploadSigned(): bool
    {
        return in_array($this->status, [self::STATUS_GENERATED, self::STATUS_REJECTED]);
    }

    public function canSubmit(): bool
    {
        return $this->signed_doc_path
            && in_array($this->status, [self::STATUS_GENERATED, self::STATUS_REJECTED]);
    }

    public function isLocked(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }
}
