<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Services\TeachingAssignmentService;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $service        = app(TeachingAssignmentService::class);
        $semesterService = app(\App\Services\SemesterService::class);

        $activeSemester  = $semesterService->getActiveSemester();
        $allSemesters    = Semester::orderByDesc('tahun_ajaran')->orderByDesc('nama_semester')->get();

        $selectedSemesterId = $request->input('semester_id', $activeSemester?->id);
        $selectedSemester   = $allSemesters->firstWhere('id', $selectedSemesterId) ?? $activeSemester;

        $tab    = $request->input('tab', 'master');
        $search = trim($request->input('search', ''));

        // ── Tab: Master Dosen ────────────────────────────────────────────────
        $dosens = null;
        if ($tab === 'master') {
            $dosens = Dosen::with(['user', 'kelasMataKuliahs.mataKuliah', 'kelasMataKuliahs.semester'])
                ->when($search, function ($q) use ($search) {
                    $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                      ->orWhere('nidn', 'like', "%{$search}%");
                })
                ->orderBy(User::select('name')->whereColumn('users.id', 'dosens.user_id'))
                ->paginate(15)
                ->withQueryString();
        }

        // ── Tab: Dosen Aktif TA ──────────────────────────────────────────────
        $dosenAktif      = collect();
        $dosenAktifCount = 0;

        if ($selectedSemester) {
            // All distinct dosen_ids assigned in selected semester
            $assignedDosenIds = DB::table('dosen_mata_kuliah')
                ->where('semester_id', $selectedSemester->id)
                ->pluck('dosen_id')
                ->unique();

            $dosenAktifCount = $assignedDosenIds->count();

            if ($tab === 'dosen-aktif') {
                $dosenAktif = Dosen::with([
                        'user',
                        'mataKuliahs' => fn($q) => $q->wherePivot('semester_id', $selectedSemester->id)->orderBy('kode_mk'),
                    ])
                    ->whereIn('id', $assignedDosenIds)
                    ->when($search, fn($q) => $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")))
                    ->get()
                    ->sortBy(fn($d) => $d->user->name);
            }
        }

        // ── Tab: Histori ─────────────────────────────────────────────────────
        $historiDosen = collect();

        if ($tab === 'histori') {
            $rows = DB::table('dosen_mata_kuliah as dma')
                ->join('dosens', 'dosens.id', '=', 'dma.dosen_id')
                ->join('users', 'users.id', '=', 'dosens.user_id')
                ->join('mata_kuliahs', 'mata_kuliahs.id', '=', 'dma.mata_kuliah_id')
                ->join('semesters', 'semesters.id', '=', 'dma.semester_id')
                ->when($search, fn($q) => $q->where(function ($q2) use ($search) {
                    $q2->where('users.name', 'like', "%{$search}%")
                       ->orWhere('dosens.nidn', 'like', "%{$search}%");
                }))
                ->select(
                    'dosens.id as dosen_id',
                    'users.name as dosen_name',
                    'dosens.nidn',
                    'dosens.pendidikan',
                    'dosens.status as dosen_status',
                    'semesters.id as semester_id',
                    'semesters.nama_semester',
                    'semesters.tahun_ajaran',
                    'semesters.is_active',
                    'mata_kuliahs.id as mk_id',
                    'mata_kuliahs.kode_mk',
                    'mata_kuliahs.nama_mk',
                    'mata_kuliahs.sks'
                )
                ->orderBy('users.name')
                ->orderByDesc('semesters.tahun_ajaran')
                ->orderByDesc('semesters.nama_semester')
                ->get();

            // Group: dosen → semester → MK
            $historiDosen = $rows->groupBy('dosen_id')->map(function ($dosenRows) {
                $first = $dosenRows->first();
                $semesters = $dosenRows->groupBy('semester_id')->map(function ($semRows) {
                    $s = $semRows->first();
                    return [
                        'id'           => $s->semester_id,
                        'label'        => $s->nama_semester . ' ' . $s->tahun_ajaran,
                        'tahun_ajaran' => $s->tahun_ajaran,
                        'nama_semester'=> $s->nama_semester,
                        'is_active'    => (bool) $s->is_active,
                        'matakuliah'   => $semRows->map(fn($r) => [
                            'id'      => $r->mk_id,
                            'kode_mk' => $r->kode_mk,
                            'nama_mk' => $r->nama_mk,
                            'sks'     => $r->sks,
                        ])->values(),
                    ];
                })->values();

                return [
                    'dosen_id'  => $first->dosen_id,
                    'name'      => $first->dosen_name,
                    'nidn'      => $first->nidn,
                    'pendidikan'=> $first->pendidikan,
                    'status'    => $first->dosen_status,
                    'semesters' => $semesters,
                    'total_ta'  => $semesters->count(),
                    'total_mk'  => $dosenRows->pluck('mk_id')->unique()->count(),
                ];
            })->values();
        }

        // ── Shared data for modals ────────────────────────────────────────────
        $previousSemester   = $selectedSemester ? $service->getPreviousSemester($selectedSemester) : null;
        $availableDosens    = Dosen::with('user')->where('status', 'aktif')
            ->get()->sortBy(fn($d) => $d->user->name);
        $availableMataKuliah = $selectedSemester
            ? \App\Models\MataKuliah::activeBySemester($selectedSemester->id)->orderBy('kode_mk')->get()
            : \App\Models\MataKuliah::orderBy('kode_mk')->get();

        return view('admin.dosen.index', compact(
            'dosens',
            'activeSemester', 'allSemesters', 'selectedSemester',
            'tab', 'search',
            'dosenAktif', 'dosenAktifCount',
            'historiDosen',
            'availableDosens', 'availableMataKuliah',
            'previousSemester'
        ));
    }

    /**
     * Bulk carry-forward: copy all dosen assignments from source semester to target.
     * POST admin/dosen/carry-forward-all
     */
    public function carryForwardAll(Request $request)
    {
        $request->validate([
            'source_semester_id' => 'required|exists:semesters,id',
            'target_semester_id' => 'required|exists:semesters,id|different:source_semester_id',
        ]);

        $sourceSemId = (int) $request->source_semester_id;
        $targetSemId = (int) $request->target_semester_id;

        // Fetch all rows from source
        $sourceRows = DB::table('dosen_mata_kuliah')
            ->where('semester_id', $sourceSemId)
            ->get();

        if ($sourceRows->isEmpty()) {
            return back()->with('error', 'Tidak ada data penugasan pada semester sumber.');
        }

        $userId = auth()->id();
        $now    = now();
        $copied = 0;
        $skipped = 0;

        DB::beginTransaction();
        try {
            foreach ($sourceRows as $row) {
                $exists = DB::table('dosen_mata_kuliah')
                    ->where('dosen_id', $row->dosen_id)
                    ->where('mata_kuliah_id', $row->mata_kuliah_id)
                    ->where('semester_id', $targetSemId)
                    ->exists();

                if ($exists) { $skipped++; continue; }

                DB::table('dosen_mata_kuliah')->insert([
                    'dosen_id'       => $row->dosen_id,
                    'mata_kuliah_id' => $row->mata_kuliah_id,
                    'semester_id'    => $targetSemId,
                    'created_by'     => $userId,
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ]);
                $copied++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal carry forward: ' . $e->getMessage());
        }

        $msg = "{$copied} penugasan berhasil disalin ke semester tujuan.";
        if ($skipped) $msg .= " {$skipped} dilewati (sudah ada).";

        return back()->with('success', $msg);
    }

    public function create()
    {
        // Prefer mata kuliah that are active in the current active semester
        $semesterService = app(\App\Services\SemesterService::class);
        $activeSemester = $semesterService->getActiveSemester();
        if ($activeSemester) {
            $mataKuliahs = \App\Models\MataKuliah::activeBySemester($activeSemester->id)->orderBy('nama_mk')->get();
        } else {
            $mataKuliahs = \App\Models\MataKuliah::orderBy('nama_mk')->get();
        }
        $prodis = \App\Models\Prodi::where('status', 'aktif')->orderBy('nama_prodi')->get();
        return view('admin.dosen.create', compact('mataKuliahs', 'prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|min:6',
            'nidn' => 'required|digits_between:1,16|unique:dosens,nidn',
            'pendidikan_terakhir' => 'required|array|min:1',
            'pendidikan_terakhir.*' => 'string',
            'universitas' => 'required|array|min:1',
            'universitas.*' => 'string',
            'dosen_tetap' => 'required|in:ya,tidak',
            'jabatan_fungsional' => 'nullable|string|max:255',
            'jabatan_fungsional_custom' => 'nullable|string|max:255',
            'prodi' => 'required|array|min:1',
            'prodi.*' => 'string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'mata_kuliah_ids' => 'nullable|array',
            'mata_kuliah_ids.*' => 'exists:mata_kuliahs,id',
        ], [
            'mata_kuliah_ids.*.exists' => 'Mata kuliah yang dipilih tidak valid. Silakan pilih mata kuliah yang tersedia.',
            'prodi.required' => 'Program studi harus dipilih minimal 1.',
            'prodi.min' => 'Program studi harus dipilih minimal 1.',
        ]);

        DB::beginTransaction();
        try {
            $plainPassword = $request->filled('password') ? $request->password : 'dosen123';
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($plainPassword),
                'role' => 'dosen',
            ]);

            // Derive pendidikan summary from last element of array
            $pendidikanArray = array_filter($request->pendidikan_terakhir ?? []);
            $pendidikanString = !empty($pendidikanArray) ? end($pendidikanArray) : null;

            $dosen = Dosen::create([
                'user_id' => $user->id,
                'nidn' => $request->nidn,
                'pendidikan' => $pendidikanString,
                'pendidikan_terakhir' => $pendidikanArray,
                'universitas' => array_filter($request->universitas ?? []),
                'dosen_tetap' => $request->dosen_tetap === 'ya',
                'jabatan_fungsional' => $request->filled('jabatan_fungsional') ? [$request->jabatan_fungsional] : [],
                'prodi' => $request->prodi,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => 'aktif',
            ]);

            // store mata_kuliah ids directly on dosens table as JSON
            if ($request->filled('mata_kuliah_ids') && $dosen) {
                $dosen->update(['mata_kuliah_ids' => array_values($request->mata_kuliah_ids)]);
            }

            DB::commit();
            return redirect()->route('admin.dosen.index')
                ->with('success', 'Data dosen berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Dosen $dosen)
    {
        $dosen->load('user', 'kelasMataKuliahs.mataKuliah');

        $service = app(TeachingAssignmentService::class);
        $activeSemester = $service->getActiveSemester();
        $semesters = $service->getSemestersWithAssignments();

        // Current TA assignments
        $currentAssignments = $activeSemester
            ? $service->getAssignments($dosen, $activeSemester->id)
            : collect();

        // Available MK for assignment
        $semesterService = app(\App\Services\SemesterService::class);
        $activeSem = $semesterService->getActiveSemester();
        if ($activeSem) {
            $availableMataKuliah = \App\Models\MataKuliah::activeBySemester($activeSem->id)->orderBy('kode_mk')->get();
        } else {
            $availableMataKuliah = \App\Models\MataKuliah::orderBy('kode_mk')->get();
        }

        // Previous semester for "copy" feature
        $previousSemester = $activeSemester ? $service->getPreviousSemester($activeSemester) : null;
        $previousAssignments = $previousSemester
            ? $service->getAssignments($dosen, $previousSemester->id)
            : collect();

        // History semesters
        $historySemesters = $service->listHistorySemesters($dosen);

        // Legacy fallback—also pass for backward compat
        $assignedMataKuliahs = $currentAssignments;

        return view('admin.dosen.show', compact(
            'dosen',
            'assignedMataKuliahs',
            'activeSemester',
            'semesters',
            'currentAssignments',
            'availableMataKuliah',
            'previousSemester',
            'previousAssignments',
            'historySemesters'
        ));
    }

    public function edit(Dosen $dosen)
    {
        // Prefer mata kuliah that are active in the current active semester
        $semesterService = app(\App\Services\SemesterService::class);
        $activeSemester = $semesterService->getActiveSemester();
        if ($activeSemester) {
            $mataKuliahs = \App\Models\MataKuliah::activeBySemester($activeSemester->id)->orderBy('nama_mk')->get();
        } else {
            $mataKuliahs = \App\Models\MataKuliah::orderBy('nama_mk')->get();
        }
        $prodis = \App\Models\Prodi::where('status', 'aktif')->orderBy('nama_prodi')->get();
        return view('admin.dosen.edit', compact('dosen', 'mataKuliahs', 'prodis'));
    }

    public function update(Request $request, Dosen $dosen)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $dosen->user_id,
            'nidn' => 'required|digits_between:1,16|unique:dosens,nidn,' . $dosen->id,
            'pendidikan_terakhir' => 'nullable|array',
            'pendidikan_terakhir.*' => 'string',
            'universitas' => 'nullable|array',
            'universitas.*' => 'string',
            'dosen_tetap' => 'required|in:ya,tidak',
            'jabatan_fungsional' => 'required|string|max:255',
            'jabatan_fungsional_custom' => 'nullable|string|max:255',
            'prodi' => 'required|array|min:1',
            'prodi.*' => 'string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'required|in:aktif,non-aktif',
            'mata_kuliah_ids' => 'nullable|array',
            'mata_kuliah_ids.*' => 'exists:mata_kuliahs,id',
        ], [
            'mata_kuliah_ids.*.exists' => 'Mata kuliah yang dipilih tidak valid. Silakan pilih mata kuliah yang tersedia.',
            'prodi.required' => 'Program studi harus dipilih minimal 1.',
            'prodi.min' => 'Program studi harus dipilih minimal 1.',
        ]);

        DB::beginTransaction();
        try {
            $dosen->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $dosen->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            // Determine jabatan_fungsional value: use custom if "lainnya" was selected
            $jabatanValue = $request->jabatan_fungsional === 'lainnya' 
                ? $request->jabatan_fungsional_custom 
                : $request->jabatan_fungsional;

            $pendidikanArray = array_filter($request->pendidikan_terakhir ?? []);
            $pendidikanString = !empty($pendidikanArray) ? end($pendidikanArray) : null;

            $dosen->update([
                'nidn' => $request->nidn,
                'pendidikan' => $pendidikanString,
                'pendidikan_terakhir' => $pendidikanArray,
                'universitas' => array_filter($request->universitas ?? []),
                'dosen_tetap' => $request->dosen_tetap === 'ya',
                'jabatan_fungsional' => !empty($jabatanValue) ? [$jabatanValue] : [],
                'prodi' => $request->prodi,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => $request->status,
            ]);

            // update mata_kuliah_ids JSON column if provided
            if ($request->has('mata_kuliah_ids')) {
                $dosen->update(['mata_kuliah_ids' => array_values($request->mata_kuliah_ids ?? [])]);
            }

            DB::commit();
            return redirect()->route('admin.dosen.index')
                ->with('success', 'Data dosen berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Dosen $dosen)
    {
        try {
            $dosen->user->delete();
            return redirect()->route('admin.dosen.index')
                ->with('success', 'Data dosen berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        $handle = fopen($path, 'r');
        if ($handle === false) {
            return back()->with('error', 'Gagal membuka file.');
        }

        // Detect delimiter
        $firstLine = fgets($handle);
        rewind($handle);
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

        $header = null;
        $imported = 0;
        $updated = 0;
        $failed = 0;
        $errors = [];
        $detailedErrors = [];
        $rowNumber = 1;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                if (!$header) {
                    $header = array_map(function ($h) {
                        // Remove BOM and trim
                        $h = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $h);
                        return strtolower(trim($h));
                    }, $row);
                    $rowNumber++;
                    continue;
                }

                $data = [];
                foreach ($header as $i => $key) {
                    $data[$key] = isset($row[$i]) ? trim($row[$i]) : null;
                }

                // Auto-generate email if missing
                if (empty($data['email']) && !empty($data['nidn'])) {
                    $data['email'] = $data['nidn'] . '@stihadhyaksa.ac.id';
                }

                // minimal required: nidn and name and email

                if (empty($data['nidn']) || empty($data['name']) || empty($data['email'])) {
                    $failed++;
                    $errors[] = 'Baris dengan NIDN/name/email kosong diabaikan.';
                    continue;
                }

                $mkIds = [];
                try {
                    // check existing user by email
                        $user = User::where('email', $data['email'])->first();

                    if ($user) {
                        // update user name if different
                        if ($user->name !== $data['name']) {
                                $user->name = $data['name'];
                                $user->save();
                            }

                            // update password if provided in CSV
                            if (!empty($data['password'])) {
                                $user->password = Hash::make($data['password']);
                                $user->save();
                            }

                        // find or create dosen record
                        $dosen = Dosen::where('user_id', $user->id)->orWhere('nidn', $data['nidn'])->first();
                                if ($dosen) {
                                    $updateData = [
                                        'nidn' => $data['nidn'],
                                        'pendidikan' => $data['pendidikan'] ?? $dosen->pendidikan,
                                        'phone' => $data['phone'] ?? $dosen->phone,
                                        'prodi' => isset($data['prodi']) ? array_map('trim', explode('|', $data['prodi'])) : $dosen->prodi,
                                        'status' => $data['status'] ?? $dosen->status,
                                    ];

                                    // handle pendidikan_terakhir if provided
                                    if (!empty($data['pendidikan_terakhir'])) {
                                        $pendidikanList = preg_split('/[|,;]+/', $data['pendidikan_terakhir']);
                                        $updateData['pendidikan_terakhir'] = array_filter(array_map('trim', $pendidikanList));
                                        
                                        // Update pendidikan string if array is present
                                        if (!empty($updateData['pendidikan_terakhir'])) {
                                            $updateData['pendidikan'] = end($updateData['pendidikan_terakhir']);
                                        }
                                    }

                                    // handle universitas if provided
                                    if (!empty($data['universitas'])) {
                                        $universitasList = preg_split('/[|,;]+/', $data['universitas']);
                                        $updateData['universitas'] = array_filter(array_map('trim', $universitasList));
                                    }

                                    // handle dosen_tetap if provided
                                    if (!empty($data['dosen_tetap'])) {
                                        $updateData['dosen_tetap'] = in_array(strtolower(trim($data['dosen_tetap'])), ['ya', 'yes', '1', 'true']);
                                    }

                                    // handle jabatan_fungsional if provided
                                    if (!empty($data['jabatan_fungsional'])) {
                                        $jabatanList = preg_split('/[|,;]+/', $data['jabatan_fungsional']);
                                        $updateData['jabatan_fungsional'] = array_filter(array_map('trim', $jabatanList));
                                    }

                                    // handle mata_kuliah_kode -> convert to ids and store
                                    if (!empty($data['mata_kuliah_kode'])) {
                                        $codes = preg_split('/[|,;]+/', $data['mata_kuliah_kode']);
                                        $codes = array_filter(array_map('trim', $codes));
                                        if (count($codes)) {
                                            $mks = \App\Models\MataKuliah::whereIn('kode_mk', $codes)->get();
                                            $mkIds = $mks->pluck('id')->toArray();
                                            $foundCodes = $mks->pluck('kode_mk')->toArray();
                                            $missing = array_values(array_diff($codes, $foundCodes));
                                            if (count($missing)) {
                                                $detailedErrors[] = "Baris $rowNumber: Kode mata kuliah tidak ditemukan: " . implode(', ', $missing);
                                            }
                                            $updateData['mata_kuliah_ids'] = $mkIds;
                                        }
                                    }

                                    $dosen->update($updateData);

                                    // try syncing pivot if exists
                                    try {
                                        if (!empty($mkIds) && method_exists($dosen, 'mataKuliahs')) {
                                            $dosen->mataKuliahs()->sync($mkIds);
                                        }
                                    } catch (\Throwable $e) {
                                        // ignore if pivot table missing
                                    }

                                    $updated++;
                                } else {
                                    $createData = [
                                        'user_id' => $user->id,
                                        'nidn' => $data['nidn'],
                                        'pendidikan' => $data['pendidikan'] ?? null,
                                        'prodi' => isset($data['prodi']) ? array_map('trim', explode('|', $data['prodi'])) : [],
                                        'phone' => $data['phone'] ?? null,
                                        'address' => $data['address'] ?? null,
                                        'status' => $data['status'] ?? 'aktif',
                                    ];

                                    // handle pendidikan_terakhir if provided
                                    if (!empty($data['pendidikan_terakhir'])) {
                                        $pendidikanList = preg_split('/[|,;]+/', $data['pendidikan_terakhir']);
                                        $createData['pendidikan_terakhir'] = array_filter(array_map('trim', $pendidikanList));
                                    } else {
                                        $createData['pendidikan_terakhir'] = [];
                                    }

                                    // handle universitas if provided
                                    if (!empty($data['universitas'])) {
                                        $universitasList = preg_split('/[|,;]+/', $data['universitas']);
                                        $createData['universitas'] = array_filter(array_map('trim', $universitasList));
                                    } else {
                                        $createData['universitas'] = [];
                                    }

                                    // handle dosen_tetap if provided
                                    if (!empty($data['dosen_tetap'])) {
                                        $createData['dosen_tetap'] = in_array(strtolower(trim($data['dosen_tetap'])), ['ya', 'yes', '1', 'true']);
                                    } else {
                                        $createData['dosen_tetap'] = false;
                                    }

                                    // handle jabatan_fungsional if provided
                                    if (!empty($data['jabatan_fungsional'])) {
                                        $jabatanList = preg_split('/[|,;]+/', $data['jabatan_fungsional']);
                                        $createData['jabatan_fungsional'] = array_filter(array_map('trim', $jabatanList));
                                    } else {
                                        $createData['jabatan_fungsional'] = [];
                                    }

                                    if (!empty($data['mata_kuliah_kode'])) {
                                        $codes = preg_split('/[|,;]+/', $data['mata_kuliah_kode']);
                                        $codes = array_filter(array_map('trim', $codes));
                                        if (count($codes)) {
                                            $mks = \App\Models\MataKuliah::whereIn('kode_mk', $codes)->get();
                                            $mkIds = $mks->pluck('id')->toArray();
                                            $foundCodes = $mks->pluck('kode_mk')->toArray();
                                            $missing = array_values(array_diff($codes, $foundCodes));
                                            if (count($missing)) {
                                                $detailedErrors[] = "Baris $rowNumber: Kode mata kuliah tidak ditemukan: " . implode(', ', $missing);
                                            }
                                            $createData['mata_kuliah_ids'] = $mkIds;
                                        }
                                    }

                                    $dosen = Dosen::create($createData);

                                    try {
                                        if (!empty($mkIds) && method_exists($dosen, 'mataKuliahs')) {
                                            $dosen->mataKuliahs()->sync($mkIds);
                                        }
                                    } catch (\Throwable $e) {
                                        // ignore if pivot table missing
                                    }

                                    $imported++;
                                }
                        } else {
                        // create new user and dosen (use provided password or default)
                        $plainPassword = !empty($data['password']) ? $data['password'] : 'dosen123';
                        $user = User::create([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'password' => Hash::make($plainPassword),
                            'role' => 'dosen',
                        ]);
                        
                        $createData = [
                            'user_id' => $user->id,
                            'nidn' => $data['nidn'],
                            'pendidikan' => $data['pendidikan'] ?? null,
                            'prodi' => isset($data['prodi']) ? array_map('trim', explode('|', $data['prodi'])) : [],
                            'phone' => $data['phone'] ?? null,
                            'address' => $data['address'] ?? null,
                            'status' => $data['status'] ?? 'aktif',
                        ];

                        // handle pendidikan_terakhir if provided
                        if (!empty($data['pendidikan_terakhir'])) {
                            $pendidikanList = preg_split('/[|,;]+/', $data['pendidikan_terakhir']);
                            $createData['pendidikan_terakhir'] = array_filter(array_map('trim', $pendidikanList));
                        } else {
                            $createData['pendidikan_terakhir'] = [];
                        }

                        // handle universitas if provided
                        if (!empty($data['universitas'])) {
                            $universitasList = preg_split('/[|,;]+/', $data['universitas']);
                            $createData['universitas'] = array_filter(array_map('trim', $universitasList));
                        } else {
                            $createData['universitas'] = [];
                        }

                        // handle dosen_tetap if provided
                        if (!empty($data['dosen_tetap'])) {
                            $createData['dosen_tetap'] = in_array(strtolower(trim($data['dosen_tetap'])), ['ya', 'yes', '1', 'true']);
                        } else {
                            $createData['dosen_tetap'] = false;
                        }

                        // handle jabatan_fungsional if provided
                        if (!empty($data['jabatan_fungsional'])) {
                            $jabatanList = preg_split('/[|,;]+/', $data['jabatan_fungsional']);
                            $createData['jabatan_fungsional'] = array_filter(array_map('trim', $jabatanList));
                        } else {
                            $createData['jabatan_fungsional'] = [];
                        }

                        if (!empty($data['mata_kuliah_kode'])) {
                            $codes = preg_split('/[|,;]+/', $data['mata_kuliah_kode']);
                            $codes = array_filter(array_map('trim', $codes));
                            if (count($codes)) {
                                $mks = \App\Models\MataKuliah::whereIn('kode_mk', $codes)->get();
                                $mkIds = $mks->pluck('id')->toArray();
                                $foundCodes = $mks->pluck('kode_mk')->toArray();
                                $missing = array_values(array_diff($codes, $foundCodes));
                                if (count($missing)) {
                                    $detailedErrors[] = "Baris $rowNumber: Kode mata kuliah tidak ditemukan: " . implode(', ', $missing);
                                }
                                $createData['mata_kuliah_ids'] = $mkIds;
                            }
                        }

                        $dosen = Dosen::create($createData);

                        try {
                            if (!empty($mkIds) && method_exists($dosen, 'mataKuliahs')) {
                                $dosen->mataKuliahs()->sync($mkIds);
                            }
                        } catch (\Throwable $e) {
                            // ignore if pivot table missing
                        }

                        $imported++;
                    }
                    $rowNumber++;
                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = $e->getMessage();
                    continue;
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        } finally {
            if (is_resource($handle)) fclose($handle);
        }

        $message = "Import selesai: $imported baru, $updated diperbarui, $failed gagal.";
        if (count($errors) || count($detailedErrors)) {
            $message .= ' Beberapa baris bermasalah atau ada peringatan.';
        }

        return redirect()->route('admin.dosen.index')
            ->with('success', $message)
            ->with('import_errors', array_merge($errors, $detailedErrors));
    }

    public function toggleStatus(Dosen $dosen)
    {
        try {
            $newStatus = $dosen->status === 'aktif' ? 'non-aktif' : 'aktif';
            $dosen->update(['status' => $newStatus]);

            $action = $newStatus === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->route('admin.dosen.index')
                ->with('success', "Dosen {$dosen->user->name} berhasil {$action}");
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="dosen_import_template.csv"',
        ];

        $columns = ['nidn', 'name', 'email', 'password', 'pendidikan', 'pendidikan_terakhir', 'universitas', 'dosen_tetap', 'jabatan_fungsional', 'phone', 'prodi', 'status', 'address', 'mata_kuliah_kode'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            // sample rows demonstrating the new structure
            fputcsv($file, ['123456', 'Budi Santoso', 'budi@example.com', 'secret123', 'S2', 'S1|S2', 'Universitas Gunadarma|Universitas Indonesia', 'ya', 'Lektor|Asisten Ahli', '08123456789', 'Teknik Informatika|Sistem Informasi', 'aktif', 'Jalan Merdeka 1', 'KD001|KD002']);
            fputcsv($file, ['234567', 'Siti Nurjanah', 'siti@example.com', 'secret123', 'S2', 'S1,S2', 'Universitas Trisakti|Universitas Padjajaran', 'tidak', 'Tenaga Pengajar', '081298765432', 'Hukum Bisnis', 'aktif', 'Jl. Contoh 2', 'KD001,KD003']);
            fputcsv($file, ['345678', 'Ahmad Fauzi', 'ahmad@example.com', 'secret123', 'S3', 'S1;S2;S3', 'Universitas Brawijaya;Universitas Airlangga;Universitas Indonesia', 'ya', 'Profesor', '081212345678', 'Hukum Pidana', 'aktif', 'Jl. Contoh 3', 'KD002;KD004']);
            fclose($file);
        };

        return response()->streamDownload($callback, 'dosen_import_template.csv', $headers);
    }

    // ────────────────────────────────────────────────
    // Teaching Assignment AJAX Endpoints
    // ────────────────────────────────────────────────

    /**
     * Save teaching assignments (draft → save).
     * POST admin/dosen/{dosen}/assignments
     */
    public function storeAssignments(Request $request, Dosen $dosen)
    {
        $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'mata_kuliah_ids' => 'required|array|min:1',
            'mata_kuliah_ids.*' => 'exists:mata_kuliahs,id',
        ], [
            'mata_kuliah_ids.required' => 'Pilih minimal 1 mata kuliah.',
            'mata_kuliah_ids.min' => 'Pilih minimal 1 mata kuliah.',
        ]);

        $service = app(TeachingAssignmentService::class);
        $result = $service->assignSubjects($dosen, $request->mata_kuliah_ids, $request->semester_id);

        if (!empty($result['duplicates'])) {
            $dupNames = array_map(fn($d) => $d['mata_kuliah_nama'] . ' (sudah diambil ' . $d['dosen_nama'] . ')', $result['duplicates']);
            return back()->with('warning', 'Penugasan disimpan. Beberapa MK dilewati karena duplikat: ' . implode(', ', $dupNames))
                ->with('success', $result['added'] . ' mata kuliah berhasil ditugaskan.');
        }

        return back()->with('success', $result['added'] . ' mata kuliah berhasil ditugaskan untuk semester ini.');
    }

    /**
     * Remove a single assignment.
     * DELETE admin/dosen/{dosen}/assignments/{mataKuliah}
     */
    public function destroyAssignment(Request $request, Dosen $dosen, \App\Models\MataKuliah $mataKuliah)
    {
        $semesterId = $request->input('semester_id');
        if (!$semesterId) {
            $activeSemester = app(TeachingAssignmentService::class)->getActiveSemester();
            $semesterId = $activeSemester ? $activeSemester->id : null;
        }

        if (!$semesterId) {
            return back()->with('error', 'Semester aktif tidak ditemukan.');
        }

        $service = app(TeachingAssignmentService::class);
        $service->removeAssignment($dosen, $mataKuliah->id, $semesterId);

        return back()->with('success', 'Penugasan ' . $mataKuliah->nama_mk . ' berhasil dihapus.');
    }

    /**
     * Copy assignments from previous TA.
     * POST admin/dosen/{dosen}/assignments/copy
     */
    public function copyAssignments(Request $request, Dosen $dosen)
    {
        $request->validate([
            'source_semester_id' => 'required|exists:semesters,id',
            'target_semester_id' => 'required|exists:semesters,id',
        ]);

        $service = app(TeachingAssignmentService::class);
        $result = $service->copyFromPreviousTA($dosen, $request->source_semester_id, $request->target_semester_id);

        $msg = $result['copied'] . ' mata kuliah berhasil disalin dari TA sebelumnya.';
        if (!empty($result['skipped_duplicates'])) {
            $dupNames = array_map(fn($d) => $d['mata_kuliah_nama'], $result['skipped_duplicates']);
            $msg .= ' Dilewati karena duplikat: ' . implode(', ', $dupNames);
        }

        return back()->with('success', $msg);
    }

    /**
     * AJAX: Get assignments for a specific semester (for history tab).
     * GET admin/dosen/{dosen}/assignments/history/{semester}
     */
    public function getHistoryAssignments(Dosen $dosen, \App\Models\Semester $semester)
    {
        $service = app(TeachingAssignmentService::class);
        $assignments = $service->getHistoryAssignments($dosen, $semester->id);

        return response()->json([
            'semester' => [
                'id' => $semester->id,
                'nama_semester' => $semester->nama_semester,
                'tahun_ajaran' => $semester->tahun_ajaran,
            ],
            'assignments' => $assignments->map(fn($mk) => [
                'id' => $mk->id,
                'kode_mk' => $mk->kode_mk,
                'nama_mk' => $mk->nama_mk,
                'sks' => $mk->sks,
                'kelas_count' => $mk->kelas_list->count(),
                'kelas' => $mk->kelas_list->map(fn($k) => [
                    'kode_kelas' => $k->kode_kelas,
                    'hari' => $k->hari,
                    'jam' => $k->jam_mulai . ' - ' . $k->jam_selesai,
                ]),
            ]),
            'total_sks' => $assignments->sum('sks'),
        ]);
    }

    /**
     * AJAX: Data for Quick Assign Drawer on index page.
     * GET admin/dosen/{dosen}/quick-assign
     */
    public function quickAssignData(Dosen $dosen)
    {
        $dosen->load(['user', 'kelasMataKuliahs.mataKuliah']);

        $semesterService = app(\App\Services\SemesterService::class);
        $activeSemester  = $semesterService->getActiveSemester();

        $service = app(TeachingAssignmentService::class);
        $currentAssignments = $activeSemester
            ? $service->getAssignments($dosen, $activeSemester->id)
            : collect();

        // All MK ever taught by this dosen (historic)
        $historicMkIds = $dosen->kelasMataKuliahs
            ->pluck('mata_kuliah_id')
            ->unique()
            ->values();

        // All available MK
        $allMK = \App\Models\MataKuliah::orderBy('semester')
            ->orderBy('kode_mk')
            ->get(['id', 'kode_mk', 'nama_mk', 'sks', 'semester']);

        return response()->json([
            'dosen' => [
                'id'   => $dosen->id,
                'name' => $dosen->user->name,
                'nidn' => $dosen->nidn,
            ],
            'active_semester' => $activeSemester ? [
                'id'    => $activeSemester->id,
                'label' => $activeSemester->nama_semester . ' ' . $activeSemester->tahun_ajaran,
            ] : null,
            'current_ids'         => $currentAssignments->pluck('id')->values(),
            'current_assignments' => $currentAssignments->map(fn($mk) => [
                'id'      => $mk->id,
                'kode_mk' => $mk->kode_mk,
                'nama_mk' => $mk->nama_mk,
                'sks'     => $mk->sks,
            ]),
            'historic_mk_ids' => $historicMkIds,
            'available_mk'    => $allMK->map(fn($mk) => [
                'id'      => $mk->id,
                'kode_mk' => $mk->kode_mk,
                'nama_mk' => $mk->nama_mk,
                'sks'     => $mk->sks,
                'semester'=> $mk->semester,
            ]),
        ]);
    }
}
