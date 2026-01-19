<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMahasiswaStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Only check for mahasiswa role
        if ($user && $user->role === 'mahasiswa') {
            $mahasiswa = $user->mahasiswa;
            
            if (!$mahasiswa) {
                return redirect()->route('login')->with('error', 'Data mahasiswa tidak ditemukan');
            }
            
            // If status is tidak_aktif, redirect to activation page
            if ($mahasiswa->status_akun === 'tidak_aktif') {
                // Allow access to activation route
                if (!$request->routeIs('mahasiswa.aktivasi*')) {
                    return redirect()->route('mahasiswa.aktivasi.index')
                        ->with('warning', 'Anda harus mengaktifkan akun terlebih dahulu');
                }
            }
        }
        
        return $next($request);
    }
}
