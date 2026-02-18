<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstallmentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'student_id',
        'requested_terms',
        'approved_terms',
        'alasan',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'SUBMITTED' => 'Diajukan',
            'APPROVED' => 'Disetujui',
            'REJECTED' => 'Ditolak',
            default => $this->status,
        };
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'student_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'SUBMITTED');
    }
}
