<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index()
    {
        $mataKuliahs = MataKuliah::paginate(10);
        return view('admin.mata-kuliah.index', compact('mataKuliahs'));
    }

    public function create()
    {
        return view('admin.mata-kuliah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|unique:mata_kuliahs,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
            'praktikum' => 'nullable|integer|min:0|max:10',
            'jenis' => 'required|in:wajib_nasional,wajib_prodi,pilihan,peminatan',
            'prodi' => 'required|string',
            'deskripsi' => 'nullable|string',
        ]);

        MataKuliah::create($request->all());

        return redirect()->route('admin.mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan');
    }

    public function show(MataKuliah $mataKuliah)
    {
        $mataKuliah->load('kelasMataKuliahs.dosen.user');
        return view('admin.mata-kuliah.show', compact('mataKuliah'));
    }

    public function edit(MataKuliah $mataKuliah)
    {
        return view('admin.mata-kuliah.edit', compact('mataKuliah'));
    }

    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $request->validate([
            'kode_mk' => 'required|unique:mata_kuliahs,kode_mk,' . $mataKuliah->id,
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
            'praktikum' => 'nullable|integer|min:0|max:10',
            'jenis' => 'required|in:wajib_nasional,wajib_prodi,pilihan,peminatan',
            'prodi' => 'required|string',
            'deskripsi' => 'nullable|string',
        ]);

        $mataKuliah->update($request->all());

        return redirect()->route('admin.mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil diupdate');
    }

    public function destroy(MataKuliah $mataKuliah)
    {
        try {
            $mataKuliah->delete();
            return redirect()->route('admin.mata-kuliah.index')
                ->with('success', 'Mata kuliah berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
