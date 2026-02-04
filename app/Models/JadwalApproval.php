<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'jadwal_proposal_id',
        'approved_by',
        'role',
        'action',
        'alasan_penolakan',
        'hari_pengganti',
        'jam_mulai_pengganti',
        'jam_selesai_pengganti',
        'ruangan_pengganti',
        'approved_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'jam_mulai_pengganti' => 'datetime',
        'jam_selesai_pengganti' => 'datetime',
    ];

    public function jadwalProposal(): BelongsTo
    {
        return $this->belongsTo(JadwalProposal::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Helper methods
    public function isApproval(): bool
    {
        return $this->action === 'approve';
    }

    public function isRejection(): bool
    {
        return $this->action === 'reject';
    }

    public function isDosenAction(): bool
    {
        return $this->role === 'dosen';
    }

    public function isAdminAction(): bool
    {
        return $this->role === 'admin';
    }

    public function hasAlternative(): bool
    {
        return $this->isRejection() && 
               !empty($this->hari_pengganti) && 
               !empty($this->jam_mulai_pengganti) && 
               !empty($this->jam_selesai_pengganti);
    }

    // Scope methods
    public function scopeApprovals($query)
    {
        return $query->where('action', 'approve');
    }

    public function scopeRejections($query)
    {
        return $query->where('action', 'reject');
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('approved_by', $userId);
    }
}