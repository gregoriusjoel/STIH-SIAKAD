<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\Fakultas;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prodis = Prodi::with('fakultas')->paginate(10);
        return view('admin.master-data.prodi.index', compact('prodis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fakultas = Fakultas::where('status', 'aktif')->get();
        return view('admin.master-data.prodi.create', compact('fakultas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_prodi' => 'required|string|max:10|unique:prodis,kode_prodi',
            'nama_prodi' => 'required|string|max:255',
            'fakultas_id' => 'required|exists:fakultas,id',
            'jenjang' => 'required|in:D3,S1,S2,S3',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Prodi::create($request->all());

        return redirect()->route('admin.prodi.index')
            ->with('success', 'Prodi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Prodi $prodi)
    {
        $prodi->load(['fakultas']);
        return view('admin.master-data.prodi.show', compact('prodi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prodi $prodi)
    {
        $fakultas = Fakultas::where('status', 'aktif')->get();
        return view('admin.master-data.prodi.edit', compact('prodi', 'fakultas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prodi $prodi)
    {
        $request->validate([
            'kode_prodi' => 'required|string|max:10|unique:prodis,kode_prodi,' . $prodi->id,
            'nama_prodi' => 'required|string|max:255',
            'fakultas_id' => 'required|exists:fakultas,id',
            'jenjang' => 'required|in:D3,S1,S2,S3',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $prodi->update($request->all());

        return redirect()->route('admin.prodi.index')
            ->with('success', 'Prodi berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prodi $prodi)
    {
        // Prevent deleting Prodi if it has related mata kuliah
        if (method_exists($prodi, 'mataKuliahs') && $prodi->mataKuliahs()->count() > 0) {
            return redirect()->route('admin.prodi.index')
                ->with('error', 'Tidak dapat menghapus prodi yang memiliki mata kuliah terkait');
        }

        $prodi->delete();

        return redirect()->route('admin.prodi.index')
            ->with('success', 'Prodi berhasil dihapus');
    }
}
