<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use App\Models\Krs;
use App\Models\Pembayaran;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentController extends Controller
{
    private function checkAccess()
    {
        if (Auth::user()->role !== 'parent') {
            abort(403, 'Unauthorized access');
        }
    }

    private function getMahasiswa()
    {
        $this->checkAccess();
        $user = Auth::user();
        $parent = ParentModel::where('user_id', $user->id)->firstOrFail();
        return $parent->mahasiswa;
    }

    public function dashboard()
    {
        $mahasiswa = $this->getMahasiswa();

        // Get active KRS for current semester
        $activeKrs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'approved')
            ->with(['kelasMataKuliah.mataKuliah'])
            ->get();

        // Calculate stats
        $totalSks = $activeKrs->sum(function ($krs) {
            return $krs->kelasMataKuliah->mataKuliah->sks ?? 0;
        });

        $jumlahMk = $activeKrs->count();

        // IPK Calculation (simplified for dashboard)
        $nilaiData = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->whereHas('nilai')
            ->with(['nilai', 'kelasMataKuliah.mataKuliah'])
            ->get();
        $totalBobot = 0;
        $totalSksNilai = 0;

        foreach ($nilaiData as $krs) {
            if ($krs->nilai && $krs->kelasMataKuliah && $krs->kelasMataKuliah->mataKuliah) {
                $sks = $krs->kelasMataKuliah->mataKuliah->sks ?? 0;
                $bobot = $this->getBobot($krs->nilai->grade);
                $totalBobot += ($bobot * $sks);
                $totalSksNilai += $sks;
            }
        }

        $ipk = $totalSksNilai > 0 ? round($totalBobot / $totalSksNilai, 2) : 0;

        // Payment status
        $pembayaran = Pembayaran::where('mahasiswa_id', $mahasiswa->id)
            ->latest()
            ->first();
        $statusPembayaran = $pembayaran ? $pembayaran->status : 'belum_bayar';

        return view('page.parent.dashboard', compact(
            'mahasiswa',
            'totalSks',
            'jumlahMk',
            'ipk',
            'statusPembayaran'
        ));
    }

    public function nilai()
    {
        $mahasiswa = $this->getMahasiswa();

        $nilaiData = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->whereHas('nilai')
            ->with(['kelasMataKuliah.mataKuliah', 'kelasMataKuliah.semester', 'nilai'])
            ->get()
            ->groupBy(function ($item) {
                return $item->kelasMataKuliah->semester->nama_semester ?? 'Unknown';
            });

        // Calculate IPK
        $allNilai = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->whereHas('nilai')
            ->with(['nilai', 'kelasMataKuliah.mataKuliah'])
            ->get();

        $totalBobot = 0;
        $totalSks = 0;
        foreach ($allNilai as $krs) {
            if ($krs->nilai) {
                $bobot = $this->getBobot($krs->nilai->grade); // using grade column as per prev file
                $sks = $krs->kelasMataKuliah->mataKuliah->sks ?? 0;
                $totalBobot += ($bobot * $sks);
                $totalSks += $sks;
            }
        }
        $ipk = $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;

        return view('page.parent.nilai', compact('mahasiswa', 'nilaiData', 'ipk'));
    }

    public function jadwal()
    {
        $mahasiswa = $this->getMahasiswa();

        // Get approved KRS for active semester (assuming there is a way to get active semester, or just show all active class schedules)
        // For now, let's just get the schedules from the approved KRS
        $activeKrs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'approved')
            ->with(['kelasMataKuliah.mataKuliah', 'kelasMataKuliah.jadwal', 'kelasMataKuliah.dosen.user', 'kelasMataKuliah.semester'])
            ->get();

        return view('page.parent.jadwal', compact('mahasiswa', 'activeKrs'));
    }

    public function presensi()
    {
        $mahasiswa = $this->getMahasiswa();

        $presensiData = Presensi::where('mahasiswa_id', $mahasiswa->id)
            ->with(['jadwal.kelas.mataKuliah'])
            ->latest()
            ->paginate(10);

        return view('page.parent.presensi', compact('mahasiswa', 'presensiData'));
    }

    public function pembayaran()
    {
        $mahasiswa = $this->getMahasiswa();

        $pembayaranData = Pembayaran::where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('page.parent.pembayaran', compact('mahasiswa', 'pembayaranData'));
    }

    // Helper function (same as in Mahasiswa logic)
    private function getBobot($grade)
    {
        $bobotMap = [
            'A' => 4.0,
            'A-' => 3.7,
            'B+' => 3.3,
            'B' => 3.0,
            'B-' => 2.7,
            'C+' => 2.3,
            'C' => 2.0,
            'C-' => 1.7,
            'D' => 1.0,
            'E' => 0,
        ];
        return $bobotMap[$grade] ?? 0;
    }
}
