<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $user = Auth::user();
        $parent = $mahasiswa->parents()->first();

        return view('page.mahasiswa.profil.index', compact('mahasiswa', 'user', 'parent'));
    }

    public function manajemen()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $user = Auth::user();
        $parent = $mahasiswa->parents()->first();

        // Read provinces from CSV
        $provinces = collect();
        $provincesPath = base_path('master/provinces.csv');
        if (file_exists($provincesPath)) {
            $handle = fopen($provincesPath, 'r');
            $header = fgetcsv($handle); // skip header
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) >= 4 && !empty($row[3])) {
                    $provinces->push([
                        'id' => $row[0],
                        'province_code' => $row[2],
                        'name' => $row[3],
                    ]);
                }
            }
            fclose($handle);
        }
        $provinces = $provinces->sortBy('name')->values();

        // Read cities from CSV
        $cities = collect();
        $citiesPath = base_path('master/cities.csv');
        if (file_exists($citiesPath)) {
            $handle = fopen($citiesPath, 'r');
            $header = fgetcsv($handle); // skip header
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) >= 5 && !empty($row[4])) {
                    $cities->push([
                        'id' => $row[0],
                        'province_code' => $row[2],
                        'city_code' => $row[3],
                        'name' => $row[4],
                    ]);
                }
            }
            fclose($handle);
        }
        $cities = $cities->sortBy('name')->values();

        // Read religions if available (CSV uses semicolon delimiter)
        $religions = collect();
        $religionsPath = base_path('master/Religion.csv');
        if (file_exists($religionsPath)) {
            $handle = fopen($religionsPath, 'r');
            $header = fgetcsv($handle, 0, ';'); // skip header, semicolon delimiter
            while (($row = fgetcsv($handle, 0, ';')) !== false) {
                if (count($row) >= 2 && !empty($row[1])) {
                    $religions->push((object)['id' => $row[0], 'name' => trim($row[1])]);
                }
            }
            fclose($handle);
        }

        // Read all villages from CSV
        $villages = collect();
        $villagesPath = base_path('master/villages.csv');
        if (file_exists($villagesPath)) {
            $handle = fopen($villagesPath, 'r');
            $header = fgetcsv($handle); // skip header
            while (($row = fgetcsv($handle)) !== false) {
                // CSV format: id, province_code, city_code, district_code, village_code, village
                if (count($row) >= 6 && !empty($row[5])) {
                    $villages->push([
                        'id' => $row[0],
                        'city_code' => $row[2],
                        'name' => $row[5],
                    ]);
                }
            }
            fclose($handle);
        }
        $villages = $villages->sortBy('name')->values();

        // Read pekerjaan (occupations) from CSV
        $pekerjaans = collect();
        $pekerjaanPath = base_path('master/pekerjaan.csv');
        if (file_exists($pekerjaanPath)) {
            $handle = fopen($pekerjaanPath, 'r');
            $header = fgetcsv($handle); // skip header
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) >= 2 && !empty($row[1])) {
                    $pekerjaans->push((object)['id' => $row[0], 'name' => trim($row[1])]);
                }
            }
            fclose($handle);
        }

        return view('page.mahasiswa.profil.manajemen', compact('mahasiswa', 'user', 'parent', 'provinces', 'cities', 'religions', 'villages', 'pekerjaans'));
    }

    /**
     * Get villages by city code (AJAX endpoint)
     */
    public function getVillages(Request $request)
    {
        $cityCode = $request->query('city_code');

        if (!$cityCode) {
            return response()->json([]);
        }

        $villages = collect();
        $villagesPath = base_path('master/villages.csv');

        if (file_exists($villagesPath)) {
            $handle = fopen($villagesPath, 'r');
            $header = fgetcsv($handle); // skip header

            while (($row = fgetcsv($handle)) !== false) {
                // CSV format: id, province_code, city_code, district_code, village_code, village
                if (count($row) >= 6 && $row[2] === $cityCode && !empty($row[5])) {
                    $villages->push([
                        'id' => $row[0],
                        'city_code' => $row[2],
                        'village_code' => $row[4],
                        'name' => $row[5],
                    ]);
                }
            }
            fclose($handle);
        }

        return response()->json($villages->sortBy('name')->values());
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-Laki,Perempuan',
            'agama' => 'nullable|string|max:255',
            'status_sipil' => 'nullable|in:Belum Menikah,Menikah,Cerai',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'kota' => 'nullable|string|max:255',
            'desa' => 'nullable|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'negara' => 'nullable|string|max:255',
            'alamat_ktp' => 'nullable|string',
            'rt_ktp' => 'nullable|string|max:10',
            'rw_ktp' => 'nullable|string|max:10',
            'provinsi_ktp' => 'nullable|string|max:255',
            'kota_ktp' => 'nullable|string|max:255',
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
            'desa_ayah' => 'nullable|string|max:255',
            'propinsi_ayah' => 'nullable|string|max:255',
            'handphone_ayah' => 'nullable|string|max:20',
            'alamat_ibu' => 'nullable|string',
            'kota_ibu' => 'nullable|string|max:255',
            'desa_ibu' => 'nullable|string|max:255',
            'propinsi_ibu' => 'nullable|string|max:255',
            'handphone_ibu' => 'nullable|string|max:20',
            'tipe_wali' => 'nullable|in:orang_tua,wali',
            'nama_wali' => 'nullable|string|max:255',
            'hubungan_wali' => 'nullable|string|max:255',
            'pendidikan_wali' => 'nullable|string|max:255',
            'pekerjaan_wali' => 'nullable|string|max:255',
            'agama_wali' => 'nullable|string|max:255',
            'alamat_wali' => 'nullable|string',
            'kota_wali' => 'nullable|string|max:255',
            'desa_wali' => 'nullable|string|max:255',
            'provinsi_wali' => 'nullable|string|max:255',
            'negara_wali' => 'nullable|string|max:255',
            'handphone_wali' => 'nullable|string|max:20',
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
        ]);

        // Update user
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update mahasiswa
        $mahasiswaData = [
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'status_sipil' => $request->status_sipil,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'kota' => $request->kota,
            'desa' => $request->desa,
            'provinsi' => $request->provinsi,
            'alamat_ktp' => $request->alamat_ktp,
            'rt_ktp' => $request->rt_ktp,
            'rw_ktp' => $request->rw_ktp,
            'provinsi_ktp' => $request->provinsi_ktp,
            'kota_ktp' => $request->kota_ktp,
            'desa_ktp' => $request->desa_ktp,
            'jenis_sekolah' => $request->jenis_sekolah,
            'jurusan_sekolah' => $request->jurusan_sekolah,
            'tahun_lulus' => $request->tahun_lulus,
            'nilai_kelulusan' => $request->nilai_kelulusan,
        ];

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Delete old foto if exists
            if ($mahasiswa->foto) {
                Storage::disk('public')->delete($mahasiswa->foto);
            }

            $fotoPath = $request->file('foto')->store('mahasiswa/foto', 'public');
            $mahasiswaData['foto'] = $fotoPath;
        }

        // Handle document uploads
        $documentTypes = ['file_ijazah', 'file_transkrip', 'file_kk', 'file_ktp'];
        foreach ($documentTypes as $docType) {
            if ($request->hasFile($docType)) {
                // Delete existing files first
                $existingFiles = $mahasiswa->$docType ?? [];
                foreach ($existingFiles as $existingFile) {
                    Storage::disk('public')->delete($existingFile);
                }

                // Upload new files
                $uploadedFiles = [];
                foreach ($request->file($docType) as $file) {
                    $path = $file->store('mahasiswa/dokumen/' . $mahasiswa->nim, 'public');
                    $uploadedFiles[] = $path;
                }

                // Replace with new files only
                $mahasiswaData[$docType] = $uploadedFiles;
            }
        }

        $mahasiswa->update($mahasiswaData);

        // Update or create parent data
        $parent = $mahasiswa->parents()->first();
        $parentData = [
            'user_id' => $user->id,
            'mahasiswa_id' => $mahasiswa->id,
            'tipe_wali' => $request->tipe_wali ?? 'orang_tua',
            'nama_ayah' => $request->nama_ayah,
            'pendidikan_ayah' => $request->pendidikan_ayah,
            'pekerjaan_ayah' => $request->pekerjaan_ayah,
            'agama_ayah' => $request->agama_ayah,
            'nama_ibu' => $request->nama_ibu,
            'pendidikan_ibu' => $request->pendidikan_ibu,
            'pekerjaan_ibu' => $request->pekerjaan_ibu,
            'agama_ibu' => $request->agama_ibu,
            'alamat_ayah' => $request->alamat_ayah,
            'kota_ayah' => $request->kota_ayah,
            'desa_ayah' => $request->desa_ayah,
            'propinsi_ayah' => $request->propinsi_ayah,
            'handphone_ayah' => $request->handphone_ayah,
            'alamat_ibu' => $request->alamat_ibu,
            'kota_ibu' => $request->kota_ibu,
            'desa_ibu' => $request->desa_ibu,
            'propinsi_ibu' => $request->propinsi_ibu,
            'handphone_ibu' => $request->handphone_ibu,
            'nama_wali' => $request->nama_wali,
            'hubungan_wali' => $request->hubungan_wali,
            'pendidikan_wali' => $request->pendidikan_wali,
            'pekerjaan_wali' => $request->pekerjaan_wali,
            'agama_wali' => $request->agama_wali,
            'alamat_wali' => $request->alamat_wali,
            'kota_wali' => $request->kota_wali,
            'desa_wali' => $request->desa_wali,
            'provinsi_wali' => $request->provinsi_wali,
            'negara_wali' => $request->negara_wali,
            'handphone_wali' => $request->handphone_wali,
            'keluarga' => $request->keluarga,
        ];

        if ($parent) {
            $parent->update($parentData);
        } else {
            ParentModel::create($parentData);
        }

        return redirect()->route('mahasiswa.profil.index')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed'
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai!');
        }

        // Update password
        $user->update([
            'password' => $request->new_password
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}
