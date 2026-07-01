@extends('layouts.super-admin')

@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="glass-card p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621]">manage_accounts</span>
                Manajemen Pengguna
            </h2>
            <p class="text-sm text-slate-500 mt-1">Lihat seluruh akun pengguna di sistem dan lakukan impersonasi jika diperlukan.</p>
        </div>
        <div class="text-right">
            <span class="text-xs text-slate-400 font-medium block">Total Akun</span>
            <span class="text-2xl font-black text-slate-800">{{ number_format($users->total()) }}</span>
        </div>
    </div>
 
    {{-- Filters --}}
    <div class="glass-card p-5">
        <form method="GET" action="{{ route('super-admin.user-management') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Cari Pengguna</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Nama atau email..."
                    class="w-full text-sm border border-slate-200 bg-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#7a1621] outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Role</label>
                <select name="role" class="text-sm border border-slate-200 bg-white rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#7a1621] outline-none min-w-[140px]">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $role->name)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-maroon px-5 py-2 rounded-xl text-sm font-bold shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">search</span> Cari
                </button>
                <a href="{{ route('super-admin.user-management') }}" class="px-5 py-2 bg-slate-100 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-200 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>
 
    {{-- Users Table --}}
    <div id="search-results">
        <div class="glass-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-500">
                    <thead class="text-xs uppercase bg-[#7a1621]/5 text-[#7a1621] border-b border-[#7a1621]/10">
                        <tr>
                            <th class="px-4 py-3">Pengguna</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Terdaftar</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $usr)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#7a1621] to-[#4c0810] text-white flex items-center justify-center font-bold text-sm flex-shrink-0">
                                            {{ strtoupper(substr($usr->name, 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-slate-800">{{ $usr->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-slate-600 text-xs">{{ $usr->email }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($usr->roles as $role)
                                            @php
                                                $rColors = [
                                                    'super_admin' => 'bg-[#7a1621]/10 text-[#7a1621] border-[#7a1621]/20',
                                                    'akademik'    => 'bg-red-100 text-red-800 border-red-200',
                                                    'keuangan'    => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                    'dosen'       => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                                    'mahasiswa'   => 'bg-sky-100 text-sky-800 border-sky-200',
                                                    'parents'     => 'bg-violet-100 text-violet-800 border-violet-200',
                                                ];
                                                $rColor = $rColors[$role->name] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                            @endphp
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $rColor }}">
                                                {{ str_replace('_', ' ', $role->name) }}
                                            </span>
                                        @endforeach
                                        @if($usr->roles->isEmpty())
                                            <span class="px-2 py-0.5 rounded bg-gray-100 border text-gray-400 text-[10px] font-bold uppercase">Tanpa Role</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-slate-400 text-xs">{{ $usr->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-right">
                                    @if($usr->id !== auth()->id())
                                        <form action="{{ route('super-admin.impersonate', $usr->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="btn-gold px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-xs">login</span> Impersonate
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Akun Anda</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center text-slate-400">
                                    <span class="material-symbols-outlined text-5xl block mb-3 text-slate-300">group</span>
                                    <p class="text-base font-semibold">Tidak ada pengguna ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
    
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
