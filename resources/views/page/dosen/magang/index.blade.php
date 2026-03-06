@extends('layouts.app')

@section('title', 'Bimbingan Magang')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
@endpush

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Bimbingan Magang</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar mahasiswa yang Anda bimbing untuk program magang.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">{{ session('success') }}</div>
    @endif

    @if($internships->isEmpty())
        <div class="bg-white rounded-2xl shadow p-12 text-center">
            <span class="material-symbols-outlined text-5xl text-gray-300 mb-4">work_off</span>
            <p class="text-gray-500">Belum ada mahasiswa bimbingan magang.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($internships as $i)
                <a href="{{ route('dosen.magang.show', $i) }}"
                   class="block bg-white rounded-2xl shadow hover:shadow-md transition p-6 border border-gray-100">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold text-gray-800 truncate">{{ $i->mahasiswa?->user?->name ?? '-' }}</h3>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $i->mahasiswa?->nim ?? '-' }} &bull; {{ $i->instansi }}</p>
                            <div class="flex flex-wrap gap-3 mt-2 text-xs text-gray-400">
                                <span><i class="fas fa-calendar mr-1"></i>{{ $i->periode_mulai?->format('d M Y') }} – {{ $i->periode_selesai?->format('d M Y') }}</span>
                                <span><i class="fas fa-book mr-1"></i>{{ $i->semester?->nama ?? '-' }}</span>
                            </div>
                        </div>
                        {!! $i->status_badge !!}
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
