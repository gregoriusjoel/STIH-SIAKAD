<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreDosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|min:6',
            'fakultas_id' => 'required|exists:fakultas,id',
            'nidn' => 'required|digits_between:1,16|unique:dosens,nidn',
            'pendidikan_terakhir' => 'required|array|min:1',
            'pendidikan_terakhir.*' => 'string',
            'universitas' => 'required|array|min:1',
            'universitas.*' => 'string',
            'dosen_tetap' => 'required|in:ya,tidak',
            'jabatan_fungsional' => 'nullable|string|max:255',
            'jabatan_fungsional_custom' => 'nullable|string|max:255',
            'prodi' => 'required|array|min:1',
            'prodi.*' => 'string',
            'phone' => 'nullable|digits_between:11,13',
            'address' => 'nullable|string',
            'mata_kuliah_ids' => 'nullable|array',
            'mata_kuliah_ids.*' => 'exists:mata_kuliahs,id',
        ];
    }

    public function messages(): array
    {
        return [
            'mata_kuliah_ids.*.exists' => 'Mata kuliah yang dipilih tidak valid. Silakan pilih mata kuliah yang tersedia.',
            'fakultas_id.required' => 'Fakultas wajib dipilih.',
            'fakultas_id.exists' => 'Fakultas yang dipilih tidak valid.',
            'prodi.required' => 'Program studi harus dipilih minimal 1.',
            'prodi.min' => 'Program studi harus dipilih minimal 1.',
        ];
    }
}
