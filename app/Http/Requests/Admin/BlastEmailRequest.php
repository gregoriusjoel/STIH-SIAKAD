<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BlastEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hanya admin yang bisa send blast email
        return auth()->check() && auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        $isCredentials = $this->boolean('send_credentials', false);
        
        return [
            'subject' => $isCredentials ? 'nullable|string|max:200' : 'required|string|max:200',
            'greeting' => $isCredentials ? 'nullable|string|max:100' : 'required|string|max:100',
            'message' => $isCredentials ? 'nullable|string|max:5000' : 'required|string|max:5000',
            'filter_type' => 'required|string|in:all,angkatan,prodi,tingkat,kelas,status,spesifik',
            'prodi_id' => 'nullable|exists:prodis,id',
            'tingkat' => 'nullable|integer|in:1,2,3,4',
            'kelas_perkuliahan_id' => 'nullable|exists:kelas_perkuliahans,id',
            'status' => 'nullable|string',
            'angkatan' => 'nullable|string|max:10',
            'mahasiswa_ids' => 'nullable|array',
            'mahasiswa_ids.*' => 'exists:mahasiswas,id',
            'send_credentials' => 'nullable|boolean',
            'immediate' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'subject.required' => 'Subject email harus diisi',
            'subject.max' => 'Subject maksimal 200 karakter',
            'greeting.required' => 'Greeting/Salutation harus diisi',
            'greeting.max' => 'Greeting maksimal 100 karakter',
            'message.required' => 'Pesan email harus diisi',
            'message.max' => 'Pesan email maksimal 5000 karakter',
            'filter_type.required' => 'Tipe filter harus dipilih',
            'filter_type.in' => 'Tipe filter tidak valid',
            'prodi_id.exists' => 'Prodi tidak ditemukan',
            'kelas_perkuliahan_id.exists' => 'Kelas perkuliahan tidak ditemukan',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Trim whitespace dari subject, greeting, message
        $this->merge([
            'subject' => trim($this->subject),
            'greeting' => trim($this->greeting),
            'message' => trim($this->message),
        ]);
    }
}
