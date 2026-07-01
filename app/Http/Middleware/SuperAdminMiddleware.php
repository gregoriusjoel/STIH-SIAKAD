<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        if (session()->has('impersonator_id') && $request->routeIs('super-admin.impersonate-stop')) {
            return $next($request);
        }

        if (!auth()->user()->isSuperAdmin()) {
            \Log::warning('SuperAdminMiddleware denied access', [
                'user_id' => auth()->id(),
                'role' => auth()->user()->role ?? null,
                'url' => $request->fullUrl(),
            ]);
            abort(403, 'Akses khusus Super Admin.');
        }

        return $next($request);
    }
}
