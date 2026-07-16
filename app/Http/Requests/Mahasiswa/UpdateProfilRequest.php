<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $mahasiswa = Auth::user()->mahasiswa;

        return [
            'name' => 'required|string|max:255',
            'email_pribadi' => 'nullable|email|max:255|unique:mahasiswas,email_pribadi,' . $mahasiswa->id,
            'no_hp' => 'required|digits_between:11,13',
            'alamat' => 'required|string',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
            'agama' => 'required|string|max:255',
            'status_sipil' => 'required|in:Belum Menikah,Menikah,Cerai',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'kota' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'desa' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'alamat_ktp' => 'nullable|string',
            'rt_ktp' => 'nullable|string|max:10',
            'rw_ktp' => 'nullable|string|max:10',
            'provinsi_ktp' => 'nullable|string|max:255',
            'kota_ktp' => 'nullable|string|max:255',
            'kecamatan_ktp' => 'nullable|string|max:255',
            'desa_ktp' => 'nullable|string|max:255',
            'jenis_sekolah' => 'nullable|string|max:255',
            'jurusan_sekolah' => 'nullable|string|max:255',
            'tahun_lulus' => 'nullable|string|max:4',
            'nilai_kelulusan' => 'nullable|numeric|min:0|max:100',
            'nama_ayah' => 'nullable|string|max:255',
            'pendidikan_ayah' => 'nullable|string|max:255',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'agama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'pendidikan_ibu' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'agama_ibu' => 'nullable|string|max:255',
            'alamat_ayah' => 'nullable|string',
            'kota_ayah' => 'nullable|string|max:255',
            'kecamatan_ayah' => 'nullable|string|max:255',
            'desa_ayah' => 'nullable|string|max:255',
            'propinsi_ayah' => 'nullable|string|max:255',
            'handphone_ayah' => 'nullable|digits_between:11,13',
            'alamat_ibu' => 'nullable|string',
            'kota_ibu' => 'nullable|string|max:255',
            'kecamatan_ibu' => 'nullable|string|max:255',
            'desa_ibu' => 'nullable|string|max:255',
            'propinsi_ibu' => 'nullable|string|max:255',
            'handphone_ibu' => 'nullable|digits_between:11,13',
            'tipe_wali' => 'nullable|in:orang_tua,wali',
            'nama_wali' => 'nullable|string|max:255',
            'hubungan_wali' => 'nullable|string|max:255',
            'pendidikan_wali' => 'nullable|string|max:255',
            'pekerjaan_wali' => 'nullable|string|max:255',
            'agama_wali' => 'nullable|string|max:255',
            'alamat_wali' => 'nullable|string',
            'kota_wali' => 'nullable|string|max:255',
            'kecamatan_wali' => 'nullable|string|max:255',
            'desa_wali' => 'nullable|string|max:255',
            'provinsi_wali' => 'nullable|string|max:255',
            'handphone_wali' => 'nullable|digits_between:11,13',
            'keluarga' => 'nullable|array',
            'keluarga.*.nama' => 'nullable|string|max:255',
            'keluarga.*.hubungan' => 'nullable|string|max:255',
            'keluarga.*.pendidikan' => 'nullable|string|max:255',
            'keluarga.*.pekerjaan' => 'nullable|string|max:255',
            'keluarga.*.agama' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|min:8',
            'file_ijazah.*' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:5120',
            'file_transkrip.*' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:5120',
            'file_kk.*' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:5120',
            'file_ktp.*' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:5120',
        ];
    }
}
