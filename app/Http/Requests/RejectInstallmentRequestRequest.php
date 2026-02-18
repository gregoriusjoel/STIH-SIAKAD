<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectInstallmentRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'finance';
    }

    public function rules(): array
    {
        return [
            'rejection_reason' => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'rejection_reason.required' => 'Alasan penolakan harus diisi',
            'rejection_reason.max' => 'Alasan maksimal 500 karakter',
        ];
    }
}
