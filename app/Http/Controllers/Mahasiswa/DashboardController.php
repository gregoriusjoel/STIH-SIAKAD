<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Krs;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        
        // Get active KRS for current semester
        $activeKrs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'approved')
            ->with(['kelasMataKuliah.mataKuliah'])
            ->get();
        
        // Calculate stats
        $totalSks = $activeKrs->sum(function($krs) {
            return $krs->kelasMataKuliah->mataKuliah->sks ?? 0;
        });
        
        $jumlahMk = $activeKrs->count();
        
        // Get nilai count (for IPK calculation)
        $nilaiData = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->whereHas('nilai')
            ->with(['nilai', 'kelasMataKuliah.mataKuliah'])
            ->get();
        
        $totalMutu = 0;
        $totalSksNilai = 0;
        
        foreach ($nilaiData as $krs) {
            if ($krs->nilai && $krs->kelasMataKuliah && $krs->kelasMataKuliah->mataKuliah) {
                $sks = $krs->kelasMataKuliah->mataKuliah->sks ?? 0;
                $bobot = $this->getBobot($krs->nilai->grade);
                $totalMutu += $sks * $bobot;
                $totalSksNilai += $sks;
            }
        }
        
        $ipk = $totalSksNilai > 0 ? round($totalMutu / $totalSksNilai, 2) : 0;
        
        // Get payment status
        $pembayaran = Pembayaran::where('mahasiswa_id', $mahasiswa->id)
            ->latest()
            ->first();
        
        $statusPembayaran = $pembayaran ? $pembayaran->status : 'belum_bayar';
        
        // Get KRS status
        $krsStatus = $activeKrs->isEmpty() ? 'Belum Diisi' : 'Disetujui';
        
        return view('page.mahasiswa.dashboard', compact(
            'mahasiswa',
            'totalSks',
            'jumlahMk',
            'ipk',
            'totalSksNilai',
            'statusPembayaran',
            'krsStatus'
        ));
    }
    
    private function getBobot($grade)
    {
        $bobotMap = [
            'A' => 4.0, 'A-' => 3.7,
            'B+' => 3.3, 'B' => 3.0, 'B-' => 2.7,
            'C+' => 2.3, 'C' => 2.0, 'C-' => 1.7,
            'D' => 1.0, 'E' => 0,
        ];
        return $bobotMap[$grade] ?? 0;
    }
}
