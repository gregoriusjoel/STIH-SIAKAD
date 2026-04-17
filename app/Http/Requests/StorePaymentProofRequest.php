<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentProofRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'mahasiswa';
    }

    public function rules(): array
    {
        return [
            'installment_id' => 'nullable|required_without:invoice_id|exists:installments,id',
            'invoice_id' => 'nullable|required_without:installment_id|exists:invoices,id',
            'transfer_date' => 'required|date|before_or_equal:today',
            'amount_submitted' => 'required|integer|min:1000',
            'method' => 'required|string|max:50',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // 2MB
            'student_notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'installment_id.required_without' => 'Cicilan harus dipilih',
            'installment_id.exists' => 'Cicilan tidak ditemukan',
            'invoice_id.required_without' => 'Tagihan harus dipilih',
            'invoice_id.exists' => 'Tagihan tidak ditemukan',
            'transfer_date.required' => 'Tanggal transfer harus diisi',
            'transfer_date.before_or_equal' => 'Tanggal transfer tidak boleh lebih dari hari ini',
            'amount_submitted.required' => 'Nominal bayar harus diisi',
            'amount_submitted.min' => 'Nominal minimal Rp 1.000',
            'method.required' => 'Metode pembayaran harus dipilih',
            'file.required' => 'Bukti bayar harus diupload',
            'file.mimes' => 'Format file harus jpg, jpeg, png, atau pdf',
            'file.max' => 'Ukuran file maksimal 2MB',
        ];
    }
}
