<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Prestasi extends Model
{
    // ── Status constants (state machine) ──
    const STATUS_DRAFT           = 'draft';
    const STATUS_DIAJUKAN        = 'diajukan';
    const STATUS_DIPROSES_ADMIN  = 'diproses_admin';
    const STATUS_SURAT_DITERBITKAN = 'surat_diterbitkan';
    const STATUS_DITOLAK         = 'ditolak';
    const STATUS_SELESAI         = 'selesai';

    const TRANSITIONS = [
        self::STATUS_DRAFT           => [self::STATUS_DIAJUKAN],
        self::STATUS_DIAJUKAN        => [self::STATUS_DIPROSES_ADMIN, self::STATUS_DITOLAK],
        self::STATUS_DIPROSES_ADMIN  => [self::STATUS_SURAT_DITERBITKAN, self::STATUS_DITOLAK, self::STATUS_SELESAI],
        self::STATUS_SURAT_DITERBITKAN => [self::STATUS_SELESAI],
        self::STATUS_DITOLAK         => [self::STATUS_DIAJUKAN], // revise & resubmit
        self::STATUS_SELESAI         => [],
    ];

    const STATUS_LABELS = [
        self::STATUS_DRAFT           => 'Draft',
        self::STATUS_DIAJUKAN        => 'Diajukan',
        self::STATUS_DIPROSES_ADMIN  => 'Diproses Admin',
        self::STATUS_SURAT_DITERBITKAN => 'Surat Diterbitkan',
        self::STATUS_DITOLAK         => 'Ditolak',
        self::STATUS_SELESAI         => 'Selesai',
    ];

    const STATUS_COLORS = [
        self::STATUS_DRAFT           => 'gray',
        self::STATUS_DIAJUKAN        => 'blue',
        self::STATUS_DIPROSES_ADMIN  => 'orange',
        self::STATUS_SURAT_DITERBITKAN => 'green',
        self::STATUS_DITOLAK         => 'red',
        self::STATUS_SELESAI         => 'emerald',
    ];

    const TINGKAT_LABELS = [
        'internal'      => 'Internal',
        'regional'      => 'Regional',
        'nasional'      => 'Nasional',
        'internasional' => 'Internasional',
    ];

    const JENIS_SURAT_LABELS = [
        'surat_tugas'       => 'Surat Tugas',
        'surat_rekomendasi' => 'Surat Rekomendasi',
        'surat_keterangan'  => 'Surat Keterangan Prestasi',
        'surat_penghargaan' => 'Surat Penghargaan',
        'surat_arsip'       => 'Surat Arsip',
    ];

    protected $fillable = [
        'tipe',
        'pengaju_type',
        'pengaju_id',
        'nama_kegiatan',
        'jenis_kegiatan',
        'tingkat_kegiatan',
        'tempat_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'penyelenggara',
        'deskripsi',
        'dosen_pendamping_id',
        'jenis_prestasi',
        'nomor_sertifikat',
        'keterangan',
        'status',
        'approved_by',
        'approved_at',
        'rejected_reason',
        'rejected_at',
        'admin_note',
        'tags',
        'external_ref',
        'hash_kegiatan',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'approved_at'     => 'datetime',
        'rejected_at'     => 'datetime',
        'tags'            => 'array',
    ];

    // ── Relationships ──

    public function pengaju(): MorphTo
    {
        return $this->morphTo();
    }

    public function dosenPendamping(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_pendamping_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function dokumens(): HasMany
    {
        return $this->hasMany(PrestasiDokumen::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PrestasiLog::class)->orderByDesc('created_at');
    }

    public function surats(): HasMany
    {
        return $this->hasMany(PrestasiSurat::class);
    }

    // ── Accessors ──

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'gray';
    }

    public function getTingkatLabelAttribute(): string
    {
        return self::TINGKAT_LABELS[$this->tingkat_kegiatan] ?? ucfirst($this->tingkat_kegiatan);
    }

    public function getPengajuNameAttribute(): string
    {
        if ($this->pengaju_type === Mahasiswa::class || $this->pengaju_type === 'App\\Models\\Mahasiswa') {
            return $this->pengaju?->user?->name ?? 'Mahasiswa';
        }
        return $this->pengaju?->user?->name ?? 'Dosen';
    }

    public function getPengajuIdentifierAttribute(): string
    {
        if ($this->pengaju_type === Mahasiswa::class || $this->pengaju_type === 'App\\Models\\Mahasiswa') {
            return $this->pengaju?->nim ?? '-';
        }
        return $this->pengaju?->nidn ?? '-';
    }

    public function getPengajuRoleAttribute(): string
    {
        if ($this->pengaju_type === Mahasiswa::class || $this->pengaju_type === 'App\\Models\\Mahasiswa') {
            return 'mahasiswa';
        }
        return 'dosen';
    }

    public function getStorageFolderAttribute(): string
    {
        $role = $this->pengaju_role;
        $identifier = $this->pengaju_identifier;
        return "prestasi/{$role}/{$identifier}";
    }

    public function getStatusBadgeAttribute(): string
    {
        $map = [
            self::STATUS_DRAFT           => ['bg-gray-100 text-gray-600 ring-1 ring-inset ring-gray-300', 'edit_note', 'Draft'],
            self::STATUS_DIAJUKAN        => ['bg-blue-100 text-blue-700 ring-1 ring-inset ring-blue-200', 'send', 'Diajukan'],
            self::STATUS_DIPROSES_ADMIN  => ['bg-amber-100 text-amber-700 ring-1 ring-inset ring-amber-200', 'pending', 'Diproses Admin'],
            self::STATUS_SURAT_DITERBITKAN => ['bg-green-100 text-green-700 ring-1 ring-inset ring-green-200', 'description', 'Surat Diterbitkan'],
            self::STATUS_DITOLAK         => ['bg-red-100 text-red-700 ring-1 ring-inset ring-red-200', 'cancel', 'Ditolak'],
            self::STATUS_SELESAI         => ['bg-emerald-100 text-emerald-700 ring-1 ring-inset ring-emerald-200', 'check_circle', 'Selesai'],
        ];

        [$classes, $icon, $label] = $map[$this->status] ?? ['bg-gray-100 text-gray-600 ring-1 ring-inset ring-gray-300', 'circle', ucfirst($this->status)];

        return "<span class=\"inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold {$classes}\"><span class=\"material-symbols-outlined\" style=\"font-size:13px;line-height:1\">{$icon}</span>{$label}</span>";
    }

    // ── State Machine Helpers ──

    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, self::TRANSITIONS[$this->status] ?? []);
    }

    public function transitionTo(string $newStatus): void
    {
        if (!$this->canTransitionTo($newStatus)) {
            throw new \LogicException("Tidak bisa mengubah status dari '{$this->status}' ke '{$newStatus}'.");
        }
        $this->update(['status' => $newStatus]);
    }

    public function isEditable(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_DITOLAK]);
    }

    public function isPending(): bool
    {
        return in_array($this->status, [self::STATUS_DIAJUKAN, self::STATUS_DIPROSES_ADMIN]);
    }

    // ── Duplicate Detection ──

    public static function generateHash(string $namaKegiatan, string $penyelenggara, string $tanggalMulai, string $pengajuType, int $pengajuId): string
    {
        $raw = mb_strtolower(trim($namaKegiatan))
             . '|' . mb_strtolower(trim($penyelenggara))
             . '|' . $tanggalMulai
             . '|' . $pengajuType
             . '|' . $pengajuId;
        return md5($raw);
    }

    // ── Scopes ──

    public function scopeForMahasiswa($query, int $mahasiswaId)
    {
        return $query->where('pengaju_type', Mahasiswa::class)->where('pengaju_id', $mahasiswaId);
    }

    public function scopeForDosen($query, int $dosenId)
    {
        return $query->where('pengaju_type', Dosen::class)->where('pengaju_id', $dosenId);
    }

    public function scopeByTingkat($query, string $tingkat)
    {
        return $query->where('tingkat_kegiatan', $tingkat);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', [self::STATUS_DIAJUKAN, self::STATUS_DIPROSES_ADMIN]);
    }

    public function scopeDampingan($query, int $dosenId)
    {
        return $query->where('dosen_pendamping_id', $dosenId);
    }
}
