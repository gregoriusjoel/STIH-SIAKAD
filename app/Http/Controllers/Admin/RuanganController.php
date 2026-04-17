<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use App\Models\KategoriRuangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Ruangan::with('kategori');

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search berdasarkan kode atau nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_ruangan', 'like', "%$search%")
                  ->orWhere('nama_ruangan', 'like', "%$search%")
                  ->orWhere('gedung', 'like', "%$search%");
            });
        }

        $ruangans = $query->orderBy('kode_ruangan')->paginate(20);
        $kategoris = KategoriRuangan::aktif()->ordered()->get();

        return view('admin.master-data.ruangan.index', compact('ruangans', 'kategoris'));
    }

    public function create()
    {
        $kategoris = KategoriRuangan::aktif()->ordered()->get();
        return view('admin.master-data.ruangan.create', compact('kategoris'));
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
            'kategori_id' => 'nullable|exists:kategori_ruangans,id',
        ]);

        Ruangan::create($request->all());

        return redirect()->route('admin.ruangan.index')
            ->with('success', 'Ruangan berhasil ditambahkan');
    }

    public function show(Ruangan $ruangan)
    {
        $ruangan->load(['kategori', 'jadwals.kelas.mataKuliah', 'jadwalProposals.mataKuliah']);
        return view('admin.master-data.ruangan.show', compact('ruangan'));
    }

    public function edit(Ruangan $ruangan)
    {
        $kategoris = KategoriRuangan::aktif()->ordered()->get();
        return view('admin.master-data.ruangan.edit', compact('ruangan', 'kategoris'));
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
            'kategori_id' => 'nullable|exists:kategori_ruangans,id',
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
