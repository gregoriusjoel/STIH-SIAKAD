<?php

namespace App\Http\Requests;

use App\Support\LetterTemplateConfig;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePengajuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->mahasiswa !== null;
    }

    public function rules(): array
    {
        $jenis = $this->input('jenis');
        $config = LetterTemplateConfig::get($jenis);

        // Base rules
        $rules = [
            'jenis'       => ['required', Rule::in(array_keys(LetterTemplateConfig::all()))],
            'keterangan'  => ['required', 'string', 'max:2000'],
        ];

        // Tambah rules field dinamis dari config
        if ($config && !empty($config['fields'])) {
            foreach ($config['fields'] as $field) {
                $ruleSrc = $field['rules'] ?? 'nullable';
                // Support rule referencing payload_template.* (untuk after_or_equal dll)
                // Simplify: strip prefix "payload_template." dan ganti jadi "payload_template.*"
                $ruleStr = str_replace(
                    'payload_template.',
                    'payload_template.',
                    $ruleSrc
                );
                $rules["payload_template.{$field['name']}"] = explode('|', $ruleStr);
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'jenis.required'      => 'Jenis pengajuan wajib dipilih.',
            'jenis.in'            => 'Jenis pengajuan tidak valid.',
            'keterangan.required' => 'Keterangan / alasan wajib diisi.',
            'keterangan.max'      => 'Keterangan maksimal 2000 karakter.',
        ];
    }
}
