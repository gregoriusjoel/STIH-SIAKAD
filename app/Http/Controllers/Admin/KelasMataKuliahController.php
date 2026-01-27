<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelasMataKuliah;
use App\Models\MataKuliah;
use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KelasMataKuliahController extends Controller
{
    public function index()
    {
        $kelasMatKul = KelasMataKuliah::with(['mataKuliah', 'dosen.user', 'semester'])->paginate(10);
        return view('admin.kelas-mata-kuliah.index', compact('kelasMatKul'));
    }

    public function create()
    {
        $mataKuliahs = MataKuliah::all();
        $dosens = Dosen::with('user')->get();
        $semesters = Semester::where('status', 'aktif')->get();
        return view('admin.kelas-mata-kuliah.create', compact('mataKuliahs', 'dosens', 'semesters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'required|exists:dosens,id',
            'nama_kelas' => 'required|string|max:10',
            'kuota' => 'required|integer|min:1',
            'ruangan' => 'nullable|string|max:50',
            'hari' => 'nullable|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i',
        ]);

        // Get semester from mata_kuliah
        $mataKuliah = MataKuliah::findOrFail($request->mata_kuliah_id);
        $semesterNumber = $mataKuliah->semester; // 1-8
        $semesterName = ($semesterNumber % 2 == 1) ? 'Ganjil' : 'Genap';
        
        // Find or create semester based on mata_kuliah semester
        $semester = Semester::where('nama_semester', 'like', '%' . $semesterName . '%')
            ->where('status', 'aktif')
            ->first();
        
        if (!$semester) {
            $semester = Semester::where('nama_semester', 'like', '%' . $semesterName . '%')->first();
        }
        
        // Create semester with correct type if not found
        if (!$semester) {
            $semester = Semester::create([
                'nama_semester' => $semesterName,
                'tahun_ajaran' => date('Y') . '/' . (date('Y') + 1),
                'status' => 'aktif',
                'tanggal_mulai' => now(),
                'tanggal_selesai' => now()->addMonths(6),
            ]);
        }

        $data = $request->only(['mata_kuliah_id', 'dosen_id', 'nama_kelas', 'kuota', 'ruangan', 'hari', 'jam_mulai', 'jam_selesai']);
        // Map form field names to database column names
        $mapped = [
            'mata_kuliah_id' => $data['mata_kuliah_id'],
            'dosen_id' => $data['dosen_id'],
            'semester_id' => $semester ? $semester->id : null,
            'kode_kelas' => $data['nama_kelas'],
            'kapasitas' => $data['kuota'],
            'ruang' => $data['ruangan'] ?? null,
            'hari' => $data['hari'] ?? null,
            'jam_mulai' => $data['jam_mulai'] ?? null,
            'jam_selesai' => $data['jam_selesai'] ?? null,
            'qr_enabled' => $request->has('qr_enabled') ? true : false,
            'qr_expires_at' => $request->qr_expires_at ?? null,
        ];

        $created = KelasMataKuliah::create($mapped);

        // Ensure a QR token exists for this class
        if (empty($created->qr_token)) {
            $created->qr_token = Str::random(40);
            $created->save();
        }
        // If schedule fields provided, create/find a corresponding `Kelas` (table `kelas`) and create Jadwal linked to it
        if (!empty($mapped['hari']) && !empty($mapped['jam_mulai']) && !empty($mapped['jam_selesai'])) {
            // Map dosen_id (dosens table) -> user id for Kelas.dosen_id (users table)
            $dosen = Dosen::find($mapped['dosen_id']);
            $userDosenId = $dosen->user_id ?? null;

            // Determine tahun_ajaran and semester_type from $semester if available
            $tahunAjaran = $semester->tahun_ajaran ?? (date('Y') . '/' . (date('Y') + 1));
            $semesterType = $semester->nama_semester ?? null;

            // Try to find existing Kelas (kelas table) matching mata_kuliah_id + section
            $kelasForJadwal = Kelas::where('mata_kuliah_id', $mapped['mata_kuliah_id'])
                ->where('section', $mapped['kode_kelas'])
                ->first();

            if (!$kelasForJadwal) {
                $kelasForJadwal = Kelas::create([
                    'mata_kuliah_id' => $mapped['mata_kuliah_id'],
                    'dosen_id' => $userDosenId,
                    'section' => $mapped['kode_kelas'],
                    'kapasitas' => $mapped['kapasitas'] ?? 40,
                    'tahun_ajaran' => $tahunAjaran,
                    'semester_type' => $semesterType ?? 'Ganjil',
                ]);
            }

            Jadwal::create([
                'kelas_id' => $kelasForJadwal->id,
                'hari' => $mapped['hari'],
                'jam_mulai' => $mapped['jam_mulai'],
                'jam_selesai' => $mapped['jam_selesai'],
                'ruangan' => $mapped['ruang'] ?? null,
                'status' => 'active',
            ]);
        }
        return redirect()->route('admin.jadwal.index')->with('success', 'Kelas mata kuliah berhasil ditambahkan');
    }

    public function edit(KelasMataKuliah $kelasMataKuliah)
    {
        $mataKuliahs = MataKuliah::all();
        $dosens = Dosen::with('user')->get();
        $semesters = Semester::all();
        
        // Generate room list R.100 - R.199
        $daftarRuangan = collect(range(100, 199))->map(fn($n) => 'R.' . $n);
        
        return view('admin.jadwal.edit', compact('kelasMataKuliah', 'mataKuliahs', 'dosens', 'semesters', 'daftarRuangan'));
    }

    public function update(Request $request, KelasMataKuliah $kelasMataKuliah)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'required|exists:dosens,id',
            'nama_kelas' => 'required|string|max:10',
            'kuota' => 'required|integer|min:1',
            'ruangan' => 'nullable|string|max:50',
            'hari' => 'nullable|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i',
        ]);

        // Get semester from mata_kuliah
        $mataKuliah = MataKuliah::findOrFail($request->mata_kuliah_id);
        $semester = Semester::where('status', 'aktif')->first();

        $data = $request->only(['mata_kuliah_id', 'dosen_id', 'nama_kelas', 'kuota', 'ruangan', 'hari', 'jam_mulai', 'jam_selesai']);
        $mapped = [
            'mata_kuliah_id' => $data['mata_kuliah_id'],
            'dosen_id' => $data['dosen_id'],
            'semester_id' => $semester ? $semester->id : $kelasMataKuliah->semester_id,
            'kode_kelas' => $data['nama_kelas'],
            'kapasitas' => $data['kuota'],
            'ruang' => $data['ruangan'] ?? null,
            'hari' => $data['hari'] ?? null,
            'jam_mulai' => $data['jam_mulai'] ?? null,
            'jam_selesai' => $data['jam_selesai'] ?? null,
            'qr_enabled' => $request->has('qr_enabled') ? true : false,
            'qr_expires_at' => $request->qr_expires_at ?? null,
        ];

        $kelasMataKuliah->update($mapped);

        // Ensure QR token exists after update
        if (empty($kelasMataKuliah->qr_token)) {
            $kelasMataKuliah->qr_token = Str::random(40);
            $kelasMataKuliah->save();
        }
        // Update or create jadwal for this kelas if schedule fields provided
        if (!empty($mapped['hari']) && !empty($mapped['jam_mulai']) && !empty($mapped['jam_selesai'])) {
            // Map dosen_id -> user id
            $dosen = Dosen::find($mapped['dosen_id']);
            $userDosenId = $dosen->user_id ?? null;
            $tahunAjaran = $semester ? ($semester->tahun_ajaran ?? (date('Y') . '/' . (date('Y') + 1))) : (date('Y') . '/' . (date('Y') + 1));
            $semesterType = $semester ? ($semester->nama_semester ?? null) : null;

            // Find or create kelas (kelas table)
            $kelasForJadwal = Kelas::where('mata_kuliah_id', $mapped['mata_kuliah_id'])
                ->where('section', $mapped['kode_kelas'])
                ->first();

            if (!$kelasForJadwal) {
                $kelasForJadwal = Kelas::create([
                    'mata_kuliah_id' => $mapped['mata_kuliah_id'],
                    'dosen_id' => $userDosenId,
                    'section' => $mapped['kode_kelas'],
                    'kapasitas' => $mapped['kapasitas'] ?? 40,
                    'tahun_ajaran' => $tahunAjaran,
                    'semester_type' => $semesterType ?? 'Ganjil',
                ]);
            }

            $existingJadwal = Jadwal::where('kelas_id', $kelasForJadwal->id)->first();
            $jadwalData = [
                'kelas_id' => $kelasForJadwal->id,
                'hari' => $mapped['hari'],
                'jam_mulai' => $mapped['jam_mulai'],
                'jam_selesai' => $mapped['jam_selesai'],
                'ruangan' => $mapped['ruang'] ?? null,
                'status' => 'active',
            ];

            if ($existingJadwal) {
                $existingJadwal->update($jadwalData);
            } else {
                Jadwal::create($jadwalData);
            }
        }
        return redirect()->route('admin.jadwal.index')->with('success', 'Kelas mata kuliah berhasil diperbarui');
    }

    public function destroy(KelasMataKuliah $kelasMataKuliah)
    {
        $kelasMataKuliah->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Kelas mata kuliah berhasil dihapus');
    }
}
