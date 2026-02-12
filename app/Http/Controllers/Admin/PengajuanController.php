<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PengajuanController extends Controller
{
    /**
     * Display listing of all pengajuan submissions
     */
    public function index(Request $request)
    {
        $query = Pengajuan::with(['mahasiswa.user', 'approver'])
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by jenis
        if ($request->has('jenis') && $request->jenis !== 'all') {
            $query->where('jenis', $request->jenis);
        }

        // Search by mahasiswa name or NIM
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('mahasiswa', function($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $pengajuans = $query->paginate(15);

        $stats = [
            'total' => Pengajuan::count(),
            'pending' => Pengajuan::where('status', 'pending')->count(),
            'disetujui' => Pengajuan::where('status', 'disetujui')->count(),
            'ditolak' => Pengajuan::where('status', 'ditolak')->count(),
        ];

        return view('admin.pengajuan.index', compact('pengajuans', 'stats'));
    }

    /**
     * Show detail of a specific pengajuan
     */
    public function show(Pengajuan $pengajuan)
    {
        $pengajuan->load(['mahasiswa.user', 'approver']);
        return view('admin.pengajuan.show', compact('pengajuan'));
    }

    /**
     * Approve pengajuan and generate letter
     */
    public function approve(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([
            'admin_note' => 'nullable|string|max:1000',
        ]);

        // Generate nomor surat
        $nomorSurat = $this->generateNomorSurat($pengajuan);

        // Update pengajuan status
        $pengajuan->update([
            'status' => 'disetujui',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'admin_note' => $request->admin_note,
            'nomor_surat' => $nomorSurat,
        ]);

        // Generate PDF letter
        $pdfPath = $this->generateLetter($pengajuan);
        $pengajuan->update(['file_surat' => $pdfPath]);

        return redirect()
            ->back()
            ->with('success', 'Pengajuan berhasil disetujui dan surat telah digenerate!');
    }

    /**
     * Reject pengajuan
     */
    public function reject(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([
            'admin_note' => 'required|string|max:1000',
        ]);

        $pengajuan->update([
            'status' => 'ditolak',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'admin_note' => $request->admin_note,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Pengajuan telah ditolak.');
    }

    /**
     * Generate nomor surat
     */
    private function generateNomorSurat(Pengajuan $pengajuan)
    {
        $year = date('Y');
        $month = date('m');
        
        // Count total letters this month
        $count = Pengajuan::where('status', 'disetujui')
            ->whereYear('approved_at', $year)
            ->whereMonth('approved_at', $month)
            ->count() + 1;

        // Format: XXX/STIH-ADH/SK/MM/YYYY
        $prefix = $pengajuan->jenis === 'cuti' ? 'CU' : 'SK';
        return sprintf('%03d/STIH-ADH/%s/%s/%s', $count, $prefix, $month, $year);
    }

    /**
     * Generate PDF letter from template
     */
    private function generateLetter(Pengajuan $pengajuan)
    {
        $pengajuan->load(['mahasiswa.user']);

        // Prepare data for template
        $data = [
            'pengajuan' => $pengajuan,
            'mahasiswa' => $pengajuan->mahasiswa,
            'user' => $pengajuan->mahasiswa->user,
            'tanggal' => now()->locale('id')->isoFormat('D MMMM YYYY'),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('surat.template-stih', $data)
            ->setPaper('a4', 'portrait');

        // Save to storage
        $fileName = 'surat_' . $pengajuan->id . '_' . time() . '.pdf';
        $path = 'surat/' . $fileName;
        
        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }

    /**
     * Download generated letter
     */
    public function download(Pengajuan $pengajuan)
    {
        if (!$pengajuan->file_surat) {
            return redirect()->back()->with('error', 'Surat belum tersedia.');
        }

        return Storage::disk('public')->download($pengajuan->file_surat);
    }

    /**
     * Preview letter before approval
     */
    public function preview(Pengajuan $pengajuan)
    {
        $pengajuan->load(['mahasiswa.user']);

        $data = [
            'pengajuan' => $pengajuan,
            'mahasiswa' => $pengajuan->mahasiswa,
            'user' => $pengajuan->mahasiswa->user,
            'tanggal' => now()->locale('id')->isoFormat('D MMMM YYYY'),
        ];

        return view('surat.template-stih', $data);
    }
}
