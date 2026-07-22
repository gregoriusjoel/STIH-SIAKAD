<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'super_admin') {
                return redirect()->route('super-admin.search');
            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'mahasiswa') {
                return redirect()->route('mahasiswa.dashboard');
            } elseif ($user->role === 'parent') {
                return redirect()->route('parent.dashboard');
            } elseif ($user->role === 'finance' || $user->role === 'keuangan') {
                return redirect()->route('finance.invoices.index');
            }
            return redirect()->route('dosen.dashboard');
        }

        // 1. Deteksi portal login berdasarkan path URL atau Subdomain
        $host = strtolower($request->getHost());
        
        if ($request->is('mahasiswa*') || str_starts_with($host, 'mahasiswa.')) {
            return view('auth.login_mahasiswa');
        }
        
        if ($request->is('dosen*') || str_starts_with($host, 'dosen.')) {
            return view('auth.login_dosen');
        }
        
        if ($request->is('parent*') || str_starts_with($host, 'parent.')) {
            return view('auth.login_parent');
        }

        // Fallback ke login utama (Admin/Staff/Keuangan)
        return view('auth.login');
    }

    /**
     * Handle login request.
     * Support login dengan email utama, email_pribadi, atau email_kampus,
     * serta memvalidasi kesesuaian peran dengan portal masuk.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');
        $email = $credentials['email'];

        // Deteksi portal login berdasarkan rute/request saat ini
        $portal = 'admin'; // default
        $host = strtolower($request->getHost());
        if ($request->is('mahasiswa*') || str_starts_with($host, 'mahasiswa.')) {
            $portal = 'mahasiswa';
        } elseif ($request->is('dosen*') || str_starts_with($host, 'dosen.')) {
            $portal = 'dosen';
        } elseif ($request->is('parent*') || str_starts_with($host, 'parent.')) {
            $portal = 'parent';
        }

        // Cari user yang sesuai berdasarkan email login
        $user = \App\Models\User::where('email', $email)->first();
        if (!$user) {
            $mahasiswa = \App\Models\Mahasiswa::where('email_pribadi', $email)
                ->orWhere('email_kampus', $email)
                ->first();
            if ($mahasiswa) {
                $user = $mahasiswa->user;
            }
        }

        // Cek kecocokan password & batasan hak akses portal
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Batasan hak akses portal HANYA diaktifkan jika diakses lewat subdomain domain asli (bukan IP lokal / localhost)
            $isIpOrLocal = filter_var($host, FILTER_VALIDATE_IP) || $host === 'localhost' || $host === '127.0.0.1';
            
            if (!$isIpOrLocal) {
                if ($portal === 'mahasiswa' && $user->role !== 'mahasiswa') {
                    return back()->with('error', 'Akun Anda tidak terdaftar sebagai Mahasiswa pada portal ini.')->withInput($request->only('email'));
                }
                if ($portal === 'dosen' && $user->role !== 'dosen') {
                    return back()->with('error', 'Akun Anda tidak terdaftar sebagai Dosen pada portal ini.')->withInput($request->only('email'));
                }
                if ($portal === 'parent' && $user->role !== 'parent') {
                    return back()->with('error', 'Akun Anda tidak terdaftar sebagai Orang Tua/Wali pada portal ini.')->withInput($request->only('email'));
                }
                if ($portal === 'admin' && !in_array($user->role, ['super_admin', 'admin', 'finance', 'keuangan'])) {
                    return back()->with('error', 'Akun Anda tidak memiliki akses ke portal Admin/Staff.')->withInput($request->only('email'));
                }
            }

            // Sukses verifikasi role, jalankan session login Laravel
            Auth::login($user, $remember);
            return $this->handleSuccessfulLogin($request);
        }

        return back()
            ->with('error', 'Email atau password salah.')
            ->withInput($request->only('email'));
    }

    /**
     * Handle successful login
     */
    private function handleSuccessfulLogin(Request $request)
    {
        $user = Auth::user();

        // Allow super_admin, admin, dosen, mahasiswa, parent, and finance roles
        if (!in_array($user->role, ['super_admin', 'admin', 'dosen', 'mahasiswa', 'parent', 'finance', 'keuangan'])) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            abort(403, 'Role tidak diizinkan untuk login');
        }

        $request->session()->regenerate();

        \App\Models\AuditLog::log('user.login', $user, [
            'ip' => $request->ip(),
            'role' => $user->role
        ]);

        $request->session()->put('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]);

        // Redirect based on role
        if ($user->role === 'super_admin') {
            return redirect()->route('super-admin.search')
                ->with('login_success', 'Selamat datang, ' . $user->name . '!');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('login_success', 'Selamat datang, ' . $user->name . '!');
        } elseif ($user->role === 'mahasiswa') {
            return redirect()->route('mahasiswa.dashboard')
                ->with('login_success', 'Selamat datang, ' . $user->name . '!');
        } elseif ($user->role === 'parent') {
            return redirect()->route('parent.dashboard')
                ->with('login_success', 'Selamat datang, ' . $user->name . '!');
        } elseif ($user->role === 'finance' || $user->role === 'keuangan') {
            return redirect()->route('finance.invoices.index')
                ->with('login_success', 'Selamat datang, ' . $user->name . '!');
        }

        return redirect()->route('dosen.dashboard')
            ->with('login_success', 'Selamat datang, ' . $user->name . '!');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        $host = strtolower($request->getHost());

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke portal login yang sesuai berdasarkan host asal request logout
        if (str_starts_with($host, 'mahasiswa.')) {
            return redirect()->route('login.mahasiswa')->with('success', 'Anda telah logout.');
        } elseif (str_starts_with($host, 'dosen.')) {
            return redirect()->route('login.dosen')->with('success', 'Anda telah logout.');
        } elseif (str_starts_with($host, 'parent.')) {
            return redirect()->route('login.parent')->with('success', 'Anda telah logout.');
        }

        return redirect()->route('login')
            ->with('success', 'Anda telah logout.');
    }
}
