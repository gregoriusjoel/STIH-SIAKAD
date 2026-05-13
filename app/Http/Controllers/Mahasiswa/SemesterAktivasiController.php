<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\KuesionerAktivasi;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Handle semester activation questionnaire (when advancing semester)
 * Separate from AktivasiController which handles first-time account activation
 */
class SemesterAktivasiController extends Controller
{
    /**
     * Show the questionnaire form for semester activation
     */
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        
        // Get current active semester
        $currentSemester = Semester::where('status', 'aktif')
            ->orWhere('is_active', true)
            ->first();
        
        if (!$currentSemester) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Tidak ada semester aktif saat ini');
        }
        
        // Check if already filled for this semester
        $existing = KuesionerAktivasi::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $currentSemester->id)
            ->first();
        
        if ($existing) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('info', 'Anda sudah mengisi kuesioner untuk semester ini');
        }
        
        return view('page.mahasiswa.semester-aktivasi.index', compact('mahasiswa', 'currentSemester'));
    }
    
    /**
     * Store the questionnaire response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fasilitas_kampus' => 'required|integer|min:1|max:5',
            'sistem_akademik' => 'required|integer|min:1|max:5',
            'kualitas_dosen' => 'required|integer|min:1|max:5',
            'layanan_administrasi' => 'required|integer|min:1|max:5',
            'kepuasan_keseluruhan' => 'required|integer|min:1|max:5',
            'saran' => 'nullable|string|max:1000',
        ]);
        
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        
        // Get current active semester
        $currentSemester = Semester::where('status', 'aktif')
            ->orWhere('is_active', true)
            ->first();
        
        if (!$currentSemester) {
            return back()->with('error', 'Tidak ada semester aktif saat ini');
        }
        
        // Check again if already filled (prevent duplicate)
        $existing = KuesionerAktivasi::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $currentSemester->id)
            ->first();
        
        if ($existing) {
            return back()->with('info', 'Anda sudah mengisi kuesioner untuk semester ini');
        }
        
        // Save kuesioner
        KuesionerAktivasi::create([
            'mahasiswa_id' => $mahasiswa->id,
            'semester_id' => $currentSemester->id,
            'fasilitas_kampus' => $validated['fasilitas_kampus'],
            'sistem_akademik' => $validated['sistem_akademik'],
            'kualitas_dosen' => $validated['kualitas_dosen'],
            'layanan_administrasi' => $validated['layanan_administrasi'],
            'kepuasan_keseluruhan' => $validated['kepuasan_keseluruhan'],
            'saran' => $validated['saran'],
        ]);
        
        return redirect()->route('mahasiswa.dashboard')
            ->with('success', 'Terima kasih! Kuesioner semester berhasil disimpan. Selamat datang di semester ' . $mahasiswa->semester);
    }
}
