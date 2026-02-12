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
        $allPengajuans = $mahasiswa->pengajuans;
        $pengajuans = $mahasiswa->pengajuans()->latest()->paginate(10);

        return view('page.mahasiswa.pengajuan.index', compact('pengajuans', 'allPengajuans'));
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

    /**
     * Download generated letter (only if approved)
     */
    public function download(Pengajuan $pengajuan)
    {
        // Security: Ensure the pengajuan belongs to the logged-in student
        if ($pengajuan->mahasiswa_id !== Auth::user()->mahasiswa->id) {
            abort(403, 'Unauthorized access');
        }

        // Check if letter is available
        if (!$pengajuan->file_surat || $pengajuan->status !== 'disetujui') {
            return redirect()->back()->with('error', 'Surat belum tersedia atau pengajuan belum disetujui.');
        }

        return Storage::disk('public')->download(
            $pengajuan->file_surat,
            'Surat_' . $pengajuan->jenis_label . '_' . $pengajuan->mahasiswa->nim . '.pdf'
        );
    }
    /**
     * Preview generated letter
     */
    public function preview(Pengajuan $pengajuan)
    {
        // Security: Ensure the pengajuan belongs to the logged-in student
        if ($pengajuan->mahasiswa_id !== Auth::user()->mahasiswa->id) {
            abort(403, 'Unauthorized access');
        }

        // Check if letter is available
        if (!$pengajuan->file_surat || $pengajuan->status !== 'disetujui') {
            return redirect()->back()->with('error', 'Surat belum tersedia atau pengajuan belum disetujui.');
        }

        return Storage::disk('public')->response(
            $pengajuan->file_surat,
            'Surat_' . $pengajuan->jenis_label . '_' . $pengajuan->mahasiswa->nim . '.pdf',
            ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline; filename="preview.pdf"']
        );
    }
}
