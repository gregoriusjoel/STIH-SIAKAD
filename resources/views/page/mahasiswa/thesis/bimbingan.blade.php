@extends('layouts.mahasiswa')
@section('title', 'Log Bimbingan Skripsi')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8 space-y-6">

    <div>
        <a href="{{ route('mahasiswa.thesis.index') }}"
            class="text-sm text-gray-500 hover:text-red-900 flex items-center gap-1 mb-3">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-black text-gray-900">Log Bimbingan</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $submission->judul }}</p>
            </div>
            <div class="text-right">
                <p class="text-3xl font-black {{ $submission->total_bimbingan >= 8 ? 'text-green-600' : 'text-blue-700' }}">
                    {{ $submission->total_bimbingan }}/8
                </p>
                <p class="text-xs text-gray-500">Bimbingan disetujui</p>
            </div>
        </div>

        {{-- Progress bar --}}
        <div class="mt-3 h-2 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-blue-600 rounded-full transition-all"
                style="width: {{ min(100, ($submission->total_bimbingan / 8) * 100) }}%"></div>
        </div>
        @if($submission->total_bimbingan >= 8)
        <p class="text-xs text-green-600 font-medium mt-1">✓ Sudah memenuhi syarat daftar sidang!</p>
        @else
        <p class="text-xs text-gray-400 mt-1">Butuh {{ 8 - $submission->total_bimbingan }} bimbingan lagi untuk daftar sidang.</p>
        @endif
    </div>

    {{-- Pembimbing Info --}}
    <div class="bg-white border border-gray-100 rounded-xl p-4 flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-900 font-bold text-sm">
            {{ substr($submission->approvedSupervisor?->nama ?? '?', 0, 1) }}
        </div>
        <div>
            <p class="text-xs text-gray-500">Dosen Pembimbing</p>
            <p class="font-semibold text-gray-800">{{ $submission->approvedSupervisor?->nama ?? '-' }}</p>
        </div>
    </div>

    {{-- Add Bimbingan Form --}}
    <div class="bg-white border border-blue-100 rounded-xl shadow-sm p-5">
        <h2 class="font-bold text-gray-800 mb-4">+ Tambah Bimbingan</h2>
        <form action="{{ route('mahasiswa.thesis.bimbingan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Tanggal Bimbingan <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_bimbingan" value="{{ old('tanggal_bimbingan') }}"
                        max="{{ now()->format('Y-m-d') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    @error('tanggal_bimbingan')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">File Bimbingan (opsional)</label>
                    <input type="file" name="file_bimbingan" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                        class="block w-full text-sm text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Catatan Bimbingan <span class="text-red-500">*</span>
                </label>
                <textarea name="catatan" rows="4"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm resize-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Apa yang dibahas pada bimbingan ini? Tuliskan poin-poin penting...">{{ old('catatan') }}</textarea>
                @error('catatan')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit"
                class="bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-blue-600 transition">
                Tambah Bimbingan
            </button>
        </form>
    </div>

    {{-- Bimbingan List --}}
    <div class="space-y-3">
        <h2 class="font-bold text-gray-700">Riwayat Bimbingan</h2>

        @forelse($guidances as $i => $g)
        @php $statusColor = $g->status->color(); @endphp
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-4">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600">
                        {{ $guidances->count() - $i }}
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-gray-400 font-medium">{{ $g->tanggal_bimbingan->format('d M Y') }}</p>
                        <p class="text-sm text-gray-700 mt-1 leading-relaxed">{{ $g->catatan }}</p>
                        @if($g->catatan_dosen)
                        <div class="mt-2 bg-gray-50 border-l-2 border-blue-400 pl-3 py-1.5 text-xs text-gray-600">
                            <strong>Catatan Dosen:</strong> {{ $g->catatan_dosen }}
                        </div>
                        @endif
                    </div>
                </div>
                <span class="shrink-0 px-2 py-0.5 rounded-full text-xs font-bold
                    bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700">
                    {{ $g->status->label() }}
                </span>
            </div>
        </div>
        @empty
        <div class="text-center py-10 text-gray-400">
            <p>Belum ada bimbingan. Mulai dengan menambahkan bimbingan pertama Anda.</p>
        </div>
        @endforelse
    </div>

</div>
@endsection
