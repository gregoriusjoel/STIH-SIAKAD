<?php

namespace App\Http\Requests\Wisuda;

use Illuminate\Foundation\Http\FormRequest;

class AssignBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'registration_ids'   => ['required', 'array', 'min:1'],
            'registration_ids.*' => ['required', 'integer', 'exists:wisuda_registrations,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'registration_ids.required' => 'Pilih minimal satu mahasiswa untuk di-assign.',
            'registration_ids.min'      => 'Pilih minimal satu mahasiswa.',
        ];
    }
}
