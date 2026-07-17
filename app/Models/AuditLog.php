<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jenssegers\Agent\Agent;

class AuditLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'actor_id',
        'actor_role',
        'action',
        'module',
        'auditable_type',
        'auditable_id',
        'meta',
        'before',
        'after',
        'ip_address',
        'user_agent',
        'session_id',
        'created_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'before' => 'array',
        'after' => 'array',
        'created_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($log) {
            if (empty($log->module) && !empty($log->action)) {
                $log->module = static::deriveModuleFromAction($log->action);
            }
            if (empty($log->session_id) && request()->hasSession()) {
                $log->session_id = request()->session()->getId();
            }
        });
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function auditable()
    {
        return $this->morphTo();
    }

    /**
     * Parse user_agent into structured device info using jenssegers/agent.
     * Returns: browser, browser_version, os, os_version, device_type, device_name, platform
     */
    public function getDeviceInfoAttribute(): array
    {
        if (empty($this->user_agent)) {
            return [
                'browser'          => 'Unknown',
                'browser_version'  => null,
                'os'               => 'Unknown',
                'os_version'       => null,
                'device_type'      => 'Unknown',
                'device_name'      => null,
                'platform'         => 'Unknown',
            ];
        }

        $agent = new Agent();
        $agent->setUserAgent($this->user_agent);

        if ($agent->isTablet()) {
            $deviceType = 'Tablet';
        } elseif ($agent->isMobile()) {
            $deviceType = 'Mobile';
        } else {
            $deviceType = 'Desktop';
        }

        $browser        = $agent->browser() ?: 'Unknown';
        $browserVersion = $agent->version($browser) ?: null;
        $os             = $agent->platform() ?: 'Unknown';
        $osVersion      = $agent->version($os) ?: null;
        $deviceName     = $agent->device() ?: null;

        // Normalize agent response
        if ($browserVersion && str_contains($browserVersion, '.')) {
            $parts          = explode('.', $browserVersion);
            $browserVersion = $parts[0] . (isset($parts[1]) ? '.' . $parts[1] : '');
        }

        return [
            'browser'         => $browser,
            'browser_version' => $browserVersion,
            'os'              => $os,
            'os_version'      => $osVersion,
            'device_type'     => $deviceType,
            'device_name'     => ($deviceName && $deviceName !== 'WebKit') ? $deviceName : null,
            'platform'        => $os . ($osVersion ? ' ' . $osVersion : ''),
        ];
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
     * Derive module from action name
     */
    public static function deriveModuleFromAction(string $action): string
    {
        $action = strtolower($action);
        
        if (str_contains($action, 'login') || str_contains($action, 'logout') || str_contains($action, 'impersonat')) {
            return 'auth';
        }
        
        if (str_starts_with($action, 'system.') || str_contains($action, 'backup') || str_contains($action, 'config')) {
            return 'system';
        }
        
        if (str_contains($action, 'krs') || str_contains($action, 'nilai') || str_contains($action, 'grade') || str_contains($action, 'academic') || str_contains($action, 'matakuliah') || str_contains($action, 'kelas')) {
            return 'akademik';
        }
        
        if (str_contains($action, 'finance') || str_contains($action, 'invoice') || str_contains($action, 'payment') || str_contains($action, 'tagihan') || str_contains($action, 'pembayaran')) {
            return 'keuangan';
        }
        
        if (str_contains($action, 'internship') || str_contains($action, 'magang')) {
            return 'magang';
        }
        
        if (str_contains($action, 'skripsi') || str_contains($action, 'thesis') || str_contains($action, 'proposal')) {
            return 'skripsi';
        }
        
        if (str_contains($action, 'wisuda') || str_contains($action, 'graduation')) {
            return 'wisuda';
        }
        
        if (str_contains($action, 'user') || str_contains($action, 'role') || str_contains($action, 'permission')) {
            return 'system';
        }
        
        return 'system';
    }

    /**
     * Resolve actor role from auth guard.
     * IMPORTANT: super_admin MUST be checked before isAdmin()
     * because isSuperAdmin() is a subset of isAdmin().
     */
    protected static function resolveActorRole(): string
    {
        if (!auth()->check()) {
            return 'system';
        }

        $user = auth()->user();

        // Check super_admin FIRST — isSuperAdmin() ⊂ isAdmin()
        if ($user->isSuperAdmin()) {
            return 'super_admin';
        }

        if ($user->isAdmin()) {
            return 'akademik';
        }

        if ($user->isFinance()) {
            return 'keuangan';
        }

        if ($user->isDosen()) {
            return 'dosen';
        }

        if ($user->isMahasiswa()) {
            return 'mahasiswa';
        }

        if ($user->isParent()) {
            return 'parents';
        }

        return 'user';
    }
}
