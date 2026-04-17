@extends('layouts.parent')

@section('title', 'Nilai Akademik - Orang Tua')
@section('page-title', 'Nilai Akademik')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div
            class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Transkrip Nilai</h2>
                <p class="text-gray-500 text-sm">Rekapitulasi hasil studi {{ $mahasiswa->user->name }} per semester</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <div class="text-xs text-gray-500 uppercase font-semibold">IPK Kumulatif</div>
                    <div class="text-2xl font-bold text-red-800">{{ number_format($ipk, 2) }}</div>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                    <i class="fas fa-award text-red-800 text-xl"></i>
                </div>
            </div>
        </div>

        {{-- Content --}}
        @forelse($nilaiData as $semester => $items)
            @php
                $ips = $ipsPerSemester[$semester] ?? 0;
                $semesterTotalSks = $items->sum(fn($k) =>
                    $k->kelasMataKuliah?->mataKuliah?->sks ?? $k->mataKuliah?->sks ?? 0
                );
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ open: true }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-full bg-red-100 text-red-700 flex items-center justify-center font-bold text-xs">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">{{ $semester }}</h3>
                        <span class="text-xs text-gray-500 ml-2">{{ $items->count() }} MK &bull; {{ $semesterTotalSks }} SKS</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-sm text-right hidden sm:block">
                            <span class="text-gray-400 text-xs">IPS</span>
                            <span class="ml-2 font-bold {{ $ips >= 3.0 ? 'text-green-600' : ($ips >= 2.0 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ number_format($ips, 2) }}
                            </span>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': !open }"></i>
                    </div>
                </button>

                <div x-show="open">
                    <div class="overflow-x-auto">
                        <table class="w-full" style="min-width: 700px;">
                            <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase font-semibold">
                                <tr>
                                    <th class="px-6 py-3 text-left">Kode MK</th>
                                    <th class="px-6 py-3 text-left">Mata Kuliah</th>
                                    <th class="px-6 py-3 text-center">SKS</th>
                                    <th class="px-6 py-3 text-center">Nilai Angka</th>
                                    <th class="px-6 py-3 text-center">Grade</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($items as $item)
                                    @php
                                        $mk = $item->kelasMataKuliah?->mataKuliah ?? $item->mataKuliah;
                                    @endphp
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-600">
                                            {{ $mk?->kode_mk ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-800 font-semibold">
                                            {{ $mk?->nama_mk ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm text-gray-600">
                                            {{ $mk?->sks ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm text-gray-600">
                                            {{ $item->nilai?->nilai_akhir ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $grade = $item->nilai?->grade ?? '-';
                                                $color = match (true) {
                                                    str_contains($grade, 'A') => 'bg-green-100 text-green-700',
                                                    str_contains($grade, 'B') => 'bg-blue-100 text-blue-700',
                                                    str_contains($grade, 'C') => 'bg-yellow-100 text-yellow-700',
                                                    default => 'bg-red-100 text-red-700'
                                                };
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $color }}">
                                                {{ $grade }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-file-alt text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Data Nilai</h3>
                <p class="text-gray-500">Mahasiswa belum memiliki riwayat nilai akademik.</p>
            </div>
        @endforelse

    </div>
@endsection