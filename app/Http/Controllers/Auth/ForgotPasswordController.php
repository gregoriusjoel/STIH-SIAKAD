<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan halaman konfirmasi email pribadi untuk Forgot Password.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot_password');
    }

    /**
     * Konfirmasi email pribadi mahasiswa & kirim link reset password.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Silakan masukkan alamat email pribadi Anda.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $emailInput = trim($request->email);

        // Cari Mahasiswa berdasarkan email_pribadi atau email_kampus atau user email
        $mahasiswa = Mahasiswa::where('email_pribadi', $emailInput)
            ->orWhere('email_kampus', $emailInput)
            ->orWhereHas('user', function ($q) use ($emailInput) {
                $q->where('email', $emailInput);
            })
            ->first();

        if (!$mahasiswa) {
            return back()->withInput()->with('error', 'Email tidak ditemukan dalam data mahasiswa kami. Silakan periksa kembali email Anda.');
        }

        // Generate token reset password
        $token = Str::random(60);
        $mahasiswa->password_reset_token = $token;
        $mahasiswa->save();

        // Tentukan target email & URL reset
        $targetEmail = $mahasiswa->email_pribadi ?: $emailInput;
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $targetEmail]);
        $namaMahasiswa = $mahasiswa->nama ?: ($mahasiswa->user->name ?? 'Mahasiswa');

        // Kirim email
        try {
            Mail::to($targetEmail)->send(new \App\Mail\ResetPasswordMail($namaMahasiswa, $resetUrl));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email reset password: ' . $e->getMessage());
        }

        return back()->with([
            'success' => 'Link reset password telah berhasil dikirimkan ke email pribadi Anda (' . $targetEmail . '). Silakan periksa kotak masuk (Inbox) atau folder Spam email Anda.',
            'dev_reset_url' => $resetUrl,
            'target_email' => $targetEmail,
        ]);
    }

    /**
     * Tampilkan halaman Form Reset Password ketika link dari email diklik.
     */
    public function showResetForm(Request $request, $token)
    {
        $email = $request->query('email');

        // Verifikasi token
        $mahasiswa = Mahasiswa::where('password_reset_token', $token)->first();

        if (!$mahasiswa) {
            return redirect()->route('login.mahasiswa')->with('error', 'Link reset password tidak valid atau telah digunakan.');
        }

        return view('auth.reset_password', [
            'token' => $token,
            'email' => $email ?: ($mahasiswa->email_pribadi ?: $mahasiswa->email_kampus),
        ]);
    }

    /**
     * Proses perbaruan password baru.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.required' => 'Password baru harus diisi.',
            'password.min' => 'Password baru minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Verifikasi mahasiswa berdasarkan token
        $mahasiswa = Mahasiswa::where('password_reset_token', $request->token)->first();

        if (!$mahasiswa) {
            return back()->with('error', 'Token reset password tidak valid atau telah kadaluarsa.');
        }

        // Update password pada User akun
        $user = $mahasiswa->user;
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Hapus token reset password & update status password
        $mahasiswa->password_reset_token = null;
        $mahasiswa->is_default_password = false;
        $mahasiswa->save();

        return redirect()->route('login.mahasiswa')->with('success', 'Password Anda berhasil diperbarui! Silakan masuk menggunakan password baru Anda.');
    }
}
