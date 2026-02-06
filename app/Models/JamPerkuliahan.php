<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JamPerkuliahan extends Model
{
    use HasFactory;

    protected $table = 'jam_perkuliahan';

    protected $fillable = [
        'jam_ke',
        'jam_mulai',
        'jam_selesai',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    /**
     * Get formatted time range
     */
    public function getFormattedTimeAttribute()
    {
        return date('H.i', strtotime($this->jam_mulai)) . ' - ' . date('H.i', strtotime($this->jam_selesai));
    }

    /**
     * Get slot label (e.g., "Jam 1 (09:00 - 09:45)")
     */
    public function getSlotLabelAttribute()
    {
        return "Jam {$this->jam_ke} ({$this->formatted_time})";
    }

    /**
     * Relationship: Has many DosenAvailability
     */
    public function dosenAvailabilities()
    {
        return $this->hasMany(DosenAvailability::class);
    }
}
