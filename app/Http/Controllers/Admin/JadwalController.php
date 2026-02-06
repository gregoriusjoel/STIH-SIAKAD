<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\JadwalReschedule;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\JadwalProposal;
use App\Models\Ruangan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        // Load active jadwals that have been approved by dosen
        // Only show jadwals that have approval record from dosen
        $activeJadwals = Jadwal::with(['kelas.mataKuliah', 'kelas.dosen'])
            ->where('status', 'active')
            ->whereHas('kelas', function($query) {
                // Only include jadwals where there's a corresponding approved proposal
                $query->whereIn('id', function($subquery) {
                    $subquery->select('kelas_id')
                        ->from('jadwal_proposals')
                        ->whereIn('status', ['approved_dosen', 'approved_admin']);
                });
            })
            ->get()
            ->map(function ($j) {
                return (object)[
                    'id' => $j->id,
                    'db_id' => $j->id,
                    'type' => 'jadwal',
                    'hari' => $j->hari,
                    'jam_mulai' => $j->jam_mulai,
                    'jam_selesai' => $j->jam_selesai,
                    'ruang' => $j->ruangan,
                    'nama_mk' => $j->kelas->mataKuliah->nama_mk ?? '-',
                    'sks' => $j->kelas->mataKuliah->sks ?? 0,
                    'kode_kelas' => $j->kelas->section ?? '-',
                    'dosen_name' => $j->kelas->dosen->name ?? 'N/A',
                    'dosen_id' => $j->kelas->dosen_id ?? null,
                    'mata_kuliah_id' => $j->kelas->mata_kuliah_id ?? null,
                ];
            });

        // Don't load legacy schedules - only show approved ones
        $legacySchedules = collect();

        // Merge and de-duplicate based on MK + Section + Dosen
        $merged = $activeJadwals->concat($legacySchedules)
            ->groupBy(function ($item) {
                return $item->mata_kuliah_id . '-' . $item->kode_kelas . '-' . $item->dosen_id;
            })
            ->map(function ($group) {
                // Prefer 'jadwal' type if both exist
                return $group->sortBy(function ($item) {
                    return $item->type === 'jadwal' ? 0 : 1;
                })->first();
            })
            ->values();

        // Sort by day and time
        $hariOrder = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7];
        $sortedSchedules = $merged->sort(function ($a, $b) use ($hariOrder) {
            $dayA = $hariOrder[$a->hari] ?? 99;
            $dayB = $hariOrder[$b->hari] ?? 99;
            if ($dayA != $dayB) return $dayA <=> $dayB;
            return $a->jam_mulai <=> $b->jam_mulai;
        });

        // Pagination for the merged collection
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 5;
        $currentPageItems = $sortedSchedules->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $kelasMataKuliahs = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $sortedSchedules->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        // Load ALL schedules for room visualization (not paginated)
        $allSchedules = $sortedSchedules;

        // Fetch data for "Tambah Jadwal Baru" form
        $mataKuliahs = MataKuliah::orderBy('nama_mk')->get();
        $dosens = \App\Models\Dosen::with('user')->get();

        // Get list of all active rooms for filter/display (fetch from DB to include empty rooms)
        $roomsMissing = false;
        $rooms = collect();
        $daftarRuangan = collect();
        if (Schema::hasTable('ruangans') && Schema::hasColumn('ruangans', 'kode_ruangan')) {
            $daftarRuangan = Ruangan::where('status', 'aktif')
                ->orderBy('kode_ruangan')
                ->get();
            $rooms = $daftarRuangan->pluck('kode_ruangan');
            if ($daftarRuangan->isEmpty()) {
                $roomsMissing = true; // no rooms defined yet
            }
        } else {
            // missing table/column -> treat as missing rooms
            $roomsMissing = true;
        }

        // Get jam perkuliahan data
        $jamPerkuliahan = \App\Models\JamPerkuliahan::where('is_active', true)
            ->orderBy('jam_ke')
            ->get();

        // Generator data (statistics + proposals)
        $statistics = [
            'total_proposals' => JadwalProposal::count(),
            'pending_dosen' => JadwalProposal::where('status', 'pending_dosen')->count(),
            'approved_dosen' => JadwalProposal::where('status', 'approved_dosen')->count(),
            'pending_admin' => JadwalProposal::where('status', 'pending_admin')->count(),
            'approved_admin' => JadwalProposal::where('status', 'approved_admin')->count(),
            'rejected' => JadwalProposal::whereIn('status', ['rejected_dosen', 'rejected_admin'])->count(),
        ];

        $jadwalProposals = JadwalProposal::with(['mataKuliah', 'kelas', 'dosen', 'generatedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.jadwal.index', compact('kelasMataKuliahs', 'allSchedules', 'mataKuliahs', 'dosens', 'rooms', 'daftarRuangan', 'jamPerkuliahan', 'statistics', 'jadwalProposals'));
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
            'ruangan_id' => 'required|exists:ruangans,id',
        ]);

        $ruangan = Ruangan::findOrFail($request->ruangan_id);
        
        Jadwal::create([
            'kelas_id' => $request->kelas_id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'ruangan' => $ruangan->kode_ruangan,
            'status' => 'active',
        ]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function edit(Jadwal $jadwal)
    {
        $kelasList = Kelas::with(['mataKuliah', 'dosen'])->get();
        $daftarRuangan = Ruangan::where('status', 'aktif')->orderBy('kode_ruangan')->get();

        // Build a compatibility object expected by the edit view (legacy KelasMataKuliah-like)
        $kelas = $jadwal->kelas;
        $kelasMataKuliah = new \stdClass();
        $kelasMataKuliah->id = $kelas->id ?? null;
        $kelasMataKuliah->mata_kuliah_id = $kelas->mata_kuliah_id ?? ($kelas->mataKuliah->id ?? null);
        $kelasMataKuliah->kode_kelas = $kelas->section ?? ($kelas->kode_kelas ?? null);
        $kelasMataKuliah->kapasitas = $kelas->kapasitas ?? null;
        $kelasMataKuliah->ruangan_id = $jadwal->ruangan_id ?? null;
        $kelasMataKuliah->ruang = $jadwal->ruangan ?? null;
        $kelasMataKuliah->hari = $jadwal->hari ?? null;
        $kelasMataKuliah->jam_mulai = $jadwal->jam_mulai ?? null;
        $kelasMataKuliah->jam_selesai = $jadwal->jam_selesai ?? null;

        // Mata kuliah list for select
        $mataKuliahs = MataKuliah::orderBy('nama_mk')->get();

        return view('admin.jadwal.edit', compact('jadwal', 'kelasList', 'daftarRuangan', 'mataKuliahs', 'kelasMataKuliah'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruangan_id' => 'required|exists:ruangans,id',
        ]);

        $ruangan = Ruangan::findOrFail($request->ruangan_id);
        
        $jadwal->update([
            'kelas_id' => $request->kelas_id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'ruangan' => $ruangan->kode_ruangan,
        ]);
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
            'ruangan_id' => 'required|exists:ruangans,id',
            'section' => 'required|string|max:10',
        ]);

        if ($jadwal->status !== 'approved') {
            return redirect()->route('admin.jadwal.index')
                ->with('error', 'Jadwal harus disetujui terlebih dahulu sebelum assign ruangan.');
        }

        $ruangan = Ruangan::findOrFail($request->ruangan_id);

        // Update kelas section
        $jadwal->kelas->update([
            'section' => $request->input('section'),
        ]);

        // Update jadwal with room and activate
        $jadwal->update([
            'ruangan' => $ruangan->kode_ruangan,
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
            'ruangan_id' => 'required|exists:ruangans,id',
        ]);

        if ($reschedule->status !== 'approved') {
            return redirect()->route('admin.jadwal.index')->with('error', 'Permintaan harus disetujui terlebih dahulu.');
        }

        $ruangan = Ruangan::findOrFail($request->ruangan_id);

        $reschedule->update([
            'new_kelas' => $request->new_kelas,
            'new_ruang' => $ruangan->kode_ruangan,
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
     * Check room availability API (uses ruangan_id or ruangan kode)
     */
    public function checkRoomAvailability(Request $request)
    {
        $request->validate([
            'hari' => 'required|string',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'ruangan_id' => 'nullable|exists:ruangans,id',
            'ruangan' => 'nullable|string',
            'ignore_id' => 'nullable|integer'
        ]);

        $hari = $request->hari;
        $mulai = $request->jam_mulai;
        $selesai = $request->jam_selesai;
        $ignoreId = $request->ignore_id;

        // If ruangan_id is provided, get the kode_ruangan
        if ($request->ruangan_id) {
            $ruanganObj = Ruangan::find($request->ruangan_id);
            $ruangan = $ruanganObj ? $ruanganObj->kode_ruangan : null;
        } else {
            $ruangan = $request->ruangan;
        }

        if (!$ruangan) {
            return response()->json([
                'available' => false,
                'message' => 'Ruangan tidak valid'
            ]);
        }

        // Cek clash: Check both old ruang field and new ruangan_id relationship
        $query = \App\Models\KelasMataKuliah::where('hari', $hari)
            ->where(function($q) use ($ruangan, $request) {
                $q->where('ruang', $ruangan);
                if ($request->ruangan_id) {
                    $q->orWhere('ruangan_id', $request->ruangan_id);
                }
            })
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
