@extends('layouts.parent')

@section('title', 'Presensi Mahasiswa - Orang Tua')
@section('page-title', 'Presensi Mahasiswa')

@section('content')
    <div class="space-y-6">

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500 font-medium">Total Kehadiran</div>
                    <div class="text-2xl font-bold text-gray-800">{{ $presensiData->total() }}</div>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600 text-xl">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            {{-- You can add more stats here involving absent/late if data allows --}}
        </div>

        {{-- Presensi Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Riwayat Kehadiran Terakhir</h3>
            </div>
            <div class="overflow-x-auto">
            <div class="overflow-x-auto">
                <table class="w-full" style="min-width: 800px;">
                    <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-3 text-left">Tanggal & Waktu</th>
                            <th class="px-6 py-3 text-left">Mata Kuliah</th>
                            <th class="px-6 py-3 text-center">Pertemuan Ke</th>
                            <th class="px-6 py-3 text-center">Status</th>
                            <th class="px-6 py-3 text-center">Jenis Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($presensiData as $presensi)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                                    <div>{{ \Carbon\Carbon::parse($presensi->created_at)->translatedFormat('d F Y') }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($presensi->created_at)->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="font-bold text-gray-800">
                                        {{ $presensi->jadwal->kelas->mataKuliah->nama_mk ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $presensi->jadwal->kelas->mataKuliah->kode_mk ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-600">
                                    <span class="px-2 py-1 bg-gray-100 rounded text-xs font-bold">
                                        {{ $presensi->pertemuan_ke }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($presensi->status == 'hadir')
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                            HADIR
                                        </span>
                                    @elseif($presensi->status == 'izin')
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">
                                            IZIN
                                        </span>
                                    @elseif($presensi->status == 'sakit')
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                            SAKIT
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                            ALFA
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($presensi->presence_mode === 'offline')
                                        <div class="flex flex-col items-center gap-1">
                                            <5pan class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                Offline (On-site)
                                            </span>
                                            @if($presensi->distance_meters !== null)
                                                <span class="text-[10px] text-gray-500">({{ round($presensi->distance_meters) }}m dari kampus)</span>
                                            @endif
                                        </div>
                                    @elseif($presensi->presence_mode === 'online')
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                                <i class="fas fa-wifi mr-1"></i>
                                                Online (Remote)
                                            </span>
                                            @if($presensi->distance_meters !== null)
                                                <span class="text-[10px] text-gray-500">({{ round($presensi->distance_meters) }}m dari kampus)</span>
                                            @endif
                                            @if($presensi->reason_category)
                                                <span class="text-[10px] text-blue-600 font-medium">
                                                    Alasan: {{ $presensi->reason_category }}
                                                    @if($presensi->reason_detail && $presensi->reason_category === 'Lainnya')
                                                        <br><span class="text-gray-600 italic">{{ Str::limit($presensi->reason_detail, 40) }}</span>
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    Belum ada riwayat kehadiran.
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