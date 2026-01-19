<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use App\Models\Semester;
use Illuminate\Http\Request;

class KrsController extends Controller
{
    public function index(Request $request)
    {
        $query = Krs::with(['mahasiswa.user', 'kelasMataKuliah.mataKuliah', 'kelasMataKuliah.dosen.user']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $krsData = $query->orderBy('created_at', 'desc')->paginate(10);
        // determine active semester for KRS settings card
        $semesterAktif = Semester::where('status', 'aktif')->first() ?? Semester::where('is_active', true)->first() ?? Semester::latest()->first();
        return view('admin.krs.index', compact('krsData', 'semesterAktif'));
    }

    public function show(Krs $kr)
    {
        $kr->load(['mahasiswa.user', 'kelasMataKuliah.mataKuliah', 'kelasMataKuliah.dosen.user', 'kelasMataKuliah.jadwal']);
        return view('admin.krs.show', compact('kr'));
    }

    public function updateStatus(Request $request, Krs $kr)
    {
        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak',
            'keterangan' => 'nullable|string',
        ]);

        $kr->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Status KRS berhasil diupdate');
    }

    public function destroy(Krs $kr)
    {
        try {
            $kr->delete();
            return redirect()->route('admin.krs.index')
                ->with('success', 'Data KRS berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
