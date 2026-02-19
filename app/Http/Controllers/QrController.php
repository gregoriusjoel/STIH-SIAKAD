<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\KelasMataKuliah;
use App\Models\Pertemuan;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrController extends Controller
{
    /**
     * Return QR image PNG for a kelas by token if enabled and not expired.
     * ✅ NOW: Query from pertemuans table
     */
    public function image(\Illuminate\Http\Request $request, $token)
    {
        // ✅ Query from Pertemuan table
        $pertemuan = Pertemuan::where('qr_token', $token)->with('kelasMataKuliah.dosen')->first();
        if (!$pertemuan) {
            abort(404);
        }

        $kelas = $pertemuan->kelasMataKuliah;

        // Allow lecturers and admins to preview/download the QR even if it's not currently enabled
        $user = auth()->user();
        $canBypass = false;
        if ($user) {
            $isAdmin = method_exists($user, 'isAdmin') ? $user->isAdmin() : ($user->role === 'admin');
            $isDosen = method_exists($user, 'isDosen') ? $user->isDosen() : ($user->role === 'dosen');
            if ($isAdmin) {
                $canBypass = true;
            } elseif ($isDosen) {
                // Compare via Dosen record: user_id → dosens.id → kelas_mata_kuliahs.dosen_id
                $dosenRecord = Dosen::where('user_id', $user->id)->first();
                if ($dosenRecord && $kelas->dosen_id == $dosenRecord->id) {
                    $canBypass = true;
                }
            }
        }

        // ✅ Validate QR from Pertemuan record
        if (! $canBypass && !$pertemuan->isQrValid()) {
            abort(410, 'QR code disabled or expired');
        }

        // Generate a URL the QR should point to (attendance/check-in)
        $target = url('/kelas/qr-redirect/' . $pertemuan->qr_token);

        // Support forcing SVG via ?format=svg for reliable inline rendering or download
        $format = strtolower($request->query('format', '')) === 'svg' ? 'svg' : 'png';
        try {
            $body = QrCode::format($format)->size(350)->generate($target);
            $contentType = $format === 'svg' ? 'image/svg+xml' : 'image/png';
            return response($body)->header('Content-Type', $contentType);
        } catch (\Exception $e) {
            // Fall back to SVG if PNG generation fails
            $svg = QrCode::format('svg')->size(350)->generate($target);
            return response($svg)->header('Content-Type', 'image/svg+xml');
        }
    }

    /**
     * Redirect target when scanning QR. Checks enable/expire and then redirects to attendance page.
     * ✅ NOW: Query from pertemuans table
     */
    public function redirect($token)
    {
        // ✅ Query from Pertemuan table
        $pertemuan = Pertemuan::where('qr_token', $token)->first();
        if (!$pertemuan) {
            abort(410, 'QR code not found');
        }

        // ✅ Validate QR from Pertemuan record
        if (!$pertemuan->isQrValid()) {
            abort(410, 'QR code disabled or expired');
        }

        // Redirect to the absen login flow for this kelas (login-based attendance)
        // This preserves the existing permission/expiry checks above but sends scanners
        // to the login-only attendance flow instead of the manual form.
        return redirect()->route('absen.login', ['token' => $pertemuan->qr_token]);
    }
}
