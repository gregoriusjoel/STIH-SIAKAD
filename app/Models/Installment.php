<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'installment_no',
        'amount',
        'due_date',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function paymentProofs(): HasMany
    {
        return $this->hasMany(PaymentProof::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('status', 'UNPAID');
    }

    public function scopeWaitingVerification($query)
    {
        return $query->where('status', 'WAITING_VERIFICATION');
    }

    /**
     * Check if this installment can be paid
     * (previous installment must be PAID)
     */
    public function canBePaid(): bool
    {
        if ($this->installment_no === 1) {
            return true;
        }

        $previousInstallment = Installment::where('invoice_id', $this->invoice_id)
            ->where('installment_no', $this->installment_no - 1)
            ->first();

        return $previousInstallment && $previousInstallment->status === 'PAID';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'UNPAID' => 'Belum Dibayar',
            'PAID' => 'Lunas',
            'WAITING_VERIFICATION' => 'Menunggu Verifikasi',
            'REJECTED_PAYMENT' => 'Pembayaran Ditolak',
            default => $this->status,
        };
    }
}
