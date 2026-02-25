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
        $mahasiswa = Auth::user()->mahasiswa ?? null;
        if (!$mahasiswa) {
            abort(403, 'Unauthorized');
        }

        $tugas = Tugas::findOrFail($tugasId);
        
        // Get submission type rules based on tugas settings
        $submissionType = $tugas->submission_type ?? 'any';
        
        // Define validation rules based on submission type
        $rules = ['comments' => 'nullable|string'];
        
        if ($submissionType === 'text') {
            $rules['text_submission'] = 'required|string|max:50000'; // 50KB text limit
        } else {
            // File validation based on submission type
            $fileRules = 'required|file|max:10240'; // 10MB max
            
            switch ($submissionType) {
                case 'pdf':
                    $fileRules .= '|mimes:pdf';
                    break;
                case 'word':
                    $fileRules .= '|mimes:doc,docx';
                    break;
                case 'excel':
                    $fileRules .= '|mimes:xls,xlsx';
                    break;
                case 'any':
                default:
                    // Allow any file type
                    break;
            }
            
            $rules['file'] = $fileRules;
        }
        
        $validated = $request->validate($rules);

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('tugas_submissions', 'public');
        }

        // Create or update submission
        $submission = TugasSubmission::updateOrCreate(
            [
                'tugas_id' => $tugas->id,
                'mahasiswa_id' => $mahasiswa->id,
            ],
            [
                'file_path' => $filePath,
                'text_submission' => $request->input('text_submission'),
                'comments' => $request->input('comments')
            ]
        );

        return back()->with('success', 'Tugas berhasil dikumpulkan.');
    }
}
