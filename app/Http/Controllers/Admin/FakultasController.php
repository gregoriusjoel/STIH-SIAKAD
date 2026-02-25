<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Http\Request;

class FakultasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fakultas = Fakultas::with(['prodis'])->paginate(10);
        $prodiCount = Prodi::where('status', 'aktif')->count();
        
        return view('admin.master-data.fakultas.index', compact('fakultas', 'prodiCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $prodis = Prodi::where('status', 'aktif')->get();
        
        // Check if there are any active prodis
        if ($prodis->isEmpty()) {
            return redirect()->route('admin.fakultas.index')
                ->with('error', 'Silakan tambahkan Prodi terlebih dahulu sebelum menambah Fakultas');
        }

        return view('admin.master-data.fakultas.create', compact('prodis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_fakultas' => 'required|string|max:10|unique:fakultas,kode_fakultas',
            'nama_fakultas' => 'required|string|max:255',
            'prodi_id' => 'required|exists:prodis,id',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $fakultas = Fakultas::create($request->only(['kode_fakultas', 'nama_fakultas', 'status']));

        // Link the selected prodi to this fakultas
        $prodi = Prodi::find($request->prodi_id);
        $prodi->fakultas_id = $fakultas->id;
        $prodi->save();

        return redirect()->route('admin.fakultas.index')
            ->with('success', 'Fakultas berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fakultas $fakultas)
    {
        $fakultas->load(['prodis']);
        return view('admin.master-data.fakultas.show', compact('fakultas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fakultas $fakultas)
    {
        $prodis = Prodi::where('status', 'aktif')->get();
        return view('admin.master-data.fakultas.edit', compact('fakultas', 'prodis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fakultas $fakultas)
    {
        $request->validate([
            'kode_fakultas' => 'required|string|max:10|unique:fakultas,kode_fakultas,' . $fakultas->id,
            'nama_fakultas' => 'required|string|max:255',
            'prodi_id' => 'required|exists:prodis,id',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $fakultas->update($request->only(['kode_fakultas', 'nama_fakultas', 'status']));

        // Update the prodi link
        // First, detach any prodi currently linked to this fakultas (if we only allow one)
        // Or just update the newly selected one.
        $prodi = Prodi::find($request->prodi_id);
        $prodi->fakultas_id = $fakultas->id;
        $prodi->save();

        return redirect()->route('admin.fakultas.index')
            ->with('success', 'Fakultas berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fakultas $fakultas)
    {
        // Check if there are any related prodis
        if ($fakultas->prodis()->count() > 0) {
            return redirect()->route('admin.fakultas.index')
                ->with('error', 'Gagal! Hapus data prodi yang bersangkutan dulu.');
        }

        $fakultas->delete();

        return redirect()->route('admin.fakultas.index')
            ->with('success', 'Fakultas berhasil dihapus');
    }
}
