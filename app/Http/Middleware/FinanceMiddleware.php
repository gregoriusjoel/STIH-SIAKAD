<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FinanceMiddleware
{
    /**
     * Only allow users with role 'finance' or 'keuangan' through.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();

        if (! in_array($user->role, ['finance', 'keuangan'])) {
            abort(403, 'Halaman ini hanya dapat diakses oleh bagian keuangan.');
        }

        return $next($request);
    }
}
