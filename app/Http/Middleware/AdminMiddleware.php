<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = auth()->user();
        // Prefer model helper if available
        $isAdmin = method_exists($user, 'isAdmin') ? $user->isAdmin() : ($user->role === 'admin');

        if (! $isAdmin) {
            \Log::warning('AdminMiddleware denied access', ['user_id' => $user->id ?? null, 'role' => $user->role ?? null, 'url' => $request->fullUrl()]);
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}
