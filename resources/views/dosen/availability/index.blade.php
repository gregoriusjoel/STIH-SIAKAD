@extends('layouts.app')
@section('title', 'Ketersediaan Waktu Mengajar')
@section('page-title', 'Ketersediaan Waktu Mengajar')

@section('content')
    <div class="space-y-6">
        {{-- Header Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg  overflow-hidden">
            <div
                class="p-6 border-b border-gray-200 dark:border-gray-700 bg-maroon text-white flex items-center justify-between">
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
                <div
                    class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-900/30 text-blue-700 dark:text-blue-300 px-4 py-3 rounded-lg text-sm flex gap-3 items-start">
                    <i class="fas fa-info-circle mt-0.5 text-blue-500 dark:text-blue-400"></i>
                    <div>
                        <span class="font-semibold block mb-0.5">Informasi</span>
                        Silakan atur ketersediaan waktu mengajar Anda untuk semester
                        <strong>{{ $activeSemester->nama_semester }} {{ $activeSemester->tahun_ajaran }}</strong>.
                        Admin akan menggunakan data ini untuk menyusun jadwal perkuliahan.
                    </div>
                </div>
            </div>
        </div>

        {{-- Availability Grid --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                    <i class="fas fa-clock text-maroon"></i>
                    Jadwal Ketersediaan Anda
                </h3>
            </div>

            <div class="p-6">
                @if($availabilities->isEmpty())
                    <div class="text-center py-12">
                        <div
                            class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-times text-3xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Belum Ada Ketersediaan</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Anda belum mengatur ketersediaan waktu
                            mengajar.</p>
                        <a href="{{ route('dosen.availability.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-maroon text-white rounded-lg hover:bg-maroon-700 transition">
                            <i class="fas fa-plus mr-2"></i>Atur Ketersediaan
                        </a>
                    </div>
                @else
                    {{-- Modern Card-Based Layout --}}
                    <div class="space-y-5">
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                            @if($availabilities->has($day))
                                <div
                                    class="group bg-gradient-to-r from-white to-gray-50 dark:from-gray-800 dark:to-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-maroon/30 dark:hover:border-maroon/50 hover:shadow-lg transition-all duration-300">
                                    <div class="p-6">
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                                            {{-- Day Label --}}
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-14 h-14 rounded-xl bg-gradient-to-br from-maroon to-red-800 flex items-center justify-center shadow-md">
                                                    <span class="text-white font-bold text-base">
                                                        {{ substr($day, 0, 3) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-gray-900 dark:text-gray-100 text-lg mb-1">{{ $day }}</h4>
                                                    @php
                                                        $bookedCount = $availabilities[$day]->where('status', 'booked')->count();
                                                        $totalCount = $availabilities[$day]->count();
                                                    @endphp
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        @if($bookedCount > 0)
                                                            <i class="fas fa-calendar-check text-blue-500 mr-1.5"></i>
                                                            {{ $bookedCount }}/{{ $totalCount }} slot terjadwal
                                                        @else
                                                            <i class="fas fa-check-circle text-green-500 mr-1.5"></i>
                                                            {{ $totalCount }} slot tersedia
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>

                                            {{-- Time Slots --}}
                                            <div class="flex-1 md:ml-8">
                                                <div class="flex flex-wrap gap-3">
                                                    @foreach($availabilities[$day] as $availability)
                                                        <div class="relative group/slot">
                                                            <span class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200
                                                                                    @if($availability->status === 'available') 
                                                                                        bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 border border-green-200 hover:from-green-100 hover:to-emerald-100 hover:shadow-md dark:from-green-900/20 dark:to-emerald-900/20 dark:text-green-400 dark:border-green-800
                                                                                    @elseif($availability->status === 'booked') 
                                                                                        bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 border border-blue-200 hover:from-blue-100 hover:to-indigo-100 hover:shadow-md dark:from-blue-900/20 dark:to-indigo-900/20 dark:text-blue-400 dark:border-blue-800
                                                                                    @else 
                                                                                        bg-gradient-to-r from-gray-50 to-slate-50 text-gray-700 border border-gray-200 hover:from-gray-100 hover:to-slate-100 hover:shadow-md dark:from-gray-900/20 dark:to-slate-900/20 dark:text-gray-400 dark:border-gray-700
                                                                                    @endif">
                                                                @if($availability->status === 'available')
                                                                    <i class="fas fa-circle text-[6px] text-green-500"></i>
                                                                @elseif($availability->status === 'booked')
                                                                    <i class="fas fa-circle text-[6px] text-blue-500"></i>
                                                                @else
                                                                    <i class="fas fa-circle text-[6px] text-gray-400"></i>
                                                                @endif
                                                                <span>{{ $availability->jamPerkuliahan->slot_label }}</span>
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection