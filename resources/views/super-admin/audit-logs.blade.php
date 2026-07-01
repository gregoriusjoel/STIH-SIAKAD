@extends('layouts.super-admin')

@section('title', 'Audit Trail Logs')
@section('page-title', 'Audit Trail Logs')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="glass-card p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621]">receipt_long</span>
                Audit Trail Logs
            </h2>
            <p class="text-sm text-slate-500 mt-1">Seluruh aktivitas krusial yang dicatat oleh sistem keamanan — siapa, kapan, apa, dan detail perubahannya.</p>
        </div>
        <div class="text-right">
            <span class="text-xs text-slate-400 font-medium block">Total Log</span>
            <span class="text-2xl font-black text-slate-800">{{ number_format($logs->total()) }}</span>
        </div>
    </div>
 
    {{-- Filters --}}
    <div class="glass-card p-5">
        <form method="GET" action="{{ route('super-admin.audit-logs') }}" class="flex flex-wrap gap-4 items-end">
            {{-- Action filter --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tipe Aksi</label>
                <select name="action" class="text-sm border border-slate-200 bg-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#7a1621] outline-none min-w-[150px]">
                    <option value="">Semua Aksi</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                            {{ ucwords(str_replace(['.', '_', '-'], ' ', $action)) }}
                        </option>
                    @endforeach
                </select>
            </div>
 
            {{-- Module filter --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Modul</label>
                <select name="module" class="text-sm border border-slate-200 bg-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#7a1621] outline-none min-w-[140px]">
                    <option value="">Semua Modul</option>
                    @foreach($modules as $mod)
                        <option value="{{ $mod }}" {{ request('module') == $mod ? 'selected' : '' }}>
                            {{ ucwords(str_replace(['.', '_', '-'], ' ', $mod)) }}
                        </option>
                    @endforeach
                </select>
            </div>
 
            {{-- Start Date --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Mulai Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="text-sm border border-slate-200 bg-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#7a1621] outline-none">
            </div>
 
            {{-- End Date --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Hingga Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="text-sm border border-slate-200 bg-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#7a1621] outline-none">
            </div>
 
            {{-- Search --}}
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Pencarian Kata Kunci</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Nama aktor, email, modul, aksi..."
                    class="w-full text-sm border border-slate-200 bg-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#7a1621] outline-none">
            </div>
 
            <div class="flex gap-2">
                <button type="submit" class="btn-maroon px-5 py-2 rounded-xl text-sm font-bold shadow-sm flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm font-bold">filter_alt</span> Filter
                </button>
                <a href="{{ route('super-admin.audit-logs') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-200 transition">
                    Reset
                </a>
                <a href="{{ route('super-admin.audit-logs.export', request()->query()) }}"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-sm flex items-center gap-1 transition">
                    <span class="material-symbols-outlined text-sm font-bold">download</span> Export CSV
                </a>
            </div>
        </form>
    </div>
 
    {{-- Logs Table --}}
    <div id="search-results" class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500">
                <thead class="text-xs uppercase bg-[#7a1621]/5 text-[#7a1621] border-b border-[#7a1621]/10">
                    <tr>
                        <th class="px-4 py-3.5">Waktu</th>
                        <th class="px-4 py-3.5">Aktor</th>
                        <th class="px-4 py-3.5">Role</th>
                        <th class="px-4 py-3.5">Aksi</th>
                        <th class="px-4 py-3.5">Modul</th>
                        <th class="px-4 py-3.5">Entitas</th>
                        <th class="px-4 py-3.5 text-center">Detail</th>
                        <th class="px-4 py-3.5">IP</th>
                    </tr>
                </thead>
                @forelse($logs as $log)
                    <tbody x-data="{ open: false }" class="bg-white divide-y divide-slate-100 border-t border-slate-100 first:border-t-0">
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-4 py-3 whitespace-nowrap text-xs font-semibold text-slate-605">
                                {{ $log->created_at->format('d/m/Y') }}<br>
                                <span class="text-slate-400 font-normal text-[10px]">{{ $log->created_at->format('H:i:s') }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="font-bold text-slate-800 block text-xs">
                                    {{ $log->actor ? $log->actor->name : 'System' }}
                                </span>
                                <span class="text-[10px] text-slate-450">
                                    {{ $log->actor ? $log->actor->email : '—' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $roleColors = [
                                        'super_admin' => 'bg-[#7a1621]/10 text-[#7a1621] border-[#7a1621]/20',
                                        'akademik'    => 'bg-red-100 text-red-800 border-red-200',
                                        'keuangan'    => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'dosen'       => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                        'mahasiswa'   => 'bg-sky-100 text-sky-800 border-sky-200',
                                        'system'      => 'bg-slate-100 text-slate-650 border-slate-200',
                                    ];
                                    $roleColor = $roleColors[$log->actor_role ?? 'system'] ?? 'bg-slate-100 text-slate-650 border-slate-200';
                                @endphp
                                <span class="px-2.5 py-0.5 text-[9px] font-black uppercase rounded-full border {{ $roleColor }}">
                                    {{ $log->actor_role ?? 'system' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @php
                                    $actionStr = $log->action ?? '';
                                    if (str_contains($actionStr, 'override')) {
                                        $chip = 'bg-rose-100 text-rose-700 border-rose-200';
                                    } elseif (str_contains($actionStr, 'impersonat')) {
                                        $chip = 'bg-[#7a1621]/10 text-[#7a1621] border-[#7a1621]/20';
                                    } elseif (str_contains($actionStr, 'login')) {
                                        $chip = 'bg-emerald-100 text-emerald-700 border-emerald-250';
                                    } elseif (str_ends_with($actionStr, '.created') || str_ends_with($actionStr, '.assigned')) {
                                        $chip = 'bg-green-100 text-green-700 border-green-200';
                                    } elseif (str_ends_with($actionStr, '.updated') || str_ends_with($actionStr, '.modified')) {
                                        $chip = 'bg-blue-100 text-blue-700 border-blue-200';
                                    } elseif (str_ends_with($actionStr, '.deleted') || str_ends_with($actionStr, '.revoked')) {
                                        $chip = 'bg-red-100 text-red-700 border-red-200';
                                    } else {
                                        $chip = 'bg-slate-100 text-slate-650 border-slate-200';
                                    }
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 text-[9px] uppercase rounded font-bold tracking-wider border {{ $chip }}">
                                    {{ $actionStr }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-700 font-semibold capitalize">
                                {{ $log->module ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <span class="font-medium text-slate-700 block">{{ $log->auditable_type ? class_basename($log->auditable_type) : '—' }}</span>
                                @if($log->auditable_id)
                                    <span class="text-[10px] text-slate-400 font-mono">ID: #{{ $log->auditable_id }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($log->before || $log->after || $log->meta)
                                    <button @click="open = !open"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-[10px] font-bold bg-[#7a1621]/10 text-[#7a1621] rounded-lg hover:bg-[#7a1621]/20 transition uppercase tracking-wide">
                                        <span class="material-symbols-outlined text-xs font-bold" x-text="open ? 'expand_less' : 'expand_more'">expand_more</span>
                                        <span x-text="open ? 'Tutup' : 'Detail'">Detail</span>
                                    </button>
                                @else
                                    <span class="text-[10px] text-slate-300 italic">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-400 font-mono">{{ $log->ip_address ?? '—' }}</td>
                        </tr>
 
                        {{-- Expanded detail row --}}
                        @if($log->before || $log->after || $log->meta)
                            <tr x-show="open" x-cloak x-transition>
                                <td colspan="8" class="px-6 py-6 bg-slate-50/80">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        @if($log->before)
                                            <div>
                                                <h4 class="text-[10px] font-bold text-rose-600 mb-3 uppercase tracking-widest flex items-center gap-2">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 inline-block"></span> Sebelum Perubahan
                                                </h4>
                                                <div class="bg-rose-50/60 border border-rose-100 rounded-xl p-4 max-h-64 overflow-y-auto">
                                                    <dl class="space-y-2">
                                                        @foreach((array)$log->before as $key => $value)
                                                            <div>
                                                                <dt class="text-[10px] uppercase font-bold text-rose-450 tracking-wider">{{ str_replace(['_', '.'], ' ', $key) }}</dt>
                                                                <dd class="text-xs font-medium text-rose-900 break-all">{{ is_array($value) ? json_encode($value) : ($value ?? '—') }}</dd>
                                                            </div>
                                                        @endforeach
                                                    </dl>
                                                </div>
                                            </div>
                                        @endif
 
                                        @if($log->after)
                                            <div>
                                                <h4 class="text-[10px] font-bold text-emerald-600 mb-3 uppercase tracking-widest flex items-center gap-2">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span> Setelah Perubahan
                                                </h4>
                                                <div class="bg-emerald-50/60 border border-emerald-100 rounded-xl p-4 max-h-64 overflow-y-auto">
                                                    <dl class="space-y-2">
                                                        @foreach((array)$log->after as $key => $value)
                                                            <div>
                                                                <dt class="text-[10px] uppercase font-bold text-emerald-450 tracking-wider">{{ str_replace(['_', '.'], ' ', $key) }}</dt>
                                                                <dd class="text-xs font-medium text-emerald-900 break-all">{{ is_array($value) ? json_encode($value) : ($value ?? '—') }}</dd>
                                                            </div>
                                                        @endforeach
                                                    </dl>
                                                </div>
                                            </div>
                                        @endif
 
                                        @if($log->meta)
                                            <div>
                                                <h4 class="text-[10px] font-bold text-blue-600 mb-3 uppercase tracking-widest flex items-center gap-2">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 inline-block"></span> Informasi Tambahan
                                                </h4>
                                                <div class="bg-blue-50/60 border border-blue-100 rounded-xl p-4 max-h-64 overflow-y-auto">
                                                    <dl class="space-y-2">
                                                        @foreach((array)$log->meta as $key => $value)
                                                            <div>
                                                                <dt class="text-[10px] uppercase font-bold text-blue-450 tracking-wider">{{ str_replace(['_', '.'], ' ', $key) }}</dt>
                                                                <dd class="text-xs font-medium text-blue-900 break-all">{{ is_array($value) ? json_encode($value) : ($value ?? '—') }}</dd>
                                                            </div>
                                                        @endforeach
                                                    </dl>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
 
                                    @if($log->user_agent)
                                        <div class="mt-4 p-3 bg-white rounded-lg border border-slate-200 text-[10px] text-slate-500 font-mono break-all flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-xs align-middle">computer</span>
                                            <span>User Agent: {{ $log->user_agent }}</span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    </tbody>
                @empty
                    <tbody class="bg-white">
                        <tr>
                            <td colspan="8" class="px-6 py-20 text-center text-slate-400">
                                <span class="material-symbols-outlined text-5xl block mb-3 text-slate-300">receipt_long</span>
                                <p class="text-base font-semibold">Belum ada log audit yang tersedia.</p>
                                <p class="text-xs text-slate-450 mt-1">Coba sesuaikan filter pencarian Anda.</p>
                            </td>
                        </tr>
                    </tbody>
                @endforelse
            </table>
        </div>
 
        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
