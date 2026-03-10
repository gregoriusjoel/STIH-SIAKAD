<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()->role, ['finance', 'keuangan']);
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:mahasiswas,id',
            'semester' => 'required|integer|min:1|max:14',
            'tahun_ajaran' => 'required|string|max:20',
            'sks_ambil' => 'nullable|integer|min:0|max:24',
            'paket_sks_bayar' => 'nullable|integer|min:0',
            'total_tagihan' => 'required|integer|min:0',
            'allow_partial' => 'nullable|boolean',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'Mahasiswa harus dipilih',
            'student_id.exists' => 'Mahasiswa tidak ditemukan',
            'semester.required' => 'Semester harus diisi',
            'tahun_ajaran.required' => 'Tahun ajaran harus diisi',
            'total_tagihan.required' => 'Total tagihan harus diisi',
            'total_tagihan.min' => 'Total tagihan tidak valid',
        ];
    }
}
