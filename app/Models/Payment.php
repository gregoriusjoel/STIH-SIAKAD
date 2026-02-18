<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'installment_id',
        'proof_id',
        'amount_approved',
        'paid_date',
        'transfer_date',
        'approved_by',
    ];

    protected $casts = [
        'amount_approved' => 'integer',
        'paid_date' => 'date',
        'transfer_date' => 'date',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function installment(): BelongsTo
    {
        return $this->belongsTo(Installment::class);
    }

    public function proof(): BelongsTo
    {
        return $this->belongsTo(PaymentProof::class, 'proof_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
