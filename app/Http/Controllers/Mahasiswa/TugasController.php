<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Tugas;
use App\Models\TugasSubmission;

class TugasController extends Controller
{
    /**
     * Download tugas file
     */
    public function download($id)
    {
        $tugas = Tugas::findOrFail($id);

        // Check if file exists
        if (!$tugas->file_path || !Storage::disk('public')->exists($tugas->file_path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        // Return file download
        return Storage::disk('public')->download(
            $tugas->file_path,
            $tugas->file_name ?? basename($tugas->file_path)
        );
    }

    public function submit(Request $request, $kelasId, $pertemuan, $tugasId)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
            'comments' => 'nullable|string'
        ]);

        $mahasiswa = Auth::user()->mahasiswa ?? null;
        if (!$mahasiswa) {
            abort(403, 'Unauthorized');
        }

        $tugas = Tugas::findOrFail($tugasId);

        $filePath = $request->file('file')->store('tugas_submissions', 'public');

        $submission = TugasSubmission::create([
            'tugas_id' => $tugas->id,
            'mahasiswa_id' => $mahasiswa->id,
            'file_path' => $filePath,
            'comments' => $request->input('comments')
        ]);

        return back()->with('success', 'Tugas berhasil dikumpulkan.');
    }
}
