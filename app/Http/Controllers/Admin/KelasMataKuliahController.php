<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelasMataKuliah;
use App\Models\MataKuliah;
use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\JamPerkuliahan;
use App\Models\Kelas;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KelasMataKuliahController extends Controller
{
    public function index()
    {
        // Get active schedules from Jadwal -> Kelas
        $activeJadwals = \App\Models\Jadwal::where('status', 'active')
            ->whereNotNull('kelas_id')
            ->with('kelas')
            ->get();
            
        $activeKelas = $activeJadwals->pluck('kelas')->filter();

        // Build normalized combination keys: mata_kuliah_id + section (lowercase, trimmed)
        $activeCombinations = $activeKelas->map(function ($k) {
            $section = strtolower(trim((string) $k->section));
            return $k->mata_kuliah_id . '-' . $section;
        })->unique()->toArray();

        // Show classes that have active jadwal mapping OR own schedule fields.
        // Do not hard-filter by active semester to avoid hiding valid classes.
        $kelasMatKul = KelasMataKuliah::with(['mataKuliah', 'dosen.user', 'semester'])
            ->get()
            ->map(function ($kmk) use ($activeJadwals, $activeCombinations) {
                $kmkClassCode = strtolower(trim((string) $kmk->kode_kelas));

                // Check if there is a matching Jadwal for this MK
                $hasActiveJadwal = in_array($kmk->mata_kuliah_id . '-' . $kmkClassCode, $activeCombinations, true);

                if (!$hasActiveJadwal) {
                    return null; // Skip if no schedule at all
                }

                // Find matching Jadwal for this MK and Class code mapping back via Kelas
                $matchingJadwal = $activeJadwals->first(function ($jadwal) use ($kmk) {
                      $jadwalSection = strtolower(trim((string) ($jadwal->kelas->section ?? '')));
                      $kmkClassCode = strtolower(trim((string) $kmk->kode_kelas));

                    return $jadwal->kelas && 
                           $jadwal->kelas->mata_kuliah_id == $kmk->mata_kuliah_id && 
                          $jadwalSection === $kmkClassCode;
                });
                
                $kmk->jadwal = $matchingJadwal;
                return $kmk;
            })
            ->filter(); // Remove nulls
            
        // Manually paginate the collection
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 10;
        $kelasMatKul = new \Illuminate\Pagination\LengthAwarePaginator(
            $kelasMatKul->forPage($page, $perPage),
            $kelasMatKul->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
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
            'kapasitas' => 'required|integer|min:1',
            'ruangan_id' => 'nullable|exists:ruangans,id',
            'hari' => 'nullable|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable',
            'jam_perkuliahan_id' => 'nullable|exists:jam_perkuliahan,id',
        ]);

        // Resolve jam_mulai / jam_selesai from jam_perkuliahan_id if not provided directly
        if ($request->jam_perkuliahan_id && (!$request->jam_mulai || !$request->jam_selesai)) {
            $jamPerkuliahan = JamPerkuliahan::find($request->jam_perkuliahan_id);
            if ($jamPerkuliahan) {
                $request->merge([
                    'jam_mulai' => substr($jamPerkuliahan->jam_mulai, 0, 5),
                    'jam_selesai' => substr($jamPerkuliahan->jam_selesai, 0, 5),
                ]);
            }
        }

        // Normalize jam_mulai / jam_selesai to H:i format (strip seconds if present)
        if ($request->jam_mulai && strlen($request->jam_mulai) > 5) {
            $request->merge(['jam_mulai' => substr($request->jam_mulai, 0, 5)]);
        }
        if ($request->jam_selesai && strlen($request->jam_selesai) > 5) {
            $request->merge(['jam_selesai' => substr($request->jam_selesai, 0, 5)]);
        }

        // Server-side schedule conflict check: same room + day + overlapping time
        if ($request->hari && $request->jam_mulai && $request->jam_selesai && $request->ruangan_id) {
            $ruanganObj = \App\Models\Ruangan::find($request->ruangan_id);
            $ruanganKode = $ruanganObj ? $ruanganObj->kode_ruangan : null;
            $mulai = $request->jam_mulai;
            $selesai = $request->jam_selesai;

            // 1. Check KelasMataKuliah table
            $conflict = KelasMataKuliah::where('hari', $request->hari)
                ->where(function($q) use ($ruanganKode, $request) {
                    $q->where('ruang', $ruanganKode);
                    if ($request->ruangan_id) {
                        $q->orWhere('ruangan_id', $request->ruangan_id);
                    }
                })
                ->where(function ($q) use ($mulai, $selesai) {
                    $q->where('jam_mulai', '<', $selesai)
                        ->where('jam_selesai', '>', $mulai);
                })
                ->with(['mataKuliah', 'dosen.user'])
                ->first();

            if ($conflict) {
                $conflictMsg = 'Jadwal bentrok! Ruangan ' . ($ruanganKode ?? '-') . ' sudah dipakai oleh ' .
                    ($conflict->dosen->user->name ?? 'Dosen') .
                    ' (' . ($conflict->mataKuliah->nama_mk ?? '-') . ') ' .
                    'pada ' . $conflict->hari . ' pukul ' . substr($conflict->jam_mulai, 0, 5) . '-' . substr($conflict->jam_selesai, 0, 5);

                return redirect()->back()->withInput()->with('error', $conflictMsg);
            }

            // 2. Check Jadwal table (active schedules)
            $jadwalConflict = Jadwal::where('hari', $request->hari)
                ->where('ruangan', $ruanganKode)
                ->where('status', 'active')
                ->where(function ($q) use ($mulai, $selesai) {
                    $q->where('jam_mulai', '<', $selesai)
                        ->where('jam_selesai', '>', $mulai);
                })
                ->with(['kelas.mataKuliah', 'kelas.dosen'])
                ->first();

            if ($jadwalConflict) {
                $dosenName = $jadwalConflict->kelas->dosen->name ?? 'Dosen';
                $mkName = $jadwalConflict->kelas->mataKuliah->nama_mk ?? '-';
                $conflictMsg = 'Jadwal bentrok! Ruangan ' . ($ruanganKode ?? '-') . ' sudah dipakai oleh ' .
                    $dosenName . ' (' . $mkName . ') ' .
                    'pada ' . $request->hari . ' pukul ' . substr($jadwalConflict->jam_mulai, 0, 5) . '-' . substr($jadwalConflict->jam_selesai, 0, 5);

                return redirect()->back()->withInput()->with('error', $conflictMsg);
            }

            // 3. Check JadwalProposal table (approved/pending proposals)
            $proposalConflict = \App\Models\JadwalProposal::where('hari', $request->hari)
                ->where(function($q) use ($ruanganKode, $request) {
                    $q->where('ruangan', $ruanganKode);
                    if ($request->ruangan_id) {
                        $q->orWhere('ruangan_id', $request->ruangan_id);
                    }
                })
                ->whereIn('status', ['approved_dosen', 'approved_admin', 'pending_admin'])
                ->where(function ($q) use ($mulai, $selesai) {
                    $q->where('jam_mulai', '<', $selesai)
                        ->where('jam_selesai', '>', $mulai);
                })
                ->with(['mataKuliah', 'dosen.user'])
                ->first();

            if ($proposalConflict) {
                $dosenName = $proposalConflict->dosen->user->name ?? 'Dosen';
                $mkName = $proposalConflict->mataKuliah->nama_mk ?? '-';
                $conflictMsg = 'Jadwal bentrok! Ruangan ' . ($ruanganKode ?? '-') . ' sudah dipakai oleh ' .
                    $dosenName . ' (' . $mkName . ') ' .
                    'pada ' . $request->hari . ' pukul ' . substr($proposalConflict->jam_mulai, 0, 5) . '-' . substr($proposalConflict->jam_selesai, 0, 5) .
                    ' (dari proposal jadwal)';

                return redirect()->back()->withInput()->with('error', $conflictMsg);
            }
        }

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

        $data = $request->only(['mata_kuliah_id', 'dosen_id', 'nama_kelas', 'kapasitas', 'ruangan_id', 'hari', 'jam_mulai', 'jam_selesai']);
        
        // Get ruangan name from ruangan_id for backward compatibility
        $ruanganName = null;
        if ($data['ruangan_id']) {
            $ruangan = \App\Models\Ruangan::find($data['ruangan_id']);
            $ruanganName = $ruangan ? $ruangan->kode_ruangan : null;
        }
        
        // Map form field names to database column names
        $mapped = [
            'mata_kuliah_id' => $data['mata_kuliah_id'],
            'dosen_id' => $data['dosen_id'],
            'semester_id' => $semester ? $semester->id : null,
            'kode_kelas' => $data['nama_kelas'],
            'kapasitas' => $data['kapasitas'],
            'ruang' => $ruanganName,
            'ruangan_id' => $data['ruangan_id'],
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
        
        // Get actual ruangan data from database
        $daftarRuangan = \App\Models\Ruangan::where('status', 'aktif')->orderBy('kode_ruangan')->get();
        
        return view('admin.kelas-mata-kuliah.edit', compact('kelasMataKuliah', 'mataKuliahs', 'dosens', 'semesters', 'daftarRuangan'));
    }

    public function update(Request $request, KelasMataKuliah $kelasMataKuliah)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'required|exists:dosens,id',
            'nama_kelas' => 'required|string|max:10',
            'kapasitas' => 'required|integer|min:1',
            'ruangan_id' => 'nullable|exists:ruangans,id',
            'hari' => 'nullable|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i',
        ]);

        // Get semester from mata_kuliah
        $mataKuliah = MataKuliah::findOrFail($request->mata_kuliah_id);
        $semester = Semester::where('status', 'aktif')->first();

        $data = $request->only(['mata_kuliah_id', 'dosen_id', 'nama_kelas', 'kapasitas', 'ruangan_id', 'hari', 'jam_mulai', 'jam_selesai']);
        
        // Get ruangan name from ruangan_id for backward compatibility
        $ruanganName = null;
        if ($data['ruangan_id']) {
            $ruangan = \App\Models\Ruangan::find($data['ruangan_id']);
            $ruanganName = $ruangan ? $ruangan->kode_ruangan : null;
        }
        
        $mapped = [
            'mata_kuliah_id' => $data['mata_kuliah_id'],
            'dosen_id' => $data['dosen_id'],
            'semester_id' => $semester ? $semester->id : $kelasMataKuliah->semester_id,
            'kode_kelas' => $data['nama_kelas'],
            'kapasitas' => $data['kapasitas'],
            'ruang' => $ruanganName,
            'ruangan_id' => $data['ruangan_id'],
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
        // Find and delete related Kelas and Jadwal records
        // This ensures the class no longer appears in lecturer's "Daftar Kelas Ajar"
        $relatedKelas = Kelas::where('mata_kuliah_id', $kelasMataKuliah->mata_kuliah_id)
            ->where('section', $kelasMataKuliah->kode_kelas)
            ->first();
        
        if ($relatedKelas) {
            // Delete all jadwal records for this kelas
            Jadwal::where('kelas_id', $relatedKelas->id)->delete();
            
            // Delete the kelas record
            $relatedKelas->delete();
        }
        
        // Finally delete the KelasMataKuliah record
        $kelasMataKuliah->delete();
        
        return redirect()->route('admin.jadwal.index')->with('success', 'Kelas mata kuliah berhasil dihapus');
    }

    /**
     * Get attendance data for AJAX polling
     */
    public function getAttendanceData($id)
    {
        $kelasMataKuliah = KelasMataKuliah::with(['mataKuliah', 'ruangan', 'semester'])->findOrFail($id);
        
        // Support new tipe+nomor format and legacy integer format
        $tipe = request('tipe', 'kuliah');
        $nomor = (int) request('nomor', request('pertemuan', 1));
        
        // Convert tipe+nomor to a slot number for presensi backward compat
        $resolver = app(\App\Services\ActiveMeetingResolver::class);
        $slotNumber = $resolver->tipeNomorToSlot($tipe, $nomor);

        // Get pertemuan detail (try tipe+nomor first, fallback to slot number)
        $pertemuan = \App\Models\Pertemuan::where('kelas_mata_kuliah_id', $id)
            ->where('tipe_pertemuan', $tipe)
            ->where('nomor_pertemuan', $nomor)
            ->first();
        
        if (!$pertemuan) {
            // Legacy fallback: try by slot number
            $pertemuan = \App\Models\Pertemuan::where('kelas_mata_kuliah_id', $id)
                ->where('nomor_pertemuan', $slotNumber)
                ->whereNull('tipe_pertemuan')
                ->orWhere(function ($q) use ($id, $slotNumber) {
                    $q->where('kelas_mata_kuliah_id', $id)
                      ->where('nomor_pertemuan', $slotNumber)
                      ->where('tipe_pertemuan', 'kuliah');
                })
                ->first();
        }

        // Fallback Date Calculation (if no pertemuan record)
        // Priority: 1. stored pertemuan.tanggal
        //           2. UTS/UAS → start date from Kalender Akademik (academic_events)
        //           3. Kuliah → perkuliahan period start + class weekday + week offset
        $tanggal = $pertemuan ? ($pertemuan->tanggal ? $pertemuan->tanggal->format('Y-m-d') : null) : null;
        if (!$tanggal) {
            $periodService = app(\App\Services\AcademicPeriodService::class);

            // UTS/UAS: use date from Kalender Akademik
            if ($tipe === 'uts') {
                $range = $periodService->getDateRange(\App\Services\AcademicPeriodService::TYPE_UTS);
                if ($range) $tanggal = $range['start']->format('Y-m-d');
            } elseif ($tipe === 'uas') {
                $range = $periodService->getDateRange(\App\Services\AcademicPeriodService::TYPE_UAS);
                if ($range) $tanggal = $range['start']->format('Y-m-d');
            }

            // Kuliah: calculate from perkuliahan period start + class weekday
            if (!$tanggal && $kelasMataKuliah->hari) {
                try {
                    $perkuliahanRange = $periodService->getDateRange(\App\Services\AcademicPeriodService::TYPE_PERKULIAHAN);
                    if ($perkuliahanRange) {
                        $start = $perkuliahanRange['start'];
                    } else {
                        $sem = $kelasMataKuliah->semester
                            ?? \App\Models\Semester::where('status', 'aktif')->first()
                            ?? \App\Models\Semester::where('is_active', true)->first();
                        $start = $sem?->tanggal_mulai ? \Carbon\Carbon::parse($sem->tanggal_mulai) : null;
                    }

                    if ($start) {
                        $dayMap = ['Minggu' => 0, 'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6];
                        $targetDay = $dayMap[$kelasMataKuliah->hari] ?? null;
                        if ($targetDay !== null) {
                            $firstOccurrence = $start->copy();
                            while ($firstOccurrence->dayOfWeek !== $targetDay) { $firstOccurrence->addDay(); }
                            // slotNumber gives correct week offset including UTS/UAS gaps
                            $tanggal = $firstOccurrence->addWeeks($slotNumber - 1)->format('Y-m-d');
                        }
                    }
                } catch (\Exception $e) { $tanggal = null; }
            }
        }

        // Meeting label
        $meetingLabel = $resolver->labelFor($tipe, $nomor);

        // Fallback Topic (from Materi table)
        $topik = $pertemuan->topik ?? null;
        if (!$topik) {
            $materi = \App\Models\Materi::where('mata_kuliah_id', $kelasMataKuliah->mata_kuliah_id)
                ->where('pertemuan', $slotNumber)
                ->first();
            $topik = $materi->judul ?? '-';
        }

        // Get students from KRS (checking both specific KMK and generic Kelas)
        $students = \App\Models\Krs::where(function($q) use ($id, $kelasMataKuliah) {
                $q->where('kelas_mata_kuliah_id', $id);
                // Fallback to finding students linked to any "Kelas" that matches this MK and section
                $kelasId = \App\Models\Kelas::where('mata_kuliah_id', $kelasMataKuliah->mata_kuliah_id)
                    ->where('section', $kelasMataKuliah->kode_kelas)
                    ->value('id');
                if ($kelasId) {
                    $q->orWhere('kelas_id', $kelasId);
                }
            })
            ->with(['mahasiswa.user'])
            ->get();

        // Get attendance records for this meeting (use slot number for backward compat)
        $presensis = \App\Models\Presensi::where('kelas_mata_kuliah_id', $id)
            ->where('pertemuan', $slotNumber)
            ->get()
            ->keyBy('mahasiswa_id');

        $formattedStudents = $students->map(function ($krs, $index) use ($presensis) {
            $isInternship = (bool) $krs->is_internship_conversion;

            // Magang conversion students are always HADIR automatically
            if ($isInternship) {
                return [
                    'no' => $index + 1,
                    'nama' => $krs->mahasiswa->user->name ?? $krs->mahasiswa->nama ?? '-',
                    'nim' => $krs->mahasiswa->nim ?? '-',
                    'status' => 'hadir',
                    'waktu_scan' => 'Otomatis (Magang)',
                    'distance_meters' => null,
                    'presence_mode' => 'internship',
                    'is_internship' => true,
                ];
            }

            $presensi = $presensis->get($krs->mahasiswa_id);
            return [
                'no' => $index + 1,
                'nama' => $krs->mahasiswa->user->name ?? $krs->mahasiswa->nama ?? '-',
                'nim' => $krs->mahasiswa->nim ?? '-',
                'status' => $presensi ? ($presensi->status ?? 'hadir') : 'tidak hadir',
                'waktu_scan' => $presensi && $presensi->created_at ? ($presensi->created_at->format('H:i') . ' WIB') : '-',
                'distance_meters' => $presensi ? $presensi->distance_meters : null,
                'presence_mode' => $presensi ? $presensi->presence_mode : null,
                'is_internship' => false,
            ];
        });

        return response()->json([
            'pertemuan' => [
                'id' => $pertemuan?->id,
                'tanggal' => $tanggal,
                'tipe' => $tipe,
                'nomor' => $nomor,
                'label' => $meetingLabel,
                'online_meeting_link' => $pertemuan?->online_meeting_link ?? null,
            ],
            'jadwal' => [
                'jam_mulai' => $kelasMataKuliah->jam_mulai ? substr($kelasMataKuliah->jam_mulai, 0, 5) : '-',
                'jam_selesai' => $kelasMataKuliah->jam_selesai ? substr($kelasMataKuliah->jam_selesai, 0, 5) : '-',
                'ruangan' => $kelasMataKuliah->ruangan->kode_ruangan ?? $kelasMataKuliah->ruang ?? '-',
            ],
            'materi_topik' => $topik,
            'students' => $formattedStudents,
            'total_students' => $students->count(),
            'total_hadir' => $formattedStudents->where('status', 'hadir')->count(),
            'total_tidak_hadir' => $formattedStudents->where('status', 'tidak hadir')->count(),
        ]);
    }

    /**
     * Update online meeting link for a pertemuan
     */
    public function updateOnlineMeetingLink($id)
    {
        $validated = request()->validate([
            'pertemuan_id' => 'required|integer',
            'online_meeting_link' => 'nullable|url',
        ]);

        $pertemuan = \App\Models\Pertemuan::where('id', $validated['pertemuan_id'])
            ->where('kelas_mata_kuliah_id', $id)
            ->first();

        if (!$pertemuan) {
            return response()->json(['error' => 'Pertemuan tidak ditemukan'], 404);
        }

        $pertemuan->update([
            'online_meeting_link' => $validated['online_meeting_link'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Link zoom berhasil diperbarui',
            'online_meeting_link' => $pertemuan->online_meeting_link,
        ]);
    }

    /**
     * Get all pertemuan links for a kelas_mata_kuliah
     */
    public function getAllPertemuanLinks($id)
    {
        $kelasMataKuliah = KelasMataKuliah::findOrFail($id);

        $pertemuans = \App\Models\Pertemuan::where('kelas_mata_kuliah_id', $id)
            ->orderBy('tipe_pertemuan')
            ->orderBy('nomor_pertemuan')
            ->get()
            ->map(function ($pertemuan) {
                return [
                    'id' => $pertemuan->id,
                    'tipe' => $pertemuan->tipe_pertemuan ?? 'kuliah',
                    'nomor' => $pertemuan->nomor_pertemuan,
                    'label' => $this->getMeetingLabel($pertemuan->tipe_pertemuan ?? 'kuliah', $pertemuan->nomor_pertemuan),
                    'tanggal' => $pertemuan->tanggal ? $pertemuan->tanggal->format('d M Y') : '-',
                    'topik' => $pertemuan->topik ?? '-',
                    'online_meeting_link' => $pertemuan->online_meeting_link,
                ];
            });

        return response()->json([
            'kelas' => [
                'id' => $kelasMataKuliah->id,
                'nama' => $kelasMataKuliah->nama_kelas,
                'mata_kuliah' => $kelasMataKuliah->mataKuliah->nama_mk,
                'online_meeting_link' => $kelasMataKuliah->online_meeting_link,
            ],
            'pertemuans' => $pertemuans,
        ]);
    }

    /**
     * Update general online meeting link for all pertemuans in a kelas
     */
    public function updateGeneralMeetingLink($id)
    {
        $validated = request()->validate([
            'online_meeting_link' => 'nullable|url',
        ]);

        $kelasMataKuliah = KelasMataKuliah::findOrFail($id);
        $kelasMataKuliah->update([
            'online_meeting_link' => $validated['online_meeting_link'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Link zoom umum berhasil disimpan',
            'online_meeting_link' => $kelasMataKuliah->online_meeting_link,
        ]);
    }

    /**
     * Helper: Get meeting label
     */
    private function getMeetingLabel($tipe, $nomor)
    {
        $resolver = app(\App\Services\ActiveMeetingResolver::class);
        return $resolver->labelFor($tipe, $nomor);
    }
}