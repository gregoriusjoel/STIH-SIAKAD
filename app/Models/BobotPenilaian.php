<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BobotPenilaian extends Model
{
    protected $table = 'bobot_penilaian';
    
    protected $fillable = [
        'kelas_id',
        'bobot_partisipatif',
        'bobot_proyek',
        'bobot_quiz',
        'bobot_tugas',
        'bobot_uts',
        'bobot_uas',
        'is_locked',
        'locked_at',
        'locked_by',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
        'locked_at' => 'datetime',
        'bobot_partisipatif' => 'decimal:2',
        'bobot_proyek' => 'decimal:2',
        'bobot_quiz' => 'decimal:2',
        'bobot_tugas' => 'decimal:2',
        'bobot_uts' => 'decimal:2',
        'bobot_uas' => 'decimal:2',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function lockedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    /**
     * Validate that total bobot equals 100
     */
    public function isValidTotal(): bool
    {
        $total = $this->bobot_partisipatif + $this->bobot_proyek + $this->bobot_quiz + 
                 $this->bobot_tugas + $this->bobot_uts + $this->bobot_uas;
        
        return abs($total - 100) < 0.01; // Allow small floating point errors
    }

    /**
     * Lock the bobot penilaian
     */
    public function lock($userId = null): bool
    {
        if (!$this->isValidTotal()) {
            return false;
        }

        $this->is_locked = true;
        $this->locked_at = now();
        $this->locked_by = $userId ?? auth()->id();
        
        return $this->save();
    }
}
