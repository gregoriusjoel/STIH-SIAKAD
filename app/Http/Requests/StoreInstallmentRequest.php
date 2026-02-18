<?php

namespace App\Http\Requests;

use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;

class StoreInstallmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $invoice = $this->route('invoice');
        
        if (!$invoice || $invoice->status !== 'PUBLISHED') {
            return false;
        }

        $student = $this->user()->student;
        return $student && $invoice->student_id === $student->id;
    }

    public function rules(): array
    {
        return [
            'requested_terms' => 'required|integer|min:2|max:12',
            'alasan' => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'requested_terms.required' => 'Jumlah cicilan harus diisi',
            'requested_terms.min' => 'Minimal 2 cicilan',
            'requested_terms.max' => 'Maksimal 12 cicilan',
            'alasan.required' => 'Alasan pengajuan cicilan harus diisi',
            'alasan.max' => 'Alasan maksimal 500 karakter',
        ];
    }
}
