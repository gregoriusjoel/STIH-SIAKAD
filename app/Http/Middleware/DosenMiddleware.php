<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DosenMiddleware
{
    /**
     * Only allow users with role 'dosen' through.
     * For any other role (including unauthenticated), redirect appropriately.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();

        if ($user->role !== 'dosen') {
            abort(403, 'Halaman ini hanya dapat diakses oleh Dosen.');
        }

        return $next($request);
    }
}
