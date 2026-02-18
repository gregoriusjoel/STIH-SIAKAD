<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class PaymentProof extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'installment_id',
        'uploaded_by',
        'transfer_date',
        'amount_submitted',
        'method',
        'file_path',
        'status',
        'finance_notes',
        'approved_by',
        'approved_at',
        'rejected_at',
        'student_notes',
    ];

    protected $casts = [
        'amount_submitted' => 'integer',
        'transfer_date' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function installment(): BelongsTo
    {
        return $this->belongsTo(Installment::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'proof_id');
    }

    public function scopeUploaded($query)
    {
        return $query->where('status', 'UPLOADED');
    }

    /**
     * Get file URL
     */
    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    /**
     * Check if proof is already approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'APPROVED';
    }

    /**
     * Check if proof is already rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'REJECTED';
    }
}
