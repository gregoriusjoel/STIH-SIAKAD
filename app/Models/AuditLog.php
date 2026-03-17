<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'actor_id',
        'actor_role',
        'action',
        'auditable_type',
        'auditable_id',
        'meta',
        'before',
        'after',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'before' => 'array',
        'after' => 'array',
        'created_at' => 'datetime',
    ];

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function auditable()
    {
        return $this->morphTo();
    }

    /**
     * Create an audit log entry
     */
    public static function log(string $action, $auditable, ?array $meta = null, ?array $before = null, ?array $after = null): self
    {
        return static::create([
            'actor_id' => auth()->id(),
            'actor_role' => static::resolveActorRole(),
            'action' => $action,
            'auditable_type' => is_object($auditable) ? get_class($auditable) : $auditable,
            'auditable_id' => is_object($auditable) ? $auditable->id : 0,
            'meta' => $meta,
            'before' => $before,
            'after' => $after,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Resolve actor role from auth guard
     */
    protected static function resolveActorRole(): string
    {
        if (!auth()->check()) {
            return 'system';
        }

        $user = auth()->user();

        if ($user->isAdmin()) {
            return 'admin';
        }

        if ($user->isDosen()) {
            return 'dosen';
        }

        if ($user->isMahasiswa()) {
            return 'mahasiswa';
        }

        if ($user->isParent()) {
            return 'parent';
        }

        return 'user';
    }
}
