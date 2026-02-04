<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalGenerateLog extends Model
{
    protected $table = 'jadwal_generate_logs';

    protected $fillable = [
        'user_id',
        'total_generated',
        'total_failed',
        'failed_items',
        'status',
        'error_message',
    ];

    protected $casts = [
        'failed_items' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk filter status
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePartial($query)
    {
        return $query->where('status', 'partial');
    }

    public function scopeErrors($query)
    {
        return $query->where('status', 'error');
    }

    // Get readable status
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'completed' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Berhasil</span>',
            'partial' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Sebagian Berhasil</span>',
            'error' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Error</span>',
        ];

        return $badges[$this->status] ?? $badges['error'];
    }
}
