<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DosenAvailability;
use App\Models\Dosen;
use App\Models\JamPerkuliahan;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DosenAvailabilityController extends Controller
{
    /**
     * Display availability dashboard
     */
    public function index(Request $request)
    {
        $activeSemester = Semester::where('status', 'aktif')->first() 
            ?? Semester::where('is_active', true)->first();
        
        $semesterId = $request->get('semester_id', $activeSemester?->id);
        $hari = $request->get('hari');
        $dosenId = $request->get('dosen_id');

        // Get all semesters for filter
        $semesters = Semester::orderBy('tahun_ajaran', 'desc')
            ->orderBy('nama_semester', 'desc')
            ->get();

        // Get all dosens for filter
        $dosens = Dosen::with('user')
            ->get()
            ->sortBy('user.name');

        // Build query
        $query = DosenAvailability::with(['dosen.user', 'jamPerkuliahan', 'semester'])
            ->when($semesterId, fn($q) => $q->forSemester($semesterId))
            ->when($hari, fn($q) => $q->forDay($hari))
            ->when($dosenId, fn($q) => $q->forDosen($dosenId));

        $availabilities = $query->get();

        // Statistics
        $stats = [
            'total_dosen' => Dosen::count(),
            'dosen_with_availability' => DosenAvailability::when($semesterId, fn($q) => $q->forSemester($semesterId))
                ->distinct('dosen_id')
                ->count('dosen_id'),
            'total_slots' => $availabilities->count(),
            'available_slots' => $availabilities->where('status', 'available')->count(),
            'booked_slots' => $availabilities->where('status', 'booked')->count(),
        ];

        // Group by dosen for display
        $availabilitiesByDosen = $availabilities->groupBy('dosen_id');

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('admin.availability.index', compact(
            'availabilitiesByDosen',
            'stats',
            'semesters',
            'dosens',
            'days',
            'activeSemester',
            'semesterId',
            'hari',
            'dosenId'
        ));
    }

    /**
     * Show specific dosen availability
     */
    public function show($dosenId)
    {
        $dosen = Dosen::with('user')->findOrFail($dosenId);
        
        $activeSemester = Semester::where('status', 'aktif')->first() 
            ?? Semester::where('is_active', true)->first();

        $availabilities = DosenAvailability::with('jamPerkuliahan')
            ->forDosen($dosenId)
            ->forSemester($activeSemester->id)
            ->get()
            ->groupBy('hari');

        $jamPerkuliahan = JamPerkuliahan::where('is_active', true)
            ->orderBy('jam_ke')
            ->get();

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('admin.availability.show', compact(
            'dosen',
            'availabilities',
            'jamPerkuliahan',
            'days',
            'activeSemester'
        ));
    }
}
