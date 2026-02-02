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
        // Load active jadwals
        $activeJadwals = Jadwal::with(['kelas.mataKuliah', 'kelas.dosen'])
            ->where('status', 'active')
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->paginate(10);

        // Load kelas mata kuliah for jadwal aktif display (paginated)
        $kelasMataKuliahs = \App\Models\KelasMataKuliah::with(['mataKuliah', 'dosen.user', 'semester'])
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->paginate(10);

        // Load ALL kelas mata kuliah for room visualization (not paginated)
        $allSchedules = \App\Models\KelasMataKuliah::with(['mataKuliah', 'dosen.user'])
            ->whereNotNull('ruang')
            ->whereNotNull('hari')
            ->get();

        // Fetch data for "Tambah Kelas Mata Kuliah Baru" form
        $mataKuliahs = MataKuliah::orderBy('nama_mk')->get();
        $dosens = \App\Models\Dosen::with('user')->get();

        // Get list of unique rooms for filter/display (from KelasMataKuliah, column 'ruang')
        $rooms = \App\Models\KelasMataKuliah::whereNotNull('ruang')->distinct()->pluck('ruang')->sort()->values();

        // Dummy room data for dropdown (R.100 - R.199)
        $daftarRuangan = collect();
        for ($i = 100; $i <= 199; $i++) {
            $daftarRuangan->push('R.' . $i);
        }

        return view('admin.jadwal.index', compact('activeJadwals', 'kelasMataKuliahs', 'allSchedules', 'mataKuliahs', 'dosens', 'rooms', 'daftarRuangan'));
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
                    'Senin' => 'Monday',
                    'Selasa' => 'Tuesday',
                    'Rabu' => 'Wednesday',
                    'Kamis' => 'Thursday',
                    'Jumat' => 'Friday',
                    'Sabtu' => 'Saturday',
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

    /**
     * Approve a weekly kelas reschedule request
     */
    public function approveKelasReschedule(Request $request, \App\Models\KelasReschedule $reschedule)
    {
        if ($reschedule->status !== 'pending') {
            return redirect()->route('admin.jadwal.index')->with('error', 'Permintaan ini sudah diproses.');
        }

        $reschedule->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Permintaan reschedule disetujui. Silakan tetapkan ruangan.');
    }

    /**
     * Reject a weekly kelas reschedule request
     */
    public function rejectKelasReschedule(Request $request, \App\Models\KelasReschedule $reschedule)
    {
        $request->validate(['catatan_admin' => 'required|string|max:1000']);

        if ($reschedule->status !== 'pending') {
            return redirect()->route('admin.jadwal.index')->with('error', 'Permintaan ini sudah diproses.');
        }

        $reschedule->update([
            'status' => 'rejected',
            'catatan_admin' => $request->catatan_admin,
        ]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Permintaan reschedule ditolak.');
    }

    /**
     * Assign room and class to an approved weekly kelas reschedule
     */
    public function assignRoomKelasReschedule(Request $request, \App\Models\KelasReschedule $reschedule)
    {
        $request->validate([
            'new_kelas' => 'required|string|max:50',
            'new_ruang' => 'required|string|max:100',
        ]);

        if ($reschedule->status !== 'approved') {
            return redirect()->route('admin.jadwal.index')->with('error', 'Permintaan harus disetujui terlebih dahulu.');
        }

        $reschedule->update([
            'new_kelas' => $request->new_kelas,
            'new_ruang' => $request->new_ruang,
            'status' => 'room_assigned',
        ]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Kelas dan ruangan berhasil ditetapkan untuk minggu tersebut.');
    }

    /**
     * Get dosens that teach a specific mata kuliah (API for form filtering)
     */
    public function getDosensByMataKuliah($mataKuliahId)
    {
        // Cari dosen yang memiliki mata_kuliah_id di dalam kolom JSON mata_kuliah_ids
        // Karena DosenController menyimpan data ke kolom JSON, bukan pivot table
        $dosens = \App\Models\Dosen::whereJsonContains('mata_kuliah_ids', (string) $mataKuliahId)
            ->orWhereJsonContains('mata_kuliah_ids', (int) $mataKuliahId)
            ->with('user')
            ->get();

        return response()->json($dosens->map(function ($dosen) {
            // Hitung total SKS yang sudah diampu dosen ini
            $totalSks = \App\Models\KelasMataKuliah::where('dosen_id', $dosen->id)
                ->with('mataKuliah')
                ->get()
                ->sum(function ($kelas) {
                    return $kelas->mataKuliah?->sks ?? 0;
                });

            return [
                'id' => $dosen->id,
                'name' => $dosen->user->name ?? 'N/A',
                'total_sks' => $totalSks
            ];
        }));
    }

    /**
     * Check room availability API (uses KelasMataKuliah model, column 'ruang')
     */
    public function checkRoomAvailability(Request $request)
    {
        $request->validate([
            'hari' => 'required|string',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'ruangan' => 'required|string',
            'ignore_id' => 'nullable|integer'
        ]);

        $hari = $request->hari;
        $mulai = $request->jam_mulai;
        $selesai = $request->jam_selesai;
        $ruangan = $request->ruangan;
        $ignoreId = $request->ignore_id;

        // Cek clash: (StartA < EndB) && (EndA > StartB) using KelasMataKuliah
        $query = \App\Models\KelasMataKuliah::where('hari', $hari)
            ->where('ruang', $ruangan)
            ->where(function ($q) use ($mulai, $selesai) {
                $q->where('jam_mulai', '<', $selesai)
                    ->where('jam_selesai', '>', $mulai);
            });

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $conflict = $query->with(['mataKuliah', 'dosen.user'])->first();

        if ($conflict) {
            return response()->json([
                'available' => false,
                'message' => "Ruangan $ruangan sudah terpakai oleh " .
                    ($conflict->dosen->user->name ?? 'Dosen') .
                    " (" . ($conflict->mataKuliah->nama_mk ?? '-') . ") " .
                    "pukul " . substr($conflict->jam_mulai, 0, 5) . "-" . substr($conflict->jam_selesai, 0, 5)
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => 'Ruangan tersedia'
        ]);
    }
}
