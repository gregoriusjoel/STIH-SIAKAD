<?php

namespace App\Http\Requests\Admin;

use App\Models\Prodi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $id = $this->route('kelasPerkuliahan')?->id ?? $this->route('kelas_perkuliahan')?->id;

        return [
            'tingkat'           => 'nullable|integer|min:0|max:8',
            'angkatan'          => 'required|digits:4',
            'kode_prodi'        => 'required|string|max:10',
            'kode_kelas'        => [
                'required',
                'string',
                'size:2',
                'regex:/^\d{2}$/',
                Rule::unique('kelas_perkuliahans')
                    ->where('angkatan', $this->input('angkatan'))
                    ->where('prodi_id', $this->input('prodi_id'))
                    ->where('tahun_akademik_id', $this->input('tahun_akademik_id'))
                    ->ignore($id),
            ],
            'prodi_id'          => 'required|exists:prodis,id',
            'tahun_akademik_id' => 'nullable|exists:semesters,id',
        ];
    }

    protected function prepareForValidation(): void
    {
        $prodi = $this->filled('prodi_id') ? Prodi::find($this->input('prodi_id')) : null;

        $this->merge([
            'angkatan' => preg_replace('/\D/', '', (string) $this->input('angkatan')),
            'kode_prodi' => strtoupper((string) ($this->input('kode_prodi') ?: $prodi?->kode_prodi)),
            'kode_kelas' => str_pad(preg_replace('/\D/', '', (string) $this->input('kode_kelas')), 2, '0', STR_PAD_LEFT),
            'tingkat' => $this->input('tingkat', 0),
        ]);
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'angkatan.required'   => 'Angkatan wajib diisi.',
            'angkatan.digits'     => 'Angkatan harus 4 digit, contoh 2025.',
            'prodi_id.required'   => 'Program Studi wajib dipilih.',
            'kode_prodi.required' => 'Kode Prodi wajib diisi.',
            'kode_kelas.required' => 'Kode Kelas wajib diisi.',
            'kode_kelas.size'     => 'Kode Kelas harus 2 digit, contoh 01.',
            'kode_kelas.regex'    => 'Kode Kelas harus 2 digit angka, contoh 01.',
            'kode_kelas.unique'   => 'Nama Kelas ini sudah ada. Gunakan Kode Kelas lain.',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'tingkat'           => 'Tingkat Legacy',
            'angkatan'          => 'Angkatan / Tahun Masuk',
            'kode_prodi'        => 'Kode Prodi',
            'kode_kelas'        => 'Kode Kelas',
            'prodi_id'          => 'Program Studi',
            'tahun_akademik_id' => 'Tahun Akademik',
        ];
    }
}
