<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\JadwalProposal;
use App\Models\JadwalApproval;
use App\Models\Jadwal;
use App\Models\Ruangan;
use App\Models\KelasMataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JadwalApprovalController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $dosen = $user->dosen;

        if (!$dosen) {
            return redirect()->back()->with('error', 'User tidak terhubung dengan data dosen');
        }

        $proposals = JadwalProposal::with(['mataKuliah', 'kelas', 'generatedBy', 'approvals'])
            ->where('dosen_id', $dosen->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Kelompokkan berdasarkan status
        $pendingProposals = $proposals->where('status', 'pending_dosen');
        $approvedProposals = $proposals->where('status', 'approved_dosen');
        $rejectedProposals = $proposals->where('status', 'rejected_dosen');
        $inAdminReview = $proposals->where('status', 'pending_admin');
        $finalApproved = $proposals->where('status', 'approved_admin');
        $finalRejected = $proposals->where('status', 'rejected_admin');

        $query = JadwalApproval::with(['jadwalProposal.mataKuliah', 'jadwalProposal.kelas'])
            ->where('approved_by', $user->id)
            ->where('role', 'dosen');
            
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('jadwalProposal.mataKuliah', function($q) use ($search) {
                $q->where('nama_mk', 'like', "%{$search}%")
                  ->orWhere('kode_mk', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('action', $request->status);
        }
        
        $historyApprovals = $query->orderBy('created_at', 'desc')
            ->paginate(4)
            ->withQueryString();

        return view('dosen.jadwal.approval', compact(
            'pendingProposals',
            'approvedProposals', 
            'rejectedProposals',
            'inAdminReview',
            'finalApproved',
            'finalRejected',
            'historyApprovals'
        ));
    }

    public function approve(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $proposal = JadwalProposal::findOrFail($id);
            
            // Validasi bahwa proposal ini milik dosen yang sedang login
            $user = Auth::user();
            $dosen = $user->dosen;
            
            if (!$dosen || $proposal->dosen_id !== $dosen->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            
            // Idempotency: jika sudah ditolak oleh dosen sebelumnya, kembalikan sukses supaya UI tidak mencoba mengirim ulang
            if ($proposal->status === 'rejected_dosen') {
                return response()->json(['success' => true, 'message' => 'Pengajuan sudah ditolak sebelumnya'], 200);
            }

            // Validasi status (terima baik jika masih menunggu dosen atau sedang menunggu admin setelah perubahan)
            if (!in_array($proposal->status, ['pending_dosen', 'pending_admin'])) {
                return response()->json(['error' => 'Pengajuan tidak dalam status pending_dosen (saat ini: ' . $proposal->status . ')'], 400);
            }
            
            // Create record approval
            JadwalApproval::create([
                'jadwal_proposal_id' => $proposal->id,
                'approved_by' => $user->id,
                'role' => 'dosen',
                'action' => 'approve',
                'approved_at' => now()
            ]);

            // If dosen approves, finalize the proposal immediately: create Jadwal and mark proposal as approved_admin
            // Determine ruangan values (prefer ruangan_id if set)
            $ruanganId = $proposal->ruangan_id ?? null;
            $ruangKode = $proposal->ruangan;
            if ($ruanganId && empty($ruangKode)) {
                $rObj = Ruangan::find($ruanganId);
                $ruangKode = $rObj?->kode_ruangan;
            }

            // Create Jadwal (avoid duplicates)
            $existing = Jadwal::where('kelas_id', $proposal->kelas_id)
                ->where('hari', $proposal->hari)
                ->where('jam_mulai', $proposal->jam_mulai)
                ->where('jam_selesai', $proposal->jam_selesai)
                ->first();

            if (!$existing) {
                Jadwal::create([
                    'kelas_id' => $proposal->kelas_id,
                    'hari' => $proposal->hari,
                    'jam_mulai' => $proposal->jam_mulai,
                    'jam_selesai' => $proposal->jam_selesai,
                    'ruangan' => $ruangKode,
                    'ruangan_id' => $ruanganId,
                    'status' => 'active',
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                ]);
            }

            // Mark proposal as fully approved so admin review is skipped
            $proposal->update(['status' => 'approved_admin']);

            // Upsert into kelas_mata_kuliahs so the active schedule is reflected there as well
            try {
                $kelas = $proposal->kelas;
                $kodeKelas = $kelas?->section ?? null;
                
                // Get active semester
                $activeSemester = \App\Models\Semester::where('status', 'aktif')->first()
                    ?? \App\Models\Semester::latest()->first();

                $kmkData = [
                    'mata_kuliah_id' => $proposal->mata_kuliah_id,
                    'dosen_id' => $proposal->dosen_id,
                    'semester_id' => $activeSemester?->id,
                    'kode_kelas' => $kodeKelas,
                    'kapasitas' => 40,
                    'ruang' => $ruangKode,
                    'ruangan_id' => $ruanganId ?? null,
                    'hari' => $proposal->hari,
                    'jam_mulai' => $proposal->jam_mulai,
                    'jam_selesai' => $proposal->jam_selesai,
                ];

                if ($kodeKelas) {
                    $existingKmk = KelasMataKuliah::where('mata_kuliah_id', $proposal->mata_kuliah_id)
                        ->where('kode_kelas', $kodeKelas)
                        ->first();
                } else {
                    $existingKmk = KelasMataKuliah::where('mata_kuliah_id', $proposal->mata_kuliah_id)->first();
                }

                if ($existingKmk) {
                    $existingKmk->update($kmkData);
                } else {
                    KelasMataKuliah::create($kmkData);
                }
            } catch (\Exception $e) {
                // ignore upsert errors
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil disetujui dan langsung diaktifkan (tidak perlu approval admin).'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Gagal menyetujui jadwal: ' . $e->getMessage()], 500);
        }
    }
    
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
            'hari_pengganti' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai_pengganti' => 'required|date_format:H:i',
            'jam_selesai_pengganti' => 'required|date_format:H:i|after:jam_mulai_pengganti'
        ]);
        
        try {
            DB::beginTransaction();
            
            $proposal = JadwalProposal::findOrFail($id);
            
            // Validasi bahwa proposal ini milik dosen yang sedang login
            $user = Auth::user();
            $dosen = $user->dosen;
            
            if (!$dosen || $proposal->dosen_id !== $dosen->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            
            // Idempotency: jika sudah ditolak oleh dosen sebelumnya, kembalikan sukses sehingga aksi tidak error
            if ($proposal->status === 'rejected_dosen') {
                return response()->json(['success' => true, 'message' => 'Pengajuan sudah ditolak sebelumnya'], 200);
            }

            // Validasi status (izinkan reject jika proposal masih menunggu dosen atau menunggu admin)
            if (!in_array($proposal->status, ['pending_dosen', 'pending_admin'])) {
                return response()->json(['error' => 'Pengajuan tidak dalam status pending_dosen (saat ini: ' . $proposal->status . ')'], 400);
            }
            
            // Update status proposal
            $proposal->update(['status' => 'rejected_dosen']);
            
            // Buat record approval dengan alternatif jadwal
            JadwalApproval::create([
                'jadwal_proposal_id' => $proposal->id,
                'approved_by' => $user->id,
                'role' => 'dosen',
                'action' => 'reject',
                'alasan_penolakan' => $request->alasan_penolakan,
                'hari_pengganti' => $request->hari_pengganti,
                'jam_mulai_pengganti' => $request->jam_mulai_pengganti,
                'jam_selesai_pengganti' => $request->jam_selesai_pengganti,
                'ruangan_pengganti' => $request->ruangan_pengganti,
                'approved_at' => now()
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Jadwal ditolak. Admin akan meninjau usulan perubahan Anda.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Gagal menolak jadwal: ' . $e->getMessage()], 500);
        }
    }
    
    public function show($id)
    {
        $user = Auth::user();
        $dosen = $user->dosen;
        
        if (!$dosen) {
            return redirect()->back()->with('error', 'User tidak terhubung dengan data dosen');
        }
        
        $proposal = JadwalProposal::with([
            'mataKuliah', 
            'kelas', 
            'generatedBy', 
            'approvals.user'
        ])
        ->where('dosen_id', $dosen->id)
        ->findOrFail($id);
        
        return view('dosen.jadwal.detail', compact('proposal'));
    }
    
    // Helper method untuk mendapatkan slot waktu yang tersedia
    public function getAvailableSlots(Request $request)
    {
        $hari = $request->get('hari');
        $sks = (int) $request->get('sks', 1);
        $user = Auth::user();
        $dosen = $user->dosen;
        
        if (!$dosen) {
            return response()->json(['error' => 'User tidak terhubung dengan data dosen'], 400);
        }

        $jamSlots = \App\Models\JamPerkuliahan::where('is_active', true)
            ->orderBy('jam_ke')
            ->get();
            
        $availableStartSlots = [];
        
        for ($i = 0; $i <= $jamSlots->count() - $sks; $i++) {
            $consecutiveValid = true;
            $startSlot = $jamSlots[$i];
            
            for ($j = 0; $j < $sks; $j++) {
                if ($jamSlots[$i + $j]->jam_ke !== $startSlot->jam_ke + $j) {
                    $consecutiveValid = false;
                    break;
                }
            }
            
            if (!$consecutiveValid) {
                continue;
            }
            
            $jamMulai = $startSlot->jam_mulai;
            $jamSelesai = $jamSlots[$i + $sks - 1]->jam_selesai;
            
            $hasConflict = JadwalProposal::where('dosen_id', $dosen->id)
                ->where('hari', $hari)
                ->where(function ($query) use ($jamMulai, $jamSelesai) {
                    // Check strict overlap: start or end overlaps, or completely engulfs
                    $query->where(function ($q) use ($jamMulai, $jamSelesai) {
                        $q->where('jam_mulai', '<', $jamSelesai)
                          ->where('jam_selesai', '>', $jamMulai);
                    });
                })
                ->whereIn('status', ['pending_dosen', 'approved_dosen', 'pending_admin', 'approved_admin'])
                ->exists();
                
            if (!$hasConflict) {
                $jamMulaiFormatted = substr($jamMulai, 0, 5);
                $jamSelesaiFormatted = substr($jamSelesai, 0, 5);
                
                $label = $sks > 1 
                    ? "Jam ke-{$startSlot->jam_ke} sd " . ($startSlot->jam_ke + $sks - 1) . " ({$jamMulaiFormatted} - {$jamSelesaiFormatted})"
                    : "Jam ke-{$startSlot->jam_ke} ({$jamMulaiFormatted} - {$jamSelesaiFormatted})";
                    
                $availableStartSlots[] = [
                    'jam_mulai' => $jamMulaiFormatted,
                    'jam_selesai' => $jamSelesaiFormatted,
                    'label' => $label
                ];
            }
        }
        
        return response()->json(array_values($availableStartSlots));
    }
}