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
        
        // Get KRS yang sudah approved untuk semester aktif
        $krsData = $mahasiswa->krs()
            ->with(['kelasMataKuliah.mataKuliah', 'kelasMataKuliah.dosen.user', 'kelasMataKuliah.jadwal'])
            ->where('semester_id', $semesterAktif->id ?? null)
            ->where('status', 'approved')
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
            'Minggu' => []
        ];
        
        foreach($krsData as $krs) {
            $kelas = $krs->kelasMataKuliah;
            if($kelas && $kelas->jadwal) {
                $hari = $kelas->jadwal->hari;
                $jadwalPerHari[$hari][] = [
                    'mata_kuliah' => $kelas->mataKuliah->nama_mk ?? '-',
                    'kode_mk' => $kelas->mataKuliah->kode_mk ?? '-',
                    'sks' => $kelas->mataKuliah->sks ?? 0,
                    'dosen' => $kelas->dosen->user->name ?? '-',
                    'kelas' => $kelas->nama_kelas,
                    'jam_mulai' => $kelas->jadwal->jam_mulai,
                    'jam_selesai' => $kelas->jadwal->jam_selesai,
                    'ruangan' => $kelas->jadwal->ruangan ?? 'Online'
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
