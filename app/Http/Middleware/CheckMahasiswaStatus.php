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

            // If mahasiswa is new (semester 1) and hasn't completed the new-student survey,
            // redirect to the survey page (except when already on survey routes or activation/profile routes)
            if ((int)$mahasiswa->semester === 1 && empty($mahasiswa->new_survey_completed)) {
                $allowedSurveyRoutes = [
                    'mahasiswa.survey_new.index',
                    'mahasiswa.survey_new.store',
                    'mahasiswa.aktivasi.index',
                    'mahasiswa.aktivasi.store',
                    'mahasiswa.profil.manajemen',
                    'mahasiswa.profil.update',
                ];

                $currentRoute = $request->route() ? $request->route()->getName() : null;
                if (!in_array($currentRoute, $allowedSurveyRoutes)) {
                    return redirect()->route('mahasiswa.survey_new.index')
                        ->with('warning', 'Harap isi kuesioner singkat untuk mahasiswa baru terlebih dahulu.');
                }
            }

            // Check if profile is complete
            // Allow access to: dashboard, profil.index, profil.manajemen, profil.update
            $allowedRoutes = [
                'mahasiswa.dashboard',
                'mahasiswa.profil.index',
                'mahasiswa.profil.manajemen',
                'mahasiswa.profil.update',
                'mahasiswa.profil.update-password',
            ];
            
            if (!$mahasiswa->isProfileComplete()) {
                $currentRoute = $request->route()->getName();
                
                if (!in_array($currentRoute, $allowedRoutes)) {
                    return redirect()->route('mahasiswa.profil.manajemen')
                        ->with('warning', 'Lengkapi data profil Anda terlebih dahulu sebelum mengakses fitur lainnya.');
                }
            }
        }
        
        return $next($request);
    }
}
