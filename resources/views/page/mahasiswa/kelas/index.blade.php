@extends('layouts.mahasiswa')

@section('title', 'E-Learning')
@section('page-title', 'E-Learning')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">E-Learning</h1>
                    <p class="text-gray-500 text-sm mt-1">Daftar kelas yang sedang Anda ambil semester ini</p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium">
                        Total SKS: {{ collect($classes)->sum('sks') }}
                    </div>
                    <div class="bg-purple-50 text-purple-700 px-4 py-2 rounded-lg text-sm font-medium">
                        Total Kelas: {{ count($classes) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Class Grid -->
        @if(count($classes) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($classes as $class)
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                        <!-- Card Header with Gradient -->
                        <div class="bg-gradient-to-r from-maroon to-red-900 p-4 text-white relative overflow-hidden">
                            <div class="relative z-10">
                                <div class="flex justify-between items-start mb-2">
                                    @if(!empty($class['kode_mk']))
                                        <span class="bg-white/20 px-2 py-1 rounded text-xs font-medium backdrop-blur-sm">
                                            {{ $class['kode_mk'] }}
                                        </span>
                                    @endif
                                    <span class="bg-white/20 px-2 py-1 rounded text-xs font-medium backdrop-blur-sm">
                                        {{ $class['sks'] }} SKS
                                    </span>
                                </div>
                                <h3 class="font-bold text-lg leading-tight mb-1 line-clamp-2" title="{{ $class['mata_kuliah'] }}">
                                    {{ $class['mata_kuliah'] }}
                                </h3>
                                <p class="text-red-100 text-sm">Kelas {{ $class['section'] }}</p>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 space-y-4">
                            <!-- Info Items -->
                            <div class="space-y-3">
                                <div class="flex items-start gap-3 text-gray-600">
                                    <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center shrink-0">
                                        <i class="fas fa-chalkboard-teacher text-maroon text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Dosen Pengampu</p>
                                        <p class="text-sm font-medium text-gray-800 line-clamp-1">{{ $class['dosen'] }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3 text-gray-600">
                                    <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center shrink-0">
                                        <i class="far fa-clock text-maroon text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Waktu & Tempat</p>
                                        <p class="text-sm font-medium text-gray-800">{{ $class['hari'] }}, {{ $class['jam'] }}</p>
                                        <p class="text-xs text-gray-500">Ruang: {{ $class['ruangan'] }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="pt-2">
                                <a href="{{ route('mahasiswa.kelas.show', $class['id']) }}"
                                    class="w-full py-2.5 bg-maroon text-white rounded-xl text-sm font-medium border border-maroon hover:bg-red-900 transition-colors flex items-center justify-center gap-2 shadow-sm shadow-maroon/20">
                                    <span>Lihat Detail</span>
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl p-12 text-center border border-dashed border-gray-300">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-book-open text-3xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Kelas</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-6">
                    Anda belum memiliki kelas yang disetujui untuk semester ini. Pastikan KRS Anda sudah disetujui oleh Dosen
                    PA.
                </p>
                <a href="{{ route('mahasiswa.krs.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-maroon text-white rounded-lg hover:bg-red-900 transition shadow-sm">
                    <i class="fas fa-file-alt"></i>
                    <span>Cek Status KRS</span>
                </a>
            </div>
        @endif
    </div>
@endsection