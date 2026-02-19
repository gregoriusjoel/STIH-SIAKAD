<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\DosenAttendance;
use App\Models\KelasMataKuliah;
use Illuminate\Http\Request;

class AbsensiDosenController extends Controller
{
    /**
     * Display one row per (dosen + mata kuliah) grouping.
     * GET /admin/absensi-dosen
     */
    public function index(Request $request)
    {
        // Base query – one row represents all pertemuan of (dosen + kelas_mata_kuliah)
        $query = DosenAttendance::with([
            'dosen.user',
            'kelasMataKuliah.mataKuliah',
        ])
        ->selectRaw('dosen_id, kelas_mata_kuliah_id,
            COUNT(*) as total_pertemuan,
            MAX(jam_absen_dosen) as last_absen,
            MIN(jam_absen_dosen) as first_absen')
        ->groupBy('dosen_id', 'kelas_mata_kuliah_id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('dosen.user', fn($sq) => $sq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('kelasMataKuliah.mataKuliah', fn($sq) => $sq->where('nama_mk', 'like', "%{$search}%")
                      ->orWhere('kode_mk', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('dosen_id')) {
            $query->where('dosen_id', $request->dosen_id);
        }

        $groups = $query->orderByDesc('last_absen')->paginate(25)->withQueryString();

        $dosens = Dosen::with('user')->orderBy('id')->get()->map(fn($d) => [
            'id'   => $d->id,
            'nama' => $d->user->name ?? 'Dosen ' . $d->id,
        ]);

        return view('admin.absensi-dosen.index', compact('groups', 'dosens'));
    }

    /**
     * Show all pertemuan attendance records for a specific (dosen + kelas_mata_kuliah).
     * GET /admin/absensi-dosen/{dosen}/{kelasMataKuliah}
     */
    public function show(Dosen $dosen, KelasMataKuliah $kelasMataKuliah)
    {
        $kelasMataKuliah->load('mataKuliah');
        $dosen->load('user');

        $attendances = DosenAttendance::with('pertemuan')
            ->where('dosen_id', $dosen->id)
            ->where('kelas_mata_kuliah_id', $kelasMataKuliah->id)
            ->orderBy('jam_absen_dosen')
            ->get();

        return view('admin.absensi-dosen.show', compact('dosen', 'kelasMataKuliah', 'attendances'));
    }

}
