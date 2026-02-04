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

class JadwalGeneratorController extends Controller
{
    private $jadwalSlots = [
        ['jam_mulai' => '07:30', 'jam_selesai' => '09:10'],
        ['jam_mulai' => '09:20', 'jam_selesai' => '11:00'],
        ['jam_mulai' => '11:10', 'jam_selesai' => '12:50'],
        ['jam_mulai' => '13:00', 'jam_selesai' => '14:40'],
        ['jam_mulai' => '14:50', 'jam_selesai' => '16:30'],
        ['jam_mulai' => '16:40', 'jam_selesai' => '18:20'],
        ['jam_mulai' => '18:30', 'jam_selesai' => '20:10'],
    ];

    private $availableHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    
    private $ruangList = []; // populated from `ruangans` table at runtime

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

            // Hapus proposal lama jika overwrite
            if ($request->overwrite_existing) {
                JadwalProposal::where('status', 'pending_dosen')->delete();
            }

            $generated = 0;
            $failed = 0;
            $failedItems = [];

            // Load active ruangan kode list from database for room assignment
            $this->ruangList = Ruangan::where('status', 'aktif')
                ->orderBy('kode_ruangan')
                ->pluck('kode_ruangan')
                ->toArray();

            // Ambil semua mata kuliah yang perlu dijadwalkan
            // Prefer data dari `kelas_mata_kuliahs` (mengandung preferensi ruang/hari/jam).
            if (DB::table('kelas_mata_kuliahs')->count() > 0) {
                $kelasMataKuliahs = DB::table('kelas_mata_kuliahs')
                    ->join('mata_kuliahs', 'kelas_mata_kuliahs.mata_kuliah_id', '=', 'mata_kuliahs.id')
                    ->join('dosens', 'kelas_mata_kuliahs.dosen_id', '=', 'dosens.id')
                    ->join('users', 'dosens.user_id', '=', 'users.id')
                    ->select(
                        'kelas_mata_kuliahs.*',
                        'mata_kuliahs.nama_mk as mata_kuliah_nama',
                        'mata_kuliahs.sks',
                        'kelas_mata_kuliahs.kode_kelas as kode_kelas',
                        'kelas_mata_kuliahs.kode_kelas as kelas_nama',
                        'users.name as dosen_nama'
                    )
                    ->get();
            } else {
                // Fallback: gunakan tabel `kelas` (sesuai form 'Tambah Jadwal Baru')
                $kelasMataKuliahs = DB::table('kelas')
                    ->join('mata_kuliahs', 'kelas.mata_kuliah_id', '=', 'mata_kuliahs.id')
                    ->join('dosens', 'kelas.dosen_id', '=', 'dosens.id')
                    ->join('users', 'dosens.user_id', '=', 'users.id')
                    ->select(
                        'kelas.*',
                        'mata_kuliahs.nama_mk as mata_kuliah_nama',
                        'mata_kuliahs.sks',
                        DB::raw('CONCAT(mata_kuliahs.kode_mk, "-", kelas.section) as kode_kelas'),
                        DB::raw('CONCAT(mata_kuliahs.kode_mk, "-", kelas.section) as kelas_nama'),
                        DB::raw('NULL as ruang'),
                        DB::raw('NULL as hari'),
                        DB::raw('NULL as jam_mulai'),
                        DB::raw('NULL as jam_selesai'),
                        'users.name as dosen_nama'
                    )
                    ->get();
            }

            foreach ($kelasMataKuliahs as $kmk) {
                // Skip if dosen tidak memiliki mata kuliah yang diajarkan
                $dosenRow = DB::table('dosens')->where('id', $kmk->dosen_id)->first();
                if (!$dosenRow) {
                    Log::info("Skipping generation: dosen id {$kmk->dosen_id} not found for {$kmk->mata_kuliah_nama}");
                    $failed++;
                    $failedItems[] = "{$kmk->mata_kuliah_nama} - {$kmk->kelas_nama} (Dosen tidak ditemukan)";
                    continue;
                }

                $mkIds = trim((string) ($dosenRow->mata_kuliah_ids ?? ''));
                // treat JSON array or CSV; if empty -> skip
                if ($mkIds === '' || $mkIds === '[]' || $mkIds === 'null') {
                    Log::info("Skipping generation: dosen id {$kmk->dosen_id} has no mata_kuliah_ids");
                    continue;
                }

                $proposal = $this->generateJadwalForMataKuliah($kmk, $request->semester, $request->tahun_ajaran);
                
                if ($proposal) {
                    $generated++;
                } else {
                    $failed++;
                    $failedItems[] = "{$kmk->mata_kuliah_nama} - {$kmk->kelas_nama} ({$kmk->dosen_nama})";
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
        if (JadwalProposal::where('mata_kuliah_id', $kmk->mata_kuliah_id)
            ->where('kelas_id', $kelasModel->id)
            ->where('dosen_id', $kmk->dosen_id)
            ->whereIn('status', ['pending_dosen', 'approved_dosen', 'pending_admin'])
            ->exists()) {
            Log::info("Skipping generation: existing proposal for {$kmk->mata_kuliah_nama} - {$kelasModel->section}");
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

        // Otherwise try available slots
        foreach ($this->availableHari as $hari) {
            foreach ($this->jadwalSlots as $slot) {
                // Cek apakah dosen sudah ada jadwal di hari dan jam ini
                if ($this->hasDosenConflictByKelas($kmk->dosen_id, $hari, $slot['jam_mulai'], $slot['jam_selesai'])) {
                    continue;
                }

                // Prefer ruang from kmk if provided, else find available
                $ruangPrefer = $kmk->ruang ?? null;
                $ruangan = $ruangPrefer ?: $this->findAvailableRoom($hari, $slot['jam_mulai'], $slot['jam_selesai']);

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
                    'catatan_generate' => 'Auto generated oleh sistem',
                    'generated_by' => auth()->id(),
                    'generated_at' => now()
                ]);

                Log::info("Generated jadwal proposal for {$kmk->mata_kuliah_nama} - {$kmk->kode_kelas}");
                return $proposal;
            }
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

    private function findAvailableRoom($hari, $jamMulai, $jamSelesai)
    {
        foreach ($this->ruangList as $ruang) {
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