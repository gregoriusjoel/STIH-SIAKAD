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
    public function image(\Illuminate\Http\Request $request, $token)
    {
        $kelas = KelasMataKuliah::where('qr_token', $token)->first();
        if (!$kelas) {
            abort(404);
        }

        // Allow lecturers and admins to preview/download the QR even if it's not currently enabled
        $user = auth()->user();
        $canBypass = false;
        if ($user) {
            $isDosen = method_exists($user, 'isDosen') ? $user->isDosen() : ($user->role === 'dosen');
            $isAdmin = method_exists($user, 'isAdmin') ? $user->isAdmin() : ($user->role === 'admin');
            if ($isAdmin) $canBypass = true;
            if ($isDosen && $kelas->dosen_id && $user->id == $kelas->dosen_id) $canBypass = true;
        }

        if (! $canBypass) {
            if (!$kelas->qr_enabled) {
                abort(410, 'QR code disabled');
            }

            if ($kelas->qr_expires_at && Carbon::now()->gt($kelas->qr_expires_at)) {
                abort(410, 'QR code expired');
            }
        }

        // Generate a URL the QR should point to (attendance/check-in)
        $target = url('/kelas/qr-redirect/' . $kelas->qr_token);

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

        // Redirect to the absen login flow for this kelas (login-based attendance)
        // This preserves the existing permission/expiry checks above but sends scanners
        // to the login-only attendance flow instead of the manual form.
        return redirect()->route('absen.login', ['token' => $kelas->qr_token]);
    }
}
