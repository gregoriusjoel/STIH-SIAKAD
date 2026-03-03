@extends('layouts.app')

@section('title', $class_info['name'])

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f8f9fa;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
@endpush

@section('content')
    @section('navbar_breadcrumb')
        <nav class="flex items-center gap-3 text-sm text-white/60 font-bold tracking-tight">
            <a class="hover:text-white transition-all duration-300 flex items-center group" href="{{ route('dosen.dashboard') }}">
                <span class="material-symbols-outlined text-[19px] group-hover:scale-110 opacity-80">home</span>
            </a>
            <span class="material-symbols-outlined text-[10px] text-white/20 font-normal">play_arrow</span>
            <a href="{{ route('dosen.kelas') }}" class="hover:text-white transition-all duration-300">Kelas</a>
            <span class="material-symbols-outlined text-[10px] text-white/20 font-normal">play_arrow</span>
            <span class="text-white font-black text-[13px] uppercase tracking-wider">
                {{ $class_info['name'] }}
            </span>
        </nav>
    @endsection

    @php
        // Build meetings list using dates resolved by controller:
        //   1. Actual tanggal stored in pertemuan table (DB)
        //   2. UTS/UAS start from Kalender Akademik (academic_events)
        //   3. Calculated: first class-weekday from perkuliahan start + week offset
        $meetings = [];
        $pertemuanDatesMap = $pertemuanDatesMap ?? [];

        if (isset($meetingSlots) && $meetingSlots->count()) {
            foreach ($meetingSlots as $slot) {
                $key     = $slot['tipe'] . ':' . $slot['nomor'];
                $dateStr = $pertemuanDatesMap[$key] ?? null;
                $displayDate = $dateStr
                    ? \Carbon\Carbon::parse($dateStr)->locale('id')->isoFormat('D MMM YYYY')
                    : '-';

                $meetings[] = [
                    'no'          => $slot['slot'],
                    'tipe'        => $slot['tipe'],
                    'nomor'       => $slot['nomor'],
                    'label'       => $slot['label'],
                    'route_param' => $slot['tipe'] . ':' . $slot['nomor'],
                    'date'        => $displayDate,
                    'time'        => $class_info['time'],
                    'present'     => 0,
                    'total'       => count($students),
                    'status'      => 'Belum Dimulai',
                    'is_exam'     => in_array($slot['tipe'], ['uts', 'uas']),
                ];
            }
        } else {
            // Fallback when no meetingSlots passed from controller
            $startDate = !empty($class_info['semester_start_date'])
                ? \Carbon\Carbon::parse($class_info['semester_start_date'])
                : now();
            for ($i = 1; $i <= ($class_info['total_pertemuan'] ?? 16); $i++) {
                $meetings[] = [
                    'no'          => $i,
                    'tipe'        => 'kuliah',
                    'nomor'       => $i,
                    'label'       => 'Pertemuan ' . $i,
                    'route_param' => $i,
                    'date'        => $startDate->copy()->addWeeks($i - 1)->locale('id')->isoFormat('D MMM YYYY'),
                    'time'        => $class_info['time'],
                    'present'     => 0,
                    'total'       => count($students),
                    'status'      => 'Belum Dimulai',
                    'is_exam'     => false,
                ];
            }
        }
    @endphp

    @section('main-class', 'p-0')
    
    <div x-data="detailKelas()">
    <div class="flex flex-col gap-8 w-full flex-1 mx-auto">

        <div class="flex flex-col gap-4">
            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-xl flex items-center gap-3">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-xl">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-red-600 dark:text-red-400">error</span>
                        <div class="flex-1">
                            @foreach($errors->all() as $error)
                                <p class="font-medium">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div
                        class="w-16 h-16 rounded-2xl bg-maroon flex items-center justify-center text-white shadow-lg shadow-maroon/10 shrink-0">
                        <span class="material-symbols-outlined text-4xl">menu_book</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h1 class="text-3xl font-black text-[#111218] dark:text-white tracking-tight">
                                {{ $class_info['name'] }}</h1>
                            <span
                                class="px-2.5 py-0.5 rounded text-xs font-bold bg-green-50 text-green-700 border border-green-100 uppercase tracking-wide">
                                Aktif
                            </span>
                        </div>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-500 dark:text-slate-400">
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px]">class</span>
                                <span class="font-medium text-gray-700 dark:text-slate-300">Kelas
                                    {{ $class_info['section'] }}</span>
                            </div>
                            <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-slate-600"></span>
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px]">qr_code_2</span>
                                <span>{{ $class_info['code'] }}</span>
                            </div>
                            <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-slate-600"></span>
                            <div>{{ $class_info['sks'] }} SKS</div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:flex gap-3 w-full md:w-auto">
                    <a href="{{ route('dosen.kelas.input-nilai', $id) }}"
                        class="flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-300 rounded-xl font-bold text-sm hover:bg-gray-50 dark:hover:bg-slate-700 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">edit_note</span>
                        Input Nilai Akhir
                    </a>

                    <a href="{{ route('dosen.nilai-tugas.index', $id) }}"
                        class="flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 rounded-xl font-bold text-sm hover:bg-red-50 dark:hover:bg-red-900/30 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">assignment_turned_in</span>
                        Nilai Tugas
                    </a>

                    {{-- Button Silabus --}}
                    @if($kelas->silabus)
                        <button @click="openPreviewModal('{{ route('dosen.kelas.dokumen.download', ['id' => $id, 'tipe' => 'silabus']) }}?view=1&t={{ time() }}', 'Silabus', 'silabus')"
                            class="flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-green-200 dark:border-green-700 text-green-600 dark:text-green-400 rounded-xl font-bold text-sm hover:bg-green-50 dark:hover:bg-green-900/30 transition-all shadow-sm">
                            <span class="material-symbols-outlined text-[20px]">description</span>
                            Silabus
                        </button>
                    @else
                        <button @click="openUploadModal('silabus')"
                            class="flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-300 rounded-xl font-bold text-sm hover:bg-gray-50 dark:hover:bg-slate-700 transition-all shadow-sm">
                            <span class="material-symbols-outlined text-[20px]">upload_file</span>
                            Upload Silabus
                        </button>
                    @endif

                    {{-- Button RPS --}}
                    @if($kelas->rps)
                        <button @click="openPreviewModal('{{ route('dosen.kelas.dokumen.download', ['id' => $id, 'tipe' => 'rps']) }}?view=1&t={{ time() }}', 'RPS', 'rps')"
                            class="flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-purple-200 dark:border-purple-700 text-purple-600 dark:text-purple-400 rounded-xl font-bold text-sm hover:bg-purple-50 dark:hover:bg-purple-900/30 transition-all shadow-sm">
                            <span class="material-symbols-outlined text-[20px]">article</span>
                            RPS
                        </button>
                    @else
                        <button @click="openUploadModal('rps')"
                            class="flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-300 rounded-xl font-bold text-sm hover:bg-gray-50 dark:hover:bg-slate-700 transition-all shadow-sm">
                            <span class="material-symbols-outlined text-[20px]">upload_file</span>
                            Upload RPS
                        </button>
                    @endif

                    <a href="{{ route('dosen.kelas.export-berita-acara', $id) }}"
                        class="flex items-center justify-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all">
                        <span class="material-symbols-outlined text-[20px]">download</span>
                        Export Data
                    </a>
                </div>
            </div>
        </div>

        {{-- OVERVIEW STATISTICS --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Time -->
            <div
                class="bg-white dark:bg-[#1a1d2e] p-5 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm flex items-center gap-4 hover:shadow-md transition-all duration-300">
                <div
                    class="size-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                    <span class="material-symbols-outlined">schedule</span>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-0.5">Waktu
                    </p>
                    <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $class_info['day'] }},
                        {{ $class_info['time'] }}</p>
                </div>
            </div>

            <!-- Room -->
            <div
                class="bg-white dark:bg-[#1a1d2e] p-5 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm flex items-center gap-4 hover:shadow-md transition-all duration-300">
                <div
                    class="size-12 rounded-xl bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 flex items-center justify-center">
                    <span class="material-symbols-outlined">location_on</span>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-0.5">
                        Ruangan</p>
                    <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $class_info['room'] }}</p>
                </div>
            </div>

            <!-- Total Students -->
            <div
                class="bg-white dark:bg-[#1a1d2e] p-5 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm flex items-center gap-4 hover:shadow-md transition-all duration-300">
                <div
                    class="size-12 rounded-xl bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                    <span class="material-symbols-outlined">group</span>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-0.5">
                        Mahasiswa</p>
                    <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $class_info['students_count'] }} Terdaftar
                    </p>
                </div>
            </div>

            <!-- Progress -->
            <div
                class="bg-white dark:bg-[#1a1d2e] p-5 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm flex items-center gap-4 hover:shadow-md transition-all duration-300">
                <div
                    class="size-12 rounded-xl bg-pink-50 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400 flex items-center justify-center">
                    <span class="material-symbols-outlined">timeline</span>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-0.5">
                        Pertemuan</p>
                    <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $class_info['progress'] }} / {{ $class_info['total_pertemuan'] }}
                        Selesai</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- LEFT: MEETING TIMELINE --}}
            <div class="col-span-12 lg:col-span-3 space-y-4">
                <h3 class="font-bold text-gray-800 dark:text-white text-lg flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">event_note</span>
                    Daftar Pertemuan
                </h3>

                <div class="bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-100 dark:border-slate-800 p-2 shadow-sm">
                    <div class="flex flex-col gap-1 min-h-[400px]">
                        @foreach($meetings as $m)
                            @php
                                $isExam = $m['is_exam'] ?? false;
                                $examColorClass = match($m['tipe'] ?? 'kuliah') {
                                    'uts' => 'border-l-amber-500',
                                    'uas' => 'border-l-red-500',
                                    default => '',
                                };
                                $examBadgeClass = match($m['tipe'] ?? 'kuliah') {
                                    'uts' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'uas' => 'bg-red-50 text-red-700 border-red-200',
                                    default => '',
                                };
                                $examIcon = match($m['tipe'] ?? 'kuliah') {
                                    'uts' => 'edit_note',
                                    'uas' => 'assignment',
                                    default => 'school',
                                };
                            @endphp
                            <div x-data="{ open: false }" class="group"
                                x-show="{{ $loop->index }} >= (meetingPage - 1) * perPage && {{ $loop->index }} < meetingPage * perPage"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100">
                                <button @click="open = !open"
                                    class="w-full flex items-start gap-4 p-3 rounded-xl transition-colors text-left relative overflow-hidden {{ $isExam ? 'border-l-2 ' . $examColorClass : '' }}"
                                    :class="open ? 'bg-gray-50 dark:bg-slate-800' : 'hover:bg-gray-50 dark:hover:bg-slate-800'">

                                    {{-- Status Indicator Line --}}
                                    @unless($isExam)
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gray-200 dark:bg-slate-700"
                                        :class="open ? 'bg-primary' : 'bg-gray-200 dark:bg-slate-700'"></div>
                                    @endunless

                                    <div class="pl-2 flex-1">
                                        <div class="flex justify-between items-start gap-2">
                                            <span class="text-xs font-bold uppercase tracking-wider {{ $isExam ? ($m['tipe'] === 'uts' ? 'text-amber-600' : 'text-red-600') : 'text-gray-500 dark:text-slate-400' }}">
                                                @if($isExam)
                                                    <span class="material-symbols-outlined text-[14px] align-middle mr-0.5">{{ $examIcon }}</span>
                                                @endif
                                                {{ $m['label'] }}
                                            </span>
                                            @if($isExam)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold border {{ $examBadgeClass }}">
                                                    {{ strtoupper($m['tipe']) }}
                                                </span>
                                            @endif
                                        </div>
                                        <h4 class="font-bold text-gray-800 dark:text-white text-sm mt-0.5">{{ $m['date'] }}</h4>

                                        <div x-show="!open" class="text-xs text-gray-500 dark:text-slate-400 mt-1 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">schedule</span>
                                            <span class="font-semibold text-gray-700 dark:text-slate-300">{{ $m['time'] }}</span>
                                        </div>
                                    </div>

                                    <span
                                        class="material-symbols-outlined text-gray-300 text-[20px] transition-transform duration-300"
                                        :class="open ? 'rotate-180 text-primary' : ''">expand_more</span>
                                </button>

                                <div x-show="open" class="pl-5 pr-3 pb-3">
                                    <div class="bg-gray-50 dark:bg-slate-800 rounded-lg p-3 border border-gray-100 dark:border-slate-700 mt-1">
                                        <div class="flex justify-between items-center text-xs mb-3">
                                            <div class="text-gray-500 dark:text-slate-400">Waktu</div>
                                            <div class="font-bold text-gray-800 dark:text-white">{{ $m['time'] }}</div>
                                        </div>

                                        <div class="grid grid-cols-1 gap-2">
                                            <a href="{{ route('dosen.kelas.pertemuan.detail', ['id' => $id, 'pertemuan' => $m['route_param'] ?? $m['no']]) }}"
                                                class="flex items-center justify-center gap-1.5 py-2 {{ $isExam ? ($m['tipe'] === 'uts' ? 'bg-amber-600 hover:bg-amber-700' : 'bg-red-600 hover:bg-red-700') : 'bg-primary hover:bg-primary-hover' }} text-white rounded-lg text-xs font-bold transition-all shadow-sm">
                                                <span class="material-symbols-outlined text-[16px]">{{ $isExam ? $examIcon : 'visibility' }}</span>
                                                {{ $isExam ? 'Lihat ' . strtoupper($m['tipe']) : 'Lihat Rincian' }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination Controls --}}
                    <div class="flex justify-between items-center px-2 py-3 border-t border-gray-100 dark:border-slate-800 mt-2"
                        x-show="totalMeetings > perPage">
                        <button @click="prevPage" :disabled="meetingPage === 1"
                            class="size-8 flex items-center justify-center rounded-lg border border-gray-200 dark:border-slate-700 text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                            <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                        </button>

                        <span class="text-xs font-bold text-gray-400 dark:text-slate-500">
                            Page <span class="text-gray-800 dark:text-white" x-text="meetingPage"></span>
                        </span>

                        <button @click="nextPage" :disabled="meetingPage === totalPages"
                            class="size-8 flex items-center justify-center rounded-lg border border-gray-200 dark:border-slate-700 text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                            <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- RIGHT: STUDENTS TABLE --}}
            <div class="col-span-12 lg:col-span-9 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <h3 class="font-bold text-gray-800 dark:text-white text-lg flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">school</span>
                        Daftar Mahasiswa
                    </h3>
                    <div class="text-sm text-gray-500 dark:text-slate-400">
                        Total <span class="font-bold text-gray-800 dark:text-white">{{ count($students) }}</span> mahasiswa terdaftar
                    </div>
                </div>

                {{-- FILTERS --}}
                <div class="bg-white dark:bg-[#1a1d2e] p-4 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm flex flex-col md:flex-row gap-4">
                    <div class="relative flex-1">
                        <span
                            class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500 material-symbols-outlined text-[20px]">search</span>
                        <input type="text" x-model="searchQuery" placeholder="     Cari nama mahasiswa atau NIM..."
                            class="w-full pl-11 pr-4 py-2.5 border border-gray-200 dark:border-slate-700 rounded-xl bg-gray-50/50 dark:bg-slate-800 focus:bg-white dark:focus:bg-slate-700 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-slate-500">
                    </div>
                    <div class="flex gap-3">
                        <div class="relative w-full md:w-48">
                            <select x-model="filterProdi"
                                class="w-full appearance-none pl-4 pr-10 py-2.5 border border-gray-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm text-gray-700 dark:text-slate-300 cursor-pointer font-medium">
                                <option value="">Semua Prodi</option>
                                <option value="Informatika">Informatika</option>
                                <option value="Sistem Informasi">Sistem Informasi</option>
                            </select>
                            <span
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500 pointer-events-none material-symbols-outlined text-[20px]">expand_more</span>
                        </div>
                        <div class="relative w-full md:w-48">
                            <select x-model="filterStatus"
                                class="w-full appearance-none pl-4 pr-10 py-2.5 border border-gray-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm text-gray-700 dark:text-slate-300 cursor-pointer font-medium">
                                <option value="">Semua Status</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Cuti">Cuti</option>
                            </select>
                            <span
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none material-symbols-outlined text-[20px]">expand_more</span>
                        </div>
                    </div>
                </div>

                {{-- TABLE --}}
                <div class="bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                    <div class="overflow-x-auto -mx-6 px-6">
                        <table class="w-full text-left border-collapse" style="min-width: 700px;">
                            <thead>
                                <tr
                                    class="bg-gray-50/80 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-800 text-xs uppercase tracking-wider text-gray-500 dark:text-slate-400 font-bold">
                                    <th class="px-6 py-4">Mahasiswa</th>
                                    <th class="px-6 py-4">Prodi</th>
                                    <th class="px-6 py-4 text-center">Semester</th>
                                    <th class="px-6 py-4 text-center">IPK</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                                @if(count($students) > 0)
                                    @foreach($students as $student)
                                        <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-800/50 transition-colors group"
                                            x-show="filterStudent('{{ $student['name'] }}', '{{ $student['nim'] }}', '{{ $student['prodi'] }}', '{{ $student['status'] }}')">

                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-4">
                                                    <div
                                                        class="size-10 rounded-full bg-maroon flex items-center justify-center text-white text-xs font-bold shadow-sm shadow-maroon/20">
                                                        {{ strtoupper(substr($student['name'], 0, 2)) }}
                                                    </div>
                                                    <div>
                                                    <p class="text-gray-900 dark:text-white font-bold text-sm">{{ $student['name'] }}</p>
                                                        <p class="text-gray-500 dark:text-slate-400 text-xs font-medium font-mono">{{ $student['nim'] }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 text-gray-600 dark:text-slate-300 font-medium text-sm">{{ $student['prodi'] }}</td>

                                            <td class="px-6 py-4 text-center">
                                                <span
                                                    class="inline-flex items-center justify-center size-6 rounded bg-gray-100 dark:bg-slate-700 text-xs font-bold text-gray-600 dark:text-slate-300">{{ $student['semester'] }}</span>
                                            </td>

                                            <td class="px-6 py-4 text-center">
                                                <span
                                                    class="text-gray-900 dark:text-white font-bold">{{ is_numeric($student['ipk']) ? number_format((float) $student['ipk'], 2) : '-' }}</span>
                                            </td>

                                            <td class="px-6 py-4">
                                                @php
                                                    $statusClass = match ($student['status']) {
                                                        'Aktif' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                        'Cuti' => 'bg-amber-50 text-amber-700 border-amber-100',
                                                        'Non-Aktif' => 'bg-rose-50 text-rose-700 border-rose-100',
                                                        default => 'bg-gray-50 text-gray-700 border-gray-200'
                                                    };
                                                @endphp
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $statusClass }}">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-1.5 opacity-60"></span>
                                                    {{ $student['status'] }}
                                                </span>
                                            </td>

                                            <td class="px-6 py-4 text-right">
                                                <div
                                                    class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-all duration-200">
                                                    <button
                                                        class="size-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-primary hover:bg-red-50 transition-colors"
                                                        title="Chat">
                                                        <span class="material-symbols-outlined text-[18px]">chat_bubble</span>
                                                    </button>
                                                    <button
                                                        class="size-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors"
                                                        title="Detail">
                                                        <span class="material-symbols-outlined text-[18px]">more_vert</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <span
                                                    class="material-symbols-outlined text-4xl text-gray-300 mb-2">supervisor_account</span>
                                                <p class="text-gray-500 font-medium">Belum ada mahasiswa</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-800/30 flex items-center justify-between">
                        <p class="text-xs text-gray-500 dark:text-slate-400">
                            Menampilkan <span class="font-bold text-gray-800 dark:text-white" x-text="filteredCount"></span> mahasiswa
                        </p>
                        <div class="flex gap-1">
                            <button
                                class="px-3 py-1 rounded border border-gray-200 dark:border-slate-700 text-xs font-bold text-gray-500 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-700 hover:text-gray-800 dark:hover:text-white disabled:opacity-50"
                                disabled>Prev</button>
                            <button
                                class="px-3 py-1 rounded border border-gray-200 dark:border-slate-700 text-xs font-bold text-gray-500 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-700 hover:text-gray-800 dark:hover:text-white disabled:opacity-50"
                                disabled>Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Upload Modal - Moved outside main container to prevent sidebar showing --}}
    <div x-show="showUploadModal" 
         x-cloak
         class="fixed inset-0 z-[9999] overflow-y-auto"
         style="margin: 0 !important;"
         @keydown.escape.window="closeUploadModal()">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"
             @click="closeUploadModal()"></div>

        <!-- Modal -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6 z-[10000]"
                 @click.stop
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100">
                
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white capitalize">
                        Upload <span x-text="uploadType"></span>
                    </h3>
                    <button @click="closeUploadModal()"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <!-- Form -->
                <form action="{{ route('dosen.kelas.dokumen.upload', $id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="tipe_dokumen" :value="uploadType">

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Pilih File (PDF/DOC - Max 10MB)
                        </label>
                        <input type="file" 
                               name="file" 
                               accept=".pdf,.doc,.docx"
                               required
                               onchange="
                                   if (this.files.length > 0) {
                                       const ext = this.files[0].name.split('.').pop().toLowerCase();
                                       if (!['pdf', 'doc', 'docx'].includes(ext)) {
                                           Swal.fire({
                                               icon: 'error',
                                               title: 'Format File Tidak Didukung',
                                               text: 'Hanya file dengan format PDF, DOC, dan DOCX yang diperbolehkan.',
                                               confirmButtonColor: '#8B1538',
                                               didOpen: () => {
                                                   const container = Swal.getContainer();
                                                   if (container) {
                                                       container.style.zIndex = '11000';
                                                   }
                                               }
                                           });
                                           this.value = '';
                                       }
                                   }
                               "
                               class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 dark:bg-slate-700 dark:text-white">
                        <p class="mt-2 text-xs text-gray-500 dark:text-slate-400">
                            Format yang didukung: PDF, DOC, DOCX (Maksimal 10MB)
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" 
                                @click="closeUploadModal()"
                                class="flex-1 px-4 py-2.5 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-gray-300 rounded-xl font-bold hover:bg-gray-50 dark:hover:bg-slate-700 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-primary text-white rounded-xl font-bold hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all">
                            Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Preview Modal --}}
    <div x-show="showPreviewModal" 
         x-cloak
         class="fixed inset-0 z-[9999] overflow-hidden flex items-center justify-center p-4 px-4 md:px-8"
         style="margin: 0 !important;"
         @keydown.escape.window="closePreviewModal()">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity"
             @click="closePreviewModal()"></div>

        <!-- Modal -->
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-[98%] h-[95vh] flex flex-col z-[10000]"
             @click.stop
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between p-4 gap-4 border-b border-gray-100 dark:border-slate-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">visibility</span>
                    Preview<span x-text="previewTitle"></span>
                </h3>
                <div class="flex flex-nowrap items-center gap-1.5 md:gap-2 overflow-x-auto no-scrollbar">
                    <button @click="let t = previewType; closePreviewModal(); openUploadModal(t)"
                            class="text-[10px] sm:text-xs md:text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-2 py-1.5 md:px-3 rounded-lg bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors flex-shrink-0 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px] sm:text-[16px] md:text-[18px]">upload_file</span>
                        <span>Ganti File</span>
                    </button>
                    <a :href="previewUrl ? previewUrl.replace('?view=1', '') : '#'" 
                       target="_blank"
                       class="text-[10px] sm:text-xs md:text-sm font-bold text-primary hover:text-primary-hover px-2 py-1.5 md:px-3 rounded-lg bg-primary/10 hover:bg-primary/20 transition-colors flex-shrink-0 flex items-center gap-1">
                       <span class="material-symbols-outlined text-[14px] sm:text-[16px] md:text-[18px]">open_in_new</span>
                        <span>Download</span>
                    </a>
                    <button @click="closePreviewModal()"
                            class="p-1 md:p-1.5 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors flex-shrink-0">
                        <span class="material-symbols-outlined text-[18px] md:text-[24px]">close</span>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="flex-1 bg-gray-50 dark:bg-slate-900 relative rounded-b-2xl overflow-hidden p-0">
                <template x-if="showPreviewModal">
                    <iframe :src="previewUrl" class="w-full h-full border-0" allowfullscreen></iframe>
                </template>
                <div x-show="!previewUrl" class="absolute inset-0 flex items-center justify-center text-gray-500">
                    Loading...
                </div>
            </div>
        </div>
    </div>
    </div>

    @push('scripts')
        <script>
            function detailKelas() {
                return {
                    searchQuery: '',
                    filterProdi: '',
                    filterStatus: '',
                    filteredCount: {{ count($students) }},

                    // Meeting Pagination
                    meetingPage: 1,
                    perPage: 5,
                    totalMeetings: {{ count($meetings) }},

                    get totalPages() {
                        return Math.ceil(this.totalMeetings / this.perPage);
                    },

                    nextPage() {
                        if (this.meetingPage < this.totalPages) {
                            this.meetingPage++;
                        }
                    },

                    prevPage() {
                        if (this.meetingPage > 1) {
                            this.meetingPage--;
                        }
                    },

                    filterStudent(name, nim, prodi, status) {
                        const query = this.searchQuery.toLowerCase();
                        const matches = (
                            (name.toLowerCase().includes(query) || nim.toLowerCase().includes(query)) &&
                            (this.filterProdi === '' || prodi === this.filterProdi) &&
                            (this.filterStatus === '' || status === this.filterStatus)
                        );

                        // Update count on next tick to wait for DOM update
                        this.$nextTick(() => {
                            this.updateCount();
                        });

                        return matches;
                    },

                    updateCount() {
                        const visibleRows = document.querySelectorAll('tbody tr:not([style*="display: none"])').length;
                        this.filteredCount = visibleRows;
                    },

                    // Upload Modal
                    showUploadModal: false,
                    uploadType: '',

                    openUploadModal(type) {
                        this.uploadType = type;
                        this.showUploadModal = true;
                    },

                    closeUploadModal() {
                        this.showUploadModal = false;
                        this.uploadType = '';
                    },

                    // Preview Modal
                    showPreviewModal: false,
                    previewUrl: '',
                    previewTitle: '',
                    previewType: '',

                    openPreviewModal(url, title, type) {
                        this.previewUrl = url;
                        this.previewTitle = title;
                        this.previewType = type;
                        this.showPreviewModal = true;
                    },

                    closePreviewModal() {
                        this.showPreviewModal = false;
                        this.previewUrl = '';
                        this.previewTitle = '';
                        this.previewType = '';
                    }
                }
            }
        </script>
    @endpush
@endsection