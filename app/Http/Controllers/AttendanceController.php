<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelasMataKuliah;
use App\Models\Mahasiswa;
use App\Models\Krs;
use App\Models\Presensi;
use App\Models\Pertemuan;
use App\Services\LocationService;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function showForm($token)
    {
        $kelas = KelasMataKuliah::where('qr_token', $token)->first();
        if (!$kelas) {
            abort(404);
        }
        if (!$kelas->qr_enabled) {
            // If QR isn't enabled, show the thank-you/closed page to the scanner.
            return redirect()->route('absensi.thanks');
        }

        if ($kelas->qr_expires_at && Carbon::now()->gt($kelas->qr_expires_at)) {
            // If QR expired, persist the disabled state so it is treated as closed,
            // then send the scanner to the thank-you page.
            try {
                $kelas->qr_enabled = false;
                $kelas->save();
                \Log::info('QR token auto-disabled due to expiry', ['kelas_mk_id' => $kelas->id, 'qr_token' => $token]);
            } catch (\Exception $e) {
                \Log::warning('Failed to auto-disable expired QR token', ['error' => $e->getMessage(), 'kelas_mk_id' => $kelas->id]);
            }

            return redirect()->route('absensi.thanks');
        }

        // Provide meeting information to the form
        $totalPertemuan = $kelas->meeting_count ?? 16;
        $currentPertemuan = $kelas->qr_current_pertemuan ?? null;
        
        // Get metode_pengajaran from Pertemuan if available
        $metodePengajaran = 'offline'; // default
        if ($currentPertemuan) {
            $pertemuanRecord = Pertemuan::where('kelas_mata_kuliah_id', $kelas->id)
                ->where('nomor_pertemuan', $currentPertemuan)
                ->first();
            if ($pertemuanRecord && $pertemuanRecord->metode_pengajaran) {
                $metodePengajaran = $pertemuanRecord->metode_pengajaran;
            }
        }

        return view('absensi.form', compact('kelas', 'token', 'totalPertemuan', 'currentPertemuan', 'metodePengajaran'));
    }

    public function store(Request $request, $token)
    {
        $kelas = KelasMataKuliah::where('qr_token', $token)->first();
        if (!$kelas) {
            abort(404);
        }
        if (!$kelas->qr_enabled) {
            // If the QR is not enabled (or has been auto-disabled), send the user to thank-you
            return redirect()->route('absensi.thanks');
        }
        if ($kelas->qr_expires_at && Carbon::now()->gt($kelas->qr_expires_at)) {
            // Auto-disable expired QR so it cannot be reused, then redirect to thank-you
            try {
                $kelas->qr_enabled = false;
                $kelas->save();
                \Log::info('QR token auto-disabled due to expiry on submit', ['kelas_mk_id' => $kelas->id, 'qr_token' => $token]);
            } catch (\Exception $e) {
                \Log::warning('Failed to auto-disable expired QR token on submit', ['error' => $e->getMessage(), 'kelas_mk_id' => $kelas->id]);
            }

            return redirect()->route('absensi.thanks');
        }

        $data = $request->validate([
            'nim' => 'nullable|string',
            'name' => 'nullable|string',
            'kontak' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'pertemuan' => 'nullable|integer',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'reason_category' => 'nullable|string',
            'reason_detail' => 'nullable|string',
        ]);

        // prefer explicit pertemuan from request, otherwise use kelas current setting
        $pertemuan = $request->input('pertemuan') ?? ($kelas->qr_current_pertemuan ?? null);

        // only include pertemuan if column exists in DB
        $canRecordPertemuan = \Illuminate\Support\Facades\Schema::hasColumn('presensis', 'pertemuan');

        // Get metode_pengajaran for this pertemuan
        $metodePengajaran = 'offline'; // default
        $pertemuanRecord = null;
        if ($canRecordPertemuan && $pertemuan) {
            $pertemuanRecord = Pertemuan::where('kelas_mata_kuliah_id', $kelas->id)
                ->where('nomor_pertemuan', $pertemuan)
                ->first();
            if ($pertemuanRecord && $pertemuanRecord->metode_pengajaran) {
                $metodePengajaran = $pertemuanRecord->metode_pengajaran;
            }
        }

        // Extract GPS coordinates
        $studentLat = $request->input('lat') ? (float) $request->input('lat') : null;
        $studentLng = $request->input('lng') ? (float) $request->input('lng') : null;

        // Determine presence mode based on location and meeting method
        $locationData = LocationService::determinePresenceMode($metodePengajaran, $studentLat, $studentLng);
        
        // Validate reason if required (offline meeting but outside radius)
        if ($locationData['requires_reason']) {
            if (empty($data['reason_category'])) {
                return back()->withErrors([
                    'reason_category' => 'Anda berada di luar radius kampus. Harap pilih alasan kehadiran online.'
                ])->withInput();
            }
            
            // If reason is "Lainnya", detail is required
            if ($data['reason_category'] === 'Lainnya' && empty($data['reason_detail'])) {
                return back()->withErrors([
                    'reason_detail' => 'Harap isi detail alasan.'
                ])->withInput();
            }
        }

        $krs = null;

        if (auth()->check() && auth()->user()->mahasiswa) {
            $mahasiswa = auth()->user()->mahasiswa;
            $krs = Krs::where('mahasiswa_id', $mahasiswa->id)->where('kelas_mata_kuliah_id', $kelas->id)->first();
        } else {
            if (empty($data['nim'])) {
                return back()->withErrors(['nim' => 'Masukkan NIM Anda jika tidak login.']);
            }
            $mahasiswa = Mahasiswa::where('nim', $data['nim'])->first();
            if (!$mahasiswa) {
                return back()->withErrors(['nim' => 'Mahasiswa dengan NIM tersebut tidak ditemukan.']);
            }
            $krs = Krs::where('mahasiswa_id', $mahasiswa->id)->where('kelas_mata_kuliah_id', $kelas->id)->first();
        }

        // Prevent duplicate attendance: same mahasiswa can't submit twice for the same kelas
        if (!empty($mahasiswa?->id)) {
            $alreadyQuery = Presensi::where('mahasiswa_id', $mahasiswa->id)
                ->where('kelas_mata_kuliah_id', $kelas->id);
            if ($canRecordPertemuan && !is_null($pertemuan)) {
                $alreadyQuery->where('pertemuan', $pertemuan);
            }
            $already = $alreadyQuery->exists();

            if ($already) {
                return redirect()->route('absensi.thanks')->with('info', 'Anda sudah mengisi absensi untuk kelas ini.');
            }
        }

        if (!$krs) {
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
                    'status' => 'approved',
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
        $createData = [
            'krs_id' => $krs->id,
            'mahasiswa_id' => $mahasiswa->id ?? null,
            'kelas_mata_kuliah_id' => $kelas->id ?? null,
            'nama' => $data['name'] ?? $mahasiswa?->user?->name ?? $mahasiswa?->nim ?? null,
            'kontak' => $mahasiswa?->phone ?? $mahasiswa?->no_hp ?? null,
            'tanggal' => Carbon::now()->toDateString(),
            'waktu' => Carbon::now(),
            'status' => 'hadir',
            'keterangan' => $data['keterangan'] ?? null,
            // Location-based fields
            'student_lat' => $studentLat,
            'student_lng' => $studentLng,
            'distance_meters' => $locationData['distance_meters'],
            'presence_mode' => $locationData['presence_mode'],
            'reason_category' => $locationData['requires_reason'] ? ($data['reason_category'] ?? null) : null,
            'reason_detail' => $locationData['requires_reason'] ? ($data['reason_detail'] ?? null) : null,
            'campus_lat' => LocationService::CAMPUS_LAT,
            'campus_lng' => LocationService::CAMPUS_LNG,
            'radius_meters' => LocationService::CAMPUS_RADIUS_METERS,
        ];

        if ($canRecordPertemuan && !is_null($pertemuan)) {
            $createData['pertemuan'] = $pertemuan;
        }

        $presensi = Presensi::create($createData);

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

        return redirect()->route('absensi.thanks')->with([
            'kelas_id' => $kelas->id,
            'pertemuan' => $pertemuan ?? 1, // Default to 1 if null
            'mata_kuliah' => $kelas->mataKuliah->nama
        ]);
    }

    public function thanks()
    {
        $kelasId = session('kelas_id');

        // Dynamic Dummy ID Fallback
        if (!$kelasId) {
            $user = auth()->user();
            if ($user && $user->role === 'dosen') {
                $kelasId = \App\Models\KelasMataKuliah::where('dosen_id', $user->dosen->id ?? 0)->value('id');
            } elseif ($user && $user->mahasiswa) {
                // Get ANY active class for this student
                $kelasId = \App\Models\Krs::where('mahasiswa_id', $user->mahasiswa->id)->value('kelas_mata_kuliah_id');
            }

            // Absolute fallback if no classes found
            $kelasId = $kelasId ?? 1;
        }

        $pertemuan = session('pertemuan', 1);
        $mataKuliah = session('mata_kuliah') ?? 'Hukum Pidana (Dummy)';

        // Force Dummy Materials to always show
        $materials = [
            [
                'name' => "Materi Pertemuan $pertemuan - Slide.pdf",
                'url' => '#',
                'type' => 'pdf'
            ],
            [
                'name' => "Referensi Pertemuan $pertemuan.docx",
                'url' => '#',
                'type' => 'doc'
            ]
        ];

        return view('absensi.thanks', compact('materials', 'pertemuan', 'mataKuliah', 'kelasId'));
    }
}
