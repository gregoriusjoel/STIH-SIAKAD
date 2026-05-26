<?php

namespace App\Http\Requests\Wisuda;

use Illuminate\Foundation\Http\FormRequest;

class StoreBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_batch'  => ['required', 'string', 'max:255'],
            'tanggal'     => ['required', 'date', 'after_or_equal:today'],
            'waktu_mulai' => ['required', 'date_format:H:i'],
            'lokasi'      => ['required', 'string', 'max:255'],
            'catatan'     => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_batch.required'  => 'Nama batch wajib diisi.',
            'tanggal.required'     => 'Tanggal wisuda wajib diisi.',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh di masa lalu.',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi.',
            'lokasi.required'      => 'Lokasi wisuda wajib diisi.',
        ];
    }
}
