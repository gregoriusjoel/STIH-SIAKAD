<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApproveInstallmentRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'finance';
    }

    public function rules(): array
    {
        return [
            'approved_terms' => 'required|integer|min:2|max:12',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'approved_terms.required' => 'Jumlah cicilan yang disetujui harus diisi',
            'approved_terms.min' => 'Minimal 2 cicilan',
            'approved_terms.max' => 'Maksimal 12 cicilan',
        ];
    }
}
