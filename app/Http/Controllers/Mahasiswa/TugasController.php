<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Services\FileStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tugas;
use App\Models\TugasSubmission;

class TugasController extends Controller
{
    public function __construct(private FileStorageService $storage) {}

    /**
     * Download tugas file from S3.
     */
    public function download($id)
    {
        $tugas = Tugas::findOrFail($id);

        if (!$tugas->file_path || !$this->storage->exists($tugas->file_path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return $this->storage->download(
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

        $submission = TugasSubmission::where('tugas_id', $tugas->id)
                                     ->where('mahasiswa_id', $mahasiswa->id)
                                     ->first();

        // Handle file upload
        $filePath = $submission ? $submission->file_path : null;

        if ($request->hasFile('file')) {
            // Delete old file from S3 if exists
            if ($submission && $submission->file_path) {
                $this->storage->delete($submission->file_path);
            }
            $filePath = $this->storage->upload($request->file('file'), 'documents/tugas-submissions');
        } elseif ($request->has('text_submission') && $submissionType === 'text') {
            // If they switch to text submission, remove old file
            if ($submission && $submission->file_path) {
                $this->storage->delete($submission->file_path);
            }
            $filePath = null;
        }

        // Create or update submission
        $submission = TugasSubmission::updateOrCreate(
            [
                'tugas_id'     => $tugas->id,
                'mahasiswa_id' => $mahasiswa->id,
            ],
            [
                'file_path'       => $filePath,
                'text_submission' => $request->input('text_submission'),
                'comments'        => $request->input('comments'),
            ]
        );

        $message = $submission->wasRecentlyCreated ? 'Tugas berhasil dikumpulkan.' : 'Tugas berhasil diunggah ulang.';
        return back()->with('success', $message);
    }
}
