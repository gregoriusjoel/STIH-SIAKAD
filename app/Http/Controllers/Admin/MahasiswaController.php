<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mahasiswas = Mahasiswa::with('user')->paginate(10);
        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.mahasiswa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'npm' => 'required|unique:mahasiswas,npm',
            'prodi' => 'required|string',
            'angkatan' => 'required|string',
            'semester' => 'required|integer|min:1|max:8',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'mahasiswa',
            ]);

            Mahasiswa::create([
                'user_id' => $user->id,
                'npm' => $request->npm,
                'prodi' => $request->prodi,
                'angkatan' => $request->angkatan,
                'semester' => $request->semester,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => 'aktif',
            ]);

            DB::commit();
            return redirect()->route('admin.mahasiswa.index')
                ->with('success', 'Data mahasiswa berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('user', 'krs.kelasMataKuliah.mataKuliah');
        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        return view('admin.mahasiswa.edit', compact('mahasiswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $mahasiswa->user_id,
            'npm' => 'required|unique:mahasiswas,npm,' . $mahasiswa->id,
            'prodi' => 'required|string',
            'angkatan' => 'required|string',
            'semester' => 'required|integer|min:1|max:8',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'required|in:aktif,cuti,lulus,drop-out',
        ]);

        DB::beginTransaction();
        try {
            $mahasiswa->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $mahasiswa->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            $mahasiswa->update([
                'npm' => $request->npm,
                'prodi' => $request->prodi,
                'angkatan' => $request->angkatan,
                'semester' => $request->semester,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => $request->status,
            ]);

            DB::commit();
            return redirect()->route('admin.mahasiswa.index')
                ->with('success', 'Data mahasiswa berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        try {
            $mahasiswa->user->delete(); // Cascade akan menghapus mahasiswa
            return redirect()->route('admin.mahasiswa.index')
                ->with('success', 'Data mahasiswa berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
