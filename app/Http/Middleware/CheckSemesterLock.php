<?php

namespace App\Http\Middleware;

use App\Models\Semester;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSemesterLock
{
    /**
     * Prevent mutations on locked semesters.
     * Reads (GET, HEAD, OPTIONS) are always allowed.
     * Superadmin can bypass.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow read-only requests
        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'])) {
            return $next($request);
        }

        // Determine semester from route or request
        $semesterId = $request->route('semester')
            ?? $request->input('semester_id')
            ?? $request->input('target_semester_id');

        if (!$semesterId) {
            // Try to get active semester
            $semester = Semester::where('is_active', true)->first();
        } else {
            $semester = $semesterId instanceof Semester
                ? $semesterId
                : Semester::find($semesterId);
        }

        if ($semester && $semester->is_locked) {
            // Allow superadmin to bypass
            $user = auth()->user();
            if ($user && method_exists($user, 'hasRole') && $user->hasRole('superadmin')) {
                return $next($request);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Semester \"{$semester->display_label}\" sudah dikunci. Perubahan tidak diizinkan.",
                ], 423);
            }

            return redirect()->back()->with(
                'error',
                "Semester \"{$semester->display_label}\" sudah dikunci. Perubahan tidak diizinkan."
            );
        }

        return $next($request);
    }
}
