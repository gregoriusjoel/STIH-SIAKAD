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
    public function index(\Illuminate\Http\Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        // Display the active semester (chosen by admin in prodi settings)
        $semesterAktif = $mahasiswa->getCurrentSemesterInfo();

        // Get the currently active semester for KRS gating
        $krsSemester = Semester::where('status', 'aktif')->first();

        // Check if KRS is open:
        // 1. Semester must be 'aktif'
        // 2. krs_dapat_diisi must be true
        // 3. Must be within the date range (if dates are set)
        $krsIsOpen = false;
        if ($krsSemester && $krsSemester->krs_dapat_diisi) {
            if (!$krsSemester->krs_mulai || !$krsSemester->krs_selesai) {
                // No dates set, KRS is open
                $krsIsOpen = true;
            } else {
                // Check if today is within the date range
                $krsIsOpen = Carbon::now()->between(
                    Carbon::parse($krsSemester->krs_mulai)->startOfDay(),
                    Carbon::parse($krsSemester->krs_selesai)->endOfDay()
                );
            }
        }

        if (!$krsIsOpen) {
            // Get semester history for downloads even when KRS is closed
            $allPastSemesters = $mahasiswa->getPastSemesters();
            $currentSemester = $mahasiswa->getCurrentSemester();
            $semesterHistory = $allPastSemesters->filter(function ($semester) use ($currentSemester) {
                return $semester->semester_number <= $currentSemester;
            });

            // Check if current semester KRS is already submitted
            $currentKodeId = 'sms' . $currentSemester;
            $currentSemesterSubmitted = Krs::where('mahasiswa_id', $mahasiswa->id)
                ->where('status', '!=', 'draft')
                ->whereHas('mataKuliah', function ($q) use ($currentKodeId) {
                    $q->where('kode_id', $currentKodeId);
                })->exists();

            // If current semester is already submitted, add it to the list
            if ($currentSemesterSubmitted) {
                $alreadyExists = $semesterHistory->contains(function ($semester) use ($currentSemester) {
                    return $semester->semester_number == $currentSemester;
                });

                if (!$alreadyExists) {
                    $isGanjil = ($currentSemester % 2 === 1);
                    $baseYear = (int) $mahasiswa->angkatan;
                    $yearOffset = floor(($currentSemester - 1) / 2);
                    $academicStartYear = $baseYear + $yearOffset;
                    $academicEndYear = $academicStartYear + 1;
                    $calculatedTahunAjaran = $academicStartYear . '/' . $academicEndYear;

                    $currentSemesterObj = (object) [
                        'semester_number' => $currentSemester,
                        'semester_display' => 'Semester ' . $currentSemester,
                        'tahun_ajaran' => $calculatedTahunAjaran,
                        'nama_semester' => $isGanjil ? 'Ganjil' : 'Genap',
                    ];
                    $semesterHistory = collect([$currentSemesterObj])->merge($semesterHistory);
                }
            }

            // Determine which semesters the student can download
            $downloadable = [];
            foreach ($semesterHistory as $s) {
                $nimOrId = $mahasiswa->nim ?? $mahasiswa->id;
                $filename = 'KRS_' . $nimOrId . '_' . $s->semester_number . '.pdf';
                $path = 'krs/' . $mahasiswa->id . '/' . $filename;

                if (Storage::disk('s3')->exists($path)) {
                    $downloadable[] = $s->semester_number;
                    continue;
                }

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

            return view('page.mahasiswa.krs.closed', [
                'mahasiswa' => $mahasiswa,
                'semesterAktif' => $semesterAktif,
                'krsSemester' => $krsSemester,
                'krsStatus' => ['status' => 'closed', 'message' => 'Pengisian KRS belum dibuka atau sudah ditutup. Silakan cek Kalender Akademik.'],
                'message' => 'Pengisian KRS belum dibuka atau sudah ditutup. Silakan cek Kalender Akademik.',
                'semesterList' => $semesterHistory,
                'downloadable' => $downloadable,
            ]);
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
        $kelasQuery = \App\Models\Kelas::with(['mataKuliah', 'dosen.user', 'jadwals']);

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
        
        // Determine if form is locked based on actual KRS status
        $isLocked = in_array($statusKrs, ['approved', 'sudah submit']);

        // Map status to display status for view
        if ($statusKrs === 'sudah submit' || $statusKrs === 'approved' || $statusKrs === 'disetujui') {
            $displayStatusKrs = 'Sudah Mengisi KRS';
        } elseif ($statusKrs === 'draft') {
            $displayStatusKrs = 'Draft';
        } elseif ($statusKrs === 'diajukan') {
            $displayStatusKrs = 'Draft'; // Legacy status treated as draft
        } elseif ($statusKrs === null) {
            $displayStatusKrs = null; // Will display "Belum Mengisi KRS" in view
        } else {
            $displayStatusKrs = $statusKrs;
        }

        // If student has not yet started filling KRS and has no existing KRS, show confirmation page first
        if ($existingKrs->isEmpty() && !$request->has('start')) {
            // Get semester history for downloads (past semesters)
            // Filter to only show semesters 1 through current semester
            $allPastSemesters = $mahasiswa->getPastSemesters();
            $currentSemester = $mahasiswa->getCurrentSemester();
            $semesterHistory = $allPastSemesters->filter(function ($semester) use ($currentSemester) {
                return $semester->semester_number <= $currentSemester;
            });
            
            // Check if current semester KRS is already submitted
            $currentKodeId = 'sms' . $mahasiswaSemester;
            $currentSemesterSubmitted = Krs::where('mahasiswa_id', $mahasiswa->id)
                ->where('status', '!=', 'draft')
                ->whereHas('mataKuliah', function ($q) use ($currentKodeId) {
                    $q->where('kode_id', $currentKodeId);
                })->exists();
            
// If current semester is already submitted, add it to the list (only if not already present)
        if ($currentSemesterSubmitted) {
            $alreadyExists = $semesterHistory->contains(function ($semester) use ($mahasiswaSemester) {
                return $semester->semester_number == $mahasiswaSemester;
            });
            
            if (!$alreadyExists) {
                $isGanjil = ($mahasiswaSemester % 2 === 1);
                // Calculate proper tahun_ajaran based on angkatan and semester
                $baseYear = (int) $mahasiswa->angkatan;
                $yearOffset = floor(($mahasiswaSemester - 1) / 2);
                $academicStartYear = $baseYear + $yearOffset;
                $academicEndYear = $academicStartYear + 1;
                $calculatedTahunAjaran = $academicStartYear . '/' . $academicEndYear;
                
                $currentSemesterObj = (object) [
                    'semester_number' => $mahasiswaSemester,
                    'semester_display' => 'Semester ' . $mahasiswaSemester,
                    'tahun_ajaran' => $calculatedTahunAjaran,
                    'nama_semester' => $isGanjil ? 'Ganjil' : 'Genap',
                ];
                // Add to beginning of collection
                $semesterHistory = collect([$currentSemesterObj])->merge($semesterHistory);
            }
            }

            // Determine which semesters the student can download (has submitted KRS for that semester)
            $downloadable = [];
            foreach ($semesterHistory as $s) {
                // Check if PDF exists
                $nimOrId = $mahasiswa->nim ?? $mahasiswa->id;
                $filename = 'KRS_' . $nimOrId . '_' . $s->semester_number . '.pdf';
                $path = 'krs/' . $mahasiswa->id . '/' . $filename;

                if (Storage::disk('s3')->exists($path)) {
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
            'statusKrs' => $displayStatusKrs ?? null,
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

        // Prefer explicit semester_number from download links.
        // Keep backward compatibility with old semester_id query param.
        $requestedSemester = (int) ($request->get('semester_number') ?? $request->get('semester_id') ?? 0);
        if ($requestedSemester <= 0) {
            $requestedSemester = (int) $mahasiswa->getCurrentSemester();
        }

        $semesterKodeId = 'sms' . $requestedSemester;

        // Only allow download if student has submitted KRS (status != draft) for that semester number.
        $hasSubmitted = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', '!=', 'draft')
            ->whereHas('mataKuliah', function ($q) use ($semesterKodeId) {
                $q->where('kode_id', $semesterKodeId);
            })->exists();

        if (!$hasSubmitted) {
            return back()->with('error', 'KRS belum diajukan/selesai untuk semester ini. Anda hanya bisa mengunduh setelah pengisian selesai.');
        }

        // Use semester_number-based filename (aligned with confirm page history table).
        $nimOrId = $mahasiswa->nim ?? $mahasiswa->id;
        $filename = 'KRS_' . $nimOrId . '_' . $requestedSemester . '.pdf';
        $path = 'krs/' . $mahasiswa->id . '/' . $filename;

        $existingKrs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->whereHas('mataKuliah', function ($q) use ($semesterKodeId) {
                $q->where('kode_id', $semesterKodeId);
            })
            ->with([
                'mataKuliah',
                'kelasMataKuliah.mataKuliah',
                'kelasMataKuliah.dosen.user',
                'kelas.mataKuliah',
                'kelas.dosen.user',
                'kelas.jadwals',
            ])
            ->get();

        // Build semester info for PDF header based on requested semester number.
        $semesterInfo = $mahasiswa->getPastSemesters()->firstWhere('semester_number', $requestedSemester);
        if (!$semesterInfo) {
            $baseYear = (int) $mahasiswa->angkatan;
            $yearOffset = floor(($requestedSemester - 1) / 2);
            $academicStartYear = $baseYear + $yearOffset;
            $academicEndYear = $academicStartYear + 1;

            $semesterInfo = (object) [
                'semester_number' => $requestedSemester,
                'semester_display' => 'Semester ' . $requestedSemester,
                'tahun_ajaran' => $academicStartYear . '/' . $academicEndYear,
                'nama_semester' => ($requestedSemester % 2 === 1) ? 'Ganjil' : 'Genap',
            ];
        }

        $pdf = PDF::loadView('page.mahasiswa.krs.pdf', [
            'mahasiswa' => $mahasiswa,
            'existingKrs' => $existingKrs,
            'semesterAktif' => $semesterInfo,
        ])->setPaper('a4', 'landscape');

        $pdfBinary = $pdf->output();

        Storage::disk('s3')->put($path, $pdfBinary, [
            'ContentType' => 'application/pdf',
            'CacheControl' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
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
        $krsSemester = $krsSemester ?? Semester::where('status', 'aktif')->first();

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

        // Get the currently active semester
        $krsSemester = Semester::where('status', 'aktif')->first();

        // Check if KRS is open:
        // 1. Semester must be 'aktif'
        // 2. krs_dapat_diisi must be true
        // 3. Must be within the date range (if dates are set)
        $krsIsOpen = false;
        if ($krsSemester && $krsSemester->krs_dapat_diisi) {
            if (!$krsSemester->krs_mulai || !$krsSemester->krs_selesai) {
                // No dates set, KRS is open
                $krsIsOpen = true;
            } else {
                // Check if today is within the date range
                $krsIsOpen = Carbon::now()->between(
                    Carbon::parse($krsSemester->krs_mulai)->startOfDay(),
                    Carbon::parse($krsSemester->krs_selesai)->endOfDay()
                );
            }
        }

        if (!$krsIsOpen) {
            return back()->with('error', 'Pengisian KRS belum dibuka atau sudah ditutup. Silakan cek Kalender Akademik.');
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
            $status = ($action === 'submit') ? 'sudah submit' : 'draft';

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
            Storage::disk('s3')->put($path, $pdf->output());

            if ($status === 'sudah submit') {
                \App\Models\AuditLog::log('krs.submitted', $mahasiswa, [
                    'semester_id' => $krsSemester->id,
                    'total_mk' => count($selectedMkIds),
                    'total_sks' => $totalSks
                ]);
                return redirect()->route('mahasiswa.krs.confirm')->with('success', 'KRS sudah di isi. Anda tidak dapat mengubahnya kembali.');
            } else {
                \App\Models\AuditLog::log('krs.draft_saved', $mahasiswa, [
                    'semester_id' => $krsSemester->id,
                    'total_mk' => count($selectedMkIds)
                ]);
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
        // Filter to only show semesters 1 through current semester
        $allPastSemesters = $mahasiswa->getPastSemesters();
        $currentSemester = $mahasiswa->getCurrentSemester();
        $semesterHistory = $allPastSemesters->filter(function ($semester) use ($currentSemester) {
            return $semester->semester_number <= $currentSemester;
        });
        
        // Get current semester number
        $currentSemester = $mahasiswa->getCurrentSemester();
        
        // Check if current semester KRS is already submitted
        $currentKodeId = 'sms' . $currentSemester;
        $currentSemesterSubmitted = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', '!=', 'draft')
            ->whereHas('mataKuliah', function ($q) use ($currentKodeId) {
                $q->where('kode_id', $currentKodeId);
            })->exists();
        
        // If current semester is already submitted, add it to the list (only if not already present)
        if ($currentSemesterSubmitted) {
            $alreadyExists = $semesterHistory->contains(function ($semester) use ($currentSemester) {
                return $semester->semester_number == $currentSemester;
            });
            
            if (!$alreadyExists) {
                $isGanjil = ($currentSemester % 2 === 1);
                // Calculate proper tahun_ajaran based on angkatan and semester
                $baseYear = (int) $mahasiswa->angkatan;
                $yearOffset = floor(($currentSemester - 1) / 2);
                $academicStartYear = $baseYear + $yearOffset;
                $academicEndYear = $academicStartYear + 1;
                $calculatedTahunAjaran = $academicStartYear . '/' . $academicEndYear;
                
                $currentSemesterObj = (object) [
                    'semester_number' => $currentSemester,
                    'semester_display' => 'Semester ' . $currentSemester,
                    'tahun_ajaran' => $calculatedTahunAjaran,
                    'nama_semester' => $isGanjil ? 'Ganjil' : 'Genap',
                ];
                // Add to beginning of collection
                $semesterHistory = collect([$currentSemesterObj])->merge($semesterHistory);
            }
        }

        // Determine which semesters the student can download (has submitted KRS for that semester)
        $downloadable = [];
        foreach ($semesterHistory as $s) {
            // Check if PDF exists
            $nimOrId = $mahasiswa->nim ?? $mahasiswa->id;
            $filename = 'KRS_' . $nimOrId . '_' . $s->semester_number . '.pdf';
            $path = 'krs/' . $mahasiswa->id . '/' . $filename;

            if (Storage::disk('s3')->exists($path)) {
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
