<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::orderBy('kode_ruangan')->paginate(20);
        return view('admin.master-data.ruangan.index', compact('ruangans'));
    }

    public function create()
    {
        return view('admin.master-data.ruangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_ruangan' => 'required|unique:ruangans,kode_ruangan|max:20',
            'nama_ruangan' => 'required|string|max:255',
            'gedung' => 'nullable|string|max:50',
            'lantai' => 'nullable|integer|min:1|max:20',
            'kapasitas' => 'required|integer|min:1|max:500',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Ruangan::create($request->all());

        return redirect()->route('admin.ruangan.index')
            ->with('success', 'Ruangan berhasil ditambahkan');
    }

    public function show(Ruangan $ruangan)
    {
        $ruangan->load(['jadwals.kelas.mataKuliah', 'jadwalProposals.mataKuliah']);
        return view('admin.master-data.ruangan.show', compact('ruangan'));
    }

    public function edit(Ruangan $ruangan)
    {
        return view('admin.master-data.ruangan.edit', compact('ruangan'));
    }

    public function update(Request $request, Ruangan $ruangan)
    {
        $request->validate([
            'kode_ruangan' => 'required|unique:ruangans,kode_ruangan,' . $ruangan->id . '|max:20',
            'nama_ruangan' => 'required|string|max:255',
            'gedung' => 'nullable|string|max:50',
            'lantai' => 'nullable|integer|min:1|max:20',
            'kapasitas' => 'required|integer|min:1|max:500',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $ruangan->update($request->all());

        return redirect()->route('admin.ruangan.index')
            ->with('success', 'Ruangan berhasil diupdate');
    }

    public function destroy(Ruangan $ruangan)
    {
        try {
            // Check if room is used in schedules
            if ($ruangan->jadwals()->count() > 0 || $ruangan->jadwalProposals()->count() > 0) {
                return back()->with('error', 'Ruangan tidak dapat dihapus karena masih digunakan dalam jadwal');
            }

            $ruangan->delete();
            return redirect()->route('admin.ruangan.index')
                ->with('success', 'Ruangan berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
