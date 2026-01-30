<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Country;

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

        // Load countries with provinces and cities for dropdowns
        $countries = Country::with('provinces.cities')->get();

        return view('page.mahasiswa.profil.manajemen', compact('mahasiswa', 'user', 'parent', 'countries'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        // Prevent updates if profile is already complete
        if ($mahasiswa->isProfileComplete()) {
            return redirect()->route('mahasiswa.profil.index')
                ->with('error', 'Data profil sudah lengkap dan tidak dapat diubah lagi.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'alamat' => 'nullable|string',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-Laki,Perempuan',
            'agama' => 'nullable|string|max:255',
            'status_sipil' => 'nullable|in:Belum Menikah,Menikah,Cerai',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'kota' => 'nullable|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'negara' => 'nullable|string|max:255',
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
            'alamat_ortu' => 'nullable|string',
            'kota_ortu' => 'nullable|string|max:255',
            'provinsi_ortu' => 'nullable|string|max:255',
            'negara_ortu' => 'nullable|string|max:255',
            'no_hp' => ['nullable','regex:/^[0-9]{1,13}$/'],
                        'handphone_ortu' => ['nullable','regex:/^[0-9]{1,13}$/'],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|min:8'
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
            'provinsi' => $request->provinsi,
            'negara' => $request->negara,
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

        $mahasiswa->update($mahasiswaData);

        // Update or create parent data
        $parent = $mahasiswa->parents()->first();
        $parentData = [
            'user_id' => $user->id,
            'mahasiswa_id' => $mahasiswa->id,
            'nama_ayah' => $request->nama_ayah,
            'pendidikan_ayah' => $request->pendidikan_ayah,
            'pekerjaan_ayah' => $request->pekerjaan_ayah,
            'agama_ayah' => $request->agama_ayah,
            'nama_ibu' => $request->nama_ibu,
            'pendidikan_ibu' => $request->pendidikan_ibu,
            'pekerjaan_ibu' => $request->pekerjaan_ibu,
            'agama_ibu' => $request->agama_ibu,
            'alamat_ortu' => $request->alamat_ortu,
            'kota_ortu' => $request->kota_ortu,
            'provinsi_ortu' => $request->provinsi_ortu,
            'negara_ortu' => $request->negara_ortu,
            'handphone_ortu' => $request->handphone_ortu,
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
