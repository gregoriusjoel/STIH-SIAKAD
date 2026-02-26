<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'mata_kuliah_id',
        'kelas_id',
        'dosen_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'ruangan_id',
        'status',
        'catatan_generate',
        'generated_by',
        'generated_at',
        'is_outside_availability',
        'outside_reason',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(JadwalApproval::class);
    }

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class);
    }

    // Helper methods untuk status
    public function isPendingDosen(): bool
    {
        return $this->status === 'pending_dosen';
    }

    public function isApprovedDosen(): bool
    {
        return $this->status === 'approved_dosen';
    }

    public function isRejectedDosen(): bool
    {
        return $this->status === 'rejected_dosen';
    }

    public function isPendingAdmin(): bool
    {
        return $this->status === 'pending_admin';
    }

    public function isApprovedAdmin(): bool
    {
        return $this->status === 'approved_admin';
    }

    public function isRejectedAdmin(): bool
    {
        return $this->status === 'rejected_admin';
    }

    // Scope untuk filter status
    public function scopePendingDosen($query)
    {
        return $query->where('status', 'pending_dosen');
    }

    public function scopeApprovedDosen($query)
    {
        return $query->where('status', 'approved_dosen');
    }

    public function scopePendingAdmin($query)
    {
        return $query->where('status', 'pending_admin');
    }

    public function scopeForDosen($query, $dosenId)
    {
        return $query->where('dosen_id', $dosenId);
    }

    // Helper untuk mendapatkan approval terakhir
    public function getLatestApproval(): ?JadwalApproval
    {
        return $this->approvals()->latest()->first();
    }

    // Helper untuk mengecek konflik jadwal
    public function hasTimeConflict(): bool
    {
        return static::where('id', '!=', $this->id)
            ->where('dosen_id', $this->dosen_id)
            ->where('hari', $this->hari)
            ->where(function ($query) {
                $query->whereBetween('jam_mulai', [$this->jam_mulai, $this->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$this->jam_mulai, $this->jam_selesai])
                      ->orWhere(function ($q) {
                          $q->where('jam_mulai', '<=', $this->jam_mulai)
                            ->where('jam_selesai', '>=', $this->jam_selesai);
                      });
            })
            ->whereIn('status', ['approved_dosen', 'approved_admin'])
            ->exists();
    }
}