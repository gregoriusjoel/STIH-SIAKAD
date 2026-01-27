@extends('layouts.mahasiswa')

@section('title', 'Detail Kelas')
@section('page-title', 'Detail Kelas')

@push('styles')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6" x-data="{ activeTab: 'pertemuan' }">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('mahasiswa.kelas.index') }}" class="hover:text-maroon transition-colors">E-Learning</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="font-medium text-gray-800">{{ $classInfo['name'] }}</span>
        </nav>

        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row gap-6 md:items-start">
                    <!-- Icon -->
                    <div
                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-maroon to-red-900 flex items-center justify-center text-white shrink-0 shadow-lg shadow-maroon/20">
                        <i class="fas fa-book-open text-3xl"></i>
                    </div>

                    <!-- Info -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $classInfo['name'] }}</h1>
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                                Kelas {{ $classInfo['section'] }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4 text-sm">
                            <div class="flex items-center gap-3 text-gray-600">
                                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center">
                                    <i class="fas fa-chalkboard-teacher text-maroon"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium uppercase">Dosen</p>
                                    <p class="font-medium truncate">{{ $classInfo['dosen'] }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 text-gray-600">
                                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center">
                                    <i class="far fa-clock text-maroon"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium uppercase">Waktu</p>
                                    <p class="font-medium">{{ $classInfo['day'] }}, {{ $classInfo['time'] }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 text-gray-600">
                                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center">
                                    <i class="fas fa-map-marker-alt text-maroon"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium uppercase">Ruangan</p>
                                    <p class="font-medium">{{ $classInfo['room'] }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 text-gray-600">
                                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center">
                                    <i class="fas fa-star text-maroon"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium uppercase">SKS</p>
                                    <p class="font-medium">{{ $classInfo['sks'] }} SKS</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="border-t border-gray-100 px-6">
                <div class="flex gap-6">
                    <button @click="activeTab = 'pertemuan'"
                        class="py-4 text-sm font-medium border-b-2 transition-colors relative"
                        :class="activeTab === 'pertemuan' ? 'text-maroon border-maroon' : 'text-gray-500 border-transparent hover:text-gray-700'">
                        Daftar Pertemuan
                    </button>
                    <button @click="activeTab = 'materi'"
                        class="py-4 text-sm font-medium border-b-2 transition-colors relative"
                        :class="activeTab === 'materi' ? 'text-maroon border-maroon' : 'text-gray-500 border-transparent hover:text-gray-700'">
                        Materi Kuliah
                    </button>
                </div>
            </div>
        </div>

        <!-- Content: Pertemuan -->
        <div x-show="activeTab === 'pertemuan'" x-transition:enter="transition ease-out duration-300" class="space-y-4">
            <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                <i class="fas fa-list-ul text-maroon"></i>
                Timeline Pertemuan
            </h3>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="divide-y divide-gray-100">
                    @foreach($meetings as $meeting)
                        <div
                            class="p-4 hover:bg-gray-50 transition-colors flex flex-col md:flex-row md:items-center justify-between gap-4 group">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 font-bold {{ $meeting['is_past'] ? 'bg-gray-100 text-gray-500' : 'bg-blue-50 text-blue-600' }}">
                                    {{ $meeting['no'] }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $meeting['label'] }}</p>
                                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                        <i class="far fa-calendar"></i> {{ $meeting['date'] }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-1 md:hidden">
                                        <i class="far fa-clock"></i> {{ $meeting['time'] }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 pl-16 md:pl-0">
                                <div class="hidden md:block text-right mr-4">
                                    <p class="text-xs text-gray-400 font-medium uppercase">Jam Kuliah</p>
                                    <p class="text-sm font-medium text-gray-700">{{ $meeting['time'] }}</p>
                                </div>

                                <div class="text-right">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $meeting['status_class'] }}">
                                        {{ $meeting['status'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Content: Materi -->
        <div x-show="activeTab === 'materi'" x-cloak class="space-y-4">
            <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                <i class="fas fa-folder-open text-maroon"></i>
                Arsip Materi
            </h3>

            <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-cloud-download-alt text-3xl text-gray-300"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Belum Ada Materi Diunggah</h3>
                <p class="text-gray-500 max-w-md mx-auto text-sm">
                    Dosen pengampu belum mengunggah materi untuk mata kuliah ini. Materi akan muncul di sini setelah
                    tersedia.
                </p>
            </div>
        </div>
    </div>
@endsection