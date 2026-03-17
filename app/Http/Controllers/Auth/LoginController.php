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
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'mahasiswa') {
                return redirect()->route('mahasiswa.dashboard');
            } elseif ($user->role === 'finance' || $user->role === 'keuangan') {
                return redirect()->route('finance.invoices.index');
            }
            return redirect()->route('dosen.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Allow admin, dosen, mahasiswa, parent, and finance roles
            if (!in_array($user->role, ['admin', 'dosen', 'mahasiswa', 'parent', 'finance', 'keuangan'])) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->with('error', 'Role tidak diizinkan untuk login')
                    ->withInput($request->only('email'));
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
            if ($user->role === 'admin') {
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

        return back()
            ->with('error', 'Email atau password salah.')
            ->withInput($request->only('email'));
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah logout.');
    }
}
