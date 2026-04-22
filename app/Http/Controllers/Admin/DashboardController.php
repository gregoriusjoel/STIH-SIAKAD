<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicEvent;
use App\Models\Dosen;
use App\Models\KelasMataKuliah;
use App\Models\Krs;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\ParentModel;
use App\Services\AcademicPeriodService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $periodService = app(AcademicPeriodService::class);
        $activeSemester = $periodService->getActiveSemester();

        $data = [
            'total_mahasiswa' => Mahasiswa::count(),
            'total_dosen' => Dosen::count(),
            'total_mata_kuliah' => MataKuliah::count(),
            'total_parent' => ParentModel::count(),
            'total_kelas' => KelasMataKuliah::count(),
            'total_krs' => Krs::count(),
            'total_jadwal' => Jadwal::count(),
            'krs_pending' => Krs::where('status', 'pending')->count(),
            'recent_krs' => Krs::with(['mahasiswa.user', 'kelas.mataKuliah'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'academic_events' => AcademicEvent::active()
                ->orderBy('start_date', 'asc')
                ->get()
                ->unique('title')
                ->values(),
            'calendar_active_periods' => AcademicEvent::currentlyActive()
                ->orderBy('end_date', 'asc')
                ->get()
                ->unique('title')
                ->take(4)
                ->values(),
            // ── Active period badges from calendar ──
            'active_periods' => $periodService->currentActiveTypes($activeSemester?->id),
            'active_semester' => $activeSemester,
        ];

        return view('admin.dashboard', $data);
    }
}
