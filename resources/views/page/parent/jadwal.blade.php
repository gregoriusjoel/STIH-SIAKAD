@extends('layouts.parent')

@section('title', 'Jadwal Kuliah - Orang Tua')
@section('page-title', 'Jadwal Kuliah')

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
    <div class="flex flex-col min-w-0 h-full">
        <div class="max-w-[1400px] mx-auto w-full flex flex-col gap-6">

            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-black text-[#111218]">Jadwal Kuliah</h1>
                    <p class="text-[#616889] text-sm">Jadwal perkuliahan mahasiswa untuk semester aktif saat ini.</p>
                </div>

                <div
                    class="inline-block px-3 py-1 rounded-full text-xs font-medium text-white bg-gradient-to-r from-red-700 to-red-900">
                    Semester Ganjil 2025/2026
                </div>
            </div>

            <!-- Schedule Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @php
                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    // Group KRS by Day
                    $groupedJadwal = $activeKrs->filter(function ($item) {
                        return isset($item->kelasMataKuliah->jadwal);
                    })->groupBy(function ($item) {
                        return $item->kelasMataKuliah->jadwal->hari;
                    });
                @endphp

                @foreach($days as $day)
                    <div
                        class="bg-white rounded-xl border border-[#dbdde6] p-5 flex flex-col gap-4 shadow-sm {{ !isset($groupedJadwal[$day]) ? 'min-h-[150px]' : '' }}">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                            <h3 class="font-bold text-[#111218]">{{ $day }}</h3>
                            @if(isset($groupedJadwal[$day]))
                                <span class="text-xs font-medium text-[#616889]">{{ $groupedJadwal[$day]->count() }} Matkul</span>
                            @endif
                        </div>

                        @if(isset($groupedJadwal[$day]) && $groupedJadwal[$day]->count() > 0)
                            @foreach($groupedJadwal[$day] as $krs)
                                @php
                                    $mk = $krs->kelasMataKuliah->mataKuliah;
                                    $jadwal = $krs->kelasMataKuliah->jadwal;
                                    $dosen = $krs->kelasMataKuliah->dosen->user->name ?? 'Dosen Belum Diatur';
                                    $jenisRaw = $mk->jenis ?? 'Teori';
                                    $jenisNorm = strtolower($jenisRaw);
                                    $jenisLabel = ucwords(str_replace('_', ' ', $jenisNorm));
                                    $color = $jenisNorm === 'praktikum' ? 'blue' : 'orange';
                                @endphp
                                <!-- Class Item -->
                                <div class="flex gap-3 relative pl-3 hover:bg-gray-50/50 rounded-r-lg transition-colors p-2 -mx-2">
                                    <div class="absolute left-0 top-1 bottom-1 w-1 bg-red-900 rounded-full"></div>
                                    <div class="flex-1 flex flex-col gap-1">
                                        <div class="flex justify-between items-start gap-2">
                                            <h4 class="font-bold text-[#111218] text-sm leading-tight">{{ $mk->nama_mk }}</h4>
                                            <span
                                                class="bg-{{ $color }}-50 text-{{ $color }}-600 px-2 py-0.5 rounded text-[10px] font-bold shrink-0">{{ $jenisLabel }}</span>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2 text-xs text-[#616889]">
                                            <span
                                                class="bg-red-50 text-red-700 px-1.5 rounded font-bold">{{ $krs->kelasMataKuliah->section ?? 'A' }}</span>
                                            <span>•</span>
                                            <span class="font-semibold">{{ $mk->sks ?? 3 }} SKS</span>
                                            <span>•</span>
                                            <div class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">schedule</span>
                                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-1 text-xs text-[#616889] mt-0.5">
                                            <span class="material-symbols-outlined text-[14px]">location_on</span>
                                            {{ $jadwal->ruangan ?? 'Belum ditentukan' }}
                                        </div>

                                        <div class="flex items-center gap-1 text-xs text-[#616889] mt-0.5">
                                            <span class="material-symbols-outlined text-[14px]">person</span>
                                            {{ $dosen }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="flex-1 flex flex-col items-center justify-center text-center gap-2 text-[#616889] py-4">
                                <span class="material-symbols-outlined text-gray-300 text-3xl">event_busy</span>
                                <p class="text-sm">Tidak ada jadwal kuliah</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            @if($activeKrs->isEmpty())
                <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <span class="material-symbols-outlined text-4xl">calendar_month</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Tidak Ada Jadwal Kuliah</h3>
                    <p class="text-gray-500">Mahasiswa belum memiliki jadwal kuliah aktif untuk semester ini.</p>
                </div>
            @endif

        </div>
    </div>
@endsection