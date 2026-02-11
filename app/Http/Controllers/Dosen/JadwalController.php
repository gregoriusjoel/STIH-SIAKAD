<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JadwalException;
use Carbon\Carbon;
use App\Models\JadwalReschedule;
use App\Models\Ruangan;
use Illuminate\Support\Facades\Schema;

class JadwalController extends Controller
{
    /**
     * Display jadwal page based on status
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get dosen record from user
        $dosen = \App\Models\Dosen::where('user_id', $user->id)->first();
        
        // Get week offset from query parameter (default 0 = current week)
        $weekOffset = (int) $request->query('week', 0);
        
        // Calculate selected week (Monday to Saturday) based on offset
        $weekStart = Carbon::today()->startOfWeek(Carbon::MONDAY)->addWeeks($weekOffset);
        $weekEnd = $weekStart->copy()->addDays(5); // Saturday
        
        // Calculate min week (current week) for navigation limit
        $currentWeekStart = Carbon::today()->startOfWeek(Carbon::MONDAY);
        
        // Get dosen's kelas mata kuliah grouped by day
        $kelasMataKuliahs = collect();
        $approvedReschedules = collect();
        $rejectedReschedules = collect();
        $pendingReschedules = collect();
        
        if ($dosen) {
            $kelasMataKuliahs = \App\Models\KelasMataKuliah::where('dosen_id', $dosen->id)
                ->with(['mataKuliah', 'semester'])
                ->whereNotNull('hari')
                ->get();
            
            // Get approved reschedules for selected week (status: approved or room_assigned)
            $approvedReschedules = \App\Models\KelasReschedule::where('dosen_id', $dosen->id)
                ->where('week_start', $weekStart->toDateString())
                ->whereIn('status', ['approved', 'room_assigned'])
                ->get()
                ->keyBy('kelas_mata_kuliah_id');
            
            // Get pending reschedules for selected week
            $pendingReschedules = \App\Models\KelasReschedule::where('dosen_id', $dosen->id)
                ->where('week_start', $weekStart->toDateString())
                ->where('status', 'pending')
                ->get()
                ->keyBy('kelas_mata_kuliah_id');
            
            // Get rejected reschedules for selected week to show notification
            $rejectedReschedules = \App\Models\KelasReschedule::where('dosen_id', $dosen->id)
                ->where('week_start', $weekStart->toDateString())
                ->where('status', 'rejected')
                ->with('kelasMataKuliah.mataKuliah')
                ->get();
        }
        
        // Overlay approved reschedules onto the kelas mata kuliah for display
        $kelasMataKuliahs = $kelasMataKuliahs->map(function ($kelas) use ($approvedReschedules, $pendingReschedules) {
            if ($approvedReschedules->has($kelas->id)) {
                $reschedule = $approvedReschedules->get($kelas->id);
                // Create a new object with rescheduled values for this week
                $kelas->display_hari = $reschedule->new_hari;
                $kelas->display_jam_mulai = $reschedule->new_jam_mulai;
                $kelas->display_jam_selesai = $reschedule->new_jam_selesai;
                $kelas->display_ruang = $reschedule->new_ruang ?: $kelas->ruang;
                $kelas->display_kelas = $reschedule->new_kelas ?: $kelas->kode_kelas;
                $kelas->is_rescheduled = true;
                $kelas->has_pending_reschedule = false;
            } else {
                $kelas->display_hari = $kelas->hari;
                $kelas->display_jam_mulai = $kelas->jam_mulai;
                $kelas->display_jam_selesai = $kelas->jam_selesai;
                $kelas->display_ruang = $kelas->ruang;
                $kelas->display_kelas = $kelas->kode_kelas;
                $kelas->is_rescheduled = false;
                $kelas->has_pending_reschedule = $pendingReschedules->has($kelas->id);
            }
            return $kelas;
        });
        
        // Group by display_hari for rendering (uses rescheduled day if applicable)
        $schedulesByDay = $kelasMataKuliahs->groupBy('display_hari');
        
        // For backward compatibility
        $activeJadwals = $kelasMataKuliahs;

        // Get actual ruangan data from database if table/columns exist
        $daftarRuangan = collect();
        if (Schema::hasTable('ruangans') && Schema::hasColumn('ruangans', 'status') && Schema::hasColumn('ruangans', 'kode_ruangan')) {
            $daftarRuangan = Ruangan::where('status', 'aktif')
                ->orderBy('kode_ruangan')
                ->get();
        }

        // Get all schedules for room availability checking
        $allSchedules = \App\Models\KelasMataKuliah::with(['mataKuliah', 'dosen.user', 'ruangan'])
            ->where(function($q) {
                $q->whereNotNull('ruang')->orWhereNotNull('ruangan_id');
            })
            ->whereNotNull('hari')
            ->get()
            ->map(function($s) {
                return [
                    'id' => $s->id,
                    'hari' => $s->hari,
                    'ruang' => $s->ruangan ? $s->ruangan->kode_ruangan : $s->ruang, // Use new ruangan if available, fallback to old
                    'ruangan_id' => $s->ruangan_id,
                    'jam_mulai' => substr($s->jam_mulai, 0, 5),
                    'jam_selesai' => substr($s->jam_selesai, 0, 5),
                    'mk' => $s->mataKuliah->nama_mk ?? '',
                    'dosen' => $s->dosen->user->name ?? ''
                ];
            });
        
        // Get Jam Perkuliahan data for availability form
        $jamPerkuliahans = \App\Models\JamPerkuliahan::where('is_active', true)
            ->orderBy('jam_ke')
            ->get();
        
        return view('page.dosen.jadwal.index', compact(
            'schedulesByDay', 
            'activeJadwals', 
            'weekStart', 
            'weekEnd',
            'weekOffset',
            'currentWeekStart',
            'kelasMataKuliahs', 
            'rejectedReschedules',
            'pendingReschedules',
            'daftarRuangan',
            'allSchedules',
            'jamPerkuliahans'
        ));
    }

    /**
     * Submit a reschedule request for a jadwal (dosen)
     */
    public function reschedule(Request $request, Jadwal $jadwal)
    {
        $user = Auth::user();

        // ensure the jadwal belongs to this dosen
        if ($jadwal->kelas->dosen_id !== $user->id) {
            return redirect()->back()->withErrors('Anda tidak memiliki izin untuk mereschedule jadwal ini.');
        }

        $request->validate([
            'new_hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'new_jam_mulai' => 'required|date_format:H:i',
            'new_jam_selesai' => 'required|date_format:H:i|after:new_jam_mulai',
            'catatan' => 'nullable|string|max:1000',
            'apply_date' => 'nullable|date',
            'one_week_only' => 'nullable|boolean',
        ]);

        JadwalReschedule::create([
            'jadwal_id' => $jadwal->id,
            'dosen_id' => $user->id,
            'old_hari' => $jadwal->hari,
            'old_jam_mulai' => $jadwal->jam_mulai,
            'old_jam_selesai' => $jadwal->jam_selesai,
            'new_hari' => $request->new_hari,
            'new_jam_mulai' => $request->new_jam_mulai,
            'new_jam_selesai' => $request->new_jam_selesai,
            'catatan' => $request->catatan,
            'apply_date' => $request->apply_date ?? null,
            'one_week_only' => $request->has('one_week_only') ? (bool)$request->one_week_only : true,
            'status' => 'pending',
        ]);

        return redirect()->route('dosen.jadwal')->with('success', 'Permintaan reschedule telah dikirim. Menunggu approval dari admin.');
    }

