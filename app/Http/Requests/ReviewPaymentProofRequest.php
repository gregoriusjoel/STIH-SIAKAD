<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewPaymentProofRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->role, ['finance', 'keuangan']);
    }

    public function rules(): array
    {
        return [
            'action' => 'required|in:approve,reject',
            'notes' => 'required_if:action,reject|nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Aksi harus dipilih',
            'action.in' => 'Aksi tidak valid',
            'notes.required_if' => 'Alasan penolakan harus diisi',
            'notes.max' => 'Catatan maksimal 500 karakter',
        ];
    }
}
