<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\DosenAttendance;
use App\Models\Kelas;
use App\Models\KelasMataKuliah;
use App\Models\Pertemuan;
use App\Services\ActiveMeetingResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DosenAttendanceController extends Controller
{
    /**
     * Update metode pengajaran for a specific pertemuan.
     * PATCH /dosen/kelas/{id}/pertemuan/{pertemuan}/metode
     */
    public function updateMetode(Request $request, $id, $pertemuanNo)
    {
        $request->validate([
            'metode_pengajaran' => 'required|in:offline,online,asynchronous',
        ]);

        $kelas = Kelas::findOrFail($id);

        $kelasMataKuliah = KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where('kode_kelas', $kelas->section)
            ->where('dosen_id', $kelas->dosen_id)
            ->first();

        if (!$kelasMataKuliah) {
            return back()->with('error', 'Data kelas mata kuliah tidak ditemukan.');
        }

        if ($request->metode_pengajaran === 'online') {
            $request->validate([
                'online_meeting_link' => 'nullable|url',
            ]);
            
            // Check if both custom and class general link are empty
            if (empty($request->online_meeting_link) && empty($kelasMataKuliah->online_meeting_link)) {
                return back()->withErrors([
                    'online_meeting_link' => 'Link meeting harus diisi jika kelas tidak memiliki link umum.',
                ])->withInput();
            }
        }

        // Resolve tipe_pertemuan from route parameter (supports "kuliah:3", "uts:1", or plain int)
        $resolver = app(ActiveMeetingResolver::class);
        if (str_contains((string) $pertemuanNo, ':')) {
            [$tipe, $nomor] = explode(':', $pertemuanNo, 2);
            $nomor = (int) $nomor;
        } else {
            $mapped = $resolver->slotToTipeNomor((int) $pertemuanNo);
            $tipe = $mapped['tipe'];
            $nomor = $mapped['nomor'];
        }

        $pertemuan = $resolver->findOrCreatePertemuan($kelasMataKuliah, $tipe, $nomor);

        $pertemuan->metode_pengajaran = $request->metode_pengajaran;
        if ($request->metode_pengajaran === 'online') {
            $pertemuan->online_meeting_link = $request->online_meeting_link;
        } else {
            $pertemuan->online_meeting_link = null;
        }
        $pertemuan->save();

        return back()->with('success', 'Metode pengajaran berhasil diperbarui ke ' . ucfirst($request->metode_pengajaran) . '.');
    }

    /**
     * Activate QR with dosen password verification + record dosen attendance.
     * POST /dosen/kelas/{id}/pertemuan/{pertemuan}/activate-qr-password
     */
    public function activateQrWithPassword(Request $request, $id, $pertemuanNo)
    {
        $request->validate([
            'password'  => 'required|string',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $user  = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return back()->with('error', 'Data dosen tidak ditemukan.');
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Password tidak cocok. Silakan coba lagi.');
        }

        $kelas = Kelas::findOrFail($id);

        $kelasMataKuliah = KelasMataKuliah::where('mata_kuliah_id', $kelas->mata_kuliah_id)
            ->where('kode_kelas', $kelas->section)
            ->where('dosen_id', $kelas->dosen_id)
            ->first();

        if (!$kelasMataKuliah) {
            return back()->with('error', 'Data kelas mata kuliah tidak ditemukan.');
        }

         // Aktivasi QR — resolve tipe_pertemuan from route param
        $resolver = app(ActiveMeetingResolver::class);
        if (str_contains((string) $pertemuanNo, ':')) {
            [$tipe, $nomor] = explode(':', $pertemuanNo, 2);
            $nomor = (int) $nomor;
        } else {
            $mapped = $resolver->slotToTipeNomor((int) $pertemuanNo);
            $tipe = $mapped['tipe'];
            $nomor = $mapped['nomor'];
        }

        $pertemuan = $resolver->findOrCreatePertemuan($kelasMataKuliah, $tipe, $nomor, [
            'metode_pengajaran' => 'offline',
        ]);

        // Do not activate QR for asynchronous meetings
        if ($pertemuan->metode_pengajaran === 'asynchronous') {
            return back()->with('error', 'QR tidak dapat diaktifkan untuk pertemuan asynchronous.');
        }

        $pertemuan->activateQr(5);

        // ── Catat absensi dosen (idempotent) ─────────────────────────────────
        $jadwal = $kelas->jadwals()->where('status', 'active')->first();

        DosenAttendance::updateOrCreate(
            [
                'dosen_id'     => $dosen->id,
                'pertemuan_id' => $pertemuan->id,
            ],
            [
                'kelas_mata_kuliah_id' => $kelasMataKuliah->id,
                'metode_pengajaran'    => $pertemuan->metode_pengajaran,
                'jam_kelas_mulai'      => $jadwal ? substr($jadwal->jam_mulai, 0, 5) : null,
                'jam_kelas_selesai'    => $jadwal ? substr($jadwal->jam_selesai, 0, 5) : null,
                'jam_absen_dosen'      => now(),
                'ip_address'           => $request->ip(),
                'user_agent'           => $request->userAgent(),
                'lokasi_dosen'         => $request->latitude . ',' . $request->longitude,
            ]
        );

        return back()->with('success', 'QR ditampilkan selama 5 menit. Absensi Anda telah dicatat.');
    }
}
