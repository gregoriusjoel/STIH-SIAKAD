<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelasMataKuliah;
use App\Models\Mahasiswa;
use App\Models\Krs;
use App\Models\Presensi;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function showForm($token)
    {
        $kelas = KelasMataKuliah::where('qr_token', $token)->first();
        if (! $kelas) {
            abort(404);
        }
        if (! $kelas->qr_enabled) {
            abort(410, 'QR disabled');
        }
        if ($kelas->qr_expires_at && Carbon::now()->gt($kelas->qr_expires_at)) {
            abort(410, 'QR expired');
        }

        return view('absensi.form', compact('kelas', 'token'));
    }

    public function store(Request $request, $token)
    {
        $kelas = KelasMataKuliah::where('qr_token', $token)->first();
        if (! $kelas) {
            abort(404);
        }
        if (! $kelas->qr_enabled) {
            return back()->withErrors(['qr' => 'QR code is not active.']);
        }
        if ($kelas->qr_expires_at && Carbon::now()->gt($kelas->qr_expires_at)) {
            return back()->withErrors(['qr' => 'QR code has expired.']);
        }

        $data = $request->validate([
            'npm' => 'nullable|string',
            'name' => 'nullable|string',
            'kontak' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $krs = null;

        if (auth()->check() && auth()->user()->mahasiswa) {
            $mahasiswa = auth()->user()->mahasiswa;
            $krs = Krs::where('mahasiswa_id', $mahasiswa->id)->where('kelas_mata_kuliah_id', $kelas->id)->first();
        } else {
            if (empty($data['npm'])) {
                return back()->withErrors(['npm' => 'Masukkan NPM Anda jika tidak login.']);
            }
            $mahasiswa = Mahasiswa::where('npm', $data['npm'])->first();
            if (! $mahasiswa) {
                return back()->withErrors(['npm' => 'Mahasiswa dengan NPM tersebut tidak ditemukan.']);
            }
            $krs = Krs::where('mahasiswa_id', $mahasiswa->id)->where('kelas_mata_kuliah_id', $kelas->id)->first();
        }

        // Prevent duplicate attendance: same mahasiswa can't submit twice for the same kelas
        if (! empty($mahasiswa?->id)) {
            $already = Presensi::where('mahasiswa_id', $mahasiswa->id)
                ->where('kelas_mata_kuliah_id', $kelas->id)
                ->exists();

            if ($already) {
                return redirect()->route('absensi.thanks')->with('info', 'Anda sudah mengisi absensi untuk kelas ini.');
            }
        }

        if (! $krs) {
            \Log::warning('Absensi attempt without KRS — auto-creating KRS', [
                'token' => $token,
                'mahasiswa_id' => $mahasiswa?->id ?? null,
                'request' => $request->all(),
            ]);

            // Auto-create a KRS record so the presensi can be recorded.
            try {
                $krs = Krs::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'kelas_mata_kuliah_id' => $kelas->id,
                    'status' => 'disetujui',
                    'keterangan' => 'Auto-created via QR attendance',
                ]);

                \Log::info('Auto-created KRS for attendance', [
                    'krs_id' => $krs->id,
                    'mahasiswa_id' => $mahasiswa->id,
                    'kelas_mata_kuliah_id' => $kelas->id,
                ]);
            } catch (\Exception $ex) {
                \Log::error('Failed to auto-create KRS for attendance', [
                    'error' => $ex->getMessage(),
                    'mahasiswa_id' => $mahasiswa?->id ?? null,
                    'kelas_id' => $kelas?->id ?? null,
                ]);

                return back()->withErrors(['krs' => 'Gagal membuat KRS otomatis. Silakan hubungi admin.']);
            }
        }

        // Create presensi entry (store additional fields per DB schema)
        $presensi = Presensi::create([
            'krs_id' => $krs->id,
            'mahasiswa_id' => $mahasiswa->id ?? null,
            'kelas_mata_kuliah_id' => $kelas->id ?? null,
            'nama' => $data['name'] ?? $mahasiswa?->user?->name ?? $mahasiswa?->npm ?? null,
            'kontak' => $mahasiswa?->phone ?? $mahasiswa?->no_hp ?? null,
            'tanggal' => Carbon::now()->toDateString(),
            'waktu' => Carbon::now(),
            'status' => 'hadir',
            'keterangan' => $data['keterangan'] ?? null,
        ]);

        if ($presensi) {
            \Log::info('Presensi created', [
                'presensi_id' => $presensi->id,
                'krs_id' => $krs->id,
                'mahasiswa_id' => $mahasiswa->id ?? null,
                'token' => $token,
            ]);
        } else {
            \Log::error('Failed to create presensi', [
                'krs_id' => $krs->id,
                'mahasiswa_id' => $mahasiswa->id ?? null,
                'token' => $token,
            ]);
        }

        return redirect()->route('absensi.thanks');
    }

    public function thanks()
    {
        return view('absensi.thanks');
    }
}
