<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    /**
     * Download materi file from S3.
     */
    public function download($id)
    {
        $materi = Materi::findOrFail($id);

        if (!$materi->file_path || !Storage::disk(\App\Helpers\FileHelper::resolveDiskForPath($materi->file_path))->exists($materi->file_path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk(\App\Helpers\FileHelper::resolveDiskForPath($materi->file_path))->download(
            $materi->file_path,
            $materi->file_name ?? basename($materi->file_path)
        );
    }
}
