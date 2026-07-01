<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    /**
     * Impersonation Center — history and active sessions
     */
    public function center()
    {
        $history = AuditLog::where('action', 'like', 'user.impersonate%')
            ->with('actor')
            ->orderByDesc('created_at')
            ->paginate(20);

        $users = User::with('roles')
            ->whereDoesntHave('roles', fn($q) => $q->where('name', 'super_admin'))
            ->orderBy('name')
            ->get();

        $isImpersonating = session()->has('impersonator_id');

        return view('super-admin.impersonation-center', compact('history', 'users', 'isImpersonating'));
    }

    /**
     * Start impersonating a user.
     * Guards: Cannot impersonate self or another super_admin.
     */
    public function start(Request $request, User $user)
    {
        // Guard: cannot impersonate self
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat meng-impersonate diri sendiri.');
        }

        // Guard: cannot impersonate another super_admin
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Tidak dapat meng-impersonate Super Admin lain.');
        }

        $impersonator = Auth::user();

        // Write audit log BEFORE switching session
        AuditLog::log(
            action: 'user.impersonate_start',
            auditable: $user,
            meta: [
                'impersonator_id'    => $impersonator->id,
                'impersonator_email' => $impersonator->email,
                'target_id'          => $user->id,
                'target_email'       => $user->email,
                'target_role'        => $user->roles->first()?->name ?? $user->role,
                'reason'             => $request->input('reason', 'Tidak ada alasan'),
            ]
        );

        // Store impersonator data in session
        session([
            'impersonator_id'          => $impersonator->id,
            'impersonation_started_at' => now()->toISOString(),
            'impersonation_reason'     => $request->input('reason', ''),
        ]);

        // Login as target user
        Auth::login($user);

        // Redirect based on target user's role
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('success', "Sesi Impersonasi Aktif: Anda login sebagai {$user->name}");
        } elseif ($user->isDosen()) {
            return redirect()->route('dosen.dashboard')
                ->with('success', "Sesi Impersonasi Aktif: Anda login sebagai {$user->name}");
        } elseif ($user->isMahasiswa()) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('success', "Sesi Impersonasi Aktif: Anda login sebagai {$user->name}");
        } elseif ($user->isFinance()) {
            return redirect()->route('finance.dashboard')
                ->with('success', "Sesi Impersonasi Aktif: Anda login sebagai {$user->name}");
        } elseif ($user->isParent()) {
            return redirect()->route('parent.dashboard')
                ->with('success', "Sesi Impersonasi Aktif: Anda login sebagai {$user->name}");
        }

        return redirect('/')->with('success', "Sesi Impersonasi Aktif: Anda login sebagai {$user->name}");
    }

    /**
     * Stop impersonating and return to Super Admin.
     */
    public function stop()
    {
        if (!session()->has('impersonator_id')) {
            return redirect('/')->with('error', 'Tidak sedang dalam sesi impersonasi.');
        }

        $impersonatorId = session('impersonator_id');
        $startedAt      = session('impersonation_started_at');
        $impersonator   = User::find($impersonatorId);

        if (!$impersonator) {
            session()->forget(['impersonator_id', 'impersonation_started_at', 'impersonation_reason']);
            Auth::logout();
            return redirect()->route('login')->with('error', 'Sesi impersonator tidak valid.');
        }

        $currentUser     = Auth::user();
        $durationSeconds = $startedAt ? now()->diffInSeconds(\Carbon\Carbon::parse($startedAt)) : null;

        // Write audit log BEFORE switching back
        AuditLog::log(
            action: 'user.impersonate_stop',
            auditable: $currentUser,
            meta: [
                'impersonator_id'    => $impersonator->id,
                'impersonator_email' => $impersonator->email,
                'target_id'          => $currentUser->id,
                'target_email'       => $currentUser->email,
                'duration_seconds'   => $durationSeconds,
                'reason'             => session('impersonation_reason', ''),
            ]
        );

        // Login back as Super Admin
        Auth::login($impersonator);

        // Clear impersonation session data
        session()->forget(['impersonator_id', 'impersonation_started_at', 'impersonation_reason']);

        return redirect()->route('super-admin.search')
            ->with('success', 'Kembali ke sesi Super Admin.');
    }
}
