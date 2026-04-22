<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class KelasPerkuliahanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'tingkat'           => 'required|integer|min:1|max:8',
            'kode_prodi'        => 'required|string|max:10',
            'kode_kelas'        => 'required|string|max:5',
            'prodi_id'          => 'required|exists:prodis,id',
            'tahun_akademik_id' => 'nullable|exists:semesters,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'tingkat.required'    => 'Tingkat wajib diisi.',
            'tingkat.min'         => 'Tingkat minimal 1.',
            'tingkat.max'         => 'Tingkat maksimal 8.',
            'prodi_id.required'   => 'Program Studi wajib dipilih.',
            'kode_prodi.required' => 'Kode Prodi wajib diisi.',
            'kode_kelas.required' => 'Kode Kelas wajib diisi.',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'tingkat'           => 'Tingkat',
            'kode_prodi'        => 'Kode Prodi',
            'kode_kelas'        => 'Kode Kelas',
            'prodi_id'          => 'Program Studi',
            'tahun_akademik_id' => 'Tahun Akademik',
        ];
    }
}
