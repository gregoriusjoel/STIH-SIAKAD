@extends('layouts.admin')
@section('title', 'Detail Ketersediaan Dosen')
@section('page-title', 'Detail Ketersediaan Dosen')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg  overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-maroon text-white flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/10 rounded-full flex items-center justify-center text-white font-bold text-xl backdrop-blur-sm">
                        {{ strtoupper(substr($dosen->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="font-bold text-white text-xl">{{ $dosen->user->name }}</h2>
                        <p class="text-sm text-white/80">NIDN: {{ $dosen->nidn }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.availability.index') }}" 
                   class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg font-semibold transition-all duration-200 inline-flex items-center border border-white/20">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            <div class="p-6 bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-700/50 dark:to-blue-900/20">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-900/30 text-blue-700 dark:text-blue-300 px-4 py-3 rounded-lg text-sm flex gap-3 items-start">
                    <i class="fas fa-info-circle mt-0.5 text-blue-500 dark:text-blue-400"></i>
                    <div>
                        <span class="font-semibold block mb-0.5">Semester Aktif</span>
                        {{ $activeSemester->nama_semester }} {{ $activeSemester->tahun_ajaran }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Availability Grid --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                    <i class="fas fa-calendar-week text-maroon"></i>
                    Jadwal Ketersediaan Mingguan
                </h3>
            </div>

            <div class="p-6 overflow-x-auto">
                @if($availabilities->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-times text-3xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Tidak Ada Ketersediaan</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Dosen belum mengisi ketersediaan waktu untuk semester ini.</p>
                    </div>
                @else
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="bg-maroon text-white">
                                <th class="px-3 py-3 text-left font-semibold border border-gray-300 dark:border-gray-600 sticky left-0 bg-maroon z-10">Jam</th>
                                @foreach($days as $day)
                                    <th class="px-3 py-3 text-center font-semibold border border-gray-300 dark:border-gray-600 min-w-[100px]">{{ $day }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jamPerkuliahan as $jam)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <td class="px-3 py-2 border border-gray-300 dark:border-gray-600 font-medium text-gray-700 dark:text-gray-300 sticky left-0 bg-white dark:bg-gray-800 z-10">
                                        <div class="text-xs">Jam {{ $jam->jam_ke }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ substr($jam->jam_mulai, 0, 5) }} - {{ substr($jam->jam_selesai, 0, 5) }}</div>
                                    </td>
                                    @foreach($days as $day)
                                        @php
                                            $slot = $availabilities->get($day)?->firstWhere('jam_perkuliahan_id', $jam->id);
                                        @endphp
                                        <td class="px-2 py-2 border border-gray-300 dark:border-gray-600 text-center">
                                            @if($slot)
                                                <div class="h-12 rounded flex items-center justify-center text-xs font-medium
                                                    @if($slot->status === 'available') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                                    @elseif($slot->status === 'booked') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                                    @else bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400
                                                    @endif">
                                                    @if($slot->status === 'available')
                                                        <i class="fas fa-check"></i>
                                                    @elseif($slot->status === 'booked')
                                                        <i class="fas fa-lock"></i>
                                                    @else
                                                        <i class="fas fa-times"></i>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="h-12 rounded bg-gray-50 dark:bg-gray-700/30 border-2 border-dashed border-gray-200 dark:border-gray-600"></div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-6 flex items-center gap-6 text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-green-500 rounded"></div>
                            <span class="text-gray-600 dark:text-gray-400">Tersedia</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-blue-500 rounded"></div>
                            <span class="text-gray-600 dark:text-gray-400">Terjadwal</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-gray-200 dark:bg-gray-600 rounded border-2 border-dashed border-gray-300 dark:border-gray-500"></div>
                            <span class="text-gray-600 dark:text-gray-400">Tidak Tersedia</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
