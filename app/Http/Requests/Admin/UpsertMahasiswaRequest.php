<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpsertMahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $mahasiswaId = $this->route('mahasiswa')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email_pribadi' => ['nullable', 'email', 'max:255', Rule::unique('mahasiswas', 'email_pribadi')->ignore($mahasiswaId)],
            'email_kampus' => ['required', 'email', 'max:255', Rule::unique('mahasiswas', 'email_kampus')->ignore($mahasiswaId)],
            'password' => ['nullable', 'string', 'min:6'],
            'nim' => ['required', 'string', 'max:50', Rule::unique('mahasiswas', 'nim')->ignore($mahasiswaId)],
            'prodi_id' => ['nullable', 'integer', 'exists:prodis,id', 'required_without:prodi'],
            'prodi' => ['nullable', 'string', 'max:255', 'required_without:prodi_id'],
            'angkatan' => ['required', 'string', 'max:10'],
            'semester' => ['required', 'integer', 'min:1', 'max:8'],
            'jenis_kelamin' => [$mahasiswaId ? 'nullable' : 'required', 'string', 'max:50'],
            'status' => ['required', 'string', Rule::in(['aktif', 'cuti', 'lulus', 'do'])],
            'no_hp' => ['nullable', 'digits_between:11,13'],
            'alamat' => ['nullable', 'string', 'max:1000'],
            'kelas_perkuliahan_id' => ['nullable', 'integer', 'exists:kelas_perkuliahans,id'],
            'tahun_akademik_id' => ['nullable', 'integer', 'exists:semesters,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => $this->input('status', 'aktif'),
            'email_aktif' => 'kampus',
        ]);
    }

    public function messages(): array
    {
        return [
            'kelas_perkuliahan_id.exists' => 'Kelas tidak ditemukan.',
            'prodi_id.required_without' => 'Program studi wajib dipilih.',
            'prodi.required_without' => 'Program studi wajib dipilih.',
            'semester.max' => 'Semester maksimal adalah 8.',
        ];
    }
}
