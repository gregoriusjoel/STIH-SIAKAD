<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportLog extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'filename',
        'total_rows',
        'success_count',
        'failed_count',
        'skipped_count',
        'details',
        'imported_at',
    ];

    protected $casts = [
        'details' => 'array',
        'imported_at' => 'datetime',
        'total_rows' => 'integer',
        'success_count' => 'integer',
        'failed_count' => 'integer',
        'skipped_count' => 'integer',
    ];

    /**
     * Get the user who performed the import
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get human readable type name
     */
    public function getTypeNameAttribute(): string
    {
        $types = [
            'mahasiswa' => 'Data Mahasiswa',
            'dosen' => 'Data Dosen',
            'dosen_pa' => 'Data Dosen PA',
            'mata_kuliah' => 'Mata Kuliah',
            'ruangan' => 'Ruangan',
        ];

        return $types[$this->type] ?? ucfirst($this->type);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute(): string
    {
        if ($this->failed_count === 0) {
            return 'bg-green-100 text-green-800';
        } elseif ($this->success_count === 0) {
            return 'bg-red-100 text-red-800';
        }
        return 'bg-yellow-100 text-yellow-800';
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute(): string
    {
        if ($this->failed_count === 0) {
            return 'Berhasil';
        } elseif ($this->success_count === 0) {
            return 'Gagal';
        }
        return 'Partial';
    }

    /**
     * Scope for recent imports
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
