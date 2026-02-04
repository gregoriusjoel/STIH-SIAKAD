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
    public function index()
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

        return view('dosen.jadwal.approval', compact(
            'pendingProposals',
            'approvedProposals', 
            'rejectedProposals',
            'inAdminReview',
            'finalApproved',
            'finalRejected'
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
                return response()->json(['success' => true, 'message' => 'Proposal sudah ditolak sebelumnya'], 200);
            }

            // Validasi status (terima baik jika masih menunggu dosen atau sedang menunggu admin setelah perubahan)
            if (!in_array($proposal->status, ['pending_dosen', 'pending_admin'])) {
                return response()->json(['error' => 'Proposal tidak dalam status pending_dosen (saat ini: ' . $proposal->status . ')'], 400);
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

                $kmkData = [
                    'mata_kuliah_id' => $proposal->mata_kuliah_id,
                    'dosen_id' => $proposal->dosen_id,
                    'kode_kelas' => $kodeKelas,
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
                return response()->json(['success' => true, 'message' => 'Proposal sudah ditolak sebelumnya'], 200);
            }

            // Validasi status (izinkan reject jika proposal masih menunggu dosen atau menunggu admin)
            if (!in_array($proposal->status, ['pending_dosen', 'pending_admin'])) {
                return response()->json(['error' => 'Proposal tidak dalam status pending_dosen (saat ini: ' . $proposal->status . ')'], 400);
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
        $user = Auth::user();
        $dosen = $user->dosen;
        
        if (!$dosen) {
            return response()->json(['error' => 'User tidak terhubung dengan data dosen'], 400);
        }
        
        $availableSlots = [
            ['jam_mulai' => '07:30', 'jam_selesai' => '09:10', 'label' => '07:30 - 09:10'],
            ['jam_mulai' => '09:20', 'jam_selesai' => '11:00', 'label' => '09:20 - 11:00'],
            ['jam_mulai' => '11:10', 'jam_selesai' => '12:50', 'label' => '11:10 - 12:50'],
            ['jam_mulai' => '13:00', 'jam_selesai' => '14:40', 'label' => '13:00 - 14:40'],
            ['jam_mulai' => '14:50', 'jam_selesai' => '16:30', 'label' => '14:50 - 16:30'],
            ['jam_mulai' => '16:40', 'jam_selesai' => '18:20', 'label' => '16:40 - 18:20'],
            ['jam_mulai' => '18:30', 'jam_selesai' => '20:10', 'label' => '18:30 - 20:10'],
        ];
        
        // Filter slot yang tidak bentrok dengan jadwal dosen
        $availableSlots = array_filter($availableSlots, function($slot) use ($hari, $dosen) {
            // Cek bentrok dengan jadwal yang sudah approved
            $hasConflict = JadwalProposal::where('dosen_id', $dosen->id)
                ->where('hari', $hari)
                ->where(function ($query) use ($slot) {
                    $query->whereBetween('jam_mulai', [$slot['jam_mulai'], $slot['jam_selesai']])
                          ->orWhereBetween('jam_selesai', [$slot['jam_mulai'], $slot['jam_selesai']])
                          ->orWhere(function ($q) use ($slot) {
                              $q->where('jam_mulai', '<=', $slot['jam_mulai'])
                                ->where('jam_selesai', '>=', $slot['jam_selesai']);
                          });
                })
                ->whereIn('status', ['approved_dosen', 'pending_admin', 'approved_admin'])
                ->exists();
                
            return !$hasConflict;
        });
        
        return response()->json(array_values($availableSlots));
    }
}