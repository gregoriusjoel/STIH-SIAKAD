<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    /**
     * Display jadwal page based on status
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get dosen's jadwals through kelas
        $activeJadwals = Jadwal::whereHas('kelas', function ($query) use ($user) {
            $query->where('dosen_id', $user->id);
        })->where('status', 'active')->with(['kelas.mataKuliah'])->get();

        $pendingJadwals = Jadwal::whereHas('kelas', function ($query) use ($user) {
            $query->where('dosen_id', $user->id);
        })->whereIn('status', ['pending', 'approved'])->with(['kelas.mataKuliah'])->get();

        // Case 1: Has active jadwals → show schedule page
        if ($activeJadwals->count() > 0) {
            // Group by day
            $schedulesByDay = $activeJadwals->groupBy('hari');
            return view('page.dosen.jadwal.index', compact('schedulesByDay', 'activeJadwals'));
        }

        // Case 2: Has pending/approved jadwals → show waiting page
        if ($pendingJadwals->count() > 0) {
            return view('page.dosen.jadwal.pending', compact('pendingJadwals'));
        }

        // Case 3: No jadwals → show input form
        $mataKuliahs = MataKuliah::orderBy('nama_mk')->get();
        return view('page.dosen.jadwal.form', compact('mataKuliahs'));
    }

    /**
     * Show the form for creating new jadwal
     */
    public function create()
    {
        $mataKuliahs = MataKuliah::orderBy('nama_mk')->get();
        return view('page.dosen.jadwal.form', compact('mataKuliahs'));
    }

    /**
     * Store a new jadwal request
     */
    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'catatan_dosen' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        // Create kelas with placeholder section (will be assigned by admin)
        $kelas = Kelas::firstOrCreate(
            [
                'mata_kuliah_id' => $request->mata_kuliah_id,
                'dosen_id' => $user->id,
                'section' => 'TBD', // To Be Determined by admin
            ],
            [
                'kapasitas' => 40,
                'tahun_ajaran' => '2023/2024',
                'semester_type' => 'Ganjil',
            ]
        );

        // Create jadwal with pending status
        Jadwal::create([
            'kelas_id' => $kelas->id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'ruangan' => null,
            'status' => 'pending',
            'catatan_dosen' => $request->catatan_dosen,
        ]);

        return redirect()->route('dosen.jadwal')
            ->with('success', 'Jadwal berhasil diajukan. Menunggu approval dari admin.');
    }
}
