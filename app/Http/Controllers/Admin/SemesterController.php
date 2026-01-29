<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            'nama_semester' => [
                'required',
                'in:Ganjil,Genap',
                Rule::unique('semesters')->where(function ($query) use ($request) {
                    return $query->where('tahun_ajaran', $request->input('tahun_ajaran'));
                }),
            ],
            'tahun_ajaran' => ['required','string','max:20'],
            'status' => ['required','in:aktif,non-aktif'],

            'tanggal_mulai' => [
                'required',
                'date',
                Rule::unique('semesters')->where(function ($query) use ($request) {
                    return $query->where('nama_semester', $request->input('nama_semester'))
                                 ->where('tahun_ajaran', $request->input('tahun_ajaran'))
                                 ->where('tanggal_mulai', $request->input('tanggal_mulai'));
                }),
            ],
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
            'nama_semester' => [
                'required',
                'in:Ganjil,Genap',
                Rule::unique('semesters')->ignore($semester->id)->where(function ($query) use ($request) {
                    return $query->where('tahun_ajaran', $request->input('tahun_ajaran'));
                }),
            ],
            'tahun_ajaran' => ['required','string','max:20'],
            'status' => ['required','in:aktif,non-aktif'],
            'tanggal_mulai' => [
                'required',
                'date',
                Rule::unique('semesters')->ignore($semester->id)->where(function ($query) use ($request) {
                    return $query->where('nama_semester', $request->input('nama_semester'))
                                 ->where('tahun_ajaran', $request->input('tahun_ajaran'))
                                 ->where('tanggal_mulai', $request->input('tanggal_mulai'));
                }),
            ],
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

    /**
     * Return the tanggal_selesai for the Ganjil semester of the given tahun_ajaran.
     *
     * Response: { tanggal_selesai: 'YYYY-MM-DD' | null }
     */
    public function previousEnd(Request $request)
    {
        try {
            Log::info('previousEnd called', ['query' => $request->query()]);

            $request->validate([
                'tahun_ajaran' => 'required|string',
            ]);

            $tahun = $request->input('tahun_ajaran');

            // Only return a date when a Ganjil exists for the exact same tahun_ajaran.
            $semester = Semester::where('nama_semester', 'Ganjil')
                ->where('tahun_ajaran', $tahun)
                ->orderBy('tanggal_selesai', 'desc')
                ->first();

            if (!$semester || !$semester->tanggal_selesai) {
                return response()->json(['tanggal_selesai' => null]);
            }

            $dt = Carbon::parse($semester->tanggal_selesai)->format('Y-m-d');
            return response()->json(['tanggal_selesai' => $dt]);
        } catch (\Exception $e) {
            Log::error('previousEnd error: '.$e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
