<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RestoreMataKuliahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'source_semester_id'  => 'required|exists:semesters,id',
            'target_semester_id'  => 'required|exists:semesters,id',
            'mata_kuliah_ids'     => 'required|array|min:1',
            'mata_kuliah_ids.*'   => 'exists:mata_kuliahs,id',
        ];
    }

    public function messages(): array
    {
        return [
            'source_semester_id.required' => 'Semester sumber harus dipilih.',
            'target_semester_id.required' => 'Semester tujuan harus dipilih.',
            'mata_kuliah_ids.required'    => 'Pilih minimal satu mata kuliah.',
            'mata_kuliah_ids.*.exists'    => 'Mata kuliah tidak valid.',
        ];
    }
}
