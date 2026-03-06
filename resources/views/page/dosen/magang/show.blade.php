@extends('layouts.app')

@section('title', 'Detail Bimbingan Magang')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8" x-data="{ showLogbookForm: false }">

    <div class="mb-6">
        <a href="{{ route('dosen.magang.index') }}" class="text-sm text-primary hover:underline">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $internship->mahasiswa?->user?->name ?? '-' }}</h2>
                <p class="text-sm text-gray-500">{{ $internship->mahasiswa?->nim ?? '-' }} &bull; {{ $internship->instansi }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $internship->posisi ?? '-' }} &bull; {{ $internship->periode_mulai?->format('d M Y') }} – {{ $internship->periode_selesai?->format('d M Y') }}</p>
            </div>
            {!! $internship->status_badge !!}
        </div>
    </div>

    {{-- Detail --}}
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Data Magang</h3>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div><dt class="font-semibold text-gray-500">Instansi</dt><dd>{{ $internship->instansi }}</dd></div>
            <div><dt class="font-semibold text-gray-500">Alamat</dt><dd>{{ $internship->alamat_instansi }}</dd></div>
            <div><dt class="font-semibold text-gray-500">Deskripsi</dt><dd>{{ $internship->deskripsi ?? '-' }}</dd></div>
            <div><dt class="font-semibold text-gray-500">Pembimbing Lapangan</dt><dd>{{ $internship->pembimbing_lapangan_nama ?? '-' }}</dd></div>
        </dl>
    </div>

    {{-- MK Konversi --}}
    @if($internship->courseMappings->isNotEmpty())
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Mata Kuliah Konversi</h3>
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-4 py-3 text-left font-semibold">MK</th>
                <th class="px-4 py-3 text-center font-semibold">SKS</th>
            </tr></thead>
            <tbody class="divide-y">
                @foreach($internship->courseMappings as $m)
                <tr>
                    <td class="px-4 py-3">{{ $m->mataKuliah?->kode_mk ?? '-' }} – {{ $m->mataKuliah?->nama_mk ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">{{ $m->sks }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Logbook --}}
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-700">Logbook & Catatan Bimbingan</h3>
            @if($internship->isOngoing())
                <button @click="showLogbookForm = !showLogbookForm" class="text-sm text-primary hover:underline font-semibold">
                    <i class="fas fa-plus mr-1"></i> Tambah Catatan
                </button>
            @endif
        </div>

        {{-- Logbook form --}}
        <div x-show="showLogbookForm" x-cloak class="mb-6 p-4 bg-gray-50 rounded-xl">
            <form method="POST" action="{{ route('dosen.magang.logbook.store', $internship) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                           class="w-full sm:w-48 rounded-lg border-gray-300 text-sm px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Kegiatan Mahasiswa</label>
                    <textarea name="kegiatan" rows="2" class="w-full rounded-lg border-gray-300 text-sm px-3 py-2"
                              placeholder="Opsional: deskripsi kegiatan yang diamati..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Catatan / Feedback Dosen <span class="text-red-500">*</span></label>
                    <textarea name="catatan_dosen" rows="3" required class="w-full rounded-lg border-gray-300 text-sm px-3 py-2"
                              placeholder="Masukan, arahan, atau evaluasi..."></textarea>
                </div>
                <button type="submit" class="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-lg">Simpan Catatan</button>
            </form>
        </div>

        @if($internship->logbooks->isEmpty())
            <p class="text-sm text-gray-400">Belum ada entri logbook.</p>
        @else
            <div class="space-y-3 max-h-[600px] overflow-y-auto">
                @foreach($internship->logbooks->sortByDesc('tanggal') as $log)
                    <div class="p-4 border border-gray-100 rounded-xl">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-bold text-gray-500">{{ \Carbon\Carbon::parse($log->tanggal)->format('d M Y') }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $log->created_by_role === 'dosen' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                {{ ucfirst($log->created_by_role) }}
                            </span>
                        </div>
                        @if($log->kegiatan)
                            <p class="text-sm text-gray-700">{{ $log->kegiatan }}</p>
                        @endif
                        @if($log->catatan_dosen)
                            <p class="text-sm text-blue-600 mt-1"><strong>Catatan Dosen:</strong> {{ $log->catatan_dosen }}</p>
                        @else
                            {{-- Allow dosen to add note to student entry --}}
                            @if($log->created_by_role === 'mahasiswa')
                                <form method="POST" action="{{ route('dosen.magang.logbook.update', [$internship, $log]) }}" class="mt-2 flex gap-2">
                                    @csrf @method('PUT')
                                    <input type="text" name="catatan_dosen" placeholder="Tambah catatan..." required
                                           class="flex-1 rounded-lg border-gray-300 text-sm px-3 py-1.5">
                                    <button class="px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg">Kirim</button>
                                </form>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
