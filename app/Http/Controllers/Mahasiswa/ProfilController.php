<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use App\Services\FileStorageService;
use App\Http\Requests\Mahasiswa\UpdateProfilRequest;
use App\Http\Requests\Mahasiswa\UpdatePasswordRequest;
use App\Http\Requests\Mahasiswa\UpdateFotoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function __construct(private FileStorageService $storage) {}

    public function index()
    {
        $user      = Auth::user();
        $mahasiswa = $user->mahasiswa;
        $parent    = $mahasiswa->parents()->first();

        return view('page.mahasiswa.profil.index', compact('mahasiswa', 'user', 'parent'));
    }

    public function manajemen()
    {
        $user      = Auth::user();
        $mahasiswa = $user->mahasiswa;
        $parent    = $mahasiswa->parents()->first();

        return view('page.mahasiswa.profil.manajemen', [
            'mahasiswa'  => $mahasiswa,
            'user'       => $user,
            'parent'     => $parent,
            'provinces'  => $this->loadCsv('provinces',
                fn($r) => count($r) >= 4 && !empty($r[3]),
                fn($r) => ['id' => $r[0], 'province_code' => $r[2], 'name' => $r[3]],
                'name'
            ),
            'cities'     => $this->loadCsv('cities',
                fn($r) => count($r) >= 5 && !empty($r[4]),
                fn($r) => ['id' => $r[0], 'province_code' => $r[2], 'city_code' => $r[3], 'name' => $r[4]],
                'name'
            ),
            'districts'  => $this->loadCsv('districts',
                fn($r) => count($r) >= 5 && !empty($r[4]),
                fn($r) => ['id' => $r[0], 'city_code' => $r[2], 'district_code' => $r[3], 'name' => $r[4]],
                'name'
            ),
            'religions'  => $this->loadCsv('Religion',
                fn($r) => count($r) >= 2 && !empty($r[1]),
                fn($r) => (object) ['id' => $r[0], 'name' => trim($r[1])],
                null,
                ';'
            ),
            'pekerjaans' => $this->loadCsv('pekerjaan',
                fn($r) => count($r) >= 2 && !empty($r[1]),
                fn($r) => (object) ['id' => $r[0], 'name' => trim($r[1])]
            ),
            // ponytail: villages not loaded server-side; AJAX via getVillages() instead | upgrade: preload if AJAX removed
        ]);
    }

    /**
     * Get villages by city code (AJAX endpoint).
     */
    public function getVillages(Request $request)
    {
        $cityCode = $request->query('city_code');

        if (!$cityCode) {
            return response()->json([]);
        }

        $villages = $this->loadCsv('villages',
            fn($r) => count($r) >= 6 && !empty($r[5]),
            fn($r) => ['id' => $r[0], 'city_code' => $r[2], 'district_code' => $r[3], 'village_code' => $r[4], 'name' => $r[5]],
            'name'
        );

        return response()->json(
            $villages->filter(fn($v) => ($v['city_code'] ?? null) === $cityCode)->values()
        );
    }

    public function update(UpdateProfilRequest $request)
    {
        $user      = Auth::user();
        $mahasiswa = $user->mahasiswa;

        $hasParentName = filled($request->input('nama_ayah')) || filled($request->input('nama_ibu'));
        $hasWaliName   = filled($request->input('nama_wali'));

        if (!$hasParentName && !$hasWaliName) {
            return back()
                ->withErrors(['orang_tua_wali' => 'Mohon isi data Orang Tua (Nama Ayah atau Nama Ibu) atau isi data Wali.'])
                ->withInput();
        }

        $user->name = $request->name;
        $user->username = $request->username;
        $user->save();

        $mahasiswaData = $request->only([
            'email_pribadi', 'no_hp', 'alamat', 'tempat_lahir', 'tanggal_lahir',
            'jenis_kelamin', 'agama', 'status_sipil', 'rt', 'rw', 'kota', 'kecamatan',
            'desa', 'provinsi', 'alamat_ktp', 'rt_ktp', 'rw_ktp', 'provinsi_ktp',
            'kota_ktp', 'kecamatan_ktp', 'desa_ktp', 'jenis_sekolah', 'jurusan_sekolah',
            'tahun_lulus', 'nilai_kelulusan',
        ]);

        if ($request->hasFile('foto')) {
            $mahasiswaData['foto'] = $this->uploadFoto($request->file('foto'), $mahasiswa);
        }

        foreach (['file_ijazah', 'file_transkrip', 'file_kk', 'file_ktp'] as $docType) {
            if ($request->hasFile($docType)) {
                foreach ($mahasiswa->$docType ?? [] as $old) {
                    $this->storage->delete($old);
                }
                $mahasiswaData[$docType] = collect($request->file($docType))
                    ->map(fn($f) => $this->storage->upload($f, 'documents/mahasiswa/' . $mahasiswa->storage_folder))
                    ->all();
            }
        }

        $mahasiswaData['is_dokumen_unlocked'] = false;
        $mahasiswa->update($mahasiswaData);

        $parentData = $request->only([
            'tipe_wali', 'nama_ayah', 'pendidikan_ayah', 'pekerjaan_ayah', 'agama_ayah',
            'nama_ibu', 'pendidikan_ibu', 'pekerjaan_ibu', 'agama_ibu',
            'alamat_ayah', 'kota_ayah', 'kecamatan_ayah', 'desa_ayah', 'propinsi_ayah', 'handphone_ayah',
            'alamat_ibu', 'kota_ibu', 'kecamatan_ibu', 'desa_ibu', 'propinsi_ibu', 'handphone_ibu',
            'nama_wali', 'hubungan_wali', 'pendidikan_wali', 'pekerjaan_wali', 'agama_wali',
            'alamat_wali', 'kota_wali', 'kecamatan_wali', 'desa_wali', 'provinsi_wali', 'handphone_wali',
            'keluarga',
        ]) + ['user_id' => $user->id, 'mahasiswa_id' => $mahasiswa->id];

        $parentData['tipe_wali'] ??= 'orang_tua';

        $parent = $mahasiswa->parents()->first();
        $parent ? $parent->update($parentData) : ParentModel::create($parentData);

        return redirect()->route('mahasiswa.profil.index')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai!');
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Password berhasil diubah!');
    }

    public function updateFoto(UpdateFotoRequest $request)
    {
        $user      = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$request->hasFile('foto')) {
            return back()->with('error', 'Gagal mengupload foto.');
        }

        $mahasiswa->update(['foto' => $this->uploadFoto($request->file('foto'), $mahasiswa)]);

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    // ── Private helpers ──────────────────────────────────────────────

    /**
     * Delete old foto and upload new one. Shared by update() and updateFoto().
     */
    private function uploadFoto($file, $mahasiswa): string
    {
        if ($mahasiswa->foto) {
            $this->storage->delete($mahasiswa->foto);
        }

        return $this->storage->upload($file, 'images/mahasiswa/foto/' . $mahasiswa->storage_folder);
    }

    /**
     * Read and cache a master CSV from base_path('master/{name}.csv').
     *
     * @param  string       $name       CSV filename without extension
     * @param  callable     $filter     Row filter — receives raw string[]
     * @param  callable     $mapper     Row mapper — receives raw string[], returns array|object
     * @param  string|null  $sortBy     Collection key to sort by, or null to skip sort
     * @param  string       $delimiter  CSV delimiter (default comma)
     */
    private function loadCsv(
        string $name,
        callable $filter,
        callable $mapper,
        ?string $sortBy = null,
        string $delimiter = ','
    ): \Illuminate\Support\Collection {
        return Cache::remember("master_csv_{$name}", 3600, function () use ($name, $filter, $mapper, $sortBy, $delimiter) {
            $path = base_path("master/{$name}.csv");

            if (!file_exists($path)) {
                return collect();
            }

            $handle = fopen($path, 'r');
            fgetcsv($handle, 0, $delimiter); // skip header

            $rows = collect();
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                if ($filter($row)) {
                    $rows->push($mapper($row));
                }
            }
            fclose($handle);

            return $sortBy ? $rows->sortBy($sortBy)->values() : $rows;
        });
    }
}
