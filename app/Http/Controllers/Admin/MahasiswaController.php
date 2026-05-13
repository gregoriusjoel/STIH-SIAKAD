<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpsertMahasiswaRequest;
use App\Models\KelasPerkuliahan;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Services\FileStorageService;
use App\Services\MahasiswaClassAssignmentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    public function __construct(
        private FileStorageService $storage,
        private MahasiswaClassAssignmentService $assignmentService
    ) {
    }

    public function index()
    {
        $mahasiswas = Mahasiswa::with([
            'user',
            'kelasPerkuliahan',
            'latestSubmittedKrs.kelas',
        ])->orderByDesc('id')->paginate(25);

        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    public function create()
    {
        $prodis = Prodi::where('status', 'aktif')->orderBy('nama_prodi')->get();
        $activeTahunAkademik = $this->assignmentService->getActiveAcademicYear();
        $selectedKelasPerkuliahan = $this->resolveSelectedClass(old('kelas_perkuliahan_id'));

        return view('admin.mahasiswa.create', compact(
            'prodis',
            'activeTahunAkademik',
            'selectedKelasPerkuliahan'
        ));
    }

    public function store(UpsertMahasiswaRequest $request)
    {
        $validated = $request->validated();
        $cleanName = $this->sanitizeName($validated['name']);
        $plainPassword = $validated['password'] ?? 'mahasiswa123';

        DB::transaction(function () use ($validated, $cleanName, $plainPassword) {
            $user = \App\Models\User::create([
                'name' => $cleanName,
                'email' => $validated['email_kampus'],
                'password' => Hash::make($plainPassword),
                'role' => 'mahasiswa',
            ]);

            $mahasiswaData = $this->assignmentService->prepareMahasiswaPayload($validated);
            $mahasiswaData['user_id'] = $user->id;
            $mahasiswaData['status_akun'] = 'baru';

            $mahasiswa = Mahasiswa::create($mahasiswaData);
            $mahasiswa->load(['user', 'kelasPerkuliahan.prodi', 'kelasPerkuliahan.tahunAkademik']);

            $this->assignmentService->logClassAssignmentChange(
                $mahasiswa,
                null,
                $mahasiswa->kelasPerkuliahan,
                'mahasiswa.class_assignment_created'
            );
        });

        return redirect()
            ->route('admin.mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan. Login menggunakan Email Kampus.');
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $prodis = Prodi::where('status', 'aktif')->orderBy('nama_prodi')->get();
        $activeTahunAkademik = $this->assignmentService->getActiveAcademicYear();
        $selectedKelasPerkuliahan = $this->resolveSelectedClass(
            old('kelas_perkuliahan_id', $mahasiswa->kelas_perkuliahan_id)
        );

        return view('admin.mahasiswa.edit', compact(
            'mahasiswa',
            'prodis',
            'activeTahunAkademik',
            'selectedKelasPerkuliahan'
        ));
    }

    public function update(UpsertMahasiswaRequest $request, Mahasiswa $mahasiswa)
    {
        $validated = $request->validated();
        $cleanName = $this->sanitizeName($validated['name']);

        DB::transaction(function () use ($validated, $cleanName, $mahasiswa) {
            $user = $mahasiswa->user;
            $user->name = $cleanName;
            $user->email = $validated['email_kampus'];

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            $beforeClass = $mahasiswa->kelasPerkuliahan()->with(['prodi', 'tahunAkademik'])->first();
            $mahasiswaData = $this->assignmentService->prepareMahasiswaPayload($validated, $mahasiswa);
            $mahasiswa->fill($mahasiswaData);
            $mahasiswa->save();
            $mahasiswa->load(['user', 'kelasPerkuliahan.prodi', 'kelasPerkuliahan.tahunAkademik']);

            $this->assignmentService->logClassAssignmentChange(
                $mahasiswa,
                $beforeClass,
                $mahasiswa->kelasPerkuliahan
            );
        });

        return redirect()
            ->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui. Login menggunakan Email Kampus.');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        try {
            if ($mahasiswa->foto) {
                $this->storage->delete($mahasiswa->foto);
            }

            $documentTypes = ['file_ijazah', 'file_transkrip', 'file_kk', 'file_ktp'];
            foreach ($documentTypes as $docType) {
                $files = $mahasiswa->$docType;
                if (!empty($files) && is_array($files)) {
                    foreach ($files as $file) {
                        $this->storage->delete($file);
                    }
                }
            }

            $this->storage->deleteDirectory('documents/mahasiswa/' . $mahasiswa->storage_folder);

            if ($mahasiswa->user) {
                $mahasiswa->user->delete();
            } else {
                $mahasiswa->delete();
            }

            return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus permanen.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function toggleDokumen(Mahasiswa $mahasiswa)
    {
        $mahasiswa->is_dokumen_unlocked = !$mahasiswa->is_dokumen_unlocked;
        $mahasiswa->save();

        $status = $mahasiswa->is_dokumen_unlocked ? 'dibuka' : 'dikunci';

        return back()->with('success', "Akses upload dokumen mahasiswa berhasil {$status}.");
    }

    protected function sanitizeName(string $name): string
    {
        $cleanName = preg_replace('/[^a-zA-Z\s]/u', '', $name);
        $cleanName = preg_replace('/\s+/', ' ', (string) $cleanName);

        return trim((string) $cleanName);
    }

    protected function resolveSelectedClass(?string $kelasPerkuliahanId): ?KelasPerkuliahan
    {
        if (!$kelasPerkuliahanId) {
            return null;
        }

        return KelasPerkuliahan::with(['prodi', 'tahunAkademik'])->find($kelasPerkuliahanId);
    }
}
