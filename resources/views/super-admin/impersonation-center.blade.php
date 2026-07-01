@extends('layouts.super-admin')
@section('title', 'Impersonation Center')
@section('page-title', 'Impersonation Center')
@section('content')
<div class="space-y-6">
    @if($isImpersonating)
    <div class="glass-card p-4 border-l-4 border-[#7a1621] bg-[#7a1621]/5">
        <p class="text-sm font-semibold text-[#7a1621] flex items-center gap-2">
            <span class="material-symbols-outlined text-lg animate-pulse">security</span>
            Sesi Impersonasi Aktif. Gunakan banner di atas untuk kembali ke Super Admin.
        </p>
    </div>
    @endif

    <div class="glass-card p-6">
        <h3 class="text-base font-bold text-[#7a1621] mb-4">Riwayat Impersonasi</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase bg-[#7a1621]/5 text-[#7a1621] border-b border-[#7a1621]/10">
                    <tr>
                        <th class="px-4 py-3">Waktu</th>
                        <th class="px-4 py-3">Impersonator</th>
                        <th class="px-4 py-3">Aksi</th>
                        <th class="px-4 py-3">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($history as $log)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 font-semibold text-slate-800 text-xs">{{ $log->actor?->name ?? 'System' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ str_contains($log->action, 'start') ? 'bg-[#7a1621]/10 text-[#7a1621] border border-[#7a1621]/20' : 'bg-slate-100 text-slate-650' }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500 max-w-xs truncate">{{ json_encode($log->meta) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-slate-400">Belum ada riwayat impersonasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $history->links() }}</div>
    </div>
</div>
@endsection
