@extends('layouts.mahasiswa')

@section('title', 'Pembayaran')
@section('page-title', 'Pembayaran')

@section('content')
    <div class="space-y-8">

        {{-- TABLE 1: PEMBAYARAN KULIAH --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-white px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-extrabold text-maroon uppercase">PEMBAYARAN KULIAH</h3>
                <p class="text-xs text-gray-500 mt-1">takes 0.05 seconds to display the data on this page</p>
            </div>

            {{-- Status Banner --}}
            <div class="p-6">
                <div class="bg-maroon text-white text-center py-3 rounded-md border border-blue-100 font-medium">
                    Status Pembayaran Kuliah Lancar
                </div>
            </div>

            {{-- Table Content --}}
            <div class="px-6 pb-6 overflow-x-auto">
                <table class="w-full text-sm border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-maroon text-white text-center font-extrabold uppercase text-xs">
                            <th rowspan="2" class="border border-white/20 px-2 py-3 w-12">SEM</th>
                            <th rowspan="2" class="border border-white/20 px-2 py-3">Σ TAGIHAN</th>
                            <th rowspan="2" class="border border-white/20 px-2 py-3">Σ PEMBAYARAN</th>
                            <th colspan="4" class="border border-white/20 px-2 py-1 bg-maroon">PEMBAYARAN</th>
                            <th rowspan="2" class="border border-white/20 px-2 py-3">PAKET SKS BAYAR</th>
                            <th rowspan="2" class="border border-white/20 px-2 py-3">SKS AMBIL</th>
                            <th rowspan="2" class="border border-white/20 px-2 py-3">KET</th>
                        </tr>
                        <tr class="bg-maroon text-white text-center font-extrabold uppercase text-xs">
                            <th class="border border-white/20 px-2 py-2">TANGGAL 1</th>
                            <th class="border border-white/20 px-2 py-2">BAYAR 1</th>
                            <th class="border border-white/20 px-2 py-2">TANGGAL 2</th>
                            <th class="border border-white/20 px-2 py-2">BAYAR 2</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        @forelse($riwayatKuliah as $item)
                            <tr class="hover:bg-gray-50 border-b border-gray-200">
                                <td class="text-center py-2 px-3 border-r border-gray-200">{{ $item['semester'] }}</td>
                                <td class="text-right py-2 px-3 border-r border-gray-200 font-medium">
                                    {{ number_format($item['tagihan'], 2, '.', ',') }}</td>
                                <td class="text-right py-2 px-3 border-r border-gray-200 font-medium">
                                    {{ number_format($item['total_bayar'], 2, '.', ',') }}</td>
                                <td class="text-center py-2 px-3 border-r border-gray-200">{{ $item['tanggal_1'] }}</td>
                                <td class="text-right py-2 px-3 border-r border-gray-200">
                                    {{ number_format($item['bayar_1'], 2, '.', ',') }}</td>
                                <td class="text-center py-2 px-3 border-r border-gray-200">{{ $item['tanggal_2'] }}</td>
                                <td class="text-right py-2 px-3 border-r border-gray-200">
                                    {{ number_format($item['bayar_2'], 2, '.', ',') }}</td>
                                <td class="text-center py-2 px-3 border-r border-gray-200">{{ $item['paket_sks'] }}</td>
                                <td class="text-center py-2 px-3 border-r border-gray-200">{{ $item['sks_ambil'] }}</td>
                                <td
                                    class="text-left py-2 px-3 font-semibold {{ $item['ket'] == 'Lunas' ? 'text-green-700' : 'text-red-600' }}">
                                    {{ $item['ket'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-6 text-gray-500">Data pembayaran kuliah tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TABLE 2: PEMBAYARAN LAINNYA --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-white px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-extrabold text-maroon">Pembayaran selain Uang Kuliah melalui VA Bank DKI</h3>
            </div>

            <div class="p-6 overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="py-3 px-4 text-center font-extrabold text-black uppercase w-48">TANGGAL</th>
                            <th class="py-3 px-4 text-right font-extrabold text-black uppercase w-40">Σ TAGIHAN</th>
                            <th class="py-3 px-4 text-right font-extrabold text-black uppercase w-40">Σ PEMBAYARAN</th>
                            <th class="py-3 px-4 text-left font-extrabold text-black uppercase">JENIS</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        @forelse($riwayatLainnya as $idx => $item)
                            {{-- Row styling: Highlighted row (like orange in image) vs plain --}}
                            @php
                                $isOrange = $idx === 0; // Just mimicking the image where first row is orange
                            @endphp
                            <tr
                                class="{{ $isOrange ? 'bg-maroon text-white font-semibold shadow-md border border-maroon' : 'border-b border-gray-200 hover:bg-gray-50' }}">
                                <td class="py-3 px-4 text-center {{ $isOrange ? 'text-white' : 'text-gray-600' }}">
                                    {{ $item['tanggal'] }}
                                </td>
                                <td class="py-3 px-4 text-right {{ $isOrange ? 'font-semibold text-white' : 'font-medium text-gray-800' }}">
                                    {{ number_format($item['tagihan'], 2, '.', ',') }}
                                </td>
                                <td class="py-3 px-4 text-right {{ $isOrange ? 'font-semibold text-white' : 'font-medium text-gray-800' }}">
                                    {{ number_format($item['bayar'], 2, '.', ',') }}
                                </td>
                                <td
                                    class="py-3 px-4 text-left font-semibold uppercase {{ $isOrange ? 'text-white' : 'text-gray-600' }}">
                                    {{ $item['jenis'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-6 text-gray-500">Belum ada data pembayaran lainnya.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 pb-6 text-center">
                <p class="text-xs font-bold text-blue-800 uppercase mt-4">JIKA TERDAPAT DATA YANG TIDAK SESUAI, SILAKAN
                    MENGHUBUNGI PSA</p>
            </div>
        </div>

    </div>
@endsection