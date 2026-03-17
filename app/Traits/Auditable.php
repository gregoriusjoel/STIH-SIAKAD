<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

/**
 * Trait Auditable
 * Automatically hooks into Eloquent model events to log CRUD operations.
 */
trait Auditable
{
    /**
     * Boot the trait and register event listeners.
     */
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->auditLog('created');
        });

        static::updated(function ($model) {
            $model->auditLog('updated');
        });

        static::deleted(function ($model) {
            $model->auditLog('deleted');
        });
    }

    /**
     * Log the action to the AuditLog model.
     */
    protected function auditLog(string $action)
    {
        $before = null;
        $after = null;

        if ($action === 'updated') {
            $after = $this->getChanges();
            
            // Filter out fields we want to exclude
            $exclude = $this->getAuditExclude();
            foreach ($after as $key => $value) {
                if (in_array($key, $exclude)) {
                    unset($after[$key]);
                }
            }

            // Don't log if no relevant changes occurred
            if (empty($after)) {
                return;
            }

            $before = [];
            foreach ($after as $key => $value) {
                $before[$key] = $this->getOriginal($key);
            }
            
        } elseif ($action === 'created') {
            $after = $this->attributesToArray();
            $exclude = $this->getAuditExclude();
            foreach ($after as $key => $value) {
                if (in_array($key, $exclude)) {
                    unset($after[$key]);
                }
            }
        } elseif ($action === 'deleted') {
            $before = $this->attributesToArray();
            $exclude = $this->getAuditExclude();
            foreach ($before as $key => $value) {
                if (in_array($key, $exclude)) {
                    unset($before[$key]);
                }
            }
        }

        AuditLog::log(
            action: strtolower(class_basename($this)) . '.' . $action,
            auditable: $this,
            before: $before,
            after: $after
        );
    }

    /**
     * Get the list of fields to exclude from auditing.
     */
    protected function getAuditExclude(): array
    {
        $defaultExclude = ['password', 'remember_token', 'created_at', 'updated_at', 'deleted_at'];
        
        return property_exists($this, 'auditExclude') 
            ? array_merge($defaultExclude, $this->auditExclude)
            : $defaultExclude;
    }
}
