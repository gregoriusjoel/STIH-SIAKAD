@extends('layouts.super-admin')

@section('title', 'Activity Monitor')
@section('page-title', 'Activity Monitor')

@section('content')
<div class="space-y-6" x-data="{
    autoRefresh: false,
    intervalId: null,
    countdown: 10,
    init() {
        this.$watch('autoRefresh', value => {
            if (value) {
                this.countdown = 10;
                this.intervalId = setInterval(() => {
                    this.countdown--;
                    if (this.countdown <= 0) {
                        window.location.reload();
                    }
                }, 1000);
            } else {
                clearInterval(this.intervalId);
            }
        });
    }
}">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621] font-bold">query_stats</span>
                Real-Time Activity Monitor
            </h2>
            <p class="text-sm text-slate-500 mt-0.5">Pantau aktivitas login, impersonasi, dan override data secara langsung</p>
        </div>

        {{-- Controls --}}
        <div class="flex items-center gap-4 bg-white border border-slate-200 rounded-xl px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm">
            <div class="flex items-center gap-2">
                <input type="checkbox" x-model="autoRefresh" id="auto-refresh-check"
                    class="w-4 h-4 rounded text-[#7a1621] focus:ring-[#7a1621] border-slate-350 transition">
                <label for="auto-refresh-check" class="cursor-pointer">Auto-Refresh Halaman</label>
            </div>
            <span x-show="autoRefresh" class="text-[#7a1621] font-bold block transition-all" x-text="'Refreshes in ' + countdown + 's'"></span>
            
            <button onclick="window.location.reload()" class="flex items-center gap-1 hover:text-[#7a1621] transition-colors">
                <span class="material-symbols-outlined text-sm font-bold">refresh</span> Refresh
            </button>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="glass-card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                <span class="material-symbols-outlined text-emerald-600 text-lg">login</span>
            </div>
            <div>
                <p class="text-xl font-black text-slate-800">{{ $loginHariIni->count() }}</p>
                <p class="text-[10px] text-slate-500 uppercase font-semibold">Login Hari Ini</p>
            </div>
        </div>
        <div class="glass-card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                <span class="material-symbols-outlined text-slate-655 text-lg">logout</span>
            </div>
            <div>
                <p class="text-xl font-black text-slate-800">{{ $logoutHariIni }}</p>
                <p class="text-[10px] text-slate-500 uppercase font-semibold">Logout Hari Ini</p>
            </div>
        </div>
        <div class="glass-card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center">
                <span class="material-symbols-outlined text-rose-600 text-lg">no_accounts</span>
            </div>
            <div>
                <p class="text-xl font-black text-rose-700">{{ $failedLogins }}</p>
                <p class="text-[10px] text-slate-500 uppercase font-semibold">Login Gagal</p>
            </div>
        </div>
        <div class="glass-card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center">
                <span class="material-symbols-outlined text-sky-600 text-lg">group</span>
            </div>
            <div>
                <p class="text-xl font-black text-sky-700">{{ $activeImpersonations }}</p>
                <p class="text-[10px] text-slate-500 uppercase font-semibold">Impersonasi</p>
            </div>
        </div>
        <div class="glass-card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-[#7a1621]/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-[#7a1621] text-lg">settings_backup_restore</span>
            </div>
            <div>
                <p class="text-xl font-black text-[#7a1621]">{{ $overrideHariIni }}</p>
                <p class="text-[10px] text-slate-500 uppercase font-semibold">Override Center</p>
            </div>
        </div>
    </div>

    {{-- Main Activity Feed --}}
    <div class="glass-card overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-[#7a1621] flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621]">list_alt</span>
                Log Aktivitas Terbaru (30 Log Terakhir)
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs font-bold uppercase text-[#7a1621] bg-[#7a1621]/5 border-b border-[#7a1621]/10">
                    <tr>
                        <th class="px-5 py-3 w-[15%]">Waktu</th>
                        <th class="px-5 py-3 w-[25%]">Pelaku (Actor)</th>
                        <th class="px-5 py-3 w-[20%]">Aksi (Action)</th>
                        <th class="px-5 py-3 w-[40%]">Detail / Metadata</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentActivity as $log)
                    @php
                        $action = $log->action;
                        $badgeClass = 'bg-slate-100 text-slate-600';
                        if (str_contains($action, 'login_failed')) {
                            $badgeClass = 'bg-rose-100 text-rose-700 border-rose-200 border';
                        } elseif (str_contains($action, 'login')) {
                            $badgeClass = 'bg-emerald-100 text-emerald-700 border-emerald-250 border';
                        } elseif (str_contains($action, 'logout')) {
                            $badgeClass = 'bg-slate-100 text-slate-655 border-slate-200 border';
                        } elseif (str_contains($action, 'override')) {
                            $badgeClass = 'bg-[#7a1621]/10 text-[#7a1621] border-[#7a1621]/20 border';
                        } elseif (str_contains($action, 'impersonate')) {
                            $badgeClass = 'bg-sky-100 text-sky-750 border-sky-200 border';
                        }
                    @endphp
                    <tr class="hover:bg-[#7a1621]/5 transition-colors">
                        <td class="px-5 py-3 text-xs text-slate-550">
                            {{ $log->created_at->format('d M Y H:i:s') }}
                        </td>
                        <td class="px-5 py-3 text-xs">
                            <p class="font-bold text-slate-700">{{ $log->actor?->name ?? 'System' }}</p>
                            @if($log->actor?->email)
                                <p class="text-[10px] text-slate-450">{{ $log->actor->email }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold {{ $badgeClass }}">
                                {{ $action }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-xs text-slate-550 max-w-sm truncate" title="{{ json_encode($log->meta) }}">
                            @if($log->meta)
                                <code class="text-[10px] bg-slate-50 border border-slate-100 rounded px-1.5 py-0.5 font-mono text-slate-655">
                                    {{ json_encode($log->meta) }}
                                </code>
                            @else
                                <span class="text-slate-400 italic">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl block mb-2 text-slate-350">query_stats</span>
                            Belum ada log aktivitas hari ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
