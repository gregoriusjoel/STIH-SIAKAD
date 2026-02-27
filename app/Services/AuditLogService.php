<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditLogService
{
    /**
     * Log an action with before/after state tracking
     */
    public function log(
        string $action,
        string $entityType,
        int $entityId = 0,
        ?array $before = null,
        ?array $after = null,
        ?array $meta = null
    ): AuditLog {
        return AuditLog::create([
            'actor_id' => auth()->id(),
            'actor_role' => $this->resolveActorRole(),
            'action' => $action,
            'auditable_type' => $entityType,
            'auditable_id' => $entityId,
            'before' => $before,
            'after' => $after,
            'meta' => $meta,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Log a model change with automatic before/after snapshotting
     */
    public function logModelChange(string $action, Model $model, ?array $before = null, ?array $meta = null): AuditLog
    {
        return $this->log(
            $action,
            get_class($model),
            $model->id,
            $before,
            $model->toArray(),
            $meta
        );
    }

    /**
     * Resolve the current actor's role
     */
    protected function resolveActorRole(): string
    {
        if (!auth()->check()) {
            return 'system';
        }

        $user = auth()->user();

        if ($user instanceof \App\Models\Admin || (method_exists($user, 'hasRole') && $user->hasRole('admin'))) {
            return 'admin';
        }

        if ($user instanceof \App\Models\Dosen || (method_exists($user, 'hasRole') && $user->hasRole('dosen'))) {
            return 'dosen';
        }

        return 'user';
    }
}
