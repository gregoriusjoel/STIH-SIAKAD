<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WisudaBatch extends Model
{
    protected $table = 'wisuda_batches';

    protected $fillable = [
        'nama_batch',
        'tanggal',
        'waktu_mulai',
        'lokasi',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal'     => 'date',
        'waktu_mulai' => 'datetime:H:i',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(WisudaRegistration::class, 'wisuda_batch_id');
    }

    /**
     * Check if this batch has any scheduled registrations.
     */
    public function hasScheduledRegistrations(): bool
    {
        return $this->registrations()->where('status', 'scheduled')->exists();
    }
}
