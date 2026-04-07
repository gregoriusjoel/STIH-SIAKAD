<?php

namespace App\Http\Requests\Skripsi;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuidanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->role === 'mahasiswa';
    }

    public function rules(): array
    {
        return [
            'tanggal_bimbingan' => ['required', 'date', 'before_or_equal:today'],
            'catatan'           => ['required', 'string', 'min:20', 'max:3000'],
            'file_bimbingan'    => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
        ];
    }

    public function attributes(): array
    {
        return [
            'tanggal_bimbingan' => 'Tanggal Bimbingan',
            'catatan'           => 'Catatan Bimbingan',
            'file_bimbingan'    => 'File Bimbingan',
        ];
    }
}
