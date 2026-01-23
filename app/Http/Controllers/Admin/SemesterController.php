<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        // Redirect to KRS management since semester listing page was removed
        return redirect()->route('admin.krs.index');
    }

    public function manage()
    {
        $semesterAktif = Semester::where('status', 'aktif')->first();
        $allSemesters = Semester::orderBy('tahun_ajaran', 'desc')->orderBy('tanggal_mulai', 'desc')->get();
        return view('admin.semester.manage', compact('semesterAktif', 'allSemesters'));
    }

    public function setActive(Request $request)
    {
        $request->validate([
            'semester_id' => 'required|exists:semesters,id',
        ]);

        // Mark all semesters as non-aktif
        Semester::where('status', 'aktif')->update(['status' => 'non-aktif', 'is_active' => false]);

        // Set the selected semester as aktif
        $semester = Semester::findOrFail($request->semester_id);
        $semester->update(['status' => 'aktif', 'is_active' => true]);

        return redirect()->route('admin.semester.manage')->with('success', 'Semester aktif berhasil diubah');
    }

    public function updateKrsSettings(Request $request, Semester $semester)
    {
        $request->validate([
            'krs_dapat_diisi' => 'nullable|boolean',
            'krs_mulai' => 'nullable|date',
            'krs_selesai' => 'nullable|date|after_or_equal:krs_mulai',
        ]);

        $semester->update([
            'krs_dapat_diisi' => $request->has('krs_dapat_diisi') ? true : false,
            'krs_mulai' => $request->krs_mulai,
            'krs_selesai' => $request->krs_selesai,
        ]);

        return redirect()->route('admin.krs.index')->with('success', 'Pengaturan KRS berhasil diperbarui');
    }

    public function create()
    {
        return view('admin.semester.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_semester' => 'required|string|max:50',
            'tahun_ajaran' => 'required|string|max:20',
            'status' => 'required|in:aktif,non-aktif',

            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        // If setting as aktif, mark others non-aktif
        if ($request->status === 'aktif') {
            Semester::where('status', 'aktif')->update(['status' => 'non-aktif']);
        }

        Semester::create(array_merge($request->all(), ['status' => $request->status ?? 'non-aktif']));
        return redirect()->route('admin.krs.index')->with('success', 'Semester berhasil ditambahkan');
    }

    public function edit(Semester $semester)
    {
        return view('admin.semester.edit', compact('semester'));
    }

    public function update(Request $request, Semester $semester)
    {
        $request->validate([
            'nama_semester' => 'required|string|max:50',
            'tahun_ajaran' => 'required|string|max:20',
            'status' => 'required|in:aktif,non-aktif',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        // If setting as aktif, mark others non-aktif
        if ($request->status === 'aktif' && $semester->status !== 'aktif') {
            Semester::where('status', 'aktif')->update(['status' => 'non-aktif']);
        }

        $semester->update(array_merge($request->all(), ['status' => $request->status ?? 'non-aktif']));
        return redirect()->route('admin.krs.index')->with('success', 'Semester berhasil diperbarui');
    }

    public function destroy(Semester $semester)
    {
        if ($semester->status === 'aktif') {
            return back()->with('error', 'Tidak dapat menghapus semester yang sedang aktif');
        }
        $semester->delete();
        return redirect()->route('admin.krs.index')->with('success', 'Semester berhasil dihapus');
    }
}
