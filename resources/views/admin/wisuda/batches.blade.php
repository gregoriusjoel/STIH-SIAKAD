@extends('layouts.admin')

@section('title', 'Daftar Batch Wisuda')
@section('page-title', 'Batch Wisuda')

@section('content')
<div class="space-y-6">
    {{-- Premium Header --}}
    <div class="relative bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm overflow-hidden group">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-red-900/[0.03] rounded-full blur-3xl transition-all duration-700 group-hover:scale-110"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-amber-500/[0.02] rounded-full blur-3xl transition-all duration-700 group-hover:scale-110"></div>

        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-red-900 via-[#800020] to-[#5a0015] flex items-center justify-center shadow-lg shadow-red-900/20 shrink-0">
                    <span class="material-symbols-outlined text-white text-3xl font-light">calendar_month</span>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight leading-none mb-2">Batch Kelulusan / Wisuda</h1>
                    <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">Jadwal pelaksanaan & penempatan mahasiswa wisuda</p>
                </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.wisuda.index') }}"
                    class="inline-flex items-center justify-center gap-2 h-11 px-5 bg-gray-50 text-gray-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gray-100 border border-gray-100 transition-all">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Daftar Registrasi
                </a>
                <a href="{{ route('admin.wisuda.batches.create') }}"
                    class="inline-flex items-center justify-center gap-2 h-11 px-5 bg-gradient-to-r from-red-900 to-red-950 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-800 shadow-md hover:shadow-lg transition-all">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Buat Batch Baru
                </a>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-emerald-600 mt-0.5">check_circle</span>
            <div>
                <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-red-600 mt-0.5">error</span>
            <div>
                <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Approved Waiting Schedule Banner --}}
    @if($approvedCount > 0)
        <div class="bg-gradient-to-r from-amber-500/10 via-amber-600/[0.04] to-transparent border border-amber-200/50 rounded-3xl p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/15 text-amber-600 flex items-center justify-center shrink-0 shadow-sm border border-amber-200/40">
                    <span class="material-symbols-outlined text-2xl animate-bounce">school</span>
                </div>
                <div>
                    <h3 class="text-sm font-black text-amber-900 uppercase tracking-widest mb-1 leading-none">Mahasiswa Belum Terjadwal</h3>
                    <p class="text-sm text-amber-800 leading-snug font-medium">Terdapat <strong>{{ $approvedCount }}</strong> mahasiswa berstatus disetujui yang belum dimasukkan ke dalam batch wisuda.</p>
                </div>
            </div>
            @if($batches->count() > 0)
                <a href="{{ route('admin.wisuda.batches.show', $batches->first()->id) }}"
                    class="shrink-0 inline-flex items-center justify-center gap-2 h-10 px-5 bg-amber-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-amber-700 shadow-md transition-all">
                    Assign ke Batch Terbaru
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                </a>
            @endif
        </div>
    @endif

    {{-- Batches Grid / Table --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 sm:p-8 min-h-[400px]">
            <div class="grid gap-4">
                @forelse($batches as $batch)
                    <div class="bg-white border border-gray-100 rounded-2xl p-5 hover:border-red-100 hover:shadow-md hover:shadow-red-900/5 transition-all group flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-red-50 group-hover:text-red-900 transition-colors shrink-0 border border-gray-100 group-hover:border-red-100">
                                <span class="material-symbols-outlined text-3xl font-light">school</span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-black text-gray-900 tracking-tight text-lg mb-1 group-hover:text-red-900 transition-colors">
                                    {{ $batch->nama_batch }}
                                </h3>
                                <div class="flex flex-wrap items-center gap-3">
                                    <div class="flex items-center gap-1 px-2.5 py-1 bg-gray-50 rounded-xl border border-gray-100">
                                        <span class="material-symbols-outlined text-[15px] text-gray-400">calendar_today</span>
                                        <span class="text-[10px] font-bold text-gray-600 uppercase tracking-wide">
                                            {{ $batch->tanggal->locale('id')->isoFormat('dddd, DD MMMM Y') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1 px-2.5 py-1 bg-gray-50 rounded-xl border border-gray-100">
                                        <span class="material-symbols-outlined text-[15px] text-gray-400">alarm</span>
                                        <span class="text-[10px] font-bold text-gray-600 uppercase tracking-wide">
                                            {{ \Carbon\Carbon::parse($batch->waktu_mulai)->format('H:i') }} WIB
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1 px-2.5 py-1 bg-gray-50 rounded-xl border border-gray-100">
                                        <span class="material-symbols-outlined text-[15px] text-gray-400">location_on</span>
                                        <span class="text-[10px] font-bold text-gray-600 truncate max-w-[200px]" title="{{ $batch->lokasi }}">
                                            {{ $batch->lokasi }}
                                        </span>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-red-50 text-red-900 border border-red-100/50">
                                        {{ $batch->registrations_count }} Mahasiswa
                                    </span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('admin.wisuda.batches.show', $batch->id) }}"
                            class="shrink-0 flex items-center justify-center gap-2 h-11 px-6 bg-red-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-800 shadow-lg shadow-red-900/10 hover:-translate-y-0.5 transition-all">
                            Detail & Tempatkan
                            <span class="material-symbols-outlined text-sm">groups</span>
                        </a>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-20 text-center group">
                        <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4 text-gray-200 group-hover:scale-110 group-hover:text-red-900/20 transition-all duration-500">
                            <span class="material-symbols-outlined text-3xl font-light">calendar_month</span>
                        </div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Belum ada batch wisuda dibuat.</p>
                        <p class="text-xs text-gray-400 font-medium max-w-xs leading-relaxed mb-4">Silakan buat batch baru terlebih dahulu untuk mulai menjadwalkan wisuda bagi mahasiswa.</p>
                        <a href="{{ route('admin.wisuda.batches.create') }}" class="inline-flex items-center gap-2 h-10 px-5 bg-gradient-to-r from-red-900 to-red-950 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-800 transition-all">
                            Buat Batch Pertama
                            <span class="material-symbols-outlined text-sm">add_circle</span>
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $batches->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
