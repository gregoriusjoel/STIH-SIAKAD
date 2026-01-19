<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semester;

class PembayaranController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        
        // Get semua pembayaran dengan relasi
        $pembayaranData = $mahasiswa->pembayaran()
            ->with('semester')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get semester aktif
        $semesterAktif = Semester::where('is_active', true)->first();
        
        // Get pembayaran semester aktif
        $pembayaranAktif = $pembayaranData->where('semester_id', $semesterAktif->id ?? null)->first();
        
        // Calculate totals
        $totalTagihan = $pembayaranData->sum('jumlah');
        $totalDibayar = $pembayaranData->sum('dibayar');
        $totalSisa = $totalTagihan - $totalDibayar;
        
        return view('page.mahasiswa.pembayaran.index', compact(
            'mahasiswa',
            'pembayaranData',
            'semesterAktif',
            'pembayaranAktif',
            'totalTagihan',
            'totalDibayar',
            'totalSisa'
        ));
    }
}
