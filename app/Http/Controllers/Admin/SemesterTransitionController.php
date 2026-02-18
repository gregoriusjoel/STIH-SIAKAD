<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SemesterTransitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SemesterTransitionController extends Controller
{
    protected $transitionService;

    public function __construct(SemesterTransitionService $transitionService)
    {
        $this->transitionService = $transitionService;
    }

    /**
     * Show semester transition status dashboard
     */
    public function index()
    {
        $status = $this->transitionService->getTransitionStatus();
        
        return view('admin.semester.transition', compact('status'));
    }

    /**
     * Get transition status as JSON
     */
    public function status()
    {
        $status = $this->transitionService->getTransitionStatus();
        
        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Manually trigger semester transition
     */
    public function process(Request $request)
    {
        // Check admin permission
        if (!auth()->user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $result = $this->transitionService->processTransition();

        if ($request->expectsJson()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }

    /**
     * Preview transition without executing
     */
    public function preview()
    {
        $status = $this->transitionService->getTransitionStatus();
        
        if (!$status['ready_for_transition']) {
            return response()->json([
                'success' => false,
                'message' => 'Sistem belum siap untuk transisi',
                'data' => $status
            ]);
        }

        // Count eligible mahasiswa
        $eligibleCount = \App\Models\Mahasiswa::where('status', 'aktif')
            ->where(function($query) use ($status) {
                if ($status['next_semester']) {
                    $query->whereNull('last_semester_id')
                        ->orWhere('last_semester_id', '!=', $status['next_semester']['id']);
                }
            })
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Preview transisi semester',
            'data' => [
                'current_semester' => $status['current_semester'],
                'next_semester' => $status['next_semester'],
                'eligible_mahasiswa_count' => $eligibleCount,
                'estimated_execution_time' => ceil($eligibleCount / 100) . ' detik'
            ]
        ]);
    }
}
