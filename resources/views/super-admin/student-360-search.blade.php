@extends('layouts.super-admin')

@section('title', 'Student 360° — Cari Mahasiswa')
@section('page-title', 'Student 360°')

@section('content')
<div class="space-y-6">
    {{-- Search Header --}}
    <div class="glass-card p-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621]">manage_search</span>
                Student 360° — Profil Lengkap Mahasiswa
            </h2>
            <p class="text-sm text-slate-500 mt-1">Cari mahasiswa berdasarkan NIM, nama, atau email untuk melihat profil lengkap dan melakukan override.</p>
        </div>
        <form action="{{ route('super-admin.student-360-search') }}" method="GET" class="flex gap-3">
            <div class="flex-1 relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input type="text" name="q" value="{{ $query }}"
                    placeholder="Ketik NIM, nama, atau email mahasiswa..."
                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#7a1621] focus:border-[#7a1621] text-sm transition">
            </div>
            <button type="submit"
                class="btn-maroon px-6 py-3 rounded-xl text-sm font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">search</span>
                Cari
            </button>
        </form>
    </div>

    {{-- Results --}}
    <div id="search-results">
        @if($query && $mahasiswa)
            @if($mahasiswa->count() > 0)
                <div class="glass-card p-6">
                    <h3 class="text-sm font-bold text-slate-655 uppercase tracking-wider mb-4">
                        {{ $mahasiswa->count() }} Mahasiswa Ditemukan untuk "{{ $query }}"
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($mahasiswa as $mhs)
                            <a href="{{ route('super-admin.student-360', $mhs) }}"
                                class="group p-4 rounded-xl border border-[#7a1621]/10 hover:border-[#7a1621]/30 hover:bg-[#7a1621]/5 transition-all duration-200 flex items-center gap-4 cursor-pointer">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#7a1621] to-[#4c0810] flex items-center justify-center text-white font-bold text-lg shrink-0 shadow">
                                    {{ strtoupper(substr($mhs->user?->name ?? 'M', 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-slate-800 text-sm truncate group-hover:text-[#7a1621] transition-colors">
                                        {{ $mhs->user?->name ?? 'Unknown' }}
                                    </p>
                                    <p class="text-xs text-slate-500">NIM: {{ $mhs->nim }}</p>
                                    <p class="text-xs text-slate-400">{{ $mhs->prodi }} · Angkatan {{ $mhs->angkatan }}</p>
                                </div>
                                <span class="material-symbols-outlined text-slate-300 group-hover:text-[#7a1621] transition">chevron_right</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="glass-card p-12 text-center">
                    <span class="material-symbols-outlined text-5xl text-slate-300 block mb-3">person_search</span>
                    <p class="text-slate-500 font-semibold">Tidak ada mahasiswa ditemukan untuk "{{ $query }}"</p>
                    <p class="text-slate-400 text-sm mt-1">Coba cari dengan NIM, nama lengkap, atau email.</p>
                </div>
            @endif
        @elseif(!$query)
            {{-- Empty State --}}
            <div class="glass-card p-12 text-center">
                <span class="material-symbols-outlined text-6xl text-[#7a1621]/30 block mb-4">person_search</span>
                <h3 class="text-slate-600 font-bold text-lg">Mulai dengan Mencari Mahasiswa</h3>
                <p class="text-slate-400 text-sm mt-2">Masukkan NIM, nama, atau email untuk melihat profil lengkap 360°.</p>
            </div>
        @endif
    </div>
</div>
@endsection
