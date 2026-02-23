<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalProposal;
use App\Models\MataKuliah;
use App\Models\Kelas;
use App\Models\Dosen;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Ruangan;
use App\Models\JamPerkuliahan;

class JadwalGeneratorController extends Controller
{
    private $availableHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    
    private $ruangList = []; // populated from `ruangans` table at runtime

    /**
     * Get active time slots from jam_perkuliahan table
     * For multi-SKS courses, this will find consecutive slot combinations
     */
    private function getJadwalSlots($requiredSks = null)
    {
        $jamPerkuliahans = JamPerkuliahan::where('is_active', true)
            ->orderBy('jam_ke')
            ->get();

        // If no SKS specified or 1 SKS, return single slots
        if ($requiredSks === null || $requiredSks == 1) {
            return $jamPerkuliahans->map(function ($jam) {
                return [
                    'jam_mulai' => date('H:i', strtotime($jam->jam_mulai)),
                    'jam_selesai' => date('H:i', strtotime($jam->jam_selesai)),
                    'jam_ke' => $jam->jam_ke,
                ];
            })->toArray();
        }

        // For multi-SKS courses, find consecutive slot combinations
        return $this->findConsecutiveSlots($jamPerkuliahans, $requiredSks);
    }

    /**
     * Find consecutive time slots that match the required SKS
     * For 2 SKS: find 2 consecutive 45-min slots (jam ke-1,2 or 2,3 etc)
     * For 3 SKS: find 3 consecutive 45-min slots, etc.
     */
    private function findConsecutiveSlots($jamPerkuliahans, $requiredSks)
    {
        $combinations = [];
        $count = $jamPerkuliahans->count();
        
        // Need at least $requiredSks slots to form a combination
        if ($count < $requiredSks) {
            return $combinations;
        }
        
        $slots = $jamPerkuliahans->toArray();
        
        // Try each starting position
        for ($i = 0; $i <= $count - $requiredSks; $i++) {
            $isConsecutive = true;
            $selectedSlots = [];
            
            // Check if next N slots are consecutive
            for ($j = 0; $j < $requiredSks; $j++) {
                $currentSlot = $slots[$i + $j];
                $selectedSlots[] = $currentSlot;
                
                // Check if this slot connects to the next one (end time = next start time)
                if ($j < $requiredSks - 1) {
                    $nextSlot = $slots[$i + $j + 1];
                    
                    $currentEnd = date('H:i', strtotime($currentSlot['jam_selesai']));
                    $nextStart = date('H:i', strtotime($nextSlot['jam_mulai']));
                    
                    if ($currentEnd !== $nextStart) {
                        $isConsecutive = false;
                        break;
                    }
                }
            }
            
            // If all slots are consecutive, create combined slot
            if ($isConsecutive && count($selectedSlots) === $requiredSks) {
                $firstSlot = $selectedSlots[0];
                $lastSlot = $selectedSlots[count($selectedSlots) - 1];
                
                $jamKeList = array_map(fn($s) => $s['jam_ke'], $selectedSlots);
                
                $combinations[] = [
                    'jam_mulai' => date('H:i', strtotime($firstSlot['jam_mulai'])),
                    'jam_selesai' => date('H:i', strtotime($lastSlot['jam_selesai'])),
                    'jam_ke' => $firstSlot['jam_ke'],
                    'jam_ke_list' => implode(',', $jamKeList),
                    'sks' => $requiredSks,
                ];
            }
        }
        
        return $combinations;
    }

