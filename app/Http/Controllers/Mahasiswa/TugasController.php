<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tugas;
use App\Models\TugasSubmission;

class TugasController extends Controller
{
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
