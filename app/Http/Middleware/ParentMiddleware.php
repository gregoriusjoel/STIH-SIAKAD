<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ParentMiddleware
{
    /**
     * Only allow users with role 'parent' through.
     * For any other role (including unauthenticated), redirect appropriately.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();

        if ($user->role !== 'parent') {
            abort(403, 'Halaman ini hanya dapat diakses oleh orang tua/wali mahasiswa.');
        }

        return $next($request);
    }
}
