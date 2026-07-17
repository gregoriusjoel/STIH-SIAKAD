<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display a listing of general audit logs with full device & session filtering.
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('actor')
            ->orderBy('created_at', 'desc');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('actor_role')) {
            $query->where('actor_role', $request->actor_role);
        }

        if ($request->filled('actor_id')) {
            $query->where('actor_id', $request->actor_id);
        }

        if ($request->filled('entity_type')) {
            $query->where('auditable_type', 'like', "%{$request->entity_type}%");
        }

        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', "%{$request->ip_address}%");
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs    = $query->paginate(20)->withQueryString();
        $actions = AuditLog::distinct()->orderBy('action')->pluck('action');
        $roles   = AuditLog::distinct()->orderBy('actor_role')->pluck('actor_role')->filter();

        return view('admin.audit-log.index', compact('logs', 'actions', 'roles'));
    }
}
