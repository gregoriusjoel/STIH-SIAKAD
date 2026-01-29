@extends('layouts.mahasiswa')

@section('title', 'Detail Kelas')
@section('page-title', 'Detail Kelas')

@push('styles')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1"
        rel="stylesheet" />
    <style>
        [x-cloak] {
            display: none !important;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            font-size: 20px;
        }
    </style>
@endpush

@section('content')
    @section('navbar_breadcrumb')
        <nav class="flex items-center gap-2 text-sm text-[#616889]">
            <a href="{{ route('mahasiswa.kelas.index') }}" class="hover:text-primary transition-colors">E-Learning</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-[#111218] font-medium">{{ $classInfo['name'] }}</span>
        </nav>
    @endsection

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 xl:px-24 2xl:px-48 py-6" x-data="{ activeTab: 'pertemuan' }">
        <div class="mx-auto w-full max-w-[1800px] space-y-6">

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
                    {{-- Tab Materi Hidden as requested --}}
                    {{-- <button @click="activeTab = 'materi'"
                        class="py-4 text-sm font-medium border-b-2 transition-colors relative"
                        :class="activeTab === 'materi' ? 'text-maroon border-maroon' : 'text-gray-500 border-transparent hover:text-gray-700'">
                        Materi Kuliah
                    </button> --}}
                </div>
            </div>
        </div>

        <!-- Content: Pertemuan -->
        <div x-show="activeTab === 'pertemuan'" x-transition:enter="transition ease-out duration-300" class="space-y-4">
            <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                <i class="fas fa-list-ul text-maroon"></i>
                Timeline Pertemuan
            </h3>

            <div class="space-y-4">
                @foreach($meetings as $meeting)
                    <div x-data="{ open: false }" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        {{-- Accordion Header --}}
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between p-5 hover:bg-gray-50 transition-colors text-left">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 font-bold {{ $meeting['is_past'] ? 'bg-gray-100 text-gray-500' : 'bg-maroon/10 text-maroon' }}">
                                    {{ $meeting['no'] }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">{{ $meeting['label'] }}</h4>
                                    <div class="flex items-center gap-2 text-xs text-gray-500 mt-0.5">
                                        <span><i class="far fa-calendar-alt mr-1"></i> {{ $meeting['date'] }}</span>
                                        <span class="hidden md:inline">•</span>
                                        <span class="hidden md:inline"><i class="far fa-clock mr-1"></i>
                                            {{ $meeting['time'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400 transition-transform duration-300"
                                    :class="open ? 'rotate-180' : ''">expand_more</span>
                            </div>
                        </button>

                        {{-- Accordion Body --}}
                        <div x-show="open" x-collapse>
                            <div class="p-6 pt-0 border-t border-gray-50">
                                <div class="mt-4 space-y-6">
                                    {{-- Description --}}
                                    <div>
                                        <h5 class="text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                                            <i class="fas fa-align-left text-maroon w-5"></i> Deskripsi
                                        </h5>
                                        <p class="text-sm text-gray-600 leading-relaxed pl-7">
                                            {{ $meeting['description'] }}
                                        </p>
                                    </div>

                                    {{-- Materials --}}
                                    <div>
                                        <h5 class="text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                                            <i class="fas fa-file-alt text-maroon w-5"></i> Materi Pembelajaran
                                        </h5>
                                        <div class="pl-7 grid gap-3">
                                            @foreach($meeting['materials'] as $material)
                                                <a href="{{ $material['url'] }}" target="_blank"
                                                    class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-gray-50 hover:bg-white hover:border-maroon/30 hover:shadow-sm transition-all group">
                                                    <div
                                                        class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p
                                                            class="text-sm font-bold text-gray-800 group-hover:text-maroon transition-colors">
                                                            {{ $material['name'] }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">Klik untuk membuka di tab baru</p>
                                                    </div>
                                                    <i class="fas fa-external-link-alt text-gray-300 group-hover:text-maroon"></i>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Assignments --}}
                                    <div>
                                        <h5 class="text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                                            <i class="fas fa-tasks text-maroon w-5"></i> Tugas
                                        </h5>
                                        <div class="pl-7 space-y-3">
                                            @foreach($meeting['assignments'] as $tugas)
                                                <div class="p-4 rounded-xl border border-orange-100 bg-orange-50/50">
                                                    <div class="flex items-start justify-between mb-3">
                                                        <div>
                                                            <p class="text-sm font-bold text-gray-800 mb-1">{{ $tugas['title'] }}</p>
                                                            <p class="text-xs text-gray-600">Deadline: <span class="font-bold text-orange-700">{{ $tugas['deadline'] }}</span></p>
                                                        </div>
                                                        <span class="px-2 py-1 rounded text-[10px] font-bold bg-white border border-gray-200 text-gray-500">Belum Dikumpulkan</span>
                                                    </div>
                                                    
                                                    <div class="flex gap-2">
                                                        @if(isset($tugas['file_url']))
                                                            <a href="{{ $tugas['file_url'] }}" target="_blank" class="flex-1 py-2 rounded-lg bg-orange-100/50 text-orange-700 border border-orange-200 text-xs font-bold text-center hover:bg-orange-100 transition-all flex items-center justify-center gap-2">
                                                                <i class="fas fa-file-pdf"></i> Soal Tugas
                                                            </a>
                                                        @endif
                                                        <button class="flex-1 py-2 rounded-lg bg-white border border-gray-200 text-xs font-bold text-gray-600 hover:text-maroon hover:border-maroon transition-all flex items-center justify-center gap-2">
                                                            <span class="material-symbols-outlined text-[16px]">upload_file</span> Upload Jawaban
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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
    </div>
@endsection