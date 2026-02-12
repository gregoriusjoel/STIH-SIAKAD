<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'jenis',
        'keterangan',
        'status',
        'file_path',
        'admin_note',
        'approved_by',
        'approved_at',
        'nomor_surat',
        'file_surat',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getJenisLabelAttribute()
    {
        return match($this->jenis) {
            'cuti' => 'Cuti Akademik',
            'surat_aktif' => 'Surat Keterangan Aktif',
            default => ucfirst($this->jenis),
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20"><span class="w-1.5 h-1.5 rounded-full bg-yellow-600 animate-pulse"></span>Menunggu</span>',
            'disetujui' => '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20"><i class="fas fa-check-circle text-[10px]"></i>Disetujui</span>',
            'ditolak' => '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20"><i class="fas fa-times-circle text-[10px]"></i>Ditolak</span>',
            default => $this->status,
        };
    }
}
