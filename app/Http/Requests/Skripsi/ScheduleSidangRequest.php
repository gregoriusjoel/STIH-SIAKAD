<?php

namespace App\Http\Requests\Skripsi;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleSidangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'tanggal'       => ['required', 'date', 'after_or_equal:today'],
            'waktu_mulai'   => ['required', 'date_format:H:i'],
            'waktu_selesai' => ['nullable', 'date_format:H:i', 'after:waktu_mulai'],
            'ruangan_id'    => ['nullable', 'exists:ruangans,id'],
            'ruangan_manual'=> ['nullable', 'string', 'max:100'],
            'pembimbing_id' => ['required', 'exists:dosens,id'],
            'penguji_1_id'  => ['required', 'exists:dosens,id', 'different:pembimbing_id'],
            'penguji_2_id'  => ['nullable', 'exists:dosens,id', 'different:pembimbing_id', 'different:penguji_1_id'],
            'notes'         => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'tanggal'       => 'Tanggal Sidang',
            'waktu_mulai'   => 'Waktu Mulai',
            'waktu_selesai' => 'Waktu Selesai',
            'ruangan_id'    => 'Ruangan',
            'pembimbing_id' => 'Dosen Pembimbing',
            'penguji_1_id'  => 'Dosen Penguji 1',
            'penguji_2_id'  => 'Dosen Penguji 2',
        ];
    }
}
