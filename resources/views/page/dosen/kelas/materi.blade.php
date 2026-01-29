@extends('layouts.app')

@section('title', 'Materi Pertemuan')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
@endpush

@section('content')
    <div class="px-4 py-6 max-w-[1200px] mx-auto">
        {{-- BREADCRUMB --}}
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('dosen.kelas') }}" class="hover:text-primary transition-colors">Kelas</a>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <a href="{{ route('dosen.kelas.detail', $kelas->id) }}" class="hover:text-primary transition-colors">Detail
                Kelas</a>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <a href="{{ route('dosen.kelas.pertemuan.detail', ['id' => $kelas->id, 'pertemuan' => $meeting['no']]) }}"
                class="hover:text-primary transition-colors">{{ $meeting['label'] }}</a>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <span class="font-medium text-gray-800">Materi</span>
        </nav>

        {{-- HEADER --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="size-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-3xl">folder_open</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Materi {{ $meeting['label'] }}</h1>
                        <p class="text-gray-500">{{ $kelas->mataKuliah->nama_mk }} - Kelas {{ $kelas->section }}</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button
                        class="px-5 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">upload_file</span>
                        Upload Materi
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6 pt-6 border-t border-gray-100">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Tanggal</p>
                    <p class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-gray-400 text-[18px]">calendar_today</span>
                        {{ $meeting['date'] }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Waktu</p>
                    <p class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-gray-400 text-[18px]">schedule</span>
                        {{ $meeting['time'] }} (WIB)
                    </p>
                </div>
            </div>
        </div>

        {{-- MATERIALS LIST --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 text-lg">Daftar Materi</h3>
            </div>

            <div class="p-8 text-center bg-gray-50/50">
                <div class="size-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-3xl">folder_off</span>
                </div>
                <h3 class="font-bold text-gray-700">Belum Ada Materi</h3>
                <p class="text-gray-500 text-sm mt-1 max-w-xs mx-auto">
                    Anda belum mengunggah materi apapun untuk pertemuan ini. Silakan klik tombol "Upload Materi" untuk
                    menambahkan file.
                </p>
            </div>
        </div>
    </div>
@endsection