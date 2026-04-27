<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'semester',
        'tahun_ajaran',
        'sks_ambil',
        'paket_sks_bayar',
        'total_tagihan',
        'status',
        'allow_partial',
        'notes',
        'bank_name',
        'va_number',
        'created_by',
        'published_at',
    ];

    protected $casts = [
        'total_tagihan' => 'integer',
        'allow_partial' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'student_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function installmentRequest(): HasOne
    {
        return $this->hasOne(InstallmentRequest::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function paymentProofs(): HasMany
    {
        return $this->hasMany(PaymentProof::class);
    }

    /**
     * Check if all installments are paid
     */
    public function allInstallmentsPaid(): bool
    {
        if ($this->installments()->count() === 0) {
            return false;
        }

        return $this->installments()->where('status', '!=', 'PAID')->count() === 0;
    }

    /**
     * Get total paid amount
     */
    public function getTotalPaidAttribute(): int
    {
        return $this->payments()->sum('amount_approved');
    }

    /**
     * Check if invoice is fully paid
     */
    public function isFullyPaid(): bool
    {
        return $this->total_paid >= $this->total_tagihan;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'DRAFT' => 'Draft',
            'PUBLISHED' => 'Tagihan Baru',
            'IN_INSTALLMENT' => 'Dalam Cicilan',
            'LUNAS' => 'Lunas',
            default => $this->status,
        };
    }
}
