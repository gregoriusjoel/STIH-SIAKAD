<?php

namespace App\Listeners;

use App\Models\AuditLog;
use Illuminate\Auth\Events\Logout;

class LogUserLogout
{
    /**
     * Handle the Logout event.
     * Records user logout to audit_logs for forensic completeness.
     */
    public function handle(Logout $event): void
    {
        if (!$event->user) {
            return;
        }

        try {
            AuditLog::log(
                action: 'user.logout',
                auditable: $event->user,
                meta: [
                    'user_id'    => $event->user->id,
                    'user_email' => $event->user->email,
                    'ip'         => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]
            );
        } catch (\Throwable $e) {
            // Silently fail — do not break the logout flow
            \Log::error('LogUserLogout: Failed to write audit log', ['error' => $e->getMessage()]);
        }
    }
}
