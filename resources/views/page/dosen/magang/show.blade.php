@extends('layouts.app')

@section('title', 'Detail Bimbingan Magang')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
@endpush

@section('content')
<div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ showLogbookForm: false }">

    {{-- Header Content --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('dosen.magang.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-[#8B1538] hover:bg-red-50 transition-colors shadow-sm border border-gray-100">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Detail Bimbingan</h1>
                <p class="text-sm text-gray-500 mt-0.5">Informasi lengkap kegiatan magang mahasiswa.</p>
            </div>
        </div>
        <div>
            {!! $internship->status_badge !!}
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50/80 border border-green-200/60 rounded-2xl flex items-start gap-3 shadow-sm">
            <span class="material-symbols-outlined text-green-600 mt-0.5">check_circle</span>
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        {{-- Left Sidebar: Info --}}
        <div class="lg:col-span-1 xl:col-span-1 space-y-6">
            
            {{-- Profile Card --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 relative overflow-hidden hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-gradient-to-br from-[#8B1538]/5 to-transparent rounded-full blur-2xl"></div>
                
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 text-slate-600 flex items-center justify-center font-black text-2xl shadow-sm border border-white shrink-0">
                        {{ substr($internship->mahasiswa?->user?->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="text-lg font-bold text-gray-900 leading-tight truncate">{{ $internship->mahasiswa?->user?->name ?? '-' }}</h2>
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600 mt-1 border border-gray-200">
                            <span class="material-symbols-outlined text-[12px]">badge</span>
                            {{ $internship->mahasiswa?->nim ?? '-' }}
                        </span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Instansi</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $internship->instansi }}</p>
                        @if($internship->alamat_instansi)
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">{{ $internship->alamat_instansi }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Posisi / Bagian</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $internship->posisi ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Pembimbing Lapangan</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $internship->pembimbing_lapangan_nama ?? '-' }}</p>
                    </div>
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Periode Magang</p>
                        <div class="flex items-center gap-3">
                            <div class="flex-1 bg-gray-50 rounded-xl p-3 border border-gray-100 text-center">
                                <p class="text-[10px] text-gray-500 font-medium mb-0.5">Mulai</p>
                                <p class="text-xs font-bold text-gray-800">{{ $internship->periode_mulai?->format('d M Y') ?? '-' }}</p>
                            </div>
                            <span class="material-symbols-outlined text-gray-300">arrow_forward</span>
                            <div class="flex-1 bg-gray-50 rounded-xl p-3 border border-gray-100 text-center">
                                <p class="text-[10px] text-gray-500 font-medium mb-0.5">Selesai</p>
                                <p class="text-xs font-bold text-gray-800">{{ $internship->periode_selesai?->format('d M Y') ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MK Konversi --}}
            @if($internship->courseMappings->isNotEmpty())
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8 hover:shadow-md transition-shadow">
                <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-indigo-500">book</span>
                    Mata Kuliah Konversi
                </h3>
                <div class="space-y-3">
                    @foreach($internship->courseMappings as $m)
                    <div class="flex items-start justify-between gap-3 p-3.5 rounded-xl bg-gray-50 border border-gray-100 hover:border-indigo-100 hover:bg-indigo-50/30 transition-colors">
                        <div class="min-w-0 pr-2">
                            <p class="text-xs font-bold text-gray-800 leading-tight">{{ $m->mataKuliah?->nama_mk ?? '-' }}</p>
                            <p class="text-[10px] text-gray-500 font-mono mt-1">{{ $m->mataKuliah?->kode_mk ?? '-' }}</p>
                        </div>
                        <span class="shrink-0 w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold shadow-sm">
                            {{ $m->sks }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Right Content: Logbook --}}
        <div class="lg:col-span-2 xl:col-span-3 space-y-6">
            {{-- Logbook --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-6 sm:p-8 bg-gradient-to-br from-white to-gray-50/50 border-b border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
                        <div>
                            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-xl bg-[#8B1538]/10 text-[#8B1538] flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[18px]">menu_book</span>
                                </div>
                                Logbook & Catatan Bimbingan
                            </h3>
                            <p class="text-xs font-medium text-gray-500 mt-1 sm:ml-10">Pantau kegiatan mahasiswa dan berikan arahan atau evaluasi harian.</p>
                        </div>
                        @if($internship->isOngoing())
                            <button @click="showLogbookForm = !showLogbookForm" 
                                    class="shrink-0 inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#8B1538] hover:bg-[#6D1029] text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-[#8B1538]/20 group">
                                <span class="material-symbols-outlined text-[16px] transition-transform" :class="showLogbookForm ? 'rotate-45 text-red-300' : 'group-hover:rotate-90'">add</span> 
                                <span x-text="showLogbookForm ? 'Batal' : 'Tambah Catatan'"></span>
                            </button>
                        @endif
                    </div>

                    {{-- Logbook form --}}
                    <div x-show="showLogbookForm" x-cloak 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 -translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-4"
                         class="mt-6 p-6 sm:p-8 bg-white border border-[#8B1538]/10 rounded-2xl shadow-lg shadow-[#8B1538]/5 relative overflow-hidden">
                        
                        <div class="absolute inset-0 bg-gradient-to-br from-[#8B1538]/[0.02] to-transparent pointer-events-none"></div>

                        <form method="POST" action="{{ route('dosen.magang.logbook.store', $internship) }}" class="space-y-6 relative">
                            @csrf
                            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                                <div class="lg:col-span-1">
                                    <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Tanggal</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                            <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                                        </span>
                                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                                               class="w-full rounded-xl border border-gray-200 bg-gray-50 text-sm pl-10 pr-4 py-2.5 transition-all text-gray-700 focus:bg-white focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538] hover:border-gray-300 text-center font-semibold tracking-wide">
                                    </div>
                                </div>
                                <div class="lg:col-span-3 space-y-5">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">
                                            Kegiatan Mahasiswa 
                                            <span class="text-gray-400 font-medium normal-case ml-1">(Opsional)</span>
                                        </label>
                                        <textarea name="kegiatan" rows="2" 
                                                  class="w-full rounded-xl border border-gray-200 bg-gray-50 text-sm px-4 py-3 transition-all text-gray-700 focus:bg-white focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538] resize-y hover:border-gray-300"
                                                  placeholder="Deskripsi kegiatan yang diamati..."></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">
                                            Catatan / Feedback 
                                            <span class="text-red-500 ml-0.5">*</span>
                                        </label>
                                        <textarea name="catatan_dosen" rows="3" required 
                                                  class="w-full rounded-xl border border-gray-200 bg-gray-50 text-sm px-4 py-3 transition-all text-gray-700 focus:bg-white focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538] resize-y hover:border-gray-300 shadow-inner"
                                                  placeholder="Masukan, arahan, atau evaluasi untuk mahasiswa..."></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end pt-2 border-t border-gray-100 mt-6 md:mt-0 md:border-t-0 md:pt-0">
                                <button type="submit" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-[#8B1538] text-white text-sm font-bold rounded-xl shadow-sm shadow-[#8B1538]/20 hover:bg-[#6D1029] hover:shadow-md transition-all group w-full md:w-auto mt-4 md:mt-2">
                                    <span class="material-symbols-outlined text-[18px] group-hover:-translate-y-0.5 transition-transform">send</span> 
                                    Simpan Catatan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="bg-gray-50/30">
                    @if($logbooks->isEmpty())
                        <div class="text-center py-20 px-4">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-5 border border-gray-200 shadow-inner">
                                <span class="material-symbols-outlined text-4xl text-gray-300">history_edu</span>
                            </div>
                            <h4 class="text-base font-bold text-gray-600 mb-2">Belum Ada Entri Logbook</h4>
                            <p class="text-sm text-gray-400 max-w-sm mx-auto leading-relaxed">Kedua belah pihak belum mencatat aktivitas atau bimbingan apapun pada magang ini.</p>
                        </div>
                    @else
                        <div class="divide-y divide-gray-100/80 max-h-[1000px] overflow-y-auto pr-1">
                            @foreach($logbooks as $log)
                                <div class="p-6 sm:p-8 hover:bg-white transition-colors group">
                                    <div class="flex flex-col xl:flex-row gap-5 xl:gap-8">
                                        {{-- Date Column --}}
                                        <div class="shrink-0 xl:w-28 pt-1 border-l-2 {{ $log->created_by_role === 'dosen' ? 'border-sky-400' : 'border-[#8B1538]' }} pl-4 xl:pl-0 xl:border-l-0 xl:text-right">
                                            <div class="text-[10px] font-black tracking-widest uppercase text-gray-400 mb-1">
                                                {{ \Carbon\Carbon::parse($log->tanggal)->format('M Y') }}
                                            </div>
                                            <div class="text-3xl font-black text-gray-800 leading-none mb-1.5 tracking-tighter group-hover:text-[#8B1538] transition-colors">
                                                {{ \Carbon\Carbon::parse($log->tanggal)->format('d') }}
                                            </div>
                                            <div class="text-xs font-bold text-gray-500">
                                                {{ \Carbon\Carbon::parse($log->tanggal)->isoFormat('dddd') }}
                                            </div>
                                        </div>
                                        
                                        {{-- Content Column --}}
                                        <div class="flex-1 min-w-0 space-y-4">
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $log->created_by_role === 'dosen' ? 'bg-sky-50 text-sky-600 border border-sky-100' : 'bg-rose-50 text-[#8B1538] border border-rose-100' }}">
                                                    <span class="material-symbols-outlined text-[14px]">{{ $log->created_by_role === 'dosen' ? 'school' : 'person' }}</span>
                                                    {{ ucfirst($log->created_by_role) }}
                                                </span>
                                            </div>
                                            
                                            @if($log->kegiatan)
                                                <div class="text-sm text-gray-700 leading-relaxed font-medium break-words">
                                                    {!! nl2br(e($log->kegiatan)) !!}
                                                </div>
                                            @endif
                                            
                                            @if($log->catatan_dosen)
                                                <div class="mt-5 p-5 rounded-2xl bg-sky-50 border border-sky-100 flex gap-4 items-start relative overflow-hidden">
                                                    <div class="absolute top-0 right-0 -mr-4 -mt-4 w-16 h-16 bg-gradient-to-br from-sky-200/40 to-transparent rounded-full blur-xl"></div>
                                                    <div class="shrink-0 w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-sky-500 border border-sky-100/50 z-10">
                                                        <span class="material-symbols-outlined text-[20px]">forum</span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 z-10">
                                                        <p class="text-[10px] font-bold text-sky-600 uppercase tracking-widest mb-1.5">Catatan Bimbingan</p>
                                                        <p class="text-sm text-sky-900 leading-relaxed break-words">{!! nl2br(e($log->catatan_dosen)) !!}</p>
                                                    </div>
                                                </div>
                                            @else
                                                {{-- Allow dosen to add note to student entry --}}
                                                @if($log->created_by_role === 'mahasiswa')
                                                    <div class="mt-4 bg-gray-50/50 rounded-xl p-4 border border-gray-100 border-dashed">
                                                        <form method="POST" action="{{ route('dosen.magang.logbook.update', [$internship, $log]) }}" class="flex flex-col sm:flex-row gap-3 w-full lg:w-4/5 xl:w-[85%] relative">
                                                            @csrf @method('PUT')
                                                            <div class="relative flex-1">
                                                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-sky-400">
                                                                    <span class="material-symbols-outlined text-[18px]">draw</span>
                                                                </span>
                                                                <input type="text" name="catatan_dosen" placeholder="Berikan catatan atau koreksi untuk kegiatan ini..." required
                                                                       class="w-full rounded-xl border border-gray-200 bg-white text-sm pl-11 pr-4 py-3 transition-all text-gray-700 focus:ring-4 focus:ring-sky-100 focus:border-sky-400 hover:border-sky-200 shadow-sm">
                                                            </div>
                                                            <button class="shrink-0 inline-flex items-center justify-center gap-2 px-6 py-3 bg-sky-600 hover:bg-sky-700 text-white text-sm font-bold rounded-xl shadow-sm shadow-sky-600/20 transition-all hover:-translate-y-0.5 w-full sm:w-auto">
                                                                <span class="material-symbols-outlined text-[16px]">send</span> Kirim
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($logbooks->hasPages())
                            <div class="p-6 border-t border-gray-100">
                                {{ $logbooks->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
