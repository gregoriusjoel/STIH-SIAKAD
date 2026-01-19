<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
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
        
        return view('page.mahasiswa.profil.index', compact('mahasiswa', 'user'));
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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        // Update user
        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);
        
        // Update mahasiswa
        $mahasiswaData = [
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat
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
            'password' => Hash::make($request->new_password)
        ]);
        
        return back()->with('success', 'Password berhasil diubah!');
    }
}
