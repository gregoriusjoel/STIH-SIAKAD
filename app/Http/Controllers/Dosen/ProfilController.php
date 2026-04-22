<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    /**
     * Display the dosen's profile
     */
    public function index()
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->firstOrFail();
        
        // Get related data
        $fakultas = $dosen->fakultas;
        
        // Get all dosen PA students
        $mahasiswaPa = $dosen->mahasiswaPa()->get();
        
        // Get internship supervisions
        $internships = $dosen->internshipSupervisions()->get();
        
        // Get mata kuliah assignments
        $mataKuliahs = $dosen->mataKuliahs()->get();
        
        return view('page.dosen.profil.index', compact(
            'user',
            'dosen',
            'fakultas',
            'mahasiswaPa',
            'internships',
            'mataKuliahs'
        ));
    }

    /**
     * Show the form for editing the dosen's profile
     */
    public function edit()
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->firstOrFail();
        $fakultas = Fakultas::all();
        $prodi = Prodi::all();
        
        return view('page.dosen.profil.edit', compact('user', 'dosen', 'fakultas', 'prodi'));
    }

    /**
     * Update the dosen's profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->firstOrFail();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|digits_between:11,13',
            'address' => 'nullable|string|max:500',
            'nidn' => 'nullable|string|max:20|unique:dosens,nidn,' . $dosen->id,
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'universitas' => 'nullable|string|max:100',
            'prodi' => 'nullable|string|max:100',
            'jabatan_fungsional' => 'nullable|string|max:100',
            'fakultas_id' => 'nullable|exists:fakultas,id',
        ]);
        
        // Update user data
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);
        
        // Update dosen data - convert string values to arrays for array-cast fields
        $dosenData = [
            'phone' => $validated['phone'] ?? $dosen->phone,
            'address' => $validated['address'] ?? $dosen->address,
            'nidn' => $validated['nidn'] ?? $dosen->nidn,
            'pendidikan_terakhir' => $validated['pendidikan_terakhir'] ? [$validated['pendidikan_terakhir']] : $dosen->pendidikan_terakhir,
            'universitas' => $validated['universitas'] ? [$validated['universitas']] : $dosen->universitas,
            'prodi' => $validated['prodi'] ? [$validated['prodi']] : $dosen->prodi,
            'jabatan_fungsional' => $validated['jabatan_fungsional'] ? [$validated['jabatan_fungsional']] : $dosen->jabatan_fungsional,
            'fakultas_id' => $validated['fakultas_id'] ?? $dosen->fakultas_id,
        ];
        
        $dosen->update($dosenData);
        
        return redirect()->route('dosen.profil.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
