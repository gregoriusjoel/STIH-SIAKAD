<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Mahasiswa;
use App\Models\KuesionerAktivasi;
use App\Models\Semester;

/**
 * Check if mahasiswa has filled semester activation questionnaire
 * If not, redirect to questionnaire form
 */
class CheckSemesterKuesioner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for non-mahasiswa or non-authenticated users
        if (!auth()->check()) {
            return $next($request);
        }
        
        // Get mahasiswa
        $mahasiswa = Mahasiswa::where('user_id', auth()->id())->first();
        if (!$mahasiswa) {
            return $next($request);
        }
        
        // Skip if mahasiswa status is not aktif (new student still in activation)
        if ($mahasiswa->status_akun !== 'aktif') {
            return $next($request);
        }
        
        // Get current active semester
        $currentSemester = Semester::where('status', 'aktif')
            ->orWhere('is_active', true)
            ->first();
        
        if (!$currentSemester) {
            return $next($request);
        }
        
        // Check if kuesioner already filled for this semester
        $hasKuesioner = KuesionerAktivasi::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $currentSemester->id)
            ->exists();
        
        // If not filled and this is not the questionnaire page itself, redirect
        if (!$hasKuesioner && !$request->routeIs('mahasiswa.semester-aktivasi.*')) {
            return redirect()->route('mahasiswa.semester-aktivasi.index')
                ->with('warning', 'Silakan isi kuesioner semester terlebih dahulu sebelum melanjutkan');
        }
        
        return $next($request);
    }
}
