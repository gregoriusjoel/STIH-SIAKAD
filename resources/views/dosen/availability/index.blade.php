@extends('layouts.app')
@section('title', 'Ketersediaan Waktu Mengajar')
@section('page-title', 'Ketersediaan Waktu Mengajar')

@section('content')
    <div class="space-y-6">
        {{-- Header Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-maroon text-white flex items-center justify-between">
                <div class="font-bold text-white text-xl flex items-center gap-3">
                    <div class="p-2 bg-white/10 rounded-lg backdrop-blur-sm">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    Ketersediaan Waktu Mengajar
                </div>
                <a href="{{ route('dosen.availability.create') }}" 
                   class="px-4 py-2 bg-white hover:bg-gray-100 text-maroon rounded-lg font-semibold transition-all duration-200 shadow-sm inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>Atur Ketersediaan
                </a>
            </div>

            <div class="p-6 bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-700/50 dark:to-blue-900/20">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-900/30 text-blue-700 dark:text-blue-300 px-4 py-3 rounded-lg text-sm flex gap-3 items-start">
                    <i class="fas fa-info-circle mt-0.5 text-blue-500 dark:text-blue-400"></i>
                    <div>
                        <span class="font-semibold block mb-0.5">Informasi</span>
                        Silakan atur ketersediaan waktu mengajar Anda untuk semester <strong>{{ $activeSemester->nama_semester }} {{ $activeSemester->tahun_ajaran }}</strong>. 
                        Admin akan menggunakan data ini untuk menyusun jadwal perkuliahan.
                    </div>
                </div>
            </div>
        </div>

        {{-- Availability Grid --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                    <i class="fas fa-clock text-maroon"></i>
                    Jadwal Ketersediaan Anda
                </h3>
            </div>

            <div class="p-6">
                @if($availabilities->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-times text-3xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Belum Ada Ketersediaan</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Anda belum mengatur ketersediaan waktu mengajar.</p>
                        <a href="{{ route('dosen.availability.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-maroon text-white rounded-lg hover:bg-maroon-700 transition">
                            <i class="fas fa-plus mr-2"></i>Atur Ketersediaan
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-maroon text-white">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold">Hari</th>
                                    <th class="px-4 py-3 text-left font-semibold">Jam Tersedia</th>
                                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                                    @if($availabilities->has($day))
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ $day }}</td>
                                            <td class="px-4 py-3">
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($availabilities[$day] as $availability)
                                                        <span class="px-3 py-1 rounded-full text-xs font-medium
                                                            @if($availability->status === 'available') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                                            @elseif($availability->status === 'booked') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                                            @else bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400
                                                            @endif">
                                                            {{ $availability->jamPerkuliahan->slot_label }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                @php
                                                    $bookedCount = $availabilities[$day]->where('status', 'booked')->count();
                                                    $totalCount = $availabilities[$day]->count();
                                                @endphp
                                                @if($bookedCount > 0)
                                                    <span class="text-xs text-blue-600 dark:text-blue-400">
                                                        {{ $bookedCount }}/{{ $totalCount }} slot terjadwal
                                                    </span>
                                                @else
                                                    <span class="text-xs text-green-600 dark:text-green-400">
                                                        Semua slot tersedia
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
