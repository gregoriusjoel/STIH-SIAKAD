<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semester;

class JadwalController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        
        // Get semester aktif
        $semesterAktif = Semester::where('is_active', true)->first();
        
        // Get KRS yang sudah disetujui untuk semester aktif
        $krsQuery = $mahasiswa->krs()
            ->with(['kelas.mataKuliah', 'kelas.dosen', 'kelas.jadwals']);

        // If there is an active semester, constrain by kelas' tahun_ajaran and semester_type
        if ($semesterAktif) {
            $krsQuery->whereHas('kelas', function ($q) use ($semesterAktif) {
                $q->where('tahun_ajaran', $semesterAktif->tahun_ajaran)
                  ->where('semester_type', $semesterAktif->nama_semester);
            });
        }

        $krsData = $krsQuery->whereIn('status', ['approved', 'disetujui'])
            ->where('ambil_mk', 'ya')
            ->get();
        
        // Group jadwal by hari
        $jadwalPerHari = [
            'Senin' => [],
            'Selasa' => [],
            'Rabu' => [],
            'Kamis' => [],
            'Jumat' => [],
            'Sabtu' => [],
        ];
        
        foreach($krsData as $krs) {
            $kelas = $krs->kelas;
            if (!$kelas) {
                continue;
            }

            // If kelas has multiple jadwals, add each one separately
            $jadwals = $kelas->jadwals ?? collect([]);
            foreach ($jadwals as $jadwal) {
                if (!$jadwal) {
                    continue;
                }
                $hari = $jadwal->hari;
                $jadwalPerHari[$hari][] = [
                    'mata_kuliah' => $kelas->mataKuliah->nama_mk ?? '-',
                    'kode_mk' => $kelas->mataKuliah->kode_mk ?? '-',
                    'sks' => $kelas->mataKuliah->sks ?? 0,
                    'dosen' => $kelas->dosen->name ?? '-',
                    'kelas' => $kelas->kode_kelas ?? $kelas->nama_kelas ?? '-',
                    'jam_mulai' => $jadwal->jam_mulai,
                    'jam_selesai' => $jadwal->jam_selesai,
                    'ruangan' => $jadwal->ruangan ?? 'Online'
                ];
            }
        }
        
        // Sort jadwal by jam_mulai for each day
        foreach($jadwalPerHari as $hari => $jadwals) {
            usort($jadwalPerHari[$hari], function($a, $b) {
                return strcmp($a['jam_mulai'], $b['jam_mulai']);
            });
        }
        
        return view('page.mahasiswa.jadwal.index', compact(
            'mahasiswa',
            'semesterAktif',
            'jadwalPerHari',
            'krsData'
        ));
    }
}
