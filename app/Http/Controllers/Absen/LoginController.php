<?php

namespace App\Http\Controllers\Absen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KelasMataKuliah;
use App\Models\Mahasiswa;
use App\Models\Krs;
use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $token = $request->query('token');
        $kelas = null;
        if ($token) {
            $kelas = KelasMataKuliah::where('qr_token', $token)
                ->with(['mataKuliah', 'dosen.user'])
                ->first();
            if (!$kelas) {
                abort(404);
            }
            if (! $kelas->qr_enabled || ($kelas->qr_expires_at && Carbon::now()->gt($kelas->qr_expires_at))) {
                return view('absen.login', compact('kelas', 'token'))->withErrors(['token' => 'Kelas tidak aktif untuk absen.']);
            }
        }

        return view('absen.login', compact('kelas', 'token'));
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
            'token' => 'required|string',
        ]);

        $token = $data['token'];
        $kelas = KelasMataKuliah::where('qr_token', $token)->first();
        if (! $kelas) {
            return back()->withErrors(['token' => 'Kelas tidak ditemukan atau token tidak valid.']);
        }

        if (! $kelas->qr_enabled || ($kelas->qr_expires_at && Carbon::now()->gt($kelas->qr_expires_at))) {
            return back()->withErrors(['token' => 'Kelas tidak aktif untuk absen.']);
        }

        $identifier = $data['identifier'];
        $user = User::where('email', $identifier)->first();

        if (! $user) {
            $mahasiswaLookup = Mahasiswa::where('nim', $identifier)->first();
            if ($mahasiswaLookup && $mahasiswaLookup->user) {
                $user = $mahasiswaLookup->user;
            }
        }

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['identifier' => 'Kredensial salah.']);
        }

        if ($user->role !== 'mahasiswa' || ! $user->mahasiswa) {
            return back()->withErrors(['identifier' => 'Hanya akun mahasiswa yang dapat melakukan absen.']);
        }

        $mahasiswa = $user->mahasiswa;

        $krs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('kelas_mata_kuliah_id', $kelas->id)
            ->first();

        if (! $krs) {
            return back()->withErrors(['identifier' => 'Anda tidak terdaftar di kelas ini.']);
        }

        // Prevent duplicate attendance for same class/date or pertemuan (if present)
        $canRecordPertemuan = Schema::hasColumn('presensis', 'pertemuan');
        $pertemuan = $kelas->qr_current_pertemuan ?? null;

        $alreadyQuery = Presensi::where('mahasiswa_id', $mahasiswa->id)
            ->where('kelas_mata_kuliah_id', $kelas->id);

        if ($canRecordPertemuan && ! is_null($pertemuan)) {
            $alreadyQuery->where('pertemuan', $pertemuan);
        } else {
            // fallback: prevent same tanggal
            $alreadyQuery->where('tanggal', Carbon::now()->toDateString());
        }

        if ($alreadyQuery->exists()) {
            Auth::guard('mahasiswa_absen')->loginUsingId($user->id);
            session([
                'kelas_id' => $kelas->id,
                'pertemuan' => $pertemuan,
                'mata_kuliah' => $kelas->mataKuliah->nama_mk ?? null,
                'presensi_exists' => true,
            ]);

            return redirect()->route('absen.thankyou');
        }

        // Create presensi
        $createData = [
            'krs_id' => $krs->id,
            'mahasiswa_id' => $mahasiswa->id,
            'kelas_mata_kuliah_id' => $kelas->id,
            'nama' => $user->name ?? $mahasiswa->nim,
            'kontak' => $mahasiswa->phone ?? $mahasiswa->no_hp ?? null,
            'tanggal' => Carbon::now()->toDateString(),
            'waktu' => Carbon::now(),
            'status' => 'hadir',
            'keterangan' => null,
        ];

        if ($canRecordPertemuan && ! is_null($pertemuan)) {
            $createData['pertemuan'] = $pertemuan;
        }

        $meta = [];
        if ($request->ip()) {
            $meta[] = 'ip:' . $request->ip();
        }
        if ($request->header('User-Agent')) {
            $meta[] = 'ua:' . substr($request->header('User-Agent'), 0, 250);
        }
        if (! empty($meta)) {
            // store optional metadata in keterangan (DB has no ip/user_agent columns)
            $createData['keterangan'] = implode(' | ', $meta);
        }

        $presensi = Presensi::create($createData);

        if ($presensi) {
            Auth::guard('mahasiswa_absen')->loginUsingId($user->id);
            session([
                'kelas_id' => $kelas->id,
                'pertemuan' => $pertemuan,
                'mata_kuliah' => $kelas->mataKuliah->nama_mk ?? null,
                'presensi_exists' => false,
                'presensi_id' => $presensi->id,
            ]);

            return redirect()->route('absen.thankyou');
        }

        return back()->withErrors(['identifier' => 'Gagal mencatat absen. Silakan coba lagi.']);
    }

    public function thankYou(Request $request)
    {
        $user = Auth::guard('mahasiswa_absen')->user();
        if (! $user) {
            return redirect()->route('absen.login');
        }

        $mahasiswa = $user->mahasiswa;
        $kelasId = session('kelas_id');
        $pertemuan = session('pertemuan');
        $mataKuliah = session('mata_kuliah');
        $presensiId = session('presensi_id');

        $presensi = null;
        if ($presensiId) {
            $presensi = Presensi::find($presensiId);
        }

        if (! $presensi) {
            $presensi = Presensi::where('mahasiswa_id', $mahasiswa->id)
                ->where('kelas_mata_kuliah_id', $kelasId)
                ->orderBy('created_at', 'desc')
                ->first();
        }
        
        if (!$mataKuliah && $kelasId) {
            $kelas = KelasMataKuliah::with('mataKuliah')->find($kelasId);
            if ($kelas && $kelas->mataKuliah) {
                $mataKuliah = $kelas->mataKuliah->nama_mk;
            }
        }

        return view('absen.thank-you', compact('user', 'mahasiswa', 'kelasId', 'mataKuliah', 'presensi', 'pertemuan'));
    }
}
