<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\KelasMataKuliah;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\ParentModel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_mahasiswa' => Mahasiswa::count(),
            'total_dosen' => Dosen::count(),
            'total_mata_kuliah' => MataKuliah::count(),
            'total_parent' => ParentModel::count(),
            'total_kelas' => KelasMataKuliah::count(),
            'total_krs' => Krs::count(),
            'krs_pending' => Krs::where('status', 'pending')->count(),
            'recent_krs' => Krs::with(['mahasiswa.user', 'kelas.mataKuliah'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ];

        return view('admin.dashboard', $data);
    }
}
