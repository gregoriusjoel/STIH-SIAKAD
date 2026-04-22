<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Services\FileStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    public function __construct(private FileStorageService $storage) {}
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
            'email_pribadi' => 'nullable|email|unique:mahasiswas,email_pribadi',
            'email_kampus' => 'required|email|unique:mahasiswas,email_kampus',
            'password' => 'nullable|min:6',
            'nim' => 'required|string|unique:mahasiswas,nim',
            'prodi' => 'required|string|max:255',
            'angkatan' => 'required|string|max:10',
            'semester' => 'required|integer|min:1|max:12',
            'jenis_kelamin' => 'required|string|max:50',
            'phone' => 'nullable|digits_between:11,13',
            'address' => 'nullable|string|max:1000',
        ]);

        // Sanitasi nama: hapus angka dan karakter khusus, hanya boleh huruf dan spasi
        $cleanName = preg_replace('/[^a-zA-Z\s]/u', '', $request->input('name'));
        $cleanName = preg_replace('/\s+/', ' ', $cleanName); // Remove multiple spaces
        $cleanName = trim($cleanName);

        // Always use kampus email for login (primary email)
        $emailForUser = $request->input('email_kampus');

        // create user
        $plainPassword = $request->filled('password') ? $request->input('password') : 'mahasiswa123';
        $user = \App\Models\User::create([
            'name' => $cleanName,
            'email' => $emailForUser,
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
            'email_pribadi' => $request->input('email_pribadi'),
            'email_kampus' => $request->input('email_kampus'),
            'email_aktif' => 'kampus',  // Always use kampus email as active
            'status' => 'aktif',
            'status_akun' => 'baru',
        ]);

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan. Login menggunakan Email Kampus.');
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
            'email_pribadi' => 'nullable|email|max:255|unique:mahasiswas,email_pribadi,' . $mahasiswa->id,
            'email_kampus' => 'required|email|max:255|unique:mahasiswas,email_kampus,' . $mahasiswa->id,
            'password' => 'nullable|string|min:6',
            'nim' => 'required|string|max:50|unique:mahasiswas,nim,' . $mahasiswa->id,
            'prodi' => 'required|string|max:255',
            'angkatan' => 'required|string|max:10',
            'jenis_kelamin' => 'nullable|string|max:50',
            'semester' => 'required|integer|min:1|max:12',
            'status' => 'required|string|max:50',
            'phone' => 'nullable|digits_between:11,13',
            'address' => 'nullable|string|max:1000',
        ]);

        // Sanitasi nama: hapus angka dan karakter khusus, hanya boleh huruf dan spasi
        $cleanName = preg_replace('/[^a-zA-Z\s]/u', '', $request->input('name'));
        $cleanName = preg_replace('/\s+/', ' ', $cleanName); // Remove multiple spaces
        $cleanName = trim($cleanName);

        // Always use kampus email for login (primary email)
        $emailForUser = $request->input('email_kampus');

        // Update user
        $user = $mahasiswa->user;
        $user->name = $cleanName;
        $user->email = $emailForUser;
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
        $mahasiswa->email_pribadi = $request->input('email_pribadi');
        $mahasiswa->email_kampus = $request->input('email_kampus');
        $mahasiswa->email_aktif = 'kampus';  // Always kampus email
        $mahasiswa->save();

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui. Login menggunakan Email Kampus.');
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
            // Delete personal files from S3
            if ($mahasiswa->foto) {
                $this->storage->delete($mahasiswa->foto);
            }

            // Delete documents from S3
            $documentTypes = ['file_ijazah', 'file_transkrip', 'file_kk', 'file_ktp'];
            foreach ($documentTypes as $docType) {
                $files = $mahasiswa->$docType;
                if (!empty($files) && is_array($files)) {
                    foreach ($files as $file) {
                        $this->storage->delete($file);
                    }
                }
            }

            // Delete student document directory from S3
            $this->storage->deleteDirectory('documents/mahasiswa/' . $mahasiswa->nim);

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
