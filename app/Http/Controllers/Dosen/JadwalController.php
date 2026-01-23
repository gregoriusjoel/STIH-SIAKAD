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
        
        return view('page.dosen.jadwal.index', compact(
            'schedulesByDay', 
            'activeJadwals', 
            'weekStart', 
            'weekEnd',
            'weekOffset',
            'currentWeekStart',
            'kelasMataKuliahs', 
            'rejectedReschedules',
            'pendingReschedules'
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

    // Note: Creation/pending submission features removed per request.

    /**
     * Submit a reschedule request for a kelas mata kuliah (weekly)
     */
    public function kelasReschedule(Request $request)
    {
        $request->validate([
            'kelas_mata_kuliah_id' => 'required|exists:kelas_mata_kuliahs,id',
            'new_hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'new_jam_mulai' => 'required|date_format:H:i',
            'new_jam_selesai' => 'required|date_format:H:i|after:new_jam_mulai',
            'catatan_dosen' => 'required|string|max:1000',
            'week_offset' => 'nullable|integer|min:0',
        ]);

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

        // Get week offset from request (default 0 = current week)
        $weekOffset = (int) $request->input('week_offset', 0);

        // Calculate the target week based on offset (Monday to Saturday)
        $weekStart = Carbon::today()->startOfWeek(Carbon::MONDAY)->addWeeks($weekOffset);
        $weekEnd = $weekStart->copy()->addDays(5); // Saturday

        // Check if there's already a pending reschedule for this week
        $existingReschedule = \App\Models\KelasReschedule::where('kelas_mata_kuliah_id', $kelasMataKuliah->id)
            ->where('week_start', $weekStart->toDateString())
            ->whereIn('status', ['pending', 'approved', 'room_assigned'])
            ->first();

        if ($existingReschedule) {
            $weekName = $weekStart->format('d M') . ' - ' . $weekEnd->format('d M Y');
            return redirect()->back()->withErrors("Sudah ada permintaan reschedule untuk minggu tersebut ({$weekName}).");
        }

        \App\Models\KelasReschedule::create([
            'kelas_mata_kuliah_id' => $kelasMataKuliah->id,
            'dosen_id' => $dosen->id,
            'old_hari' => $kelasMataKuliah->hari,
            'old_jam_mulai' => $kelasMataKuliah->jam_mulai,
            'old_jam_selesai' => $kelasMataKuliah->jam_selesai,
            'new_hari' => $request->new_hari,
            'new_jam_mulai' => $request->new_jam_mulai,
            'new_jam_selesai' => $request->new_jam_selesai,
            'week_start' => $weekStart->toDateString(),
            'week_end' => $weekEnd->toDateString(),
            'catatan_dosen' => $request->catatan_dosen,
            'status' => 'pending',
        ]);

        // Redirect back to the same week view
        return redirect()->route('dosen.jadwal', ['week' => $weekOffset])->with('success', 'Permintaan reschedule telah dikirim untuk minggu ' . $weekStart->format('d M') . ' - ' . $weekEnd->format('d M Y') . '. Menunggu approval dari admin.');
    }
}
