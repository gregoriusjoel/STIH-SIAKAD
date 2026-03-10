@extends('layouts.parent')

@section('title', 'Presensi Mahasiswa - Orang Tua')
@section('page-title', 'Presensi Mahasiswa')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-800">Riwayat Kehadiran</h2>
            <p class="text-gray-500 text-sm">
                Presensi perkuliahan {{ $mahasiswa->user->name }}
                @if($activeSemester) &bull; {{ $activeSemester->nama_semester }} @endif
            </p>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600 text-xl flex-shrink-0">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800">{{ $presensiStats['hadir'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Hadir</div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 text-xl flex-shrink-0">
                    <i class="fas fa-notes-medical"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800">{{ $presensiStats['sakit'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Sakit</div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center text-yellow-600 text-xl flex-shrink-0">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800">{{ $presensiStats['izin'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Izin</div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center text-red-600 text-xl flex-shrink-0">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-800">{{ $presensiStats['alfa'] }}</div>
                    <div class="text-xs text-gray-500 font-medium">Alfa</div>
                </div>
            </div>
        </div>

        {{-- Presensi Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Detail Kehadiran</h3>
                <span class="text-sm text-gray-500">Total: {{ $presensiStats['total'] }} pertemuan</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full" style="min-width: 700px;">
                    <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3 text-left">Tanggal</th>
                            <th class="px-6 py-3 text-left">Mata Kuliah</th>
                            <th class="px-6 py-3 text-center">Pertemuan</th>
                            <th class="px-6 py-3 text-center">Status</th>
                            <th class="px-6 py-3 text-center">Mode</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($presensiData as $presensi)
                            @php
                                // Correct path: presensi → krs → kelasMataKuliah → mataKuliah
                                $mk = $presensi->krs?->kelasMataKuliah?->mataKuliah;
                                $jadwal = $presensi->krs?->kelasMataKuliah?->jadwal;
                                $tgl = $presensi->tanggal ?? $presensi->created_at;
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                                    <div>{{ \Carbon\Carbon::parse($tgl)->translatedFormat('d F Y') }}</div>
                                    @if($jadwal)
                                        <div class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} –
                                            {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="font-bold text-gray-800">{{ $mk?->nama_mk ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $mk?->kode_mk ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-600">
                                    <span class="px-2 py-1 bg-gray-100 rounded text-xs font-bold">
                                        {{ $presensi->pertemuan ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $status = strtolower($presensi->status ?? '');
                                    @endphp
                                    @if($status === 'hadir')
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">HADIR</span>
                                    @elseif($status === 'izin')
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">IZIN</span>
                                    @elseif($status === 'sakit')
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">SAKIT</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">ALFA</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($presensi->presence_mode === 'offline')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                            <i class="fas fa-map-marker-alt mr-1"></i> Offline
                                        </span>
                                        @if($presensi->distance_meters !== null)
                                            <div class="text-[10px] text-gray-500 mt-0.5">{{ round($presensi->distance_meters) }}m</div>
                                        @endif
                                    @elseif($presensi->presence_mode === 'online')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                            <i class="fas fa-wifi mr-1"></i> Online
                                        </span>
                                        @if($presensi->reason_category)
                                            <div class="text-[10px] text-blue-600 mt-0.5">{{ $presensi->reason_category }}</div>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                                        </div>
                                        <p class="text-gray-500 text-sm">Belum ada riwayat kehadiran.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($presensiData->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $presensiData->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection