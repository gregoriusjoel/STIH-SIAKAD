<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Krs;
use App\Models\Mahasiswa;
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
        $mahasiswas = \App\Models\Mahasiswa::with('user')->orderBy('npm')->paginate(25);
        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    /**
     * Show the form for creating a new mahasiswa.
     */
    public function create()
    {
        return view('admin.mahasiswa.create');
    }

    /**
     * Store a newly created mahasiswa.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'npm' => 'required|string|unique:mahasiswas,npm',
            'prodi' => 'required|string|max:255',
            'angkatan' => 'required|string|max:10',
            'semester' => 'required|integer|min:1|max:12',
            'jenis_kelamin' => 'required|string|max:50',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:1000',
        ]);

        // create user
        $user = \App\Models\User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => 'mahasiswa',
        ]);

        // create mahasiswa
        Mahasiswa::create([
            'user_id' => $user->id,
            'npm' => $request->input('npm'),
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
        return view('admin.mahasiswa.edit', compact('mahasiswa'));
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
            'npm' => 'required|string|max:50',
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
        $mahasiswa->npm = $request->input('npm');
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
}
