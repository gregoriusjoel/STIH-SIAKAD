<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadSignedDocRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->mahasiswa !== null;
    }

    public function rules(): array
    {
        return [
            'signed_doc' => [
                'required',
                'file',
                'mimes:pdf,docx,doc',
                'max:5120', // 5 MB
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'signed_doc.required' => 'File dokumen bertanda tangan wajib di-upload.',
            'signed_doc.mimes'    => 'Format file harus PDF atau DOCX.',
            'signed_doc.max'      => 'Ukuran file maksimal 5 MB.',
        ];
    }
}
