<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use Illuminate\Http\Request;

class FakultasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fakultas = Fakultas::paginate(10);
        
        return view('admin.master-data.fakultas.index', compact('fakultas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.master-data.fakultas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_fakultas' => 'required|string|max:10|unique:fakultas,kode_fakultas',
            'nama_fakultas' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Fakultas::create($request->only(['kode_fakultas', 'nama_fakultas', 'status']));

        return redirect()->route('admin.fakultas.index')
            ->with('success', 'Fakultas berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fakultas $fakultas)
    {
        return view('admin.master-data.fakultas.show', compact('fakultas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fakultas $fakultas)
    {
        return view('admin.master-data.fakultas.edit', compact('fakultas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fakultas $fakultas)
    {
        $request->validate([
            'kode_fakultas' => 'required|string|max:10|unique:fakultas,kode_fakultas,' . $fakultas->id,
            'nama_fakultas' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $fakultas->update($request->only(['kode_fakultas', 'nama_fakultas', 'status']));

        return redirect()->route('admin.fakultas.index')
            ->with('success', 'Fakultas berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fakultas $fakultas)
    {
        // Check if fakultas has related mata kuliah
        if ($fakultas->mataKuliahs()->count() > 0) {
            return redirect()->route('admin.fakultas.index')
                ->with('error', 'Tidak dapat menghapus Fakultas yang masih memiliki Mata Kuliah. Silakan hapus Mata Kuliah terlebih dahulu atau ubah Fakultasnya.');
        }

        // Check if fakultas has related prodi
        if ($fakultas->prodis()->count() > 0) {
            return redirect()->route('admin.fakultas.index')
                ->with('error', 'Tidak dapat menghapus Fakultas yang masih memiliki Program Studi. Silakan ubah Program Studi terlebih dahulu.');
        }

        $fakultas->delete();

        return redirect()->route('admin.fakultas.index')
            ->with('success', 'Fakultas berhasil dihapus');
    }
}
