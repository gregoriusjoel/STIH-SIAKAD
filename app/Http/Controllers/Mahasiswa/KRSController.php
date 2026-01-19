<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Krs;
use App\Models\KelasMataKuliah;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KRSController extends Controller
{
    /**
     * Determine the current active semester.
     * Prefer semester with status='aktif', then is_active flag, then latest.
     */
    protected function getCurrentSemester()
    {
        $now = Carbon::now();

        // Prefer a semester that currently allows KRS filling and (if period set) is within the period
        $openSemesters = Semester::where('krs_dapat_diisi', true)->get();
        foreach ($openSemesters as $s) {
            if ($s->krs_mulai && $s->krs_selesai) {
                $mulai = Carbon::parse($s->krs_mulai)->startOfDay();
                $selesai = Carbon::parse($s->krs_selesai)->endOfDay();
                if ($now->between($mulai, $selesai)) {
                    return $s;
                }
            } else {
                return $s;
            }
        }

        // Fallback: prefer status='aktif', then is_active, then latest
        $semester = Semester::where('status', 'aktif')->first();
        if ($semester) return $semester;
        $semester = Semester::where('is_active', true)->first();
        if ($semester) return $semester;
        return Semester::latest()->first();
    }
    public function index(\Illuminate\Http\Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        
        // Display semester (card) should reflect admin-selected semester (status='aktif' or is_active)
        $displaySemester = Semester::where('status', 'aktif')->first()
            ?? Semester::where('is_active', true)->first()
            ?? Semester::latest()->first();

        // KRS control semester: prefer semester with KRS open (getCurrentSemester)
        $krsSemester = $this->getCurrentSemester();

        // Check if KRS filling is allowed (by KRS settings)
        if (!$krsSemester || !$krsSemester->krs_dapat_diisi) {
            return view('page.mahasiswa.krs.closed', [
                'mahasiswa' => $mahasiswa,
                'semesterAktif' => $displaySemester,
                'message' => 'Pengisian KRS belum dibuka atau sudah ditutup. Silakan hubungi admin.',
            ]);
        }

        // Check if current date is within the allowed KRS period (if configured) on krsSemester
        $now = Carbon::now();
        if ($krsSemester->krs_mulai && $krsSemester->krs_selesai) {
            $mulai = Carbon::parse($krsSemester->krs_mulai)->startOfDay();
            $selesai = Carbon::parse($krsSemester->krs_selesai)->endOfDay();
            if ($now->lt($mulai) || $now->gt($selesai)) {
                return view('page.mahasiswa.krs.closed', [
                    'mahasiswa' => $mahasiswa,
                    'semesterAktif' => $displaySemester,
                    'message' => 'Pengisian KRS hanya dibuka pada ' . $mulai->format('d M Y') . ' sampai ' . $selesai->format('d M Y') . '.',
                ]);
            }
        }
        
        // Get all available kelas for current semester
        $availableKelas = KelasMataKuliah::where('semester_id', $krsSemester->id ?? null)
            ->with(['mataKuliah', 'dosen.user', 'jadwal'])
            ->get();
        
        // Get existing KRS
        $existingKrs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->whereHas('kelasMataKuliah', function($q) use ($krsSemester) {
                $q->where('semester_id', $krsSemester->id ?? null);
            })
            ->with('kelasMataKuliah')
            ->get()
            ->keyBy('kelas_mata_kuliah_id');
        
        // Calculate total SKS
        $totalSks = 0;
        foreach ($existingKrs as $krs) {
            if ($krs->ambil_mk === 'ya') {
                $totalSks += $krs->kelasMataKuliah->mataKuliah->sks ?? 0;
            }
        }
        
        $maxSks = 24; // Max SKS per semester
        $statusKrs = $existingKrs->first()->status ?? null;
        $isLocked = in_array($statusKrs, ['diajukan', 'approved']);

        // If student has not yet started filling KRS and has no existing KRS, show confirmation page first
        if ($existingKrs->isEmpty() && !$request->has('start')) {
            // prepare list of past semesters and which are downloadable (student has submitted KRS)
            $semesterList = Semester::orderBy('tahun_ajaran', 'desc')->orderBy('tanggal_mulai', 'desc')->get();
            $downloadable = [];
            foreach ($semesterList as $s) {
                $has = Krs::where('mahasiswa_id', $mahasiswa->id)
                    ->where('status', '!=', 'draft')
                    ->whereHas('kelasMataKuliah', function($q) use ($s) {
                        $q->where('semester_id', $s->id);
                    })->exists();
                if ($has) $downloadable[] = $s->id;
            }

            return view('page.mahasiswa.krs.confirm', [
                'mahasiswa' => $mahasiswa,
                'semesterAktif' => $displaySemester,
                'availableCount' => $availableKelas->count(),
                'semesterList' => $semesterList,
                'downloadable' => $downloadable,
            ]);
        }

        return view('page.mahasiswa.krs.index', [
            'mahasiswa' => $mahasiswa,
            'availableKelas' => $availableKelas,
            'existingKrsData' => $existingKrs,
            'totalSks' => $totalSks,
            'maxSks' => $maxSks,
            'statusKrs' => $statusKrs,
            'isLocked' => $isLocked,
            'semesterAktif' => $displaySemester,
        ]);
    }

    public function print(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        // determine which semester to print: optional query param semester_id, otherwise krsSemester
        $krsSemester = null;
        if ($request->has('semester_id')) {
            $krsSemester = Semester::find($request->get('semester_id'));
        }
        $krsSemester = $krsSemester ?? $this->getCurrentSemester();

        if (!$krsSemester) {
            return back()->with('error', 'Semester tidak ditemukan.');
        }

        // Only allow printing if student has submitted KRS (status != draft) for that semester
        $hasSubmitted = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', '!=', 'draft')
            ->whereHas('kelasMataKuliah', function($q) use ($krsSemester) {
                $q->where('semester_id', $krsSemester->id);
            })->exists();

        if (!$hasSubmitted) {
            return back()->with('error', 'KRS belum diajukan/selesai untuk semester ini. Anda hanya bisa mengunduh setelah pengisian selesai.');
        }

        $existingKrs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->whereHas('kelasMataKuliah', function($q) use ($krsSemester) {
                $q->where('semester_id', $krsSemester->id ?? null);
            })
            ->with(['kelasMataKuliah.mataKuliah', 'kelasMataKuliah.dosen.user', 'kelasMataKuliah.jadwal'])
            ->get();

        // display semester should still use admin-selected semester for header/card
        $displaySemester = Semester::where('status', 'aktif')->first()
            ?? Semester::where('is_active', true)->first()
            ?? Semester::latest()->first();

        return view('page.mahasiswa.krs.print', [
            'mahasiswa' => $mahasiswa,
            'existingKrs' => $existingKrs,
            'semesterAktif' => $displaySemester,
        ]);
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        
        // display semester (admin-chosen) and krs semester (for gating/filtering)
        $displaySemester = Semester::where('status', 'aktif')->first()
            ?? Semester::where('is_active', true)->first()
            ?? Semester::latest()->first();

        $krsSemester = $this->getCurrentSemester();

        if (!$krsSemester || !$krsSemester->krs_dapat_diisi) {
            return back()->with('error', 'Pengisian KRS belum dibuka atau sudah ditutup.');
        }

        // Server-side check for KRS period (based on krsSemester)
        $now = Carbon::now();
        if ($krsSemester->krs_mulai && $krsSemester->krs_selesai) {
            $mulai = Carbon::parse($krsSemester->krs_mulai)->startOfDay();
            $selesai = Carbon::parse($krsSemester->krs_selesai)->endOfDay();
            if ($now->lt($mulai) || $now->gt($selesai)) {
                return back()->with('error', 'Pengisian KRS tidak berada pada periode yang diizinkan.');
            }
        }
        
        $request->validate([
            'kelas_mata_kuliah' => 'required|array',
            'kelas_mata_kuliah.*' => 'in:ya,tidak',
        ]);
        
        DB::beginTransaction();
        try {
            // Delete existing KRS for this semester (draft only)
            Krs::where('mahasiswa_id', $mahasiswa->id)
                ->where('status', 'draft')
                ->whereHas('kelasMataKuliah', function($q) use ($krsSemester) {
                    $q->where('semester_id', $krsSemester->id ?? null);
                })
                ->delete();
            
            // Create new KRS
            foreach ($request->kelas_mata_kuliah as $kelasId => $ambil) {
                Krs::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'kelas_mata_kuliah_id' => $kelasId,
                    'ambil_mk' => $ambil,
                    'status' => 'draft',
                ]);
            }
            
            DB::commit();
            return redirect()->route('mahasiswa.krs.index')
                ->with('success', 'KRS berhasil disimpan sebagai draft');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menyimpan KRS: ' . $e->getMessage());
        }
    }
    
    public function submit()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        
        // Update all draft KRS to diajukan
        Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'draft')
            ->update(['status' => 'diajukan']);
        
        return redirect()->route('mahasiswa.krs.index')
            ->with('success', 'KRS berhasil diajukan. Menunggu persetujuan.');
    }
}
