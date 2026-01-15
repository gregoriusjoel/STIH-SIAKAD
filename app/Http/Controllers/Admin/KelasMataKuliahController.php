<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelasMataKuliah;
use App\Models\MataKuliah;
use App\Models\Dosen;
use App\Models\Semester;
use Illuminate\Http\Request;

class KelasMataKuliahController extends Controller
{
    public function index()
    {
        $kelasMatKul = KelasMataKuliah::with(['mataKuliah', 'dosen.user', 'semester'])->paginate(10);
        return view('admin.kelas-mata-kuliah.index', compact('kelasMatKul'));
    }

    public function create()
    {
        $mataKuliahs = MataKuliah::all();
        $dosens = Dosen::with('user')->get();
        $semesters = Semester::where('status', 'aktif')->get();
        return view('admin.kelas-mata-kuliah.create', compact('mataKuliahs', 'dosens', 'semesters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'required|exists:dosens,id',
            'semester_id' => 'required|exists:semesters,id',
            'nama_kelas' => 'required|string|max:10',
            'kuota' => 'required|integer|min:1',
            'ruangan' => 'nullable|string|max:50',
        ]);

        KelasMataKuliah::create($request->all());
        return redirect()->route('admin.kelas-mata-kuliah.index')->with('success', 'Kelas mata kuliah berhasil ditambahkan');
    }

    public function edit(KelasMataKuliah $kelasMataKuliah)
    {
        $mataKuliahs = MataKuliah::all();
        $dosens = Dosen::with('user')->get();
        $semesters = Semester::all();
        return view('admin.kelas-mata-kuliah.edit', compact('kelasMataKuliah', 'mataKuliahs', 'dosens', 'semesters'));
    }

    public function update(Request $request, KelasMataKuliah $kelasMataKuliah)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'required|exists:dosens,id',
            'semester_id' => 'required|exists:semesters,id',
            'nama_kelas' => 'required|string|max:10',
            'kuota' => 'required|integer|min:1',
            'ruangan' => 'nullable|string|max:50',
        ]);

        $kelasMataKuliah->update($request->all());
        return redirect()->route('admin.kelas-mata-kuliah.index')->with('success', 'Kelas mata kuliah berhasil diperbarui');
    }

    public function destroy(KelasMataKuliah $kelasMataKuliah)
    {
        $kelasMataKuliah->delete();
        return redirect()->route('admin.kelas-mata-kuliah.index')->with('success', 'Kelas mata kuliah berhasil dihapus');
    }
}
