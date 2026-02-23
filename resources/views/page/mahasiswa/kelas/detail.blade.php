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

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 max-w-none" x-data="{ activeTab: 'pertemuan' }">
        <div class="w-full space-y-6">

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
                                {{-- Status Kehadiran --}}
                                @if(!empty($meeting['attendance_status']))
                                    @if($meeting['attendance_status'] === 'hadir')
                                        @php
                                            $attendance = $meeting['attendance_data'] ?? null;
                                            $presenceMode = $attendance['presence_mode'] ?? null;
                                        @endphp
                                        
                                        @if($presenceMode === 'offline')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 shadow-sm">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                Hadir Offline
                                            </span>
                                        @elseif($presenceMode === 'online')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 shadow-sm">
                                                <i class="fas fa-wifi mr-1"></i>
                                                Hadir Online
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 shadow-sm">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Hadir
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 shadow-sm">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            {{ ucfirst($meeting['attendance_status']) }}
                                        </span>
                                    @endif
                                @elseif($meeting['is_past'])
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 shadow-sm">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        Belum Absen
                                    </span>
                                @endif
                                
                                <span class="material-symbols-outlined text-gray-400 transition-transform duration-300"
                                    :class="open ? 'rotate-180' : ''">expand_more</span>
                            </div>
                        </button>

                        {{-- Accordion Body --}}
                        <div x-show="open">
                            <div class="p-6 pt-0 border-t border-gray-50">
                                {{-- Attendance Info (if exists) --}}
                                @if(!empty($meeting['attendance_status']) && $meeting['attendance_status'] === 'hadir')
                                    @php
                                        $attendance = $meeting['attendance_data'] ?? null;
                                        $presenceMode = $attendance['presence_mode'] ?? null;
                                        $distance = $attendance['distance_meters'] ?? null;
                                        $reasonCategory = $attendance['reason_category'] ?? null;
                                        $reasonDetail = $attendance['reason_detail'] ?? null;
                                    @endphp
                                    
                                    @if($presenceMode)
                                    <div class="mb-4 p-4 bg-gradient-to-r {{ $presenceMode === 'offline' ? 'from-green-50 to-green-100' : 'from-blue-50 to-blue-100' }} rounded-xl border {{ $presenceMode === 'offline' ? 'border-green-200' : 'border-blue-200' }}">
                                        <div class="flex items-start gap-3">
                                            <div class="w-10 h-10 rounded-full {{ $presenceMode === 'offline' ? 'bg-green-500' : 'bg-blue-500' }} flex items-center justify-center text-white shadow-lg flex-shrink-0">
                                                <i class="fas {{ $presenceMode === 'offline' ? 'fa-map-marker-alt' : 'fa-wifi' }}"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h5 class="text-sm font-bold {{ $presenceMode === 'offline' ? 'text-green-900' : 'text-blue-900' }} mb-1">
                                                    Status Kehadiran: {{ $presenceMode === 'offline' ? 'Hadir Offline (On-site)' : 'Hadir Online (Remote)' }}
                                                </h5>
                                                @if($distance !== null)
                                                    <p class="text-xs {{ $presenceMode === 'offline' ? 'text-green-700' : 'text-blue-700' }}">
                                                        <i class="fas fa-location-arrow mr-1"></i>
                                                        Jarak dari kampus: <strong>{{ round($distance) }} meter</strong>
                                                    </p>
                                                @endif
                                                @if($presenceMode === 'online' && $reasonCategory)
                                                    <div class="mt-2 p-2 bg-white/70 rounded-lg border border-blue-200">
                                                        <p class="text-xs text-blue-800 font-medium">
                                                            <i class="fas fa-info-circle mr-1"></i>
                                                            Alasan: {{ $reasonCategory }}
                                                        </p>
                                                        @if($reasonDetail && $reasonCategory === 'Lainnya')
                                                            <p class="text-xs text-blue-700 mt-1 italic">{{ $reasonDetail }}</p>
                                                        @endif
                                                    </div>
                                                @endif
                                                <p class="text-[10px] {{ $presenceMode === 'offline' ? 'text-green-600' : 'text-blue-600' }} mt-2">
                                                    <i class="far fa-clock mr-1"></i>
                                                    Waktu absen: {{ $attendance['waktu'] ?? '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endif
                                
                                {{-- Method & Link Section --}}
                                <div class="mt-4 mb-2 p-4 bg-gray-50 rounded-xl border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                         <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-sm text-gray-500">
                                            @if(($meeting['method'] ?? 'offline') == 'online')
                                                <span class="material-symbols-outlined">videocam</span>
                                            @elseif(($meeting['method'] ?? 'offline') == 'asynchronous')
                                                <span class="material-symbols-outlined">schedule</span>
                                            @else
                                                <span class="material-symbols-outlined">school</span>
                                            @endif
                                         </div>
                                         <div>
                                            <h5 class="text-sm font-bold text-gray-800">Metode Pembelajaran</h5>
                                            <p class="text-xs text-gray-500 capitalize">
                                                {{ ($meeting['method'] ?? 'offline') == 'offline' ? 'Tatap Muka (Offline)' : (($meeting['method'] ?? 'offline') == 'online' ? 'Daring (Online)' : 'Asynchronous') }}
                                            </p>
                                         </div>
                                    </div>
                                    
                                    @if(($meeting['method'] ?? 'offline') == 'online' && !empty($meeting['online_link']))
                                        <a href="{{ $meeting['online_link'] }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 shadow-sm shadow-blue-200">
                                            <span class="material-symbols-outlined text-[18px]">videocam</span>
                                            Join Meeting
                                        </a>
                                    @endif
                                </div>

                                <div class="mt-4 space-y-6">
                                    {{-- Judul Materi dari Database --}}
                                    @if(count($meeting['materials']) > 0)
                                        @foreach($meeting['materials'] as $material)
                                            <div class="mb-4 bg-gray-50 p-3 rounded-lg border-l-4 border-maroon">
                                                <h5 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                                    <span class="material-symbols-outlined text-maroon text-[22px]">school</span>
                                                    {{ $material['judul'] }}
                                                </h5>
                                            </div>
                                        @endforeach
                                    @endif

                                    {{-- Description --}}
                                    <div>
                                        <h5 class="text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                                            <i class="fas fa-align-left text-maroon w-5"></i> Deskripsi
                                        </h5>
                                        <div class="pl-7 space-y-3">
                                            {{-- Tampilkan deskripsi dari materi yang diupload --}}
                                            @if(count($meeting['materials']) > 0)
                                                @foreach($meeting['materials'] as $material)
                                                    <p class="text-sm text-gray-600 leading-relaxed">
                                                        {{ $material['deskripsi'] }}
                                                    </p>
                                                @endforeach
                                            @else
                                                <p class="text-sm text-gray-600 leading-relaxed">
                                                    {{ $meeting['description'] }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Materials --}}
                                    <div>
                                        <h5 class="text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                                            <i class="fas fa-file-alt text-maroon w-5"></i> Materi Pembelajaran
                                        </h5>
                                        <div class="pl-7 space-y-3">
                                            @forelse($meeting['materials'] as $material)
                                                {{-- File Download Link --}}
                                                <a href="{{ $material['url'] }}" target="_blank"
                                                        class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 hover:border-maroon/30 hover:shadow-sm transition-all group">
                                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white"
                                                            style="background: {{ match($material['type']) {
                                                                'pdf' => 'linear-gradient(135deg, #dc2626 0%, #991b1b 100%)',
                                                                'doc', 'docx' => 'linear-gradient(135deg, #2563eb 0%, #1e40af 100%)',
                                                                'ppt', 'pptx' => 'linear-gradient(135deg, #ea580c 0%, #c2410c 100%)',
                                                                'xls', 'xlsx' => 'linear-gradient(135deg, #16a34a 0%, #15803d 100%)',
                                                                'zip', 'rar' => 'linear-gradient(135deg, #eab308 0%, #ca8a04 100%)',
                                                                default => 'linear-gradient(135deg, #6b7280 0%, #4b5563 100%)'
                                                            } }}">
                                                            <span class="material-symbols-outlined text-[20px]">
                                                                {{ match($material['type']) {
                                                                    'pdf' => 'picture_as_pdf',
                                                                    'doc', 'docx' => 'description',
                                                                    'ppt', 'pptx' => 'slideshow',
                                                                    'xls', 'xlsx' => 'table_chart',
                                                                    'zip', 'rar' => 'folder_zip',
                                                                    default => 'insert_drive_file'
                                                                } }}
                                                            </span>
                                                        </div>
                                                        <div class="flex-1">
                                                            <p class="text-sm font-bold text-gray-800 group-hover:text-maroon transition-colors">
                                                                {{ $material['name'] }}
                                                            </p>
                                                            <p class="text-xs text-gray-500">
                                                                {{ $material['size'] }} • Klik untuk download
                                                            </p>
                                                        </div>
                                                        <span class="material-symbols-outlined text-gray-300 group-hover:text-maroon text-[20px]">
                                                            download
                                                        </span>
                                                    </a>
                                            @empty
                                                <div class="p-4 rounded-xl border border-gray-100 bg-gray-50 text-center">
                                                    <p class="text-xs text-gray-500">Belum ada materi untuk pertemuan ini</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>

                                    {{-- Assignments --}}
                                    <div>
                                        <h5 class="text-sm font-bold text-gray-800 mb-2 flex items-center gap-2">
                                            <i class="fas fa-tasks text-maroon w-5"></i> Tugas
                                        </h5>
                                        <div class="pl-7 space-y-3">
                                            @forelse($meeting['assignments'] as $tugas)
                                                <div class="p-4 rounded-xl border border-orange-100 bg-orange-50/50">
                                                    <div class="flex items-start justify-between mb-3">
                                                        <div class="flex-1">
                                                            <p class="text-sm font-bold text-gray-800 mb-1">{{ $tugas['title'] }}</p>
                                                            
                                                            {{-- Deskripsi Tugas --}}
                                                            @if(!empty($tugas['description']))
                                                                <p class="text-xs text-gray-600 mb-2 leading-relaxed">{{ $tugas['description'] }}</p>
                                                            @endif
                                                            
                                                            <p class="text-xs text-gray-600">Deadline: <span class="font-bold text-orange-700">{{ $tugas['deadline'] }}</span></p>
                                                        </div>
                                                        <span class="px-2 py-1 rounded text-[10px] font-bold bg-white border border-gray-200 text-gray-500">Belum Dikumpulkan</span>
                                                    </div>
                                                    
                                                    <div class="flex gap-2">
                                                        @if(isset($tugas['file_url']) && $tugas['file_url'])
                                                            <a href="{{ $tugas['file_url'] }}" target="_blank" class="flex-1 py-2 rounded-lg bg-orange-100/50 text-orange-700 border border-orange-200 text-xs font-bold text-center hover:bg-orange-100 transition-all flex items-center justify-center gap-2">
                                                                <span class="material-symbols-outlined text-[16px]">download</span> Download Soal
                                                            </a>
                                                        @endif
                                                        <button class="flex-1 py-2 rounded-lg bg-white border border-gray-200 text-xs font-bold text-gray-600 hover:text-maroon hover:border-maroon transition-all flex items-center justify-center gap-2">
                                                            <span class="material-symbols-outlined text-[16px]">upload_file</span> Upload Jawaban
                                                        </button>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="p-4 rounded-xl border border-orange-100 bg-orange-50/50 text-center">
                                                    <p class="text-xs text-gray-500">Belum ada tugas untuk pertemuan ini</p>
                                                </div>
                                            @endforelse
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