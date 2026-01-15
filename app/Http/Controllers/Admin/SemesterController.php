<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::orderBy('tahun_ajaran', 'desc')->orderBy('tanggal_mulai', 'desc')->paginate(10);
        return view('admin.semester.index', compact('semesters'));
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
        return redirect()->route('admin.semester.index')->with('success', 'Semester berhasil ditambahkan');
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
        return redirect()->route('admin.semester.index')->with('success', 'Semester berhasil diperbarui');
    }

    public function destroy(Semester $semester)
    {
        if ($semester->status === 'aktif') {
            return back()->with('error', 'Tidak dapat menghapus semester yang sedang aktif');
        }
        $semester->delete();
        return redirect()->route('admin.semester.index')->with('success', 'Semester berhasil dihapus');
    }
}
