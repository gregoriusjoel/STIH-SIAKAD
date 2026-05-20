<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\KelasPerkuliahanRequest;
use App\Models\KelasPerkuliahan;
use App\Models\Prodi;
use App\Models\Semester;
use App\Services\KelasPerkuliahanService;
use App\Services\MahasiswaClassAssignmentService;
use Illuminate\Http\Request;

class KelasPerkuliahanController extends Controller
{
    public function __construct(
        protected KelasPerkuliahanService $service
    ) {}

    /**
     * Display a listing of kelas perkuliahan.
     */
    public function index(Request $request)
    {
        $query = KelasPerkuliahan::with(['prodi', 'tahunAkademik'])
            ->orderByDesc('angkatan')
            ->orderBy('kode_prodi')
            ->orderBy('kode_kelas');

        // Filters
        if ($request->filled('prodi_id')) {
            $query->where('prodi_id', $request->prodi_id);
        }

        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->angkatan);
        }

        if ($request->filled('tahun_akademik_id')) {
            $query->where('tahun_akademik_id', $request->tahun_akademik_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kelas', 'like', "%{$search}%")
                  ->orWhere('kode_prodi', 'like', "%{$search}%")
                  ->orWhere('kode_kelas', 'like', "%{$search}%");
            });
        }

        $kelasPerkuliahans = $query->paginate(10)->withQueryString();
        $prodis = Prodi::orderBy('nama_prodi')->get();
        $semesters = Semester::orderByDesc('id')->get();

        return view('admin.kelas-perkuliahan.index', compact(
            'kelasPerkuliahans',
            'prodis',
            'semesters'
        ));
    }

    /**
     * Show the form for creating a new kelas perkuliahan.
     */
    public function create()
    {
        $prodis = Prodi::orderBy('nama_prodi')->get();
        $semesters = Semester::orderByDesc('id')->get();
        $angkatanOptions = range((int) date('Y'), 1960);

        return view('admin.kelas-perkuliahan.create', compact('prodis', 'semesters', 'angkatanOptions'));
    }

    /**
     * Store a newly created kelas perkuliahan.
     * Uses Service Layer with firstOrCreate to prevent duplicates.
     */
    public function store(KelasPerkuliahanRequest $request)
    {
        try {
            $kp = $this->service->findOrCreate($request->validated());
        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['kode_kelas' => $e->getMessage()]);
        }

        return redirect()
            ->route('admin.kelas-perkuliahan.index')
            ->with('success', "Kelas Perkuliahan \"{$kp->nama_kelas}\" berhasil ditambahkan.");
    }



    /**
     * Display the specified kelas perkuliahan.
     */
    public function show(KelasPerkuliahan $kelasPerkuliahan)
    {
        $kelasPerkuliahan->load([
            'prodi',
            'tahunAkademik',
            'kelasMataKuliahs.mataKuliah',
            'kelasMataKuliahs.dosen',
            'jadwals',
            'mahasiswas.user',
        ]);

        return view('admin.kelas-perkuliahan.show', compact('kelasPerkuliahan'));
    }

    /**
     * Show the form for editing the specified kelas perkuliahan.
     */
    public function edit(KelasPerkuliahan $kelasPerkuliahan)
    {
        $prodis = Prodi::orderBy('nama_prodi')->get();
        $semesters = Semester::orderByDesc('id')->get();
        $angkatanOptions = range((int) date('Y'), 1960);

        return view('admin.kelas-perkuliahan.edit', compact(
            'kelasPerkuliahan',
            'prodis',
            'semesters',
            'angkatanOptions'
        ));
    }

    /**
     * Update the specified kelas perkuliahan.
     */
    public function update(KelasPerkuliahanRequest $request, KelasPerkuliahan $kelasPerkuliahan)
    {
        try {
            $this->service->update($kelasPerkuliahan, $request->validated());
        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['kode_kelas' => $e->getMessage()]);
        }

        return redirect()
            ->route('admin.kelas-perkuliahan.index')
            ->with('success', "Kelas Perkuliahan \"{$kelasPerkuliahan->nama_kelas}\" berhasil diperbarui.");
    }

    /**
     * Permanently delete the specified kelas perkuliahan.
     */
    public function destroy(KelasPerkuliahan $kelasPerkuliahan)
    {
        $namaKelas = $kelasPerkuliahan->nama_kelas;
        $kelasPerkuliahan->forceDelete();

        return redirect()
            ->route('admin.kelas-perkuliahan.index')
            ->with('success', "Kelas Perkuliahan \"{$namaKelas}\" berhasil dihapus.");
    }

    /**
     * Bulk generate kelas perkuliahan for a specific prodi.
     */
    public function generateBulk(Request $request)
    {
        $mode = $request->input('mode', 'manual');

        if ($mode === 'auto') {
            $request->validate([
                'prodi_id'                  => 'required|exists:prodis,id',
                'angkatan'                  => 'required|array|min:1',
                'angkatan.*'                => 'digits:4',
                'tahun_akademik_id'         => 'nullable|exists:semesters,id',
                'max_students_per_class'    => 'required|integer|min:1|max:100',
                'jumlah_mahasiswa'          => 'required|integer|min:0|max:10000',
                'overwrite'                 => 'nullable|boolean',
            ]);

            $maxPerKelas = $request->integer('max_students_per_class');
            $jumlahMahasiswa = $request->integer('jumlah_mahasiswa');
            $overwrite = $request->boolean('overwrite', false);
            $kelasPerAngkatan = $jumlahMahasiswa > 0 ? (int) ceil($jumlahMahasiswa / $maxPerKelas) : 0;
            $kelasPerAngkatan = max(1, $kelasPerAngkatan);

            $totalCreated = 0;
            $totalDeleted = 0;
            $totalSkipped = 0;

            $angkatanList = $request->input('angkatan', []);

            foreach ($angkatanList as $angkatan) {
                $result = $overwrite
                    ? $this->service->generateForAngkatanWithOverwrite(
                        $request->prodi_id,
                        $angkatan,
                        $request->tahun_akademik_id,
                        $kelasPerAngkatan
                    )
                    : $this->service->generateForAngkatan(
                        $request->prodi_id,
                        $angkatan,
                        $request->tahun_akademik_id,
                        $kelasPerAngkatan
                    );

                $totalCreated += $result['created']->count();
                if ($overwrite) {
                    $totalDeleted += $result['deleted'];
                } else {
                    $totalSkipped += $result['skipped'];
                }
            }

            if ($overwrite) {
                $message = "Berhasil menimpa data. Dihapus: {$totalDeleted}, dibuat ulang: {$totalCreated} Kelas Perkuliahan.";
            } else {
                $message = "Berhasil generate {$totalCreated} Kelas Perkuliahan baru.";
                if ($totalSkipped > 0) {
                    $message .= " {$totalSkipped} data sudah ada sebelumnya (dilewati).";
                }
            }
        } else {
            $request->validate([
                'prodi_id'          => 'required|exists:prodis,id',
                'angkatan'          => 'required|array|min:1',
                'angkatan.*'        => 'digits:4',
                'tahun_akademik_id' => 'nullable|exists:semesters,id',
                'kelas_per_angkatan' => 'required|integer|min:1|max:20',
                'overwrite'         => 'nullable|boolean',
            ]);

            $kelasPerAngkatan = $request->integer('kelas_per_angkatan');
            $overwrite = $request->boolean('overwrite', false);

            $totalCreated = 0;
            $totalDeleted = 0;
            $totalSkipped = 0;

            $angkatanList = $request->input('angkatan', []);

            foreach ($angkatanList as $angkatan) {
                $result = $overwrite
                    ? $this->service->generateForAngkatanWithOverwrite(
                        $request->prodi_id,
                        $angkatan,
                        $request->tahun_akademik_id,
                        $kelasPerAngkatan
                    )
                    : $this->service->generateForAngkatan(
                        $request->prodi_id,
                        $angkatan,
                        $request->tahun_akademik_id,
                        $kelasPerAngkatan
                    );

                $totalCreated += $result['created']->count();
                if ($overwrite) {
                    $totalDeleted += $result['deleted'];
                } else {
                    $totalSkipped += $result['skipped'];
                }
            }

            if ($overwrite) {
                $message = "Berhasil menimpa data. Dihapus: {$totalDeleted}, dibuat ulang: {$totalCreated} Kelas Perkuliahan.";
            } else {
                $message = "Berhasil generate {$totalCreated} Kelas Perkuliahan baru.";
                if ($totalSkipped > 0) {
                    $message .= " {$totalSkipped} data sudah ada sebelumnya (dilewati).";
                }
            }
        }

        return redirect()
            ->route('admin.kelas-perkuliahan.index')
            ->with('success', $message);
    }

    public function options(Request $request, MahasiswaClassAssignmentService $assignmentService)
    {
        $request->validate([
            'prodi_id' => 'nullable|exists:prodis,id',
            'angkatan' => 'nullable|digits:4',
            'tahun_akademik_id' => 'nullable|exists:semesters,id',
        ]);

        $tahunAkademik = $assignmentService->resolveAcademicYear(
            $request->filled('tahun_akademik_id') ? (int) $request->tahun_akademik_id : null
        );

        if (!$request->filled('prodi_id') || !$request->filled('angkatan')) {
            return response()->json([
                'data' => [],
                'meta' => [
                    'count' => 0,
                    'angkatan' => null,
                    'tahun_akademik_id' => $tahunAkademik?->id,
                    'tahun_akademik_label' => $tahunAkademik?->display_label,
                    'message' => 'Pilih prodi dan angkatan terlebih dahulu.',
                ],
            ]);
        }

        $options = $this->service->getStudentDropdownOptions(
            (int) $request->prodi_id,
            (string) $request->angkatan,
            $tahunAkademik?->id
        );

        $missingAcademicYearCount = 0;
        if ($options->isEmpty()) {
            $missingAcademicYearCount = KelasPerkuliahan::query()
                ->where('prodi_id', (int) $request->prodi_id)
                ->where('angkatan', (string) $request->angkatan)
                ->whereNull('tahun_akademik_id')
                ->count();
        }

        return response()->json([
            'data' => $options,
            'meta' => [
                'count' => $options->count(),
                'angkatan' => (string) $request->angkatan,
                'tahun_akademik_id' => $tahunAkademik?->id,
                'tahun_akademik_label' => $tahunAkademik?->display_label,
                'message' => $options->isEmpty()
                    ? ($missingAcademicYearCount > 0
                        ? 'Kelas untuk prodi dan angkatan ini ada, tetapi belum dihubungkan ke tahun akademik aktif. Silakan set Tahun Akademik di Master Data Kelas.'
                        : 'Belum ada kelas tersedia, silakan buat di Master Data Kelas.')
                    : 'Kelas difilter berdasarkan prodi, angkatan, dan tahun akademik.',
            ],
        ]);
    }

}
