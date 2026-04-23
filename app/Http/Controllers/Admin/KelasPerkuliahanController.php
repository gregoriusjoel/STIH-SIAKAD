<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\KelasPerkuliahanRequest;
use App\Models\KelasPerkuliahan;
use App\Models\Prodi;
use App\Models\Semester;
use App\Services\KelasPerkuliahanService;
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
            ->orderBy('tingkat')
            ->orderBy('kode_prodi')
            ->orderBy('kode_kelas');

        // Filters
        if ($request->filled('prodi_id')) {
            $query->where('prodi_id', $request->prodi_id);
        }

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
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

        return view('admin.kelas-perkuliahan.create', compact('prodis', 'semesters'));
    }

    /**
     * Store a newly created kelas perkuliahan.
     * Uses Service Layer with firstOrCreate to prevent duplicates.
     */
    public function store(KelasPerkuliahanRequest $request)
    {
        $kp = KelasPerkuliahan::create($request->validated());

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

        return view('admin.kelas-perkuliahan.edit', compact(
            'kelasPerkuliahan',
            'prodis',
            'semesters'
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
     * Soft delete the specified kelas perkuliahan.
     */
    public function destroy(KelasPerkuliahan $kelasPerkuliahan)
    {
        $namaKelas = $kelasPerkuliahan->nama_kelas;
        $kelasPerkuliahan->delete();

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
                'tahun_akademik_id'         => 'nullable|exists:semesters,id',
                'max_tingkat'               => 'required|integer|min:1|max:8',
                'max_students_per_class'    => 'required|integer|min:1|max:100',
                'overwrite'                 => 'nullable|boolean',
            ]);

            // Validate siswa per tingkat
            $maxTingkat = $request->integer('max_tingkat');
            $siswaPerTingkat = [];
            for ($i = 1; $i <= $maxTingkat; $i++) {
                $siswaPerTingkat[$i] = $request->integer("siswa_tingkat_{$i}", 0);
            }

            $maxPerKelas = $request->integer('max_students_per_class');
            $overwrite = $request->boolean('overwrite', false);

            // Calculate classes per level based on student count
            $result = $overwrite 
                ? $this->service->generateForProdiWithOverwritePerLevel(
                    $request->prodi_id,
                    $request->tahun_akademik_id,
                    $siswaPerTingkat,
                    $maxPerKelas
                )
                : $this->service->generateForProdiPerLevel(
                    $request->prodi_id,
                    $request->tahun_akademik_id,
                    $siswaPerTingkat,
                    $maxPerKelas
                );

            $createdCount = $result['created']->count();

            if ($overwrite) {
                $deletedCount = $result['deleted'];
                $message = "Berhasil menimpa data. Dihapus: {$deletedCount}, dibuat ulang: {$createdCount} Kelas Perkuliahan.";
            } else {
                $skipped = $result['skipped'];
                $message = "Berhasil generate {$createdCount} Kelas Perkuliahan baru.";
                if ($skipped > 0) {
                    $message .= " {$skipped} data sudah ada sebelumnya (dilewati).";
                }
            }
        } else {
            $request->validate([
                'prodi_id'          => 'required|exists:prodis,id',
                'tahun_akademik_id' => 'nullable|exists:semesters,id',
                'max_tingkat'       => 'required|integer|min:1|max:8',
                'kelas_per_tingkat' => 'required|integer|min:1|max:10',
                'overwrite'         => 'nullable|boolean',
            ]);

            $kelasPerTingkat = $request->integer('kelas_per_tingkat');
            $maxTingkat = $request->integer('max_tingkat');
            $overwrite = $request->boolean('overwrite', false);

            $result = $overwrite
                ? $this->service->generateForProdiWithOverwrite(
                    $request->prodi_id,
                    $request->tahun_akademik_id,
                    $maxTingkat,
                    $kelasPerTingkat
                )
                : $this->service->generateForProdi(
                    $request->prodi_id,
                    $request->tahun_akademik_id,
                    $maxTingkat,
                    $kelasPerTingkat
                );

            $createdCount = $result['created']->count();

            if ($overwrite) {
                $deletedCount = $result['deleted'];
                $message = "Berhasil menimpa data. Dihapus: {$deletedCount}, dibuat ulang: {$createdCount} Kelas Perkuliahan.";
            } else {
                $skipped = $result['skipped'];
                $message = "Berhasil generate {$createdCount} Kelas Perkuliahan baru.";
                if ($skipped > 0) {
                    $message .= " {$skipped} data sudah ada sebelumnya (dilewati).";
                }
            }
        }

        return redirect()
            ->route('admin.kelas-perkuliahan.index')
            ->with('success', $message);
    }

}
