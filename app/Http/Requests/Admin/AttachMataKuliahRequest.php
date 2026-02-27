<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AttachMataKuliahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'semester_id'      => 'required|exists:semesters,id',
            'mata_kuliah_ids'  => 'required|array|min:1',
            'mata_kuliah_ids.*'=> 'exists:mata_kuliahs,id',
        ];
    }

    public function messages(): array
    {
        return [
            'semester_id.required'       => 'Semester harus dipilih.',
            'mata_kuliah_ids.required'   => 'Pilih minimal satu mata kuliah.',
            'mata_kuliah_ids.*.exists'   => 'Mata kuliah tidak valid.',
        ];
    }
}
