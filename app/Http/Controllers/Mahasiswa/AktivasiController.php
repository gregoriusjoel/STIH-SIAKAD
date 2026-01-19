<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\KuesionerAktivasi;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AktivasiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        
        // If already active, redirect to dashboard
        if ($mahasiswa->isAktif()) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('info', 'Akun Anda sudah aktif');
        }
        
        return view('page.mahasiswa.aktivasi.index', compact('mahasiswa'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'fasilitas_kampus' => 'required|integer|min:1|max:5',
            'sistem_akademik' => 'required|integer|min:1|max:5',
            'kualitas_dosen' => 'required|integer|min:1|max:5',
            'layanan_administrasi' => 'required|integer|min:1|max:5',
            'kepuasan_keseluruhan' => 'required|integer|min:1|max:5',
            'saran' => 'nullable|string|max:1000',
        ]);
        
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        
        // Get current semester
        $semester = Semester::latest()->first();
        
        // Save kuesioner
        KuesionerAktivasi::create([
            'mahasiswa_id' => $mahasiswa->id,
            'semester_id' => $semester->id ?? null,
            'fasilitas_kampus' => $request->fasilitas_kampus,
            'sistem_akademik' => $request->sistem_akademik,
            'kualitas_dosen' => $request->kualitas_dosen,
            'layanan_administrasi' => $request->layanan_administrasi,
            'kepuasan_keseluruhan' => $request->kepuasan_keseluruhan,
            'saran' => $request->saran,
        ]);
        
        // Update status mahasiswa to aktif
        $mahasiswa->update(['status_akun' => 'aktif']);
        
        return redirect()->route('mahasiswa.dashboard')
            ->with('success', 'Aktivasi akun berhasil! Selamat datang kembali.');
    }
}
