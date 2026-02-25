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
            'file' => 'nullable|file|max:51200', // 50MB max
            'max_score' => 'nullable|integer|min:0',
            'submission_type' => 'required|in:pdf,word,excel,text,any'
        ]);

        // Get kelas to extract mata_kuliah_id
        $kelas = \App\Models\Kelas::findOrFail($kelasId);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('tugas', 'public');
        }

        $dosen = Auth::user()->dosen ?? null;

        $tugas = Tugas::create([
            'kelas_id' => $kelasId, // Keep for backward compatibility
            'mata_kuliah_id' => $kelas->mata_kuliah_id, // Share across all classes
            'pertemuan' => (int) $pertemuan,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'due_date' => $request->input('due_date'),
            'dosen_id' => $dosen?->id ?? null,
            'file_path' => $filePath,
            'max_score' => $request->input('max_score'),
            'submission_type' => $request->input('submission_type', 'any')
        ]);

        return back()->with('success', 'Tugas berhasil dibuat dan akan ditampilkan di semua kelas untuk mata kuliah ini.');
    }

    public function index(Request $request, $kelasId, $pertemuan)
    {
        // Get kelas to extract mata_kuliah_id
        $kelas = \App\Models\Kelas::findOrFail($kelasId);
        
        // Get tugas for this mata kuliah and pertemuan (shared across all classes)
        $tugas = Tugas::where('mata_kuliah_id', $kelas->mata_kuliah_id)
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
