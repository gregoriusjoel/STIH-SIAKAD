<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    /**
     * Download materi file
     */
    public function download($id)
    {
        $materi = Materi::findOrFail($id);

        // Check if file exists
        if (!Storage::disk('public')->exists($materi->file_path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        // Return file download
        return Storage::disk('public')->download(
            $materi->file_path,
            $materi->file_name
        );
    }
}
