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
        .day-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .day-card:hover {
            box-shadow: 0 12px 32px -8px rgba(0, 0, 0, 0.08);
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endpush

@section('content')
    <div class="space-y-8 animate-fade-in-up">

        {{-- Hero Header --}}
        <div class="relative overflow-hidden rounded-3xl bg-linear-to-br from-[#7a1621] via-[#9b1c2a] to-[#5a0015] shadow-2xl shadow-red-900/20 text-white">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-[0.07] rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-red-300 opacity-[0.08] rounded-full blur-2xl pointer-events-none translate-y-1/2 -translate-x-1/4"></div>

            <div class="relative p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-2xl shadow-lg shrink-0">
                        <span class="material-symbols-outlined text-white/90">calendar_month</span>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black tracking-tight">Jadwal Kuliah</h2>
                        <p class="text-red-200/80 text-sm mt-0.5">Jadwal perkuliahan mahasiswa untuk semester aktif saat ini.</p>
                    </div>
                </div>
                <div class="shrink-0">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold text-white bg-white/10 backdrop-blur-md border border-white/10 uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        {{ $activeSemester?->nama_semester ?? 'Semester Aktif' }}
                    </span>
                </div>
            </div>
        </div>

        @php
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $dayColors = ['blue', 'teal', 'purple', 'orange', 'pink', 'indigo'];
            $jadwalItems = collect();
            foreach ($activeKrs as $krs) {
                if ($krs->kelas?->jadwals?->isNotEmpty()) {
                    foreach ($krs->kelas->jadwals as $j) {
                        $jadwalItems->push([
                            'hari' => $j->hari,
                            'jam_mulai' => $j->jam_mulai,
                            'jam_selesai' => $j->jam_selesai,
                            'ruangan' => $j->ruangan,
                            'krs' => $krs,
                        ]);
                    }
                } elseif ($krs->kelasMataKuliah?->hari) {
                    $jadwalItems->push([
                        'hari' => $krs->kelasMataKuliah->hari,
                        'jam_mulai' => $krs->kelasMataKuliah->jam_mulai,
                        'jam_selesai' => $krs->kelasMataKuliah->jam_selesai,
                        'ruangan' => $krs->kelasMataKuliah->ruang ?? $krs->kelasMataKuliah->ruangan?->nama_ruangan ?? 'Belum ditentukan',
                        'krs' => $krs,
                    ]);
                }
            }
            $groupedJadwal = $jadwalItems->groupBy('hari');
        @endphp

        <!-- Schedule Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6">
            @foreach ($days as $index => $day)
                @php $color = $dayColors[$index]; @endphp
                <div class="day-card bg-white dark:bg-[#1a1c23] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden flex flex-col {{ !isset($groupedJadwal[$day]) ? 'min-h-45' : '' }}">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-white/2">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-{{ $color }}-50 dark:bg-{{ $color }}-900/20 text-{{ $color }}-600 dark:text-{{ $color }}-400 flex items-center justify-center">
                                <span class="material-symbols-outlined text-lg">today</span>
                            </div>
                            <h3 class="font-bold text-gray-900 dark:text-white text-sm">{{ $day }}</h3>
                        </div>
                        @if (isset($groupedJadwal[$day]))
                            <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider bg-gray-100 dark:bg-gray-800 px-2.5 py-1 rounded-md">{{ $groupedJadwal[$day]->count() }} Matkul</span>
                        @endif
                    </div>

                    <div class="p-4 flex-1 flex flex-col">
                        @if (isset($groupedJadwal[$day]) && $groupedJadwal[$day]->count() > 0)
                            <div class="space-y-3">
                                @foreach ($groupedJadwal[$day]->sortBy('jam_mulai') as $item)
                                    @php
                                        $krs = $item['krs'];
                                        $mk = $krs->kelasMataKuliah?->mataKuliah ?? $krs->mataKuliah;
                                        $dosen = $krs->kelasMataKuliah?->dosen?->user?->name ?? $krs->kelas?->dosen?->user?->name ?? 'Dosen Belum Diatur';
                                        $jenisRaw = $mk?->jenis ?? 'Teori';
                                        $jenisNorm = strtolower($jenisRaw);
                                        $jenisLabel = ucwords(str_replace('_', ' ', $jenisNorm));
                                        $isPraktikum = $jenisNorm === 'praktikum';
                                    @endphp
                                    <div class="relative pl-3 pr-2 py-3 rounded-xl bg-gray-50/50 dark:bg-white/2 hover:bg-gray-50 dark:hover:bg-white/2 transition-colors border border-gray-100 dark:border-gray-800/50">
                                        <div class="absolute left-0 top-3 bottom-3 w-1 rounded-r-full {{ $isPraktikum ? 'bg-blue-500' : 'bg-maroon' }}"></div>
                                        <div class="space-y-2">
                                            <div class="flex items-start justify-between gap-2">
                                                <h4 class="font-bold text-gray-900 dark:text-white text-sm leading-snug">{{ $mk?->nama_mk }}</h4>
                                                <span class="shrink-0 px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wide {{ $isPraktikum ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800' : 'bg-orange-50 dark:bg-orange-900/20 text-orange-700 dark:text-orange-400 border border-orange-200 dark:border-orange-800' }}">
                                                    {{ $jenisLabel }}
                                                </span>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] text-gray-500 dark:text-gray-400">
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 font-bold text-gray-700 dark:text-gray-300">{{ $krs->kelasMataKuliah?->kode_kelas ?? ($krs->kelas?->resolved_kelas_name ?? 'A') }}</span>
                                                <span class="inline-flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[13px]">schedule</span>
                                                    {{ \Carbon\Carbon::parse($item['jam_mulai'])->format('H:i') }} – {{ \Carbon\Carbon::parse($item['jam_selesai'])->format('H:i') }}
                                                </span>
                                                <span class="inline-flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[13px]">location_on</span>
                                                    {{ $item['ruangan'] ?? 'Belum ditentukan' }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-1 text-[11px] text-gray-500 dark:text-gray-400">
                                                <span class="material-symbols-outlined text-[13px]">person</span>
                                                {{ $dosen }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex-1 flex flex-col items-center justify-center text-center gap-3 py-6">
                                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded-xl flex items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-300 dark:text-gray-600 text-2xl">event_busy</span>
                                </div>
                                <p class="text-xs text-gray-400 dark:text-gray-500 font-medium">Tidak ada jadwal kuliah</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($activeKrs->isEmpty())
            <div class="bg-white dark:bg-[#1a1c23] rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm p-12 md:p-16 text-center">
                <div class="w-20 h-20 bg-gray-50 dark:bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-inner">
                    <span class="material-symbols-outlined text-gray-300 dark:text-gray-600 text-3xl">calendar_month</span>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1">Tidak Ada Jadwal Kuliah</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto">Mahasiswa belum memiliki jadwal kuliah aktif untuk semester ini.</p>
            </div>
        @endif
    </div>
@endsection