<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display a listing of general audit logs.
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('actor')
            ->orderBy('created_at', 'desc');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        
        if ($request->filled('actor_id')) {
            $query->where('actor_id', $request->actor_id);
        }
        
        if ($request->filled('entity_type')) {
            // Support partial matches for partial class names
            $query->where('auditable_type', 'like', "%{$request->entity_type}%");
        }

        $logs = $query->paginate(10)->withQueryString();

        $actions = AuditLog::distinct()->pluck('action');

        return view('admin.audit-log.index', compact('logs', 'actions'));
    }
}
