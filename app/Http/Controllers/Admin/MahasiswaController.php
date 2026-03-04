<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Admin listing of all mahasiswa with pagination
        $mahasiswas = \App\Models\Mahasiswa::with('user')->orderBy('id', 'desc')->paginate(25);
        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    /**
     * Show the form for creating a new mahasiswa.
     */
    public function create()
    {
        $prodis = Prodi::where('status', 'aktif')->orderBy('nama_prodi')->get();
        return view('admin.mahasiswa.create', compact('prodis'));
    }

    /**
     * Store a newly created mahasiswa.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|min:6',
            'nim' => 'required|string|unique:mahasiswas,nim',
            'prodi' => 'required|string|max:255',
            'angkatan' => 'required|string|max:10',
            'semester' => 'required|integer|min:1|max:12',
            'jenis_kelamin' => 'required|string|max:50',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:1000',
        ]);

        // create user
        $plainPassword = $request->filled('password') ? $request->input('password') : 'mahasiswa123';
        $user = \App\Models\User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($plainPassword),
            'role' => 'mahasiswa',
        ]);

        // create mahasiswa
        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $request->input('nim'),
            'prodi' => $request->input('prodi'),
            'angkatan' => $request->input('angkatan'),
            'semester' => $request->input('semester'),
            'jenis_kelamin' => $request->input('jenis_kelamin'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'status' => 'aktif',
            'status_akun' => 'baru',
        ]);

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified mahasiswa.
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        $prodis = Prodi::where('status', 'aktif')->orderBy('nama_prodi')->get();
        return view('admin.mahasiswa.edit', compact('mahasiswa', 'prodis'));
    }

    /**
     * Update the specified mahasiswa in storage.
     */
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:6',
            'nim' => 'required|string|max:50',
            'prodi' => 'required|string|max:255',
            'angkatan' => 'required|string|max:10',
            'jenis_kelamin' => 'nullable|string|max:50',
            'semester' => 'required|integer|min:1|max:12',
            'status' => 'required|string|max:50',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:1000',
        ]);

        // Update user
        $user = $mahasiswa->user;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->save();

        // Update mahasiswa fields
        $mahasiswa->nim = $request->input('nim');
        $mahasiswa->prodi = $request->input('prodi');
        $mahasiswa->angkatan = $request->input('angkatan');
        $mahasiswa->jenis_kelamin = $request->input('jenis_kelamin');
        $mahasiswa->semester = $request->input('semester');
        $mahasiswa->status = $request->input('status');
        $mahasiswa->phone = $request->input('phone');
        $mahasiswa->address = $request->input('address');
        $mahasiswa->save();

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mahasiswa $mahasiswa)
    {
        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Remove the specified mahasiswa from storage.
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        try {
            // Delete personal files
            if ($mahasiswa->foto) {
                Storage::disk('public')->delete($mahasiswa->foto);
            }

            // Delete documents
            $documentTypes = ['file_ijazah', 'file_transkrip', 'file_kk', 'file_ktp'];
            foreach ($documentTypes as $docType) {
                $files = $mahasiswa->$docType;
                if (!empty($files) && is_array($files)) {
                    foreach ($files as $file) {
                        Storage::disk('public')->delete($file);
                    }
                }
            }

            // Delete specific directory for student documents if it exists
            $docDirectory = 'mahasiswa/dokumen/' . $mahasiswa->nim;
            if (Storage::disk('public')->exists($docDirectory)) {
                Storage::disk('public')->deleteDirectory($docDirectory);
            }

            // Delete associated user record (which will cascade delete the mahasiswa and parent records)
            if ($mahasiswa->user) {
                $mahasiswa->user->delete();
            } else {
                // Fallback if user doesn't exist for some reason
                $mahasiswa->delete();
            }

            return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus permanen.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the document upload unlock status.
     */
    public function toggleDokumen(Mahasiswa $mahasiswa)
    {
        $mahasiswa->is_dokumen_unlocked = !$mahasiswa->is_dokumen_unlocked;
        $mahasiswa->save();

        $status = $mahasiswa->is_dokumen_unlocked ? 'dibuka' : 'dikunci';
        return back()->with('success', "Akses upload dokumen mahasiswa berhasil {$status}.");
    }
}
