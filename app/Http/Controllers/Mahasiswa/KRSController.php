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
use Illuminate\Support\Facades\Storage;
use PDF;
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
        if ($semester)
            return $semester;
        $semester = Semester::where('is_active', true)->first();
        if ($semester)
            return $semester;
        return Semester::latest()->first();
    }
    public function index(\Illuminate\Http\Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        // Get student's current semester info (auto-calculated from angkatan)
        $semesterAktif = $mahasiswa->getCurrentSemesterInfo();

        // KRS control semester: prefer semester with KRS open (getCurrentSemester)
        $krsSemester = $this->getCurrentSemester();

        // Check if KRS filling is allowed (by KRS settings)
        if (!$krsSemester || !$krsSemester->krs_dapat_diisi) {
            return view('page.mahasiswa.krs.closed', [
                'mahasiswa' => $mahasiswa,
                'semesterAktif' => $semesterAktif,
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
                    'semesterAktif' => $semesterAktif,
                    'message' => 'Pengisian KRS hanya dibuka pada ' . $mulai->format('d M Y') . ' sampai ' . $selesai->format('d M Y') . '.',
                ]);
            }
        }

        // Get student's current semester number (1-8)
        $mahasiswaSemester = $mahasiswa->getCurrentSemester();

        // Determine current semester kode_id based on student's semester
        $currentKodeId = 'sms' . $mahasiswaSemester;

        // Build list of allowed kode_id based on odd/even semester rules for cross-semester enrollment
        $allowedKodeIds = [];
        if ($mahasiswaSemester % 2 == 1) {
            // Odd semester student can take odd semesters only
            $allowedKodeIds = ['sms1', 'sms3', 'sms5', 'sms7'];
        } else {
            // Even semester student can take even semesters only
            $allowedKodeIds = ['sms2', 'sms4', 'sms6', 'sms8'];
        }

        // Check if student has already submitted KRS (status != draft) for the relevant semester codes
        // If so, redirect to confirm page with info
        $relevantKodeIds = array_merge([$currentKodeId], $allowedKodeIds);
        $hasSubmitted = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', '!=', 'draft')
            ->whereHas('mataKuliah', function ($q) use ($relevantKodeIds) {
                $q->whereIn('kode_id', $relevantKodeIds);
            })->exists();

        if ($hasSubmitted && !$request->has('view_only')) {
            return redirect()->route('mahasiswa.krs.confirm')->with('info', 'Anda sudah melakukan pengisian KRS untuk semester ini.');
        }

        // Get mata kuliah for current semester (will be shown in main table)
        $currentSemesterMataKuliah = \App\Models\MataKuliah::where('kode_id', $currentKodeId)
            ->orderBy('kode_mk')
            ->get();

        // Get mata kuliah for additional courses (cross-semester, shown in dropdown)
        $additionalMataKuliah = \App\Models\MataKuliah::whereIn('kode_id', $allowedKodeIds)
            ->where('kode_id', '!=', $currentKodeId) // Exclude current semester
            ->orderBy('kode_id')
            ->orderBy('kode_mk')
            ->get();

        // Build available classes (kelas) for the active KRS semester to drive calendar and optional class-level info
        $kelasQuery = \App\Models\Kelas::with(['mataKuliah', 'dosen', 'jadwals']);

        // Try to filter by tahun_ajaran and semester_type if available
        // But if no results, fall back to getting all kelas for the allowed mata kuliah
        $kelasQueryWithFilters = clone $kelasQuery;
        if ($krsSemester && $krsSemester->tahun_ajaran) {
            $kelasQueryWithFilters->where('tahun_ajaran', $krsSemester->tahun_ajaran);
        }
        if ($krsSemester && $krsSemester->nama_semester) {
            $kelasQueryWithFilters->where('semester_type', $krsSemester->nama_semester);
        }

        // Filter kelas to only allowed semesters (current + cross semester)
        $kelasQueryWithFilters->whereHas('mataKuliah', function ($q) use ($allowedKodeIds, $currentKodeId) {
            $q->whereIn('kode_id', array_merge([$currentKodeId], $allowedKodeIds));
        });

        $availableKelas = $kelasQueryWithFilters->get();

        // If no kelas found with strict filters, try without tahun_ajaran/semester_type filters
        if ($availableKelas->isEmpty()) {
            $kelasQuery->whereHas('mataKuliah', function ($q) use ($allowedKodeIds, $currentKodeId) {
                $q->whereIn('kode_id', array_merge([$currentKodeId], $allowedKodeIds));
            });
            $availableKelas = $kelasQuery->get();
        }

        // Calendar only needs kelas that have jadwals
        $calendarKelas = $availableKelas->filter(function ($k) {
            return isset($k->jadwals) && $k->jadwals->isNotEmpty();
        });

        // Get existing KRS entries for the relevant semester codes with full details for calendar
        $relevantKodeIds = array_merge([$currentKodeId], $allowedKodeIds);
        $existingKrs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->whereHas('mataKuliah', function ($q) use ($relevantKodeIds) {
                $q->whereIn('kode_id', $relevantKodeIds);
            })
            ->with(['mataKuliah', 'kelas.jadwals', 'kelas.dosen', 'kelas.mataKuliah'])
            ->get()
            ->keyBy('mata_kuliah_id');

        // Merge taken classes (from existingKrs) into calendarKelas to ensure they appear in the calendar
        // even if they were filtered out of availableKelas
        $takenKelas = $existingKrs->pluck('kelas')->filter(function ($k) {
            return $k && isset($k->jadwals) && $k->jadwals->isNotEmpty();
        });

        $calendarKelas = $calendarKelas->merge($takenKelas)->unique('id');

        // Calculate total SKS
        $totalSks = 0;
        foreach ($existingKrs as $krs) {
            if ($krs->ambil_mk === 'ya' && $krs->mataKuliah) {
                $totalSks += $krs->mataKuliah->sks ?? 0;
            }
        }

        $statusKrs = $existingKrs->first()->status ?? null;
        $isLocked = in_array($statusKrs, ['diajukan', 'approved']);

        // If student has not yet started filling KRS and has no existing KRS, show confirmation page first
        if ($existingKrs->isEmpty() && !$request->has('start')) {
            // Get semester history for downloads (past semesters)
            $semesterHistory = $mahasiswa->getPastSemesters();
            
            // Check if current semester KRS is already submitted
            $currentKodeId = 'sms' . $mahasiswaSemester;
            $currentSemesterSubmitted = Krs::where('mahasiswa_id', $mahasiswa->id)
                ->where('status', '!=', 'draft')
                ->whereHas('mataKuliah', function ($q) use ($currentKodeId) {
                    $q->where('kode_id', $currentKodeId);
                })->exists();
            
            // If current semester is already submitted, add it to the list
            if ($currentSemesterSubmitted) {
                $isGanjil = ($mahasiswaSemester % 2 === 1);
                $currentSemesterObj = (object) [
                    'semester_number' => $mahasiswaSemester,
                    'semester_display' => 'Semester ' . $mahasiswaSemester,
                    'tahun_ajaran' => $semesterAktif->tahun_ajaran,
                    'nama_semester' => $isGanjil ? 'Ganjil' : 'Genap',
                ];
                // Add to beginning of collection
                $semesterHistory = collect([$currentSemesterObj])->merge($semesterHistory);
            }

            // Determine which semesters the student can download (has submitted KRS for that semester)
            $downloadable = [];
            foreach ($semesterHistory as $s) {
                // Check if PDF exists
                $nimOrId = $mahasiswa->nim ?? $mahasiswa->id;
                $filename = 'KRS_' . $nimOrId . '_' . $s->semester_number . '.pdf';
                $path = 'krs/' . $mahasiswa->id . '/' . $filename;

                if (Storage::disk('public')->exists($path)) {
                    $downloadable[] = $s->semester_number;
                    continue;
                }

                // Fallback: detect submitted KRS via DB for this specific semester
                $semesterKodeId = 'sms' . $s->semester_number;
                $hasSubmittedForSemester = Krs::where('mahasiswa_id', $mahasiswa->id)
                    ->where('status', '!=', 'draft')
                    ->whereHas('mataKuliah', function ($q) use ($semesterKodeId) {
                        $q->where('kode_id', $semesterKodeId);
                    })->exists();

                if ($hasSubmittedForSemester) {
                    $downloadable[] = $s->semester_number;
                }
            }

            return view('page.mahasiswa.krs.confirm', [
                'mahasiswa' => $mahasiswa,
                'semesterAktif' => $semesterAktif,
                'availableCount' => $currentSemesterMataKuliah->count() + $additionalMataKuliah->count(),
                'semesterList' => $semesterHistory,
                'downloadable' => $downloadable,
            ]);
        }

        return view('page.mahasiswa.krs.index', [
            'mahasiswa' => $mahasiswa,
            'currentSemesterMataKuliah' => $currentSemesterMataKuliah,
            'additionalMataKuliah' => $additionalMataKuliah,
            'existingKrs' => $existingKrs,
            'totalSks' => $totalSks,
            'statusKrs' => $statusKrs,
            'isLocked' => $isLocked,
            'semesterAktif' => $semesterAktif,
            'mahasiswaSemester' => $mahasiswaSemester,
            'currentKodeId' => $currentKodeId,
            'allowedKodeIds' => $allowedKodeIds,
            'availableKelas' => $availableKelas ?? collect(),
            'calendarKelas' => $calendarKelas ?? collect(),
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

        // If a saved PDF exists in storage for this mahasiswa+semester, serve it immediately
        $nimOrId = $mahasiswa->nim ?? $mahasiswa->id;
        $filename = 'KRS_' . $nimOrId . '_' . ($krsSemester->id ?? 'sem') . '.pdf';
        $path = 'krs/' . $mahasiswa->id . '/' . $filename;
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->download($path, $filename);
        }

        // Only allow printing if student has submitted KRS (status != draft) for that semester
        $hasSubmitted = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', '!=', 'draft')
            ->whereHas('kelasMataKuliah', function ($q) use ($krsSemester) {
                $q->where('semester_id', $krsSemester->id);
            })->exists();

        if (!$hasSubmitted) {
            return back()->with('error', 'KRS belum diajukan/selesai untuk semester ini. Anda hanya bisa mengunduh setelah pengisian selesai.');
        }

        $existingKrs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->whereHas('kelas', function ($q) use ($krsSemester) {
                $q->where('tahun_ajaran', $krsSemester->tahun_ajaran ?? null)
                    ->where('semester_type', $krsSemester->nama_semester ?? null);
            })
            ->with(['kelas.mataKuliah', 'kelas.dosen', 'kelas.jadwals'])
            ->get();

        // display semester should still use admin-selected semester for header/card
        $displaySemester = Semester::where('status', 'aktif')->first()
            ?? Semester::where('is_active', true)->first()
            ?? Semester::latest()->first();

        // If a saved PDF exists in storage for this mahasiswa+semester, serve it
        $nimOrId = $mahasiswa->nim ?? $mahasiswa->id;
        $filename = 'KRS_' . $nimOrId . '_' . ($krsSemester->id ?? 'sem') . '.pdf';
        $path = 'krs/' . $mahasiswa->id . '/' . $filename;
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->download($path, $filename);
        }

        return view('page.mahasiswa.krs.print', [
            'mahasiswa' => $mahasiswa,
            'existingKrs' => $existingKrs,
            'semesterAktif' => $displaySemester,
        ]);
    }

    public function review(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        // determine which semester to review
        $krsSemester = null;
        if ($request->has('semester_id')) {
            $krsSemester = Semester::find($request->get('semester_id'));
        }
        $krsSemester = $krsSemester ?? $this->getCurrentSemester();

        if (!$krsSemester) {
            return back()->with('error', 'Semester tidak ditemukan.');
        }

        // Use same data fetch logic as print
        $existingKrs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->whereHas('kelas', function ($q) use ($krsSemester) {
                $q->where('tahun_ajaran', $krsSemester->tahun_ajaran ?? null)
                    ->where('semester_type', $krsSemester->nama_semester ?? null);
            })
            ->with(['kelas.mataKuliah', 'kelas.dosen', 'kelas.jadwals'])
            ->get();

        $displaySemester = Semester::where('status', 'aktif')->first()
            ?? Semester::where('is_active', true)->first()
            ?? Semester::latest()->first();

        // Load PDF view but stream it
        $pdf = PDF::loadView('page.mahasiswa.krs.pdf', [
            'mahasiswa' => $mahasiswa,
            'existingKrs' => $existingKrs,
            'semesterAktif' => $krsSemester, // Use the specific semester being reviewed
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Review_KRS.pdf');
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
            'mata_kuliah' => 'required|array',
            'mata_kuliah.*' => 'in:ya',
        ]);

        // Validate odd/even semester rules and SKS limit
        $mahasiswaSemester = $mahasiswa->getCurrentSemester();
        $totalSks = 0;
        $selectedMkIds = [];

        foreach ($request->mata_kuliah as $mkId => $ambil) {
            if ($ambil === 'ya') {
                $selectedMkIds[] = $mkId;
            }
        }

        if (!empty($selectedMkIds)) {
            $selectedMataKuliah = \App\Models\MataKuliah::whereIn('id', $selectedMkIds)->get();

            // Validate odd/even semester rule
            $isOddSemester = ($mahasiswaSemester % 2 == 1);
            foreach ($selectedMataKuliah as $mk) {
                $kodeId = $mk->kode_id ?? '';

                // Extract semester number from kode_id (e.g., 'sms3' -> 3)
                if (preg_match('/sms(\d+)/', $kodeId, $matches)) {
                    $semesterNum = (int) $matches[1];
                    $isKodeOdd = ($semesterNum % 2 == 1);

                    // Check if semester parity matches
                    if ($isOddSemester !== $isKodeOdd) {
                        return back()->with(
                            'error',
                            'Anda tidak dapat mengambil mata kuliah dari semester ' .
                            ($isKodeOdd ? 'ganjil' : 'genap') . ' karena Anda berada di semester ' .
                            ($isOddSemester ? 'ganjil' : 'genap') . '. Mata kuliah: ' . $mk->nama_mk
                        );
                    }
                }

                $totalSks += $mk->sks ?? 0;
            }

            // Max SKS limit removed — no server-side SKS cap enforced here
        }

        $currentKodeId = 'sms' . $mahasiswaSemester;
        if ($mahasiswaSemester % 2 == 1) {
            $allowedKodeIds = ['sms1', 'sms3', 'sms5', 'sms7'];
        } else {
            $allowedKodeIds = ['sms2', 'sms4', 'sms6', 'sms8'];
        }

        // Check for double submission
        $relevantKodeIds = array_merge([$currentKodeId], $allowedKodeIds);
        $hasSubmitted = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', '!=', 'draft')
            ->whereHas('mataKuliah', function ($q) use ($relevantKodeIds) {
                $q->whereIn('kode_id', $relevantKodeIds);
            })->exists();

        if ($hasSubmitted) {
            return back()->with('error', 'KRS sudah disubmit dan tidak dapat diubah.');
        }

        DB::beginTransaction();
        try {
            // Delete existing KRS for this mahasiswa for the relevant semester codes
            $relevantKodeIds = array_merge([$currentKodeId], $allowedKodeIds);
            Krs::where('mahasiswa_id', $mahasiswa->id)
                ->whereHas('mataKuliah', function ($q) use ($relevantKodeIds) {
                    $q->whereIn('kode_id', $relevantKodeIds);
                })->delete();

            // Determine status based on user action
            $action = $request->input('action', 'draft');
            $status = ($action === 'submit') ? 'approved' : 'draft';

            // Create new KRS entries for selected mata kuliah
            foreach ($request->mata_kuliah as $mkId => $ambil) {
                if ($ambil === 'ya') {
                    // Try to associate the KRS with an existing Kelas and KelasMataKuliah
                    $kelasRecord = \App\Models\Kelas::where('mata_kuliah_id', $mkId)->first();
                    $kelasMkRecord = \App\Models\KelasMataKuliah::where('mata_kuliah_id', $mkId)->first();

                    $createData = [
                        'mahasiswa_id' => $mahasiswa->id,
                        'mata_kuliah_id' => $mkId,
                        'ambil_mk' => 'ya',
                        'status' => $status,
                    ];

                    if ($kelasRecord) {
                        $createData['kelas_id'] = $kelasRecord->id;
                    }
                    if ($kelasMkRecord) {
                        $createData['kelas_mata_kuliah_id'] = $kelasMkRecord->id;
                    }

                    Krs::create($createData);
                }
            }

            DB::commit();

            // After successful save, generate PDF of the submitted KRS and return it for download
            // Determine semester for PDF: use current KRS semester
            $krsSemesterForPdf = $krsSemester;

            $existingKrsPdf = Krs::where('mahasiswa_id', $mahasiswa->id)
                ->whereHas('mataKuliah', function ($q) use ($relevantKodeIds) {
                    $q->whereIn('kode_id', $relevantKodeIds);
                })
                ->with(['kelas.mataKuliah', 'kelas.dosen', 'kelas.jadwals', 'mataKuliah'])
                ->get();

            $displaySemesterPdf = Semester::where('status', 'aktif')->first()
                ?? Semester::where('is_active', true)->first()
                ?? Semester::latest()->first();

            $pdf = PDF::loadView('page.mahasiswa.krs.pdf', [
                'mahasiswa' => $mahasiswa,
                'existingKrs' => $existingKrsPdf,
                'semesterAktif' => $displaySemesterPdf,
            ])->setPaper('a4', 'landscape');

            // Save PDF to public storage for later download; do not force immediate download
            $nimOrId = $mahasiswa->nim ?? $mahasiswa->id;
            $filename = 'KRS_' . $nimOrId . '_' . ($krsSemesterForPdf->id ?? 'sem') . '.pdf';
            $path = 'krs/' . $mahasiswa->id . '/' . $filename;
            Storage::disk('public')->put($path, $pdf->output());

            if ($status === 'approved') {
                return redirect()->route('mahasiswa.krs.confirm')->with('success', 'KRS berhasil diajukan dan difinalisasi. Anda tidak dapat mengubahnya kembali.');
            } else {
                return redirect()->route('mahasiswa.krs.confirm')->with('success', 'Draft KRS berhasil disimpan. Anda dapat melanjutkan pengisian kapan saja.');
            }

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menyimpan KRS: ' . $e->getMessage());
        }
    }

    public function submit()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        // Update all draft KRS to approved so admin sees immediately
        Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'draft')
            ->update(['status' => 'approved']);

        return redirect()->route('mahasiswa.krs.index')
            ->with('success', 'KRS berhasil disimpan dan tercatat.');
    }

    public function confirm()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        // Get student's current semester info (auto-calculated from angkatan)
        $semesterAktif = $mahasiswa->getCurrentSemesterInfo();

        // Get semester history for downloads (past semesters)
        $semesterHistory = $mahasiswa->getPastSemesters();
        
        // Get current semester number
        $currentSemester = $mahasiswa->getCurrentSemester();
        
        // Check if current semester KRS is already submitted
        $currentKodeId = 'sms' . $currentSemester;
        $currentSemesterSubmitted = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', '!=', 'draft')
            ->whereHas('mataKuliah', function ($q) use ($currentKodeId) {
                $q->where('kode_id', $currentKodeId);
            })->exists();
        
        // If current semester is already submitted, add it to the list
        if ($currentSemesterSubmitted) {
            $isGanjil = ($currentSemester % 2 === 1);
            $currentSemesterObj = (object) [
                'semester_number' => $currentSemester,
                'semester_display' => 'Semester ' . $currentSemester,
                'tahun_ajaran' => $semesterAktif->tahun_ajaran,
                'nama_semester' => $isGanjil ? 'Ganjil' : 'Genap',
            ];
            // Add to beginning of collection
            $semesterHistory = collect([$currentSemesterObj])->merge($semesterHistory);
        }

        // Determine which semesters the student can download (has submitted KRS for that semester)
        $downloadable = [];
        foreach ($semesterHistory as $s) {
            // Check if PDF exists
            $nimOrId = $mahasiswa->nim ?? $mahasiswa->id;
            $filename = 'KRS_' . $nimOrId . '_' . $s->semester_number . '.pdf';
            $path = 'krs/' . $mahasiswa->id . '/' . $filename;

            if (Storage::disk('public')->exists($path)) {
                $downloadable[] = $s->semester_number;
                continue;
            }

            // Fallback: detect submitted KRS via DB for this specific semester
            $semesterKodeId = 'sms' . $s->semester_number;
            $hasSubmittedForSemester = Krs::where('mahasiswa_id', $mahasiswa->id)
                ->where('status', '!=', 'draft')
                ->whereHas('mataKuliah', function ($q) use ($semesterKodeId) {
                    $q->where('kode_id', $semesterKodeId);
                })->exists();

            if ($hasSubmittedForSemester) {
                $downloadable[] = $s->semester_number;
            }
        }

        // Check if validated/finalized KRS for this semester exists
        $mahasiswaSemester = $mahasiswa->getCurrentSemester();
        $currentKodeId = 'sms' . $mahasiswaSemester;
        $allowedKodeIds = ($mahasiswaSemester % 2 == 1)
            ? ['sms1', 'sms3', 'sms5', 'sms7']
            : ['sms2', 'sms4', 'sms6', 'sms8'];
        $relevantKodeIds = array_merge([$currentKodeId], $allowedKodeIds);

        $alreadySubmitted = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', '!=', 'draft')
            ->whereHas('mataKuliah', function ($q) use ($relevantKodeIds) {
                $q->whereIn('kode_id', $relevantKodeIds);
            })->exists();

        $hasDraft = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'draft')
            ->whereHas('mataKuliah', function ($q) use ($relevantKodeIds) {
                $q->whereIn('kode_id', $relevantKodeIds);
            })->exists();

        return view('page.mahasiswa.krs.confirm', [
            'mahasiswa' => $mahasiswa,
            'semesterAktif' => $semesterAktif,
            'semesterList' => $semesterHistory,
            'downloadable' => $downloadable,
            'alreadySubmitted' => $alreadySubmitted,
            'hasDraft' => $hasDraft,
        ]);
    }
}
