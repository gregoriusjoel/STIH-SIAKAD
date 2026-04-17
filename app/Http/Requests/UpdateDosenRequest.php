<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDosenRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $dosenId = $this->route('dosen')?->id;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($this->dosen?->user_id ?? 'NULL'),
            'fakultas_id' => 'required|exists:fakultas,id',
            'nidn' => 'required|digits_between:1,16|unique:dosens,nidn,' . ($dosenId ?? 'NULL'),
            'pendidikan_terakhir' => 'nullable|array',
            'pendidikan_terakhir.*' => 'string',
            'universitas' => 'nullable|array',
            'universitas.*' => 'string',
            'dosen_tetap' => 'required|in:ya,tidak',
            'jabatan_fungsional' => 'required|string|max:255',
            'jabatan_fungsional_custom' => 'nullable|string|max:255',
            'prodi' => 'required|array|min:1',
            'prodi.*' => 'string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'required|in:aktif,non-aktif',
            'password' => 'nullable|min:6',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'nidn.required' => 'NIDN wajib diisi',
            'nidn.unique' => 'NIDN sudah terdaftar',
            'fakultas_id.required' => 'Fakultas wajib dipilih',
            'prodi.required' => 'Program studi harus dipilih minimal 1',
            'prodi.min' => 'Program studi harus dipilih minimal 1',
            'dosen_tetap.required' => 'Status dosen wajib dipilih',
            'status.required' => 'Status aktif/non-aktif wajib dipilih',
        ];
    }

    public function validated()
    {
        return array_filter(parent::validated(), fn($value) => $value !== null && $value !== '');
    }
}