    /**
     * Generic reschedule that accepts jadwal_id in request body
     */
    public function rescheduleGeneric(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'new_hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'new_jam_mulai' => 'required|date_format:H:i',
            'new_jam_selesai' => 'required|date_format:H:i|after:new_jam_mulai',
            'catatan' => 'nullable|string|max:1000',
            'apply_date' => 'nullable|date',
            'one_week_only' => 'nullable|boolean',
        ]);

        $jadwal = Jadwal::findOrFail($request->jadwal_id);
        $user = Auth::user();

        if ($jadwal->kelas->dosen_id !== $user->id) {
            return redirect()->back()->withErrors('Anda tidak memiliki izin untuk mereschedule jadwal ini.');
        }

        JadwalReschedule::create([
            'jadwal_id' => $jadwal->id,
            'dosen_id' => $user->id,
            'old_hari' => $jadwal->hari,
            'old_jam_mulai' => $jadwal->jam_mulai,
            'old_jam_selesai' => $jadwal->jam_selesai,
            'new_hari' => $request->new_hari,
            'new_jam_mulai' => $request->new_jam_mulai,
            'new_jam_selesai' => $request->new_jam_selesai,
            'catatan' => $request->catatan,
            'apply_date' => $request->apply_date ?? null,
            'one_week_only' => $request->has('one_week_only') ? (bool)$request->one_week_only : true,
            'status' => 'pending',
        ]);

        return redirect()->route('dosen.jadwal')->with('success', 'Permintaan reschedule telah dikirim. Menunggu approval dari admin.');
    }

    /**
     * Direct reschedule for a kelas mata kuliah (no approval needed)
     * Directly updates the schedule if no room conflict exists
     */
    public function kelasReschedule(Request $request)
    {
        $metode = $request->input('metode_pengajaran', 'offline');

        $rules = [
            'kelas_mata_kuliah_id' => 'required|exists:kelas_mata_kuliahs,id',
            'new_hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'new_jam_mulai' => 'required|date_format:H:i',
            'new_jam_selesai' => 'required|date_format:H:i|after:new_jam_mulai',
            'metode_pengajaran' => 'required|in:offline,online,asynchronous',
            'week_offset' => 'nullable|integer|min:0',
        ];

        // Room is only required for offline mode
        if ($metode === 'offline') {
            $rules['new_ruang'] = 'required|string';
        }
        if ($metode === 'online') {
            $rules['online_link'] = 'required|url';
        }
        if ($metode === 'asynchronous') {
            $rules['asynchronous_tugas'] = 'required|string';
        }

        $request->validate($rules);

        $user = Auth::user();
        $dosen = \App\Models\Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return redirect()->back()->withErrors('Dosen tidak ditemukan.');
        }

        $kelasMataKuliah = \App\Models\KelasMataKuliah::findOrFail($request->kelas_mata_kuliah_id);

        // Ensure the kelas belongs to this dosen
        if ($kelasMataKuliah->dosen_id !== $dosen->id) {
            return redirect()->back()->withErrors('Anda tidak memiliki izin untuk mereschedule kelas ini.');
        }

        $updateData = [
            'hari' => $request->new_hari,
            'jam_mulai' => $request->new_jam_mulai,
            'jam_selesai' => $request->new_jam_selesai,
            'metode_pengajaran' => $metode,
            'online_link' => $metode === 'online' ? $request->online_link : null,
            'asynchronous_tugas' => $metode === 'asynchronous' ? $request->asynchronous_tugas : null,
        ];

        // Handle asynchronous file upload
        if ($metode === 'asynchronous' && $request->hasFile('asynchronous_file')) {
            $file = $request->file('asynchronous_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('asynchronous_files'), $filename);
            $updateData['asynchronous_file'] = 'asynchronous_files/' . $filename;
        } elseif ($metode !== 'asynchronous') {
            $updateData['asynchronous_file'] = null;
        }

        $ruanganLabel = '';

        if ($metode === 'offline') {
            // Look up the ruangan by kode_ruangan
            $ruangan = Ruangan::where('kode_ruangan', $request->new_ruang)->first();

            if (!$ruangan) {
                return redirect()->back()->withErrors('Ruangan tidak ditemukan.');
            }

            // Check for room conflict (same day, same room, overlapping time)
            $conflict = \App\Models\KelasMataKuliah::where('id', '!=', $kelasMataKuliah->id)
                ->where('hari', $request->new_hari)
                ->where(function($q) use ($ruangan) {
                    $q->where('ruangan_id', $ruangan->id)
                      ->orWhere('ruang', $ruangan->kode_ruangan);
                })
                ->where(function($q) use ($request) {
                    $q->where('jam_mulai', '<', $request->new_jam_selesai)
                      ->where('jam_selesai', '>', $request->new_jam_mulai);
                })
                ->with(['mataKuliah', 'dosen.user'])
                ->first();

            if ($conflict) {
                $conflictInfo = ($conflict->dosen->user->name ?? 'Dosen lain') . 
                               ' (' . ($conflict->mataKuliah->nama_mk ?? '-') . ') ' .
                               'pukul ' . substr($conflict->jam_mulai, 0, 5) . '-' . substr($conflict->jam_selesai, 0, 5);
                return redirect()->back()->withErrors("Ruangan {$ruangan->kode_ruangan} sudah terpakai oleh {$conflictInfo}. Silakan pilih ruangan atau waktu lain.");
            }

            $updateData['ruangan_id'] = $ruangan->id;
            $updateData['ruang'] = $ruangan->kode_ruangan;
            $ruanganLabel = ' di ruang ' . $ruangan->kode_ruangan;
        } else {
            // For online/asynchronous, keep existing room assignment (ruang column is NOT NULL)
        }

        // Directly update the kelas mata kuliah
        $kelasMataKuliah->update($updateData);

        $weekOffset = (int) $request->input('week_offset', 0);

        $metodeLabels = ['offline' => 'Tatap Muka', 'online' => 'Online', 'asynchronous' => 'Asynchronous'];
        $metodeLabel = $metodeLabels[$metode] ?? $metode;

        return redirect()->route('dosen.jadwal', ['week' => $weekOffset])->with('success', 'Jadwal berhasil diubah ke ' . $request->new_hari . ' pukul ' . $request->new_jam_mulai . ' - ' . $request->new_jam_selesai . $ruanganLabel . ' (' . $metodeLabel . ').');
    }
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $dosen = \App\Models\Dosen::where('user_id', auth()->id())->firstOrFail();

        \App\Models\DosenAvailabilityCheck::create([
            'dosen_id' => $dosen->id,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->back()->with('success', 'Permintaan cek ketersediaan berhasil dikirim ke Admin.');
    }
}
