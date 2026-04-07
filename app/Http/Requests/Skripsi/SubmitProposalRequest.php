<?php

namespace App\Http\Requests\Skripsi;

use Illuminate\Foundation\Http\FormRequest;

class SubmitProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->role === 'mahasiswa';
    }

    public function rules(): array
    {
        return [
            'judul'                   => ['required', 'string', 'min:10', 'max:500'],
            'deskripsi_proposal'      => ['nullable', 'string', 'max:5000'],
            'proposal_file'           => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'requested_supervisor_id' => ['required', 'exists:dosens,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'judul'                   => 'Judul Skripsi',
            'deskripsi_proposal'      => 'Deskripsi Proposal',
            'proposal_file'           => 'File Proposal',
            'requested_supervisor_id' => 'Dosen Pembimbing',
        ];
    }
}
