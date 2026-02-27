<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CarryForwardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'source_semester_id' => 'required|exists:semesters,id|different:target_semester_id',
            'target_semester_id' => 'required|exists:semesters,id',
        ];
    }

    public function messages(): array
    {
        return [
            'source_semester_id.required'   => 'Semester sumber harus dipilih.',
            'source_semester_id.different'  => 'Semester sumber dan tujuan tidak boleh sama.',
            'target_semester_id.required'   => 'Semester tujuan harus dipilih.',
        ];
    }
}
