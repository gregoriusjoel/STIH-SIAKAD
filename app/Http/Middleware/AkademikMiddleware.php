<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AkademikMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = auth()->user();
        if (!$user->isAdmin()) {
            \Log::warning('AkademikMiddleware denied access', [
                'user_id' => $user->id,
                'role' => $user->role ?? null,
                'url' => $request->fullUrl(),
            ]);
            abort(403, 'Akses khusus Akademik / Super Admin.');
        }

        return $next($request);
    }
}
