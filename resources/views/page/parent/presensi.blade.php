@extends('layouts.parent')

@section('title', 'Presensi Mahasiswa - Orang Tua')
@section('page-title', 'Presensi Mahasiswa')

@push('styles')
    <style>
        .stat-card-presensi {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .stat-card-presensi:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 36px -10px rgba(0, 0, 0, 0.1);
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

            <div class="relative p-6 md:p-8">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-2xl shadow-lg shrink-0">
                        <i class="fas fa-clock text-white/90"></i>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black tracking-tight">Riwayat Kehadiran</h2>
                        <p class="text-red-200/80 text-sm mt-0.5">
                            Presensi perkuliahan {{ $mahasiswa->user->name }}
                            @if($activeSemester) &bull; {{ $activeSemester->nama_semester }} @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-5">
            {{-- Hadir --}}
            <div class="stat-card-presensi relative bg-white dark:bg-[#1a1c23] rounded-2xl p-5 border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="absolute left-0 top-5 bottom-5 w-1 rounded-r-full bg-green-500"></div>
                <div class="flex items-center gap-4 pl-3">
                    <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 flex items-center justify-center text-xl shadow-sm shrink-0">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ $presensiStats['hadir'] }}</div>
                        <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-0.5">Hadir</div>
                    </div>
                </div>
            </div>
            {{-- Sakit --}}
            <div class="stat-card-presensi relative bg-white dark:bg-[#1a1c23] rounded-2xl p-5 border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="absolute left-0 top-5 bottom-5 w-1 rounded-r-full bg-blue-500"></div>
                <div class="flex items-center gap-4 pl-3">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl shadow-sm shrink-0">
                        <i class="fas fa-notes-medical"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ $presensiStats['sakit'] }}</div>
                        <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-0.5">Sakit</div>
                    </div>
                </div>
            </div>
            {{-- Izin --}}
            <div class="stat-card-presensi relative bg-white dark:bg-[#1a1c23] rounded-2xl p-5 border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="absolute left-0 top-5 bottom-5 w-1 rounded-r-full bg-yellow-500"></div>
                <div class="flex items-center gap-4 pl-3">
                    <div class="w-12 h-12 rounded-xl bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400 flex items-center justify-center text-xl shadow-sm shrink-0">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ $presensiStats['izin'] }}</div>
                        <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-0.5">Izin</div>
                    </div>
                </div>
            </div>
            {{-- Alfa --}}
            <div class="stat-card-presensi relative bg-white dark:bg-[#1a1c23] rounded-2xl p-5 border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="absolute left-0 top-5 bottom-5 w-1 rounded-r-full bg-red-500"></div>
                <div class="flex items-center gap-4 pl-3">
                    <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 flex items-center justify-center text-xl shadow-sm shrink-0">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ $presensiStats['alfa'] }}</div>
                        <div class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-0.5">Alfa</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Presensi Table --}}
        <div class="bg-white dark:bg-[#1a1c23] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 flex items-center justify-center">
                        <i class="fas fa-list-ul text-sm"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white">Detail Kehadiran</h3>
                </div>
                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total: {{ $presensiStats['total'] }} pertemuan</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full" style="min-width: 700px;">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-white/3 border-b border-gray-100 dark:border-gray-800">
                            <th class="px-6 py-3.5 text-left text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Tanggal</th>
                            <th class="px-6 py-3.5 text-left text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Mata Kuliah</th>
                            <th class="px-6 py-3.5 text-center text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Pertemuan</th>
                            <th class="px-6 py-3.5 text-center text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-3.5 text-center text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Mode</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                        @forelse($presensiData as $presensi)
                            @php
                                $krs = $presensi->krs;
                                $mk = $krs?->kelasMataKuliah?->mataKuliah ?? $krs?->kelas?->mataKuliah ?? $krs?->mataKuliah;
                                $jadwal = null;
                                if ($krs?->kelasMataKuliah?->hari) {
                                    $jadwal = (object)[
                                        'jam_mulai' => $krs->kelasMataKuliah->jam_mulai,
                                        'jam_selesai' => $krs->kelasMataKuliah->jam_selesai,
                                    ];
                                } elseif ($krs?->kelas?->jadwals?->isNotEmpty()) {
                                    $jadwal = $krs->kelas->jadwals->first();
                                }
                                $tgl = $presensi->tanggal ?? $presensi->created_at;
                                $status = strtolower($presensi->status ?? '');
                            @endphp
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-white/2 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($tgl)->translatedFormat('d F Y') }}</div>
                                    @if($jadwal)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 tabular-nums">
                                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900 dark:text-white text-sm">{{ $mk?->nama_mk ?? '-' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 font-mono mt-0.5">{{ $mk?->kode_mk ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block px-2.5 py-1 bg-gray-100 dark:bg-gray-800 rounded-lg text-xs font-black text-gray-700 dark:text-gray-300 tabular-nums">
                                        {{ $presensi->pertemuan ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($status === 'hadir')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black tracking-wide bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> HADIR
                                        </span>
                                    @elseif($status === 'izin')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black tracking-wide bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> IZIN
                                        </span>
                                    @elseif($status === 'sakit')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black tracking-wide bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> SAKIT
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black tracking-wide bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> ALFA
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($presensi->presence_mode === 'offline')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800">
                                            <i class="fas fa-map-marker-alt text-[9px]"></i> Offline
                                        </span>
                                        @if($presensi->distance_meters !== null)
                                            <div class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 tabular-nums">{{ round($presensi->distance_meters) }}m</div>
                                        @endif
                                    @elseif($presensi->presence_mode === 'online')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                            <i class="fas fa-wifi text-[9px]"></i> Online
                                        </span>
                                        @if($presensi->reason_category)
                                            <div class="text-[10px] text-blue-600 dark:text-blue-400 mt-1">{{ $presensi->reason_category }}</div>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-800 rounded-2xl flex items-center justify-center shadow-inner">
                                            <i class="fas fa-calendar-times text-gray-300 dark:text-gray-600 text-2xl"></i>
                                        </div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Belum ada riwayat kehadiran.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($presensiData->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-white/2">
                    {{ $presensiData->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection