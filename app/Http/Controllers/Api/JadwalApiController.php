<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class JadwalApiController extends Controller
{
    /**
     * Get all pending schedules for admin review
     */
    public function pending(): JsonResponse
    {
        $pendingJadwals = Jadwal::with(['kelas.mataKuliah', 'kelas.dosen'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($jadwal) {
                return [
                    'id' => $jadwal->id,
                    'kelas_id' => $jadwal->kelas_id,
                    'mata_kuliah' => $jadwal->kelas->mataKuliah->nama,
                    'kode' => $jadwal->kelas->mataKuliah->kode,
                    'sks' => $jadwal->kelas->mataKuliah->sks,
                    'section' => $jadwal->kelas->section,
                    'dosen' => $jadwal->kelas->dosen->name,
                    'hari' => $jadwal->hari,
                    'jam_mulai' => $jadwal->jam_mulai,
                    'jam_selesai' => $jadwal->jam_selesai,
                    'catatan_dosen' => $jadwal->catatan_dosen,
                    'status' => $jadwal->status,
                    'created_at' => $jadwal->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $pendingJadwals,
            'count' => $pendingJadwals->count(),
        ]);
    }

    /**
     * Get all approved schedules waiting for room assignment
     */
    public function approved(): JsonResponse
    {
        $approvedJadwals = Jadwal::with(['kelas.mataKuliah', 'kelas.dosen', 'approvedBy'])
            ->where('status', 'approved')
            ->orderBy('approved_at', 'desc')
            ->get()
            ->map(function ($jadwal) {
                return [
                    'id' => $jadwal->id,
                    'mata_kuliah' => $jadwal->kelas->mataKuliah->nama,
                    'kode' => $jadwal->kelas->mataKuliah->kode,
                    'section' => $jadwal->kelas->section,
                    'dosen' => $jadwal->kelas->dosen->name,
                    'hari' => $jadwal->hari,
                    'jam_mulai' => $jadwal->jam_mulai,
                    'jam_selesai' => $jadwal->jam_selesai,
                    'approved_by' => $jadwal->approvedBy?->name,
                    'approved_at' => $jadwal->approved_at?->format('Y-m-d H:i:s'),
                    'status' => $jadwal->status,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $approvedJadwals,
        ]);
    }

    /**
     * Approve a pending schedule
     */
    public function approve(Request $request, int $id): JsonResponse
    {
        $jadwal = Jadwal::findOrFail($id);

        if ($jadwal->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal ini sudah tidak dalam status pending.',
            ], 400);
        }

        $jadwal->update([
            'status' => 'approved',
            'approved_by' => $request->input('admin_id', auth()->id()),
            'approved_at' => now(),
            'catatan_admin' => $request->input('catatan'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil disetujui. Silakan assign ruangan.',
            'data' => $jadwal->fresh(['kelas.mataKuliah', 'kelas.dosen']),
        ]);
    }

    /**
     * Reject a pending schedule
     */
    public function reject(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'catatan' => 'required|string|max:1000',
        ]);

        $jadwal = Jadwal::findOrFail($id);

        if ($jadwal->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal ini sudah tidak dalam status pending.',
            ], 400);
        }

        $jadwal->update([
            'status' => 'rejected',
            'approved_by' => $request->input('admin_id', auth()->id()),
            'approved_at' => now(),
            'catatan_admin' => $request->input('catatan'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal ditolak.',
            'data' => $jadwal->fresh(['kelas.mataKuliah', 'kelas.dosen']),
        ]);
    }

    /**
     * Assign room and section to an approved schedule (activates the schedule)
     */
    public function assignRoom(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'ruangan' => 'required|string|max:100',
            'section' => 'required|string|max:10',
        ]);

        $jadwal = Jadwal::with('kelas')->findOrFail($id);

        if ($jadwal->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal harus disetujui terlebih dahulu sebelum assign ruangan.',
            ], 400);
        }

        // Update kelas section
        $jadwal->kelas->update([
            'section' => $request->input('section'),
        ]);

        // Update jadwal
        $jadwal->update([
            'ruangan' => $request->input('ruangan'),
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kelas dan ruangan berhasil di-assign. Jadwal sekarang aktif.',
            'data' => $jadwal->fresh(['kelas.mataKuliah', 'kelas.dosen']),
        ]);
    }

    /**
     * Get all active schedules
     */
    public function active(): JsonResponse
    {
        $activeJadwals = Jadwal::with(['kelas.mataKuliah', 'kelas.dosen'])
            ->where('status', 'active')
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get()
            ->map(function ($jadwal) {
                return [
                    'id' => $jadwal->id,
                    'mata_kuliah' => $jadwal->kelas->mataKuliah->nama,
                    'kode' => $jadwal->kelas->mataKuliah->kode,
                    'sks' => $jadwal->kelas->mataKuliah->sks,
                    'jenis' => $jadwal->kelas->mataKuliah->jenis,
                    'section' => $jadwal->kelas->section,
                    'dosen' => $jadwal->kelas->dosen->name,
                    'hari' => $jadwal->hari,
                    'jam_mulai' => $jadwal->jam_mulai,
                    'jam_selesai' => $jadwal->jam_selesai,
                    'ruangan' => $jadwal->ruangan,
                    'status' => $jadwal->status,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $activeJadwals,
        ]);
    }
}
