<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        
        // Get semua nilai dengan relasi, hanya yang sudah di-publish
        $nilaiData = $mahasiswa->krs()
            ->with([
                'kelasMataKuliah.mataKuliah', 
                'kelasMataKuliah.semester', 
                'nilai' => function($q) {
                    $q->where('is_published', true);
                },
                'nilai.bobotPenilaian',
                'kelas.bobotPenilaian'
            ])
            ->whereHas('nilai', function($q) {
                $q->where('is_published', true);
            })
            ->get();
        
        // Group by semester
        $nilaiPerSemester = $nilaiData->groupBy(function($item) {
            return $item->kelasMataKuliah->semester->nama_semester ?? 'Unknown';
        });
        
        // Calculate IPK (overall)
        $totalBobot = 0;
        $totalSks = 0;
        
        foreach($nilaiData as $krs) {
            if($krs->nilai) {
                $bobot = $krs->nilai->bobot ?? $this->getBobot($krs->nilai->nilai_akhir ?? 0);
                $sks = $krs->kelasMataKuliah->mataKuliah->sks ?? 0;
                $totalBobot += ($bobot * $sks);
                $totalSks += $sks;
            }
        }
        
        $ipk = $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;
        
        // Calculate IPS per semester
        $ipsPerSemester = [];
        foreach($nilaiPerSemester as $semesterNama => $nilaiList) {
            $semesterBobot = 0;
            $semesterSks = 0;
            
            foreach($nilaiList as $krs) {
                if($krs->nilai) {
                    $bobot = $krs->nilai->bobot ?? $this->getBobot($krs->nilai->nilai_akhir ?? 0);
                    $sks = $krs->kelasMataKuliah->mataKuliah->sks ?? 0;
                    $semesterBobot += ($bobot * $sks);
                    $semesterSks += $sks;
                }
            }
            
            $ipsPerSemester[$semesterNama] = [
                'ips' => $semesterSks > 0 ? round($semesterBobot / $semesterSks, 2) : 0,
                'sks' => $semesterSks
            ];
        }
        
        return view('page.mahasiswa.nilai.index', compact(
            'mahasiswa',
            'nilaiPerSemester',
            'ipk',
            'totalSks',
            'ipsPerSemester'
        ));
    }

    public function khs()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        $nilaiData = $mahasiswa->krs()
            ->with([
                'kelasMataKuliah.mataKuliah', 
                'kelasMataKuliah.semester', 
                'nilai' => function($q) {
                    $q->where('is_published', true);
                },
                'nilai.bobotPenilaian',
                'kelas.bobotPenilaian'
            ])
            ->whereHas('nilai', function($q) {
                $q->where('is_published', true);
            })
            ->get();

        $nilaiPerSemester = $nilaiData->groupBy(function($item) {
            return $item->kelasMataKuliah->semester->nama_semester ?? 'Unknown';
        });

        // reuse IPK calculation
        $totalBobot = 0;
        $totalSks = 0;
        foreach($nilaiData as $krs) {
            if($krs->nilai) {
                $bobot = $krs->nilai->bobot ?? $this->getBobot($krs->nilai->nilai_akhir ?? 0);
                $sks = $krs->kelasMataKuliah->mataKuliah->sks ?? 0;
                $totalBobot += ($bobot * $sks);
                $totalSks += $sks;
            }
        }
        $ipk = $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;

        $ipsPerSemester = [];
        foreach($nilaiPerSemester as $semesterNama => $nilaiList) {
            $semesterBobot = 0;
            $semesterSks = 0;
            foreach($nilaiList as $krs) {
                if($krs->nilai) {
                    $bobot = $krs->nilai->bobot ?? $this->getBobot($krs->nilai->nilai_akhir ?? 0);
                    $sks = $krs->kelasMataKuliah->mataKuliah->sks ?? 0;
                    $semesterBobot += ($bobot * $sks);
                    $semesterSks += $sks;
                }
            }
            $ipsPerSemester[$semesterNama] = [
                'ips' => $semesterSks > 0 ? round($semesterBobot / $semesterSks, 2) : 0,
                'sks' => $semesterSks
            ];
        }

        return view('page.mahasiswa.khs.index', compact(
            'mahasiswa', 'nilaiPerSemester', 'ipk', 'totalSks', 'ipsPerSemester'
        ));
    }

    public function print()
    {
        // reuse index data
        $mahasiswa = Auth::user()->mahasiswa;

        $nilaiData = $mahasiswa->krs()
            ->with([
                'kelasMataKuliah.mataKuliah', 
                'kelasMataKuliah.semester', 
                'nilai' => function($q) {
                    $q->where('is_published', true);
                },
                'nilai.bobotPenilaian',
                'kelas.bobotPenilaian'
            ])
            ->whereHas('nilai', function($q) {
                $q->where('is_published', true);
            })
            ->get();

        $nilaiPerSemester = $nilaiData->groupBy(function($item) {
            return $item->kelasMataKuliah->semester->nama_semester ?? 'Unknown';
        });

        // Calculate IPK and IPS per semester as in index
        $totalBobot = 0;
        $totalSks = 0;
        foreach($nilaiData as $krs) {
            if($krs->nilai) {
                $bobot = $krs->nilai->bobot ?? $this->getBobot($krs->nilai->nilai_akhir ?? 0);
                $sks = $krs->kelasMataKuliah->mataKuliah->sks ?? 0;
                $totalBobot += ($bobot * $sks);
                $totalSks += $sks;
            }
        }
        $ipk = $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;

        $ipsPerSemester = [];
        foreach($nilaiPerSemester as $semesterNama => $nilaiList) {
            $semesterBobot = 0;
            $semesterSks = 0;
            foreach($nilaiList as $krs) {
                if($krs->nilai) {
                    $bobot = $this->getBobot($krs->nilai->nilai);
                    $sks = $krs->kelasMataKuliah->mataKuliah->sks ?? 0;
                    $semesterBobot += ($bobot * $sks);
                    $semesterSks += $sks;
                }
            }
            $ipsPerSemester[$semesterNama] = [
                'ips' => $semesterSks > 0 ? round($semesterBobot / $semesterSks, 2) : 0,
                'sks' => $semesterSks
            ];
        }

        return view('page.mahasiswa.nilai.print', compact(
            'mahasiswa', 'nilaiPerSemester', 'ipk', 'totalSks', 'ipsPerSemester'
        ));
    }
    
    public function getBobot($nilai)
    {
        if ($nilai >= 85) return 4.0;
        if ($nilai >= 80) return 3.7;
        if ($nilai >= 75) return 3.3;
        if ($nilai >= 70) return 3.0;
        if ($nilai >= 65) return 2.7;
        if ($nilai >= 60) return 2.3;
        if ($nilai >= 55) return 2.0;
        if ($nilai >= 50) return 1.7;
        if ($nilai >= 45) return 1.3;
        return 0;
    }
    
    public function getGrade($nilai)
    {
        if ($nilai >= 85) return 'A';
        if ($nilai >= 80) return 'A-';
        if ($nilai >= 75) return 'B+';
        if ($nilai >= 70) return 'B';
        if ($nilai >= 65) return 'B-';
        if ($nilai >= 60) return 'C+';
        if ($nilai >= 55) return 'C';
        if ($nilai >= 50) return 'C-';
        if ($nilai >= 45) return 'D';
        return 'E';
    }
}
