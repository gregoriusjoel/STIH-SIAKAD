<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        // Load pending jadwals (waiting for approval)
        $pendingJadwals = Jadwal::with(['kelas.mataKuliah', 'kelas.dosen'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Load approved jadwals (waiting for room assignment)
        $approvedJadwals = Jadwal::with(['kelas.mataKuliah', 'kelas.dosen', 'approvedBy'])
            ->where('status', 'approved')
            ->orderBy('approved_at', 'desc')
            ->get();

        // Load active jadwals
        $activeJadwals = Jadwal::with(['kelas.mataKuliah', 'kelas.dosen'])
            ->where('status', 'active')
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->paginate(10);

        return view('admin.jadwal.index', compact('pendingJadwals', 'approvedJadwals', 'activeJadwals'));
    }

    public function create()
    {
        $mataKuliahs = MataKuliah::orderBy('nama_mk')->get();
        $kelasList = Kelas::with(['mataKuliah', 'dosen'])->get();
        return view('admin.jadwal.create', compact('mataKuliahs', 'kelasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruangan' => 'required|string|max:50',
        ]);

        Jadwal::create([
            'kelas_id' => $request->kelas_id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'ruangan' => $request->ruangan,
            'status' => 'active',
        ]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function edit(Jadwal $jadwal)
    {
        $kelasList = Kelas::with(['mataKuliah', 'dosen'])->get();
        return view('admin.jadwal.edit', compact('jadwal', 'kelasList'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruangan' => 'required|string|max:50',
        ]);

        $jadwal->update($request->only(['kelas_id', 'hari', 'jam_mulai', 'jam_selesai', 'ruangan']));
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus');
    }

    /**
     * Approve a pending jadwal
     */
    public function approve(Request $request, Jadwal $jadwal)
    {
        if ($jadwal->status !== 'pending') {
            return redirect()->route('admin.jadwal.index')
                ->with('error', 'Jadwal ini sudah tidak dalam status pending.');
        }

        $jadwal->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'catatan_admin' => $request->input('catatan'),
        ]);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil disetujui. Silakan assign ruangan dan kelas.');
    }

    /**
     * Reject a pending jadwal
     */
    public function reject(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'catatan' => 'required|string|max:1000',
        ]);

        if ($jadwal->status !== 'pending') {
            return redirect()->route('admin.jadwal.index')
                ->with('error', 'Jadwal ini sudah tidak dalam status pending.');
        }

        $jadwal->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'catatan_admin' => $request->input('catatan'),
        ]);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil ditolak.');
    }

    /**
     * Assign room and section to an approved jadwal
     */
    public function assignRoom(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'ruangan' => 'required|string|max:100',
            'section' => 'required|string|max:10',
        ]);

        if ($jadwal->status !== 'approved') {
            return redirect()->route('admin.jadwal.index')
                ->with('error', 'Jadwal harus disetujui terlebih dahulu sebelum assign ruangan.');
        }

        // Update kelas section
        $jadwal->kelas->update([
            'section' => $request->input('section'),
        ]);

        // Update jadwal with room and activate
        $jadwal->update([
            'ruangan' => $request->input('ruangan'),
            'status' => 'active',
        ]);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Ruangan dan kelas berhasil di-assign. Jadwal sekarang aktif.');
    }
}