    public function index()
    {
        $jadwalProposals = JadwalProposal::with(['mataKuliah', 'kelas', 'dosen', 'generatedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $statistics = [
            'total_proposals' => JadwalProposal::count(),
            'pending_dosen' => JadwalProposal::where('status', 'pending_dosen')->count(),
            'approved_dosen' => JadwalProposal::where('status', 'approved_dosen')->count(),
            'pending_admin' => JadwalProposal::where('status', 'pending_admin')->count(),
            'approved_admin' => JadwalProposal::where('status', 'approved_admin')->count(),
            'rejected' => JadwalProposal::whereIn('status', ['rejected_dosen', 'rejected_admin'])->count(),
        ];

        return view('admin.jadwal.generator', compact('jadwalProposals', 'statistics'));
    }

    public function autoGenerate(Request $request)
    {
        $request->validate([
            'semester' => 'required|string',
            'tahun_ajaran' => 'required|string',
            'overwrite_existing' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // Hapus jadwal & proposal lama untuk periode ini jika overwrite
            if ($request->overwrite_existing) {
                $kelasIdsForPeriod = \App\Models\Kelas::where('semester_type', $request->semester)
                    ->where('tahun_ajaran', $request->tahun_ajaran)
                    ->pluck('id');

                // Delete both approved schedules and proposals for this period
                \App\Models\Jadwal::whereIn('kelas_id', $kelasIdsForPeriod)->delete();
                \App\Models\JadwalProposal::whereIn('kelas_id', $kelasIdsForPeriod)->delete();
            }

            $generated = 0;
            $failed = 0;
            $failedItems = [];

            // Load active ruangan kode list from database for room assignment
            $this->ruangList = Ruangan::where('status', 'aktif')
                ->orderBy('kode_ruangan')
                ->pluck('kode_ruangan')
                ->toArray();

            // Generate berdasarkan mata_kuliah_ids dari tabel dosens
            $dosens = DB::table('dosens')
                ->join('users', 'dosens.user_id', '=', 'users.id')
                ->select('dosens.id', 'dosens.mata_kuliah_ids', 'users.name as dosen_nama')
                ->whereNotNull('dosens.mata_kuliah_ids')
                ->where('dosens.mata_kuliah_ids', '!=', '')
                ->where('dosens.mata_kuliah_ids', '!=', '[]')
                ->where('dosens.mata_kuliah_ids', '!=', 'null')
                ->get();

            $kelasMataKuliahs = collect();
            
            foreach ($dosens as $dosen) {
                // Parse JSON mata_kuliah_ids
                $mkIds = json_decode($dosen->mata_kuliah_ids, true);
                
                if (!is_array($mkIds) || empty($mkIds)) {
                    continue;
                }
                
                // Untuk setiap mata kuliah yang diampu dosen ini
                foreach ($mkIds as $mkId) {
                    // Ambil data mata kuliah
                    $mataKuliah = DB::table('mata_kuliahs')->where('id', $mkId)->first();
                    
                    if (!$mataKuliah) {
                        continue;
                    }
                    
                    // Cek apakah sudah ada di kelas_mata_kuliahs (untuk ambil preferensi)
                    $kmk = DB::table('kelas_mata_kuliahs')
                        ->where('mata_kuliah_id', $mkId)
                        ->where('dosen_id', $dosen->id)
                        ->first();
                    
                    // Buat object untuk generate
                    $kelasMataKuliahs->push((object)[
                        'id' => $kmk->id ?? null,
                        'mata_kuliah_id' => $mkId,
                        'dosen_id' => $dosen->id,
                        'kode_kelas' => $kmk->kode_kelas ?? 'A',
                        'kapasitas' => $kmk->kapasitas ?? 40,
                        'ruang' => $kmk->ruang ?? null,
                        'hari' => $kmk->hari ?? null,
                        'jam_mulai' => $kmk->jam_mulai ?? null,
                        'jam_selesai' => $kmk->jam_selesai ?? null,
                        'mata_kuliah_nama' => $mataKuliah->nama_mk,
                        'sks' => $mataKuliah->sks,
                        'kelas_nama' => ($kmk->kode_kelas ?? $mataKuliah->kode_mk . '-A'),
                        'dosen_nama' => $dosen->dosen_nama,
                    ]);
                }
            }
            
            // Tracking untuk prevent duplicate dalam satu batch
            $processedCombinations = [];

            foreach ($kelasMataKuliahs as $kmk) {
                // Check if this combination already processed in this batch
                $combinationKey = "{$kmk->mata_kuliah_id}_{$kmk->dosen_id}_{$kmk->kode_kelas}";
                if (isset($processedCombinations[$combinationKey])) {
                    Log::info("Skipping duplicate in batch: {$kmk->mata_kuliah_nama} - {$kmk->kelas_nama}");
                    continue;
                }
                $processedCombinations[$combinationKey] = true;
                
                // Data sudah difilter dari kelas_mata_kuliahs dengan dosen_id
                // Jika ada di sini, berarti dosen memang sudah di-assign untuk mengajar mata kuliah ini
                // Tidak perlu validasi mata_kuliah_ids lagi

                $proposal = $this->generateJadwalForMataKuliah($kmk, $request->semester, $request->tahun_ajaran);
                
                if ($proposal) {
                    $generated++;
                } else {
                    $failed++;
                    // Get SKS info for better error message
                    $sks = isset($kmk->sks) ? $kmk->sks : null;
                    $sksInfo = $sks ? " ({$sks} SKS - slot berturut-turut tidak tersedia)" : "";
                    $failedItems[] = "{$kmk->mata_kuliah_nama} - {$kmk->kelas_nama}{$sksInfo}";
                }
            }

            DB::commit();

            // If everything succeeded, keep notification minimal as requested
            if ($failed === 0) {
                $message = 'Generate Jadwal berhasil';
                $status = 'completed';
            } else {
                $message = "Auto generate jadwal selesai. Berhasil: {$generated}, Gagal: {$failed}";
                $status = 'partial';
                if (!empty($failedItems)) {
                    // Store failed items in session array instead of concatenating to message
                    session()->flash('failed_items', $failedItems);
                }
            }

            // Log the generation to database (if table exists)
            try {
                \App\Models\JadwalGenerateLog::create([
                    'user_id' => auth()->id(),
                    'total_generated' => $generated,
                    'total_failed' => $failed,
                    'failed_items' => $failed > 0 ? $failedItems : null,
                    'status' => $status,
                ]);
            } catch (\Exception $logError) {
                Log::warning('Could not log jadwal generation: ' . $logError->getMessage());
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            
            // Log error to database (if table exists)
            try {
                \App\Models\JadwalGenerateLog::create([
                    'user_id' => auth()->id(),
                    'total_generated' => 0,
                    'total_failed' => 0,
                    'status' => 'error',
                    'error_message' => $e->getMessage(),
                ]);
            } catch (\Exception $logError) {
                Log::warning('Could not log jadwal generation error: ' . $logError->getMessage());
            }
            
            Log::error('Error generating jadwal: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal generate jadwal: ' . $e->getMessage());
        }
    }

    private function generateJadwalForMataKuliah($kmk, $semester, $tahunAjaran)
    {
        // Determine kelas_id: try to map from kode_kelas -> section
        $section = null;
        if (!empty($kmk->kode_kelas)) {
            $parts = explode('-', $kmk->kode_kelas);
            $section = end($parts);
        }

        $kelasModel = null;
        if ($section) {
            $kelasModel = Kelas::where('mata_kuliah_id', $kmk->mata_kuliah_id)
                ->where('section', $section)
                ->where('tahun_ajaran', $tahunAjaran)
                ->first();
        }

        // Jika tidak ada, buat kelas baru menggunakan data dari kmk
        if (!$kelasModel) {
            $kelasModel = Kelas::create([
                'mata_kuliah_id' => $kmk->mata_kuliah_id,
                'dosen_id' => $kmk->dosen_id,
                'section' => $section ?? ($kmk->kode_kelas ?? 'A'),
                'kapasitas' => $kmk->kapasitas ?? 30,
                'tahun_ajaran' => $tahunAjaran,
                'semester_type' => $semester,
            ]);
        }

        // Prevent duplicate proposal for same mata_kuliah/kelas/dosen
        // Check for existing proposals in any active status
        $existingProposal = JadwalProposal::where('mata_kuliah_id', $kmk->mata_kuliah_id)
            ->where('kelas_id', $kelasModel->id)
            ->where('dosen_id', $kmk->dosen_id)
            ->whereIn('status', ['pending_dosen', 'approved_dosen', 'pending_admin', 'approved_admin'])
            ->first();
            
        if ($existingProposal) {
            Log::info("Skipping generation: existing proposal (status: {$existingProposal->status}) for {$kmk->mata_kuliah_nama} - {$kelasModel->section}");
            return null;
        }
        
        // Also check if jadwal already exists
        $existingJadwal = Jadwal::whereHas('kelas', function ($query) use ($kmk, $tahunAjaran) {
                $query->where('mata_kuliah_id', $kmk->mata_kuliah_id)
                      ->where('dosen_id', $kmk->dosen_id)
                      ->where('tahun_ajaran', $tahunAjaran);
            })
            ->exists();
            
        if ($existingJadwal) {
            Log::info("Skipping generation: jadwal already exists for {$kmk->mata_kuliah_nama} - {$kelasModel->section}");
            return null;
        }

        // If kelas_mata_kuliahs already specifies hari/jam/ruang, prefer those
        if (!empty($kmk->hari) && !empty($kmk->jam_mulai) && !empty($kmk->jam_selesai)) {
            // check conflict for dosen
            if ($this->hasDosenConflictByKelas($kmk->dosen_id, $kmk->hari, $kmk->jam_mulai, $kmk->jam_selesai)) {
                Log::warning("Conflict when using preset time for {$kmk->mata_kuliah_nama} - {$kmk->kode_kelas}");
                return null;
            }

            $proposal = JadwalProposal::create([
                'mata_kuliah_id' => $kmk->mata_kuliah_id,
                'kelas_id' => $kelasModel->id,
                'dosen_id' => $kmk->dosen_id,
                'hari' => $kmk->hari,
                'jam_mulai' => $kmk->jam_mulai,
                'jam_selesai' => $kmk->jam_selesai,
                'ruangan' => $kmk->ruang ?? $this->findAvailableRoom($kmk->hari, $kmk->jam_mulai, $kmk->jam_selesai),
                'status' => 'pending_dosen',
                'catatan_generate' => 'Auto generated oleh sistem',
                'generated_by' => auth()->id(),
                'generated_at' => now()
            ]);

            Log::info("Generated jadwal proposal (preset) for {$kmk->mata_kuliah_nama} - {$kmk->kode_kelas}");
            return $proposal;
        }

        // Otherwise try available slots with randomization
        // Fetch mata kuliah to get SKS
        $mataKuliah = \App\Models\MataKuliah::find($kmk->mata_kuliah_id);
        $requiredSks = $mataKuliah ? $mataKuliah->sks : 1;
        
        $jadwalSlots = $this->getJadwalSlots($requiredSks);
        
        if (empty($jadwalSlots)) {
            Log::warning("No consecutive time slots available for {$requiredSks} SKS - {$kmk->mata_kuliah_nama}");
            return null;
        }
        
        // Randomize hari and time slots untuk variasi jadwal
        $randomHari = $this->availableHari;
        shuffle($randomHari);
        
        // Buat kombinasi hari + jam slot yang tersedia
        $availableCombinations = [];
        foreach ($randomHari as $hari) {
            foreach ($jadwalSlots as $slot) {
                $availableCombinations[] = [
                    'hari' => $hari,
                    'slot' => $slot
                ];
            }
        }
        
        // Randomize kombinasi
        shuffle($availableCombinations);
        
        foreach ($availableCombinations as $combination) {
            $hari = $combination['hari'];
            $slot = $combination['slot'];
            
            // Cek apakah dosen sudah ada jadwal di hari dan jam ini
            if ($this->hasDosenConflictByKelas($kmk->dosen_id, $hari, $slot['jam_mulai'], $slot['jam_selesai'])) {
                continue;
            }

            // Prefer ruang from kmk if provided, else find available room (random)
            $ruangPrefer = $kmk->ruang ?? null;
            $ruangan = $ruangPrefer ?: $this->findAvailableRoom($hari, $slot['jam_mulai'], $slot['jam_selesai'], true);

            if (!$ruangan) {
                continue; // Tidak ada ruang tersedia di slot ini
            }

            // Buat proposal jadwal
            $proposal = JadwalProposal::create([
                'mata_kuliah_id' => $kmk->mata_kuliah_id,
                'kelas_id' => $kelasModel->id,
                'dosen_id' => $kmk->dosen_id,
                'hari' => $hari,
                'jam_mulai' => $slot['jam_mulai'],
                'jam_selesai' => $slot['jam_selesai'],
                'ruangan' => $ruangan,
                'status' => 'pending_dosen',
                'catatan_generate' => 'Auto generated oleh sistem (random)',
                'generated_by' => auth()->id(),
                'generated_at' => now()
            ]);

            Log::info("Generated jadwal proposal for {$kmk->mata_kuliah_nama} - {$kmk->kode_kelas}");
            return $proposal;
        }

        Log::warning("Failed to generate jadwal for {$kmk->mata_kuliah_nama} - {$kmk->kode_kelas}");
        return null;
    }

    private function hasDosenConflict($dosenId, $hari, $jamMulai, $jamSelesai)
    {
        return $this->hasDosenConflictByKelas($dosenId, $hari, $jamMulai, $jamSelesai);
    }

    private function hasDosenConflictByKelas($dosenId, $hari, $jamMulai, $jamSelesai)
    {
        // Cek di tabel jadwal yang sudah aktif -> join kelas untuk mengetahui dosen yang bertugas pada kelas tersebut
        $existingJadwal = DB::table('jadwals')
            ->join('kelas', 'jadwals.kelas_id', '=', 'kelas.id')
            ->where('kelas.dosen_id', $dosenId)
            ->where('jadwals.hari', $hari)
            ->where(function ($query) use ($jamMulai, $jamSelesai) {
                $query->whereBetween('jadwals.jam_mulai', [$jamMulai, $jamSelesai])
                      ->orWhereBetween('jadwals.jam_selesai', [$jamMulai, $jamSelesai])
                      ->orWhere(function ($q) use ($jamMulai, $jamSelesai) {
                          $q->where('jadwals.jam_mulai', '<=', $jamMulai)
                            ->where('jadwals.jam_selesai', '>=', $jamSelesai);
                      });
            })
            ->exists();

        // Cek di proposal yang sudah approved dosen atau admin (proposal menyimpan dosen_id langsung)
        $existingProposal = JadwalProposal::where('dosen_id', $dosenId)
            ->where('hari', $hari)
            ->where(function ($query) use ($jamMulai, $jamSelesai) {
                $query->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
                      ->orWhereBetween('jam_selesai', [$jamMulai, $jamSelesai])
                      ->orWhere(function ($q) use ($jamMulai, $jamSelesai) {
                          $q->where('jam_mulai', '<=', $jamMulai)
                            ->where('jam_selesai', '>=', $jamSelesai);
                      });
            })
            ->whereIn('status', ['approved_dosen', 'pending_admin', 'approved_admin'])
            ->exists();

        return $existingJadwal || $existingProposal;
    }

    private function findAvailableRoom($hari, $jamMulai, $jamSelesai, $randomize = false)
    {
        $ruangList = $this->ruangList;
        
        // Randomize ruangan jika diminta untuk variasi
        if ($randomize) {
            shuffle($ruangList);
        }
        
        foreach ($ruangList as $ruang) {
            // Cek apakah ruang sudah terpakai di jadwal aktif
            $ruangTerpakai = Jadwal::where('hari', $hari)
                ->where('ruangan', $ruang)
                ->where(function ($query) use ($jamMulai, $jamSelesai) {
                    $query->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
                          ->orWhereBetween('jam_selesai', [$jamMulai, $jamSelesai])
                          ->orWhere(function ($q) use ($jamMulai, $jamSelesai) {
                              $q->where('jam_mulai', '<=', $jamMulai)
                                ->where('jam_selesai', '>=', $jamSelesai);
                          });
                })
                ->exists();

            // Cek apakah ruang sudah terpakai di proposal yang approved
            $ruangTerpakaiProposal = JadwalProposal::where('hari', $hari)
                ->where('ruangan', $ruang)
                ->where(function ($query) use ($jamMulai, $jamSelesai) {
                    $query->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
                          ->orWhereBetween('jam_selesai', [$jamMulai, $jamSelesai])
                          ->orWhere(function ($q) use ($jamMulai, $jamSelesai) {
                              $q->where('jam_mulai', '<=', $jamMulai)
                                ->where('jam_selesai', '>=', $jamSelesai);
                          });
                })
                ->whereIn('status', ['approved_dosen', 'pending_admin', 'approved_admin'])
                ->exists();

            if (!$ruangTerpakai && !$ruangTerpakaiProposal) {
                return $ruang;
            }
        }

        return null; // Tidak ada ruang tersedia
    }

    public function destroy($id)
    {
        try {
            $proposal = JadwalProposal::findOrFail($id);
            
            // Hanya bisa hapus jika masih pending
            if (!in_array($proposal->status, ['pending_dosen', 'rejected_dosen', 'rejected_admin'])) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus proposal dengan status ini');
            }
            
            $proposal->delete();
            
            return redirect()->back()->with('success', 'Proposal jadwal berhasil dihapus');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus proposal: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'proposal_ids' => 'required|array',
            'proposal_ids.*' => 'exists:jadwal_proposals,id'
        ]);

        try {
            $deleted = JadwalProposal::whereIn('id', $request->proposal_ids)
                ->whereIn('status', ['pending_dosen', 'rejected_dosen', 'rejected_admin'])
                ->delete();

            return redirect()->back()->with('success', "{$deleted} proposal jadwal berhasil dihapus");
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus proposal: ' . $e->getMessage());
        }
    }
}