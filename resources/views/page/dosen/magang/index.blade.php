@extends('layouts.app')

@section('title', 'Bimbingan Magang')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
@endpush

@section('content')
<div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-8 flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-[#8B1538]/10 text-[#8B1538] flex items-center justify-center">
            <span class="material-symbols-outlined text-[24px]">group</span>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Bimbingan Magang</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar mahasiswa yang Anda bimbing untuk program magang.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50/80 border border-green-200/60 rounded-2xl flex items-start gap-3 shadow-sm">
            <span class="material-symbols-outlined text-green-600 mt-0.5">check_circle</span>
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if($internships->isEmpty())
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-16 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-5 border border-gray-100">
                <span class="material-symbols-outlined text-4xl text-gray-300">work_off</span>
            </div>
            <h3 class="text-lg font-bold text-gray-700 mb-2">Belum ada mahasiswa bimbingan magang</h3>
            <p class="text-sm text-gray-500 max-w-sm mx-auto">Saat ini belum ada mahasiswa yang ditugaskan kepada Anda untuk bimbingan magang.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($internships as $i)
                <a href="{{ route('dosen.magang.show', $i) }}"
                   class="block bg-white rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1 hover:border-[#8B1538]/20 transition-all duration-300 p-6 sm:p-8 border border-gray-100 relative overflow-hidden group">
                    
                    <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                        <div class="flex-1 min-w-0 flex gap-4 sm:gap-6 items-start sm:items-center">
                            <div class="shrink-0 w-12 h-12 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-lg border border-slate-200">
                                {{ substr($i->mahasiswa?->user?->name ?? 'A', 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-gray-900 truncate group-hover:text-[#8B1538] transition-colors">{{ $i->mahasiswa?->user?->name ?? '-' }}</h3>
                                <div class="flex flex-wrap items-center gap-2 mt-1.5 -ml-1">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600 border border-gray-200">
                                        <span class="material-symbols-outlined text-[12px]">badge</span>
                                        {{ $i->mahasiswa?->nim ?? '-' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        <span class="material-symbols-outlined text-[12px]">apartment</span>
                                        <span class="truncate max-w-[200px]">{{ $i->instansi }}</span>
                                    </span>
                                </div>
                                <div class="flex flex-wrap items-center gap-4 mt-3 text-xs font-semibold text-gray-500">
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[14px]">calendar_month</span>
                                        {{ $i->periode_mulai?->format('d M Y') }} – {{ $i->periode_selesai?->format('d M Y') }}
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="shrink-0 pt-2 sm:pt-0">
                            {!! $i->status_badge !!}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
