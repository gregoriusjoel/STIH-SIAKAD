<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\JadwalReschedule;
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

        // Also fetch pending reschedule requests to show within the pending card
        $pendingReschedules = JadwalReschedule::with(['jadwal.kelas.mataKuliah', 'dosen'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.jadwal.index', compact('pendingJadwals', 'approvedJadwals', 'activeJadwals', 'pendingReschedules'));
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

    /**
     * List pending reschedule requests for admin approval
     */
    public function reschedules()
    {
        $reschedules = JadwalReschedule::with(['jadwal.kelas.mataKuliah', 'dosen'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.jadwal.reschedules.index', compact('reschedules'));
    }

    /**
     * Approve a reschedule request: update the jadwal and mark reschedule as approved
     */
    public function approveReschedule(Request $request, JadwalReschedule $reschedule)
    {
        if ($reschedule->status !== 'pending') {
            return redirect()->route('admin.jadwal.index')->with('error', 'Permintaan ini sudah diproses.');
        }

        // If this reschedule is only for one week and has an apply_date,
        // create a one-off jadwal exception for that date instead of changing the master jadwal.
        if ($reschedule->one_week_only) {
            $applyDate = $reschedule->apply_date;
            if (empty($applyDate)) {
                // compute next date for the requested new_hari by mapping Indonesian day to English
                $dayMap = [
                    'Senin' => 'Monday', 'Selasa' => 'Tuesday', 'Rabu' => 'Wednesday',
                    'Kamis' => 'Thursday', 'Jumat' => 'Friday', 'Sabtu' => 'Saturday',
                ];
                $english = $dayMap[$reschedule->new_hari] ?? null;
                if ($english) {
                    try {
                        $applyDate = \Carbon\Carbon::parse('next ' . $english)->toDateString();
                    } catch (\Exception $e) {
                        $applyDate = null;
                    }
                }
            }

            if ($applyDate) {
                \App\Models\JadwalException::create([
                    'jadwal_id' => $reschedule->jadwal_id,
                    'date' => $applyDate,
                    'hari' => $reschedule->new_hari,
                    'jam_mulai' => $reschedule->new_jam_mulai,
                    'jam_selesai' => $reschedule->new_jam_selesai,
                    'catatan' => $reschedule->catatan,
                ]);

                $reschedule->update(['status' => 'approved', 'apply_date' => $applyDate]);

                return redirect()->route('admin.jadwal.index')->with('success', 'Permintaan reschedule disetujui untuk minggu tersebut (satu kali).');
            }
        }

        // Fallback: update the master jadwal (apply permanently)
        $jadwal = $reschedule->jadwal;
        $jadwal->update([
            'hari' => $reschedule->new_hari,
            'jam_mulai' => $reschedule->new_jam_mulai,
            'jam_selesai' => $reschedule->new_jam_selesai,
            'status' => 'approved', // Move to Menunggu Ruangan for room assignment
        ]);

        $reschedule->update(['status' => 'approved']);

        return redirect()->route('admin.jadwal.index')->with('success', 'Permintaan reschedule disetujui. Silakan tetapkan ruangan.');
    }

    /**
     * Reject a reschedule request
     */
    public function rejectReschedule(Request $request, JadwalReschedule $reschedule)
    {
        $request->validate(['catatan' => 'required|string|max:1000']);

        if ($reschedule->status !== 'pending') {
            return redirect()->route('admin.jadwal.index')->with('error', 'Permintaan ini sudah diproses.');
        }

        $reschedule->update([
            'status' => 'rejected',
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Permintaan reschedule ditolak.');
    }
}
