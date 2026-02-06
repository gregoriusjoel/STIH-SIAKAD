@extends('layouts.admin')
@section('title', 'Ketersediaan Dosen')
@section('page-title', 'Ketersediaan Dosen')

@section('content')
    <div class="space-y-6">
        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Dosen</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total_dosen'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                        <i class="fas fa-users text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Dosen Tersedia</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['dosen_with_availability'] }}</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full">
                        <i class="fas fa-user-check text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Slot Tersedia</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['available_slots'] }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-full">
                        <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow border-l-4 border-maroon">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Slot Terjadwal</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['booked_slots'] }}</p>
                    </div>
                    <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-full">
                        <i class="fas fa-calendar-check text-maroon dark:text-red-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Panel --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-maroon text-white">
                <h3 class="font-semibold text-white text-lg flex items-center gap-2">
                    <i class="fas fa-filter"></i>
                    Filter Ketersediaan
                </h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('admin.availability.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Semester</label>
                        <select name="semester_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon-500 text-sm">
                            <option value="">Semua Semester</option>
                            @foreach($semesters as $sem)
                                <option value="{{ $sem->id }}" {{ $semesterId == $sem->id ? 'selected' : '' }}>
                                    {{ $sem->nama_semester }} {{ $sem->tahun_ajaran }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dosen</label>
                        <select name="dosen_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon-500 text-sm">
                            <option value="">Semua Dosen</option>
                            @foreach($dosens as $d)
                                <option value="{{ $d->id }}" {{ $dosenId == $d->id ? 'selected' : '' }}>
                                    {{ $d->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hari</label>
                        <select name="hari" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon-500 text-sm">
                            <option value="">Semua Hari</option>
                            @foreach($days as $day)
                                <option value="{{ $day }}" {{ $hari == $day ? 'selected' : '' }}>{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-maroon text-white rounded-lg hover:bg-maroon-700 transition text-sm font-semibold">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Availability List --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                    <i class="fas fa-list text-maroon"></i>
                    Daftar Ketersediaan Dosen
                </h3>
            </div>

            <div class="p-6">
                @if($availabilitiesByDosen->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-inbox text-3xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Tidak Ada Data</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada dosen yang mengisi ketersediaan waktu.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($availabilitiesByDosen as $dosenId => $avails)
                            @php
                                $dosen = $avails->first()->dosen;
                                $availableCount = $avails->where('status', 'available')->count();
                                $bookedCount = $avails->where('status', 'booked')->count();
                            @endphp
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden hover:shadow-md transition">
                                <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-maroon rounded-full flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($dosen->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $dosen->user->name }}</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">NIDN: {{ $dosen->nidn }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="text-right text-sm">
                                            <span class="text-green-600 dark:text-green-400 font-semibold">{{ $availableCount }}</span>
                                            <span class="text-gray-500 dark:text-gray-400">tersedia</span>
                                            <span class="mx-1">•</span>
                                            <span class="text-blue-600 dark:text-blue-400 font-semibold">{{ $bookedCount }}</span>
                                            <span class="text-gray-500 dark:text-gray-400">terjadwal</span>
                                        </div>
                                        <a href="{{ route('admin.availability.show', $dosen->id) }}" 
                                           class="px-3 py-1.5 bg-maroon text-white text-xs rounded-lg hover:bg-maroon-700 transition">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </a>
                                    </div>
                                </div>
                                <div class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($avails->groupBy('hari') as $day => $dayAvails)
                                            <div class="inline-flex items-center gap-1 px-3 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 rounded-full text-xs">
                                                <i class="fas fa-calendar-day"></i>
                                                <span class="font-medium">{{ $day }}</span>
                                                <span class="text-blue-500 dark:text-blue-300">({{ $dayAvails->count() }} slot)</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
