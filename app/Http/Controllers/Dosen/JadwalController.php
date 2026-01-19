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
        
        // Get dosen's jadwals through kelas (both active and approved/rescheduled waiting for room)
        $activeJadwals = Jadwal::whereHas('kelas', function ($query) use ($user) {
            $query->where('dosen_id', $user->id);
        })->whereIn('status', ['active', 'approved'])->with(['kelas.mataKuliah'])->get();

        // Only show pending jadwals that are truly pending (not approved/rescheduled)
        $pendingJadwals = Jadwal::whereHas('kelas', function ($query) use ($user) {
            $query->where('dosen_id', $user->id);
        })->where('status', 'pending')->with(['kelas.mataKuliah'])->get();

        // Case 1: Has active jadwals → show schedule page
        if ($activeJadwals->count() > 0) {
            // Determine requested week (from query) or default to current week (Monday - Saturday)
            if ($request->has('week_start') && !empty($request->query('week_start'))) {
                try {
                    $weekStartCarbon = Carbon::parse($request->query('week_start'))->startOfWeek(Carbon::MONDAY);
                } catch (\Exception $e) {
                    $weekStartCarbon = Carbon::today()->startOfWeek(Carbon::MONDAY);
                }
            } else {
                $weekStartCarbon = Carbon::today()->startOfWeek(Carbon::MONDAY);
            }

            $weekStart = $weekStartCarbon->toDateString();
            $weekEnd = $weekStartCarbon->copy()->addDays(5)->toDateString();

            // Fetch any one-off exceptions that apply this week for the user's jadwals
            $exceptions = JadwalException::whereIn('jadwal_id', $activeJadwals->pluck('id')->toArray())
                ->whereBetween('date', [$weekStart, $weekEnd])
                ->get()
                ->keyBy('jadwal_id');

            // Overlay exceptions onto the active jadwals for this week's view
            $activeJadwals = $activeJadwals->map(function ($jadwal) use ($exceptions) {
                if (isset($exceptions[$jadwal->id])) {
                    $ex = $exceptions[$jadwal->id];
                    $jadwal->jam_mulai = $ex->jam_mulai;
                    $jadwal->jam_selesai = $ex->jam_selesai;
                    $jadwal->hari = $ex->hari;
                    $jadwal->is_exception = true;
                    $jadwal->exception_date = $ex->date;
                }
                return $jadwal;
            });

            // Group by day for rendering
            $schedulesByDay = $activeJadwals->groupBy('hari');
            return view('page.dosen.jadwal.index', compact('schedulesByDay', 'activeJadwals', 'weekStart'));
        }

        // No active jadwals: show empty schedule view (no creation/pending UI)
        if ($activeJadwals->count() == 0) {
            // create empty schedule grouping
            $weekStartCarbon = Carbon::today()->startOfWeek(Carbon::MONDAY);
            $weekStart = $weekStartCarbon->toDateString();
            $schedulesByDay = collect();
            return view('page.dosen.jadwal.index', compact('schedulesByDay', 'activeJadwals', 'weekStart'));
        }
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
}
