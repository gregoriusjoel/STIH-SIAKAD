<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengajuanController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $pengajuans = $mahasiswa->pengajuans()->latest()->get();

        return view('page.mahasiswa.pengajuan.index', compact('pengajuans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:cuti,surat_aktif',
            'keterangan' => 'required|string|max:1000',
            'file_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $filePath = $request->file('file_pendukung')->store('pengajuan/' . Auth::user()->id, 'public');
        }

        Auth::user()->mahasiswa->pengajuans()->create([
            'jenis' => $request->jenis,
            'keterangan' => $request->keterangan,
            'file_path' => $filePath,
            'status' => 'pending',
        ]);

        return redirect()->route('mahasiswa.pengajuan.index')->with('success', 'Pengajuan berhasil dikirim!');
    }
}
