<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalProposal;
use App\Models\JadwalApproval;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JadwalAdminApprovalController extends Controller
{
    public function index()
    {
        $proposals = JadwalProposal::with([
            'mataKuliah', 
            'kelas', 
            'dosen', 
            'generatedBy', 
            'approvals.user'
        ])
        ->whereIn('status', ['approved_dosen', 'rejected_dosen', 'pending_admin'])
        ->orderBy('created_at', 'desc')
        ->get();

        // Kelompokkan berdasarkan status
        $needingApproval = $proposals->whereIn('status', ['approved_dosen', 'rejected_dosen']);
        $inReview = $proposals->where('status', 'pending_admin');

        $statistics = [
            'waiting_admin' => $needingApproval->count(),
            'in_review' => $inReview->count(),
            'approved_dosen' => $proposals->where('status', 'approved_dosen')->count(),
            'rejected_dosen' => $proposals->where('status', 'rejected_dosen')->count(),
        ];

        return view('admin.jadwal.approval', compact('proposals', 'statistics', 'needingApproval', 'inReview'));
    }

    public function show($id)
    {
        $proposal = JadwalProposal::with([
            'mataKuliah', 
            'kelas', 
            'dosen',
            'generatedBy', 
            'approvals.user'
        ])
        ->findOrFail($id);

        // Dapatkan approval terakhir dari dosen
        $dosenApproval = $proposal->approvals()
            ->where('role', 'dosen')
            ->latest()
            ->first();

        return view('admin.jadwal.detail', compact('proposal', 'dosenApproval'));
    }

    public function approve(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $proposal = JadwalProposal::findOrFail($id);
            
            // Validasi status - harus sudah disetujui dosen atau sedang pending admin
            if (!in_array($proposal->status, ['approved_dosen', 'pending_admin'])) {
                return response()->json([
                    'error' => 'Proposal tidak dalam status yang dapat disetujui admin'
                ], 400);
            }
            
            // Cek konflik jadwal sebelum approve
            if ($this->hasScheduleConflict($proposal)) {
                return response()->json([
                    'error' => 'Ada konflik jadwal. Periksa kembali waktu dan ruangan.'
                ], 400);
            }
            
            // Update status ke approved admin
            $proposal->update(['status' => 'approved_admin']);
            
            // Buat record approval admin
            JadwalApproval::create([
                'jadwal_proposal_id' => $proposal->id,
                'approved_by' => Auth::id(),
                'role' => 'admin',
                'action' => 'approve',
                'approved_at' => now()
            ]);
            
            // Pindahkan ke tabel jadwal aktif
            $this->moveToActiveSchedule($proposal);
            
            DB::commit();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Proposal jadwal disetujui dan jadwal telah aktif'
                ]);
            }
            return redirect()->route('admin.jadwal_admin_approval.index')->with('success', 'Proposal jadwal disetujui and jadwal telah aktif');
            
        } catch (\Exception $e) {
            DB::rollback();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Gagal menyetujui proposal: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Gagal menyetujui proposal: ' . $e->getMessage());
        }
    }
    
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ]);
        
        try {
            DB::beginTransaction();
            
            $proposal = JadwalProposal::findOrFail($id);
            
            // Validasi status
            if (!in_array($proposal->status, ['approved_dosen', 'pending_admin'])) {
                return response()->json([
                    'error' => 'Proposal tidak dalam status yang dapat ditolak admin'
                ], 400);
            }
            
            // Update status ke rejected admin
            $proposal->update(['status' => 'rejected_admin']);
            
            // Buat record approval admin
            JadwalApproval::create([
                'jadwal_proposal_id' => $proposal->id,
                'approved_by' => Auth::id(),
                'role' => 'admin',
                'action' => 'reject',
                'alasan_penolakan' => $request->alasan_penolakan,
                'approved_at' => now()
            ]);
            
            DB::commit();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Proposal jadwal ditolak'
                ]);
            }
            return redirect()->route('admin.jadwal_admin_approval.index')->with('success', 'Proposal jadwal ditolak');
            
        } catch (\Exception $e) {
            DB::rollback();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Gagal menolak proposal: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Gagal menolak proposal: ' . $e->getMessage());
        }
    }
    
    public function approveWithChanges(Request $request, $id)
    {
        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruangan' => 'required|string|max:100',
            'catatan' => 'nullable|string|max:500'
        ]);
        
        try {
            DB::beginTransaction();
            
            $proposal = JadwalProposal::findOrFail($id);
            
            // Update proposal dengan perubahan admin
            $proposal->update([
                'hari' => $request->hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'ruangan' => $request->ruangan,
                'status' => 'approved_admin'
            ]);
            
            // Buat record approval admin dengan perubahan
            JadwalApproval::create([
                'jadwal_proposal_id' => $proposal->id,
                'approved_by' => Auth::id(),
                'role' => 'admin',
                'action' => 'approve',
                'alasan_penolakan' => $request->catatan ?? 'Disetujui dengan perubahan jadwal',
                'approved_at' => now()
            ]);
            
            // Pindahkan ke tabel jadwal aktif dengan perubahan
            $this->moveToActiveSchedule($proposal);
            
            DB::commit();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Proposal disetujui dengan perubahan jadwal'
                ]);
            }
            return redirect()->route('admin.jadwal_admin_approval.index')->with('success', 'Proposal disetujui dengan perubahan jadwal');
            
        } catch (\Exception $e) {
            DB::rollback();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Gagal menyetujui dengan perubahan: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Gagal menyetujui dengan perubahan: ' . $e->getMessage());
        }
    }
    
    public function processDosenRequest(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve_alternative,reject_alternative,propose_new',
            'hari' => 'required_if:action,propose_new|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required_if:action,propose_new|date_format:H:i',
            'jam_selesai' => 'required_if:action,propose_new|date_format:H:i|after:jam_mulai',
            'ruangan' => 'required_if:action,propose_new|string|max:100',
            'catatan' => 'nullable|string|max:500'
        ]);
        
        try {
            DB::beginTransaction();
            
            $proposal = JadwalProposal::findOrFail($id);
            
            // Harus dalam status rejected_dosen
            if ($proposal->status !== 'rejected_dosen') {
                return response()->json([
                    'error' => 'Proposal tidak dalam status yang dapat diproses'
                ], 400);
            }
            
            $dosenApproval = $proposal->approvals()
                ->where('role', 'dosen')
                ->where('action', 'reject')
                ->latest()
                ->first();
                
            if (!$dosenApproval) {
                return response()->json(['error' => 'Data penolakan dosen tidak ditemukan'], 400);
            }
            
            switch ($request->action) {
                case 'approve_alternative':
                    // Setujui usulan dosen
                    if (!$dosenApproval->hasAlternative()) {
                        return response()->json(['error' => 'Dosen tidak memberikan usulan alternatif'], 400);
                    }
                    
                    $proposal->update([
                        'hari' => $dosenApproval->hari_pengganti,
                        'jam_mulai' => $dosenApproval->jam_mulai_pengganti,
                        'jam_selesai' => $dosenApproval->jam_selesai_pengganti,
                        'ruangan' => $dosenApproval->ruangan_pengganti ?? $proposal->ruangan,
                        'status' => 'approved_admin'
                    ]);
                    
                    $this->moveToActiveSchedule($proposal);
                    $message = 'Usulan jadwal dosen disetujui dan jadwal telah aktif';
                    break;
                    
                case 'reject_alternative':
                    // Tolak usulan dosen
                    $proposal->update(['status' => 'rejected_admin']);
                    $message = 'Usulan jadwal dosen ditolak';
                    break;
                    
                case 'propose_new':
                    // Admin memberikan usulan baru
                    $proposal->update([
                        'hari' => $request->hari,
                        'jam_mulai' => $request->jam_mulai,
                        'jam_selesai' => $request->jam_selesai,
                        'ruangan' => $request->ruangan,
                        'status' => 'pending_admin'
                    ]);
                    $message = 'Usulan jadwal baru telah dibuat, menunggu konfirmasi';
                    break;
            }
            
            // Buat record approval admin
            JadwalApproval::create([
                'jadwal_proposal_id' => $proposal->id,
                'approved_by' => Auth::id(),
                'role' => 'admin',
                'action' => in_array($request->action, ['approve_alternative', 'propose_new']) ? 'approve' : 'reject',
                'alasan_penolakan' => $request->catatan,
                'approved_at' => now()
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Gagal memproses request: ' . $e->getMessage()
            ], 500);
        }
    }

    public function process(Request $request, $id)
    {
        $proposal = JadwalProposal::findOrFail($id);

        switch ($request->status) {
            case 'approved':
                return $this->approve($request, $id);
            case 'rejected':
                return $this->reject($request, $id);
            case 'approved_with_changes':
                return $this->approveWithChanges($request, $id);
            default:
                return back()->with('error', 'Status tidak valid');
        }
    }
    
    private function hasScheduleConflict(JadwalProposal $proposal)
    {
        // Cek konflik dengan jadwal aktif
        $conflictActive = Jadwal::where('id', '!=', $proposal->id)
            ->whereHas('kelas', function($q) use ($proposal) {
                $q->where('dosen_id', $proposal->dosen_id);
            })
            ->where('hari', $proposal->hari)
            ->where(function ($query) use ($proposal) {
                $query->whereBetween('jam_mulai', [$proposal->jam_mulai, $proposal->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$proposal->jam_mulai, $proposal->jam_selesai])
                      ->orWhere(function ($q) use ($proposal) {
                          $q->where('jam_mulai', '<=', $proposal->jam_mulai)
                            ->where('jam_selesai', '>=', $proposal->jam_selesai);
                      });
            })
            ->exists();
            
        // Cek konflik dengan proposal lain yang sudah approved
        $conflictProposal = JadwalProposal::where('id', '!=', $proposal->id)
            ->where('dosen_id', $proposal->dosen_id)
            ->where('hari', $proposal->hari)
            ->where(function ($query) use ($proposal) {
                $query->whereBetween('jam_mulai', [$proposal->jam_mulai, $proposal->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$proposal->jam_mulai, $proposal->jam_selesai])
                      ->orWhere(function ($q) use ($proposal) {
                          $q->where('jam_mulai', '<=', $proposal->jam_mulai)
                            ->where('jam_selesai', '>=', $proposal->jam_selesai);
                      });
            })
            ->whereIn('status', ['approved_admin'])
            ->exists();
            
        return $conflictActive || $conflictProposal;
    }
    
    private function moveToActiveSchedule(JadwalProposal $proposal)
    {
        // Cek apakah sudah ada jadwal aktif untuk kelas ini
        $existingJadwal = Jadwal::where('kelas_id', $proposal->kelas_id)
            ->first();
            
        if ($existingJadwal) {
            // Update jadwal yang ada
            $existingJadwal->update([
                'hari' => $proposal->hari,
                'jam_mulai' => $proposal->jam_mulai,
                'jam_selesai' => $proposal->jam_selesai,
                'ruangan' => $proposal->ruangan,
                'status' => 'active',
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);
        } else {
            // Buat jadwal baru
            Jadwal::create([
                'kelas_id' => $proposal->kelas_id,
                'hari' => $proposal->hari,
                'jam_mulai' => $proposal->jam_mulai,
                'jam_selesai' => $proposal->jam_selesai,
                'ruangan' => $proposal->ruangan,
                'status' => 'active',
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);
        }
    }
}