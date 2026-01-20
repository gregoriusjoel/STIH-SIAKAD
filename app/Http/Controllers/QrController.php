<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelasMataKuliah;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrController extends Controller
{
    /**
     * Return QR image PNG for a kelas by token if enabled and not expired.
     */
    public function image($token)
    {
        $kelas = KelasMataKuliah::where('qr_token', $token)->first();
        if (!$kelas) {
            abort(404);
        }

        if (!$kelas->qr_enabled) {
            abort(410, 'QR code disabled');
        }

        if ($kelas->qr_expires_at && Carbon::now()->gt($kelas->qr_expires_at)) {
            abort(410, 'QR code expired');
        }

        // Generate a URL the QR should point to (attendance/check-in)
        $target = url('/kelas/qr-redirect/' . $kelas->qr_token);

        try {
            $png = QrCode::format('png')->size(350)->generate($target);
            return response($png)->header('Content-Type', 'image/png');
        } catch (\Exception $e) {
            // Common failure: imagick PHP extension missing. Fall back to SVG output.
            $svg = QrCode::format('svg')->size(350)->generate($target);
            return response($svg)->header('Content-Type', 'image/svg+xml');
        }
    }

    /**
     * Redirect target when scanning QR. Checks enable/expire and then redirects to attendance page.
     */
    public function redirect($token)
    {
        $kelas = KelasMataKuliah::where('qr_token', $token)->first();
        if (!$kelas || !$kelas->qr_enabled) {
            abort(410);
        }
        if ($kelas->qr_expires_at && Carbon::now()->gt($kelas->qr_expires_at)) {
            abort(410);
        }

        // Redirect to the public attendance form for this kelas
        return redirect()->route('absensi.form', ['token' => $kelas->qr_token]);
    }
}
