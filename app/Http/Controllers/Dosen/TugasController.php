<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tugas;
use Illuminate\Support\Str;

class TugasController extends Controller
{
    public function store(Request $request, $kelasId, $pertemuan)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'file' => 'nullable|file|max:10240',
            'max_score' => 'nullable|integer|min:0'
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('tugas', 'public');
        }

        $dosen = Auth::user()->dosen ?? null;

        $tugas = Tugas::create([
            'kelas_id' => $kelasId,
            'pertemuan' => (int) $pertemuan,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'due_date' => $request->input('due_date'),
            'dosen_id' => $dosen?->id ?? null,
            'file_path' => $filePath,
            'max_score' => $request->input('max_score')
        ]);

        return back()->with('success', 'Tugas berhasil dibuat.');
    }

    public function index(Request $request, $kelasId, $pertemuan)
    {
        $tugas = Tugas::where('kelas_id', $kelasId)
            ->where('pertemuan', $pertemuan)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($tugas);
    }

    public function destroy($kelasId, $pertemuan, $id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->delete();
        return back()->with('success', 'Tugas dihapus.');
    }
}
