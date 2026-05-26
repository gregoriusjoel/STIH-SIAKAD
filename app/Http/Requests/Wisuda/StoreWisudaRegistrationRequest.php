<?php

namespace App\Http\Requests\Wisuda;

use Illuminate\Foundation\Http\FormRequest;

class StoreWisudaRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'no_hp'       => ['required', 'string', 'max:20'],
            'email_aktif' => ['required', 'email', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'no_hp.required'       => 'Nomor HP wajib diisi.',
            'email_aktif.required' => 'Email aktif wajib diisi.',
            'email_aktif.email'    => 'Format email tidak valid.',
        ];
    }
}
