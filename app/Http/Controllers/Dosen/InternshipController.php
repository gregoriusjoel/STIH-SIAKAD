<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use App\Models\InternshipLogbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternshipController extends Controller
{
    /**
     * List internships supervised by the logged-in dosen.
     */
    public function index()
    {
        $dosen = Auth::user()->dosen;
        if (!$dosen) abort(403);

        $internships = Internship::where('supervisor_dosen_id', $dosen->id)
            ->with(['mahasiswa.user', 'semester'])
            ->latest()
            ->get();

        return view('page.dosen.magang.index', compact('internships'));
    }

    /**
     * Show detail + logbooks for a supervised internship.
     */
    public function show(Internship $internship)
    {
        $dosen = Auth::user()->dosen;
        if (!$dosen || $internship->supervisor_dosen_id !== $dosen->id) abort(403);

        $internship->load(['mahasiswa.user', 'semester', 'courseMappings.mataKuliah', 'logbooks', 'revisions']);

        return view('page.dosen.magang.show', compact('internship'));
    }

    /**
     * Add a logbook / bimbingan note as dosen.
     */
    public function storeLogbook(Request $request, Internship $internship)
    {
        $dosen = Auth::user()->dosen;
        if (!$dosen || $internship->supervisor_dosen_id !== $dosen->id) abort(403);

        $request->validate([
            'tanggal'        => 'required|date',
            'kegiatan'       => 'nullable|string|max:2000',
            'catatan_dosen'  => 'required|string|max:2000',
        ]);

        InternshipLogbook::create([
            'internship_id'  => $internship->id,
            'tanggal'        => $request->tanggal,
            'kegiatan'       => $request->kegiatan,
            'catatan_dosen'  => $request->catatan_dosen,
            'created_by_role' => 'dosen',
        ]);

        return redirect()->back()->with('success', 'Catatan bimbingan berhasil ditambahkan.');
    }

    /**
     * Update logbook note by dosen (add catatan_dosen to existing entry).
     */
    public function updateLogbook(Request $request, Internship $internship, InternshipLogbook $logbook)
    {
        $dosen = Auth::user()->dosen;
        if (!$dosen || $internship->supervisor_dosen_id !== $dosen->id) abort(403);

        $request->validate([
            'catatan_dosen' => 'required|string|max:2000',
        ]);

        $logbook->update(['catatan_dosen' => $request->catatan_dosen]);

        return redirect()->back()->with('success', 'Catatan berhasil diperbarui.');
    }
}
