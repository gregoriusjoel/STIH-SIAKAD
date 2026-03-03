@extends('layouts.app')

@section('title', 'Detail Pertemuan')

@section('content')
    @section('navbar_breadcrumb')
        <nav class="flex items-center gap-3 text-sm text-white/60 font-bold tracking-tight">
            <a class="hover:text-white transition-all duration-300 flex items-center group" href="{{ route('dosen.dashboard') }}">
                <span class="material-symbols-outlined text-[19px] group-hover:scale-110 opacity-80">home</span>
            </a>
            <span class="material-symbols-outlined text-[10px] text-white/20 font-normal">play_arrow</span>
            <a href="{{ route('dosen.kelas') }}" class="hover:text-white transition-all duration-300">Kelas</a>
            <span class="material-symbols-outlined text-[10px] text-white/20 font-normal">play_arrow</span>
            <a href="{{ route('dosen.kelas.detail', $kelas->id) }}" class="hover:text-white transition-all duration-300">Detail
                Kelas</a>
            <span class="material-symbols-outlined text-[10px] text-white/20 font-normal">play_arrow</span>
            <span class="text-white font-black text-[13px] uppercase tracking-wider">
                {{ $meeting['label'] }}
                @if(($meeting['tipe_pertemuan'] ?? 'kuliah') !== 'kuliah')
                    <span class="ml-1 px-1.5 py-0.5 rounded text-[10px] {{ ($meeting['tipe_pertemuan'] ?? '') === 'uts' ? 'bg-amber-500/20 text-amber-200' : 'bg-red-500/20 text-red-200' }}">
                        {{ strtoupper($meeting['tipe_pertemuan'] ?? '') }}
                    </span>
                @endif
            </span>
        </nav>
    @endsection

    <div class="px-4 py-6 max-w-[1600px] mx-auto">

        {{-- HEADER --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    @php
                        $headerIcon = match($meeting['tipe_pertemuan'] ?? 'kuliah') {
                            'uts' => 'edit_note',
                            'uas' => 'assignment',
                            default => 'event_note',
                        };
                        $headerBg = match($meeting['tipe_pertemuan'] ?? 'kuliah') {
                            'uts' => 'bg-amber-50 text-amber-600',
                            'uas' => 'bg-red-50 text-red-600',
                            default => 'bg-blue-50 text-blue-600',
                        };
                    @endphp
                    <div class="size-14 rounded-2xl {{ $headerBg }} flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-3xl">{{ $headerIcon }}</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-3">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $meeting['label'] }}</h1>
                            @if(($meeting['tipe_pertemuan'] ?? 'kuliah') !== 'kuliah')
                                @php
                                    $tipeBadge = match($meeting['tipe_pertemuan']) {
                                        'uts' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'uas' => 'bg-red-100 text-red-700 border-red-200',
                                        default => 'bg-blue-100 text-blue-700 border-blue-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold border {{ $tipeBadge }}">
                                    <span class="material-symbols-outlined text-[14px]">{{ $headerIcon }}</span>
                                    {{ strtoupper($meeting['tipe_pertemuan']) }}
                                </span>
                            @endif
                        </div>
                        <p class="text-gray-500">{{ $kelas->mataKuliah->nama_mk }} - Kelas {{ $kelas->section }}</p>
                    </div>
                </div>

                {{-- Meeting Type Dropdown Navigator --}}
                @if(isset($meetingSlots) && $meetingSlots->count())
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.away="open = false"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">swap_horiz</span>
                        Pindah Pertemuan
                        <span class="material-symbols-outlined text-[16px] transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-72 bg-white border border-gray-200 rounded-xl shadow-xl z-50 py-2 max-h-80 overflow-y-auto">
                        @foreach($meetingSlots as $slot)
                            @php
                                $isCurrentSlot = ($slot['tipe'] === ($meeting['tipe_pertemuan'] ?? 'kuliah')) && ($slot['nomor'] === ($meeting['nomor_pertemuan'] ?? 1));
                                $slotIcon = match($slot['tipe']) {
                                    'uts' => 'edit_note',
                                    'uas' => 'assignment',
                                    default => 'school',
                                };
                                $slotColor = match($slot['tipe']) {
                                    'uts' => 'text-amber-600',
                                    'uas' => 'text-red-600',
                                    default => 'text-gray-600',
                                };
                                $slotBgActive = match($slot['tipe']) {
                                    'uts' => 'bg-amber-50 border-l-amber-500',
                                    'uas' => 'bg-red-50 border-l-red-500',
                                    default => 'bg-blue-50 border-l-blue-500',
                                };
                            @endphp
                            <a href="{{ route('dosen.kelas.pertemuan.detail', ['id' => $id, 'pertemuan' => $slot['tipe'] . ':' . $slot['nomor']]) }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm transition-all border-l-2 {{ $isCurrentSlot ? $slotBgActive . ' font-bold' : 'border-l-transparent hover:bg-gray-50' }}">
                                <span class="material-symbols-outlined text-[16px] {{ $slotColor }}">{{ $slotIcon }}</span>
                                <span class="{{ $isCurrentSlot ? 'text-gray-900' : 'text-gray-700' }}">{{ $slot['label'] }}</span>
                                @if($isCurrentSlot)
                                    <span class="material-symbols-outlined text-[14px] text-green-500 ml-auto">check_circle</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6 pt-6 border-t border-gray-100">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Tanggal</p>
                    <p class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-gray-400 text-[18px]">calendar_today</span>
                        {{ $meeting['date'] }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Waktu</p>
                    <p class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-gray-400 text-[18px]">schedule</span>
                        {{ $meeting['time'] }} (WIB)
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Ruangan</p>
                    <p class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-gray-400 text-[18px]">location_on</span>
                        {{ $meeting['room'] }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Materi / Topik</p>
                    @php
                        $headerTopic = '-';
                        if(isset($materis) && $materis->count()) {
                            $headerTopic = $materis->first()->judul;
                        } elseif(!empty($meeting['topic'])) {
                            $headerTopic = $meeting['topic'];
                        } elseif(!empty($meeting['title'])) {
                            $headerTopic = $meeting['title'];
                        }
                    @endphp
                    <p class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-gray-400 text-[18px]">topic</span>
                        {{ $headerTopic }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Left: Attendance List --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 text-lg">Daftar Kehadiran</h3>
                    @php
                        $totalStudents = count($students);
                        $attendedCount = collect($students)->where('attendance_status', 'hadir')->count();
                    @endphp
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full bg-green-100 text-xs font-bold text-green-700">
                            Hadir: <span id="attendance-present-count">{{ $attendedCount }}</span>
                        </span>
                        <span class="px-3 py-1 rounded-full bg-gray-100 text-xs font-bold text-gray-600">
                            Total: <span id="attendance-total-count">{{ $totalStudents }}</span>
                        </span>
                    </div>
                </div>

                {{-- Metode Pengajaran Bar --}}
                @php
                    $metodePT = $pertemuanRecord?->metode_pengajaran ?? 'offline';
                    $metodeLabels = ['offline' => 'Offline / Tatap Muka', 'online' => 'Online / Daring', 'asynchronous' => 'Asynchronous'];
                    $metodeColorMap = ['offline' => 'bg-blue-50 text-blue-700 border-blue-200', 'online' => 'bg-green-50 text-green-700 border-green-200', 'asynchronous' => 'bg-orange-50 text-orange-700 border-orange-200'];
                    $metodeIconMap = ['offline' => 'location_on', 'online' => 'video_call', 'asynchronous' => 'schedule'];
                @endphp
                <div class="px-6 py-3 border-b border-gray-100 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Metode:</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $metodeColorMap[$metodePT] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                            <span class="material-symbols-outlined text-[14px]">{{ $metodeIconMap[$metodePT] ?? 'school' }}</span>
                            {{ $metodeLabels[$metodePT] ?? ucfirst($metodePT) }}
                        </span>
                        @if($metodePT === 'online' && !empty($pertemuanRecord?->online_meeting_link))
                            <a href="{{ $pertemuanRecord->online_meeting_link }}" target="_blank" 
                               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 transition-colors">
                                <span class="material-symbols-outlined text-[14px]">videocam</span>
                                Join Meeting
                            </a>
                        @endif
                    </div>
                    <button type="button" x-data @click="$dispatch('open-reschedule-metode')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl text-xs font-bold hover:bg-gray-50 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">edit_calendar</span>
                        Reschedule Metode
                    </button>
                </div>

                {{-- QR Section (Moved from Absensi) --}}
                <div class="px-6 py-6 border-b border-gray-100 bg-gray-50/30" 
                    x-data="{ 
                        isLoaded: {{ (isset($token) && $token && $qrEnabled) ? 'true' : 'false' }},
                        activating: false,
                        showQrModal: false,
                        showQr(formId) {
                            this.isLoaded = false;
                            this.activating = true;
                            setTimeout(() => {
                                document.getElementById(formId).submit();
                            }, 1000);
                        }
                    }">
                    
                    {{-- QR Zoom Modal --}}
                    <div x-show="showQrModal" 
                         style="display: none;"
                         class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">
                        
                        {{-- Backdrop --}}
                        <div class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm" @click="showQrModal = false"></div>
                        
                        {{-- Modal Content --}}
                        <div class="relative bg-white p-4 rounded-2xl shadow-2xl transform transition-all max-w-sm w-full top-0"
                             @click.stop
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                            
                            <button @click="showQrModal = false" class="absolute -top-12 right-0 text-white hover:text-gray-200 transition-colors">
                                <span class="material-symbols-outlined text-4xl">close</span>
                            </button>

                            <div class="bg-white rounded-xl overflow-hidden">
                                @if(isset($token) && $token)
                                    <img src="{{ route('qrcode.kelas.image', $token) }}" alt="QR Besar" class="w-full h-auto aspect-square object-contain" />
                                @endif
                            </div>
                            
                            <div class="mt-4 text-center">
                                <p class="text-sm font-bold text-gray-800">Scan QR Code</p>
                                <p class="text-xs text-gray-500 mt-1">Gunakan aplikasi untuk melakukan absensi</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        {{-- Left: QR Code --}}
                        <div class="flex flex-col items-center justify-center text-center border-r-0 md:border-r border-gray-100 pr-0 md:pr-8">
                            @if(isset($token) && $token && $qrEnabled)
                                <div class="mb-4">
                                    <h4 class="text-sm font-bold text-primary mb-1 flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-lg">qr_code_2</span>
                                        QR Absensi Aktif
                                    </h4>
                                    @if($qrExpires)
                                        <div class="text-xs text-red-600 font-bold bg-red-50 px-3 py-1 rounded-full inline-flex items-center gap-1 mt-1">
                                            <span class="material-symbols-outlined text-[14px]">timer</span>
                                            Berakhir: <span id="qrCountdown"></span>
                                        </div>
                                    @endif
                                </div>

                                <div class="bg-white p-2 rounded-xl border border-gray-200 mb-4 relative group w-max mx-auto cursor-pointer"
                                     @click="showQrModal = true">
                                    <div x-show="isLoaded && !activating" x-transition.opacity class="relative z-10">
                                        <img id="generatedQr" src="{{ route('qrcode.kelas.image', $token) }}" 
                                            alt="QR Kelas" class="w-32 h-32 transition-transform duration-300 group-hover:scale-105 rounded-lg origin-bottom"
                                            @load="isLoaded = true" />
                                    </div>
                                    <div x-show="!isLoaded || activating" 
                                        class="w-32 h-32 bg-gray-100 flex items-center justify-center rounded text-gray-400 absolute inset-2">
                                        <span class="material-symbols-outlined text-4xl animate-pulse">qr_code_scanner</span>
                                    </div>
                                    
                                    {{-- Simple tooltip instruction --}}
                                    <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-[10px] text-gray-500 font-medium whitespace-nowrap flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[10px]">zoom_in</span>
                                        Klik untuk memperbesar
                                    </div>
                                </div>

                                <div class="flex gap-2 w-full max-w-[200px] mt-8">
                                    <form action="{{ route('dosen.kelas.deactivate_qr', ['id' => $id]) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full py-2 bg-white border border-red-200 text-red-600 hover:bg-red-50 rounded-lg text-sm font-bold transition-colors">
                                            Matikan QR
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="flex flex-col items-center">
                                    @if(isset($metodePT) && $metodePT === 'asynchronous')
                                        <div class="w-16 h-16 bg-orange-50 rounded-full flex items-center justify-center text-orange-500 mb-3">
                                            <span class="material-symbols-outlined text-4xl">schedule</span>
                                        </div>
                                        <h4 class="text-sm font-bold text-orange-700 mb-1">Pertemuan Asynchronous</h4>
                                        <p class="text-xs text-orange-600/80 font-medium text-center max-w-[240px]">
                                            QR Code tidak tersedia. Mahasiswa melakukan absensi melalui penyelesaian tugas atau materi.
                                        </p>
                                    @else
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-3">
                                            <span class="material-symbols-outlined text-3xl">qr_code_2</span>
                                        </div>
                                        <h4 class="text-sm font-bold text-gray-700 mb-1">QR Absensi Belum Aktif</h4>
                                        <p class="text-xs text-gray-500 mb-4 max-w-[250px]">Aktifkan QR Code agar mahasiswa dapat melakukan absensi mandiri via aplikasi.</p>
                                        
                                        <button type="button" x-data @click="$dispatch('open-qr-password-modal')"
                                            class="px-5 py-2.5 bg-maroon text-white rounded-xl text-sm font-bold hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[18px]">lock_open</span>
                                            Aktifkan QR Absensi
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Right: Link Absensi --}}
                        <div class="flex flex-col justify-center h-full">
                            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-3 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">link</span>
                                    Link Absensi Alternatif
                                </label>
                                <div class="flex flex-col gap-3">
                                    <div class="relative">
                                        <input id="absensiLink" type="text" readonly 
                                            value="{{ (isset($token) && $token) ? route('absensi.form', ['token' => $token]) : 'Link belum tersedia' }}" 
                                            class="w-full text-sm px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-600 focus:outline-none focus:ring-2 focus:ring-primary/20 pr-12 font-medium" />
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                            @if(isset($token) && $token && $qrEnabled)
                                                <span class="flex h-2 w-2 relative">
                                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                                </span>
                                            @else
                                                 <span class="h-2 w-2 rounded-full bg-red-500 block"></span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <button id="copyBtn" type="button" 
                                        class="w-full px-4 py-2.5 bg-[#8B1538] hover:bg-[#7A1231] text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-red-900/10 flex items-center justify-center gap-2 active:scale-95">
                                        <span class="material-symbols-outlined text-[18px]">content_copy</span>
                                        <span>Salin Link Absensi</span>
                                    </button>
                                </div>
                                <div class="mt-4 flex items-start gap-2 bg-blue-50/50 p-3 rounded-lg border border-blue-100">
                                    <span class="material-symbols-outlined text-blue-500 text-lg shrink-0 mt-0.5">info</span>
                                    <p class="text-xs text-gray-500 leading-relaxed">
                                        Gunakan link ini jika mahasiswa mengalami kendala saat melakukan scan QR Code. Link ini akan otomatis kadaluarsa bersamaan dengan QR Code.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto -mx-6 px-6">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 text-[11px] uppercase tracking-wider text-gray-500 font-bold">
                                <th class="px-4 py-3 w-12">No</th>
                                <th class="px-4 py-3">Mahasiswa</th>
                                <th class="px-4 py-3">NIM</th>
                                <th class="px-4 py-3 text-center">Status Kehadiran</th>
                                <th class="px-4 py-3 text-center">Waktu Scan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100" id="attendance-table-body">
                            @include('page.dosen.kelas.partials.student_attendance_table', ['students' => $students])
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Right: Materi & Tugas --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col h-full">
                <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white sticky top-0 z-10">
                    <h3 class="font-bold text-gray-800 text-lg">Materi & Tugas</h3>
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                         <button x-data x-on:click="$dispatch('open-upload-materi')"
                            class="flex-1 sm:flex-none justify-center px-3 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">upload_file</span>
                            Materi
                        </button>
                        <button x-data x-on:click="$dispatch('open-create-tugas')"
                            class="flex-1 sm:flex-none justify-center px-3 py-2 bg-primary text-white hover:bg-primary-hover rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                            <span class="material-symbols-outlined text-[16px]">add_task</span>
                            Buat Tugas
                        </button>
                    </div>
                </div>

                <div class="p-6 overflow-y-auto custom-scrollbar">
                    {{-- Materi Section --}}
                    <div class="mb-8">
                        <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-500">folder_open</span>
                            Materi Pembelajaran
                        </h4>
                        
                        @if(isset($materis) && $materis->count())
                            <div class="space-y-3">
                                @foreach($materis as $materi)
                                    <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/30 hover:bg-gray-50 transition-colors group">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex items-start gap-3">
                                                <div class="size-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                                    @php
                                                        $iconMap = ['pdf'=>'picture_as_pdf', 'doc'=>'description', 'docx'=>'description', 'ppt'=>'slideshow', 'pptx'=>'slideshow', 'xls'=>'table_chart', 'xlsx'=>'table_chart', 'zip'=>'folder_zip', 'rar'=>'folder_zip'];
                                                        $icon = $iconMap[$materi->file_type] ?? 'insert_drive_file';
                                                    @endphp
                                                    <span class="material-symbols-outlined text-xl">{{ $icon }}</span>
                                                </div>
                                                <div>
                                                    <h5 class="text-sm font-bold text-gray-800 line-clamp-1">{{ $materi->judul }}</h5>
                                                    <p class="text-xs text-gray-500 line-clamp-1">{{ $materi->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <span class="text-[10px] text-gray-400 bg-white border border-gray-200 px-1.5 rounded flex items-center gap-1">
                                                                {{ strtoupper($materi->file_type) }}
                                                        </span>
                                                        <span class="text-[10px] text-gray-400">{{ $materi->file_size_human }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="flex shrink-0 items-center justify-end gap-1 opacity-100 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                                                <a href="{{ route('dosen.materi.download', $materi->id) }}" target="_blank"
                                                    class="size-8 flex items-center justify-center text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Download">
                                                    <span class="material-symbols-outlined text-[18px]">download</span>
                                                </a>
                                                <form action="{{ route('dosen.kelas.pertemuan.materi.destroy', ['id' => $kelas->id, 'pertemuan' => $meeting['no'], 'materi' => $materi->id]) }}" 
                                                      method="POST" onsubmit="event.preventDefault(); showDeleteConfirm('materi', () => this.submit());">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="size-8 flex items-center justify-center text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 border-2 border-dashed border-gray-100 rounded-xl bg-gray-50/30">
                                <p class="text-xs text-gray-400">Belum ada materi diunggah.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Tugas Section --}}
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                             <span class="material-symbols-outlined text-gray-500">assignment</span>
                             Tugas Mahasiswa
                        </h4>
                        <div class="space-y-4">
                            @if(isset($tasks) && $tasks->count())
                                @foreach($tasks as $t)
                                    <div class="p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition-colors">
                                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                            <div>
                                                <p class="text-sm font-bold text-gray-800">{{ $t->title }}</p>
                                                <p class="text-xs text-gray-500">Diunggah: {{ $t->created_at->format('d M Y') }}</p>
                                                @if($t->due_date)
                                                    <p class="text-xs text-orange-700 mt-1 font-medium bg-orange-50 px-2 py-0.5 rounded inline-block">Deadline:
                                                        {{ \Carbon\Carbon::parse($t->due_date)->format('d M Y H:i') }}</p>
                                                @endif
                                            </div>
                                            <div class="text-left sm:text-right">
                                                @if($t->file_path)
                                                    <a href="{{ asset('storage/' . $t->file_path) }}"
                                                       target="_blank"
                                                       class="text-[10px] sm:text-xs text-primary hover:underline font-bold inline-flex items-center gap-1">
                                                        <span class="material-symbols-outlined text-[14px] sm:text-[16px]">visibility</span>
                                                        Lihat Soal
                                                    </a>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mt-3 flex sm:justify-start gap-2 border-t border-gray-100 pt-3 sm:border-none sm:pt-0">
                                            <form method="POST"
                                                action="{{ route('dosen.kelas.pertemuan.tugas.destroy', ['id' => $kelas->id, 'pertemuan' => $meeting['no'], 'tugas' => $t->id]) }}"
                                                onsubmit="event.preventDefault(); showDeleteConfirm('tugas', () => this.submit());" class="flex-1 sm:flex-none">
                                                @csrf
                                                @method('DELETE')
                                                <button class="w-full sm:w-auto px-3 py-1.5 bg-white border border-gray-200 hover:bg-red-50 hover:text-red-600 hover:border-red-200 rounded text-xs transition-colors">Hapus</button>
                                            </form>
                                            <button type="button"  
                                                    data-task="{{ base64_encode(json_encode([
                                                        'title' => $t->title,
                                                        'description' => $t->description,
                                                        'dueDate' => $t->due_date ? \Carbon\Carbon::parse($t->due_date)->format('d M Y H:i') : '-',
                                                        'maxScore' => $t->max_score ?? 100,
                                                        'submissionType' => strtoupper($t->submission_type ?? 'ANY'),
                                                        'filePath' => $t->file_path ? asset('storage/' . $t->file_path) : null
                                                    ])) }}"
                                                    x-data @click="$dispatch('open-detail-tugas', JSON.parse(atob($el.dataset.task)))"
                                                    class="flex-1 sm:flex-none w-full sm:w-auto px-3 py-1.5 bg-white border border-gray-200 hover:bg-gray-50 rounded text-xs transition-colors">Detail</button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-6 border-2 border-dashed border-gray-100 rounded-xl bg-gray-50/30">
                                    <p class="text-xs text-gray-400">Belum ada tugas.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL UPLOAD MATERI --}}
    <div x-data="{ open: false, fileName: '' }" 
         @open-upload-materi.window="open = true; fileName = ''"
         @close-upload-materi.window="open = false"
         @keydown.escape.window="open = false"
         x-show="open"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm" @click="open = false"></div>

            <!-- Modal content -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="open" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10"
                 @click.stop>
                {{-- Header with Full Gradient --}}
                <div class="bg-maroon px-5 sm:px-8 py-4 sm:py-6 rounded-t-2xl">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="size-10 sm:size-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg shrink-0">
                                <span class="material-symbols-outlined text-white text-2xl sm:text-3xl">upload_file</span>
                            </div>
                            <div class="flex flex-col justify-center">
                                <h3 class="text-lg sm:text-2xl font-bold text-white mb-1 leading-tight">Upload Materi</h3>
                                <p class="text-[11px] sm:text-sm text-white/90">Tambahkan materi pembelajaran untuk pertemuan ini</p>
                            </div>
                        </div>
                        <button type="button" @click="open = false" class="text-white/80 hover:text-white transition-colors p-2 hover:bg-white/10 rounded-lg shrink-0">
                            <span class="material-symbols-outlined text-2xl">close</span>
                        </button>
                    </div>
                </div>

                <div class="p-6">

                <form action="{{ route('dosen.kelas.pertemuan.materi.store', ['id' => $kelas->id, 'pertemuan' => $meeting['no']]) }}" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-5">
                        <div>
                            <label class="flex items-center gap-2 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                <span class="material-symbols-outlined text-primary text-sm">title</span>
                                Judul Materi *
                            </label>
                            <input type="text" name="judul" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm placeholder:text-gray-400 font-medium bg-white"
                                   placeholder="Contoh: Pengenalan Hukum Tata Negara">
                        </div>

                        <div>
                            <label class="flex items-center gap-2 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                <span class="material-symbols-outlined text-primary text-sm">description</span>
                                Deskripsi (Opsional)
                            </label>
                            <textarea name="deskripsi" rows="3"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm placeholder:text-gray-400 font-medium bg-white resize-none"
                                      placeholder="Jelaskan singkat tentang materi ini..."></textarea>
                        </div>

                        <div>
                            <label class="flex items-center gap-2 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                <span class="material-symbols-outlined text-primary text-sm">attach_file</span>
                                File Materi *
                            </label>
                            <label class="relative flex flex-col items-center justify-center w-full h-44 border-2 border-dashed rounded-2xl cursor-pointer overflow-hidden group
                                          border-primary/30 bg-gradient-to-br from-primary/5 to-red-50/50 hover:from-primary/10 hover:to-red-100/50 transition-all duration-300">
                                <div class="absolute inset-0 bg-gradient-to-br from-primary/0 to-primary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative flex flex-col items-center justify-center py-6 z-10 w-full px-4">
                                    <div class="size-14 rounded-full bg-white shadow-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                                        <span class="material-symbols-outlined text-primary text-3xl">cloud_upload</span>
                                    </div>
                                    <template x-if="!fileName">
                                        <p class="text-sm text-gray-700 font-semibold mb-1">
                                            <span class="text-primary">Klik untuk upload</span> atau drag & drop
                                        </p>
                                    </template>
                                    <template x-if="fileName">
                                        <p class="text-sm text-primary font-semibold mb-1 line-clamp-2 px-4 text-center" x-text="fileName"></p>
                                    </template>
                                    <div class="flex items-center gap-2 mt-2" x-show="!fileName">
                                        <span class="px-2 py-0.5 bg-white rounded-full text-[10px] font-bold text-gray-600 shadow-sm">PDF</span>
                                        <span class="px-2 py-0.5 bg-white rounded-full text-[10px] font-bold text-gray-600 shadow-sm">DOCX</span>
                                        <span class="px-2 py-0.5 bg-white rounded-full text-[10px] font-bold text-gray-600 shadow-sm">PPTX</span>
                                        <span class="px-2 py-0.5 bg-white rounded-full text-[10px] font-bold text-gray-600 shadow-sm">XLSX</span>
                                        <span class="px-2 py-0.5 bg-white rounded-full text-[10px] font-bold text-gray-600 shadow-sm">ZIP</span>
                                    </div>
                                    <p class="text-[10px] text-gray-500 mt-2 font-medium" x-show="!fileName">Maksimal 50MB</p>
                                </div>
                                <input name="file" type="file" required accept=".pdf,.docx,.pptx,.xls,.xlsx,.zip,.rar" class="hidden" @change="
                                    const file = $event.target.files[0];
                                    if (file) {
                                        const ext = file.name.split('.').pop().toLowerCase();
                                        const allowed = ['pdf','docx','pptx','xls','xlsx','zip','rar'];
                                        if (!allowed.includes(ext)) {
                                            Swal.fire({ icon: 'error', iconColor: '#8B1538', title: 'Format File Tidak Valid!', html: 'File yang diizinkan: <b>PDF, DOCX, PPTX, XLS, XLSX, ZIP, RAR</b>.<br><br>File &quot;<strong>' + file.name + '</strong>&quot; tidak dapat diupload.', confirmButtonColor: '#8B1538' });
                                            $event.target.value = '';
                                            fileName = '';
                                        } else {
                                            fileName = file.name;
                                        }
                                    } else {
                                        fileName = '';
                                    }
                                " />
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                        <button type="button" @click="open = false"
                                class="w-full sm:w-auto px-5 py-2.5 bg-white border-2 border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all order-2 sm:order-1">
                            <span class="flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-lg">close</span>
                                Batal
                            </span>
                        </button>
                        <button type="submit"
                                class="w-full sm:w-auto px-5 py-2.5 bg-maroon text-white rounded-xl text-sm font-bold hover:from-primary-hover hover:to-red-800 shadow-lg shadow-primary/30 hover:shadow-xl hover:shadow-primary/40 transition-all order-1 sm:order-2">
                            <span class="flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-lg">upload</span>
                                Upload Materi
                             </span>
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL CREATE TUGAS --}}
    <div x-data="{ open: false, fileName: '' }" @open-create-tugas.window="open = true; fileName = ''">
        <div x-show="open" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            {{-- Backdrop --}}
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true"
                @click="open = false"></div>

            {{-- Modal Panel --}}
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="open" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl">

                    <form method="POST" enctype="multipart/form-data"
                        action="{{ route('dosen.kelas.pertemuan.tugas.store', ['id' => $kelas->id, 'pertemuan' => $meeting['no']]) }}">
                        @csrf

                    {{-- Header with Full Gradient --}}
                    <div class="bg-maroon px-5 sm:px-8 py-4 sm:py-6 rounded-t-2xl mb-6">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div class="size-10 sm:size-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg shrink-0">
                                    <span class="material-symbols-outlined text-white text-2xl sm:text-3xl">assignment</span>
                                </div>
                                <div class="flex flex-col justify-center">
                                    <h3 class="text-lg sm:text-2xl font-bold text-white leading-tight">Buat Tugas Baru</h3>
                                    <p class="text-[11px] sm:text-sm text-white/90 m-0">Tambahkan tugas untuk mahasiswa</p>
                                </div>
                            </div>
                            <button type="button" @click="open = false" class="text-white/80 hover:text-white transition-colors p-2 hover:bg-white/10 rounded-lg shrink-0">
                                <span class="material-symbols-outlined text-2xl">close</span>
                            </button>
                        </div>
                    </div>

                    <div class="px-8 pb-8">
                        <div class="space-y-5">
                            {{-- Title Input --}}
                            <div>
                                <label for="title" class="flex items-center gap-2 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <span class="material-symbols-outlined text-primary text-sm">title</span>
                                    Judul Tugas *
                                </label>
                                <input type="text" name="title" id="title" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm placeholder:text-gray-400 font-medium bg-white"
                                    placeholder="Contoh: Resume Pertemuan 1">
                            </div>

                            {{-- Description Input --}}
                            <div class="mt-4">
                                <label for="description" class="flex items-center gap-2 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <span class="material-symbols-outlined text-primary text-sm">description</span>
                                    Deskripsi / Instruksi *
                                </label>
                                <textarea name="description" id="description" rows="3" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm placeholder:text-gray-400 font-medium bg-white resize-none"
                                    placeholder="Jelaskan instruksi pengerjaan tugas..."></textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                                {{-- Due Date --}}
                                <div>
                                    <label for="due_date" class="flex items-center gap-2 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        <span class="material-symbols-outlined text-primary text-sm">schedule</span>
                                        Deadline *
                                    </label>
                                    <input type="datetime-local" name="due_date" id="due_date" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm font-medium text-gray-700 bg-white">
                                </div>

                                {{-- Max Score --}}
                                <div>
                                    <label for="max_score" class="flex items-center gap-2 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        <span class="material-symbols-outlined text-primary text-sm">grade</span>
                                        Nilai Maksimal *
                                    </label>
                                    <input type="number" name="max_score" id="max_score" min="0" value="100" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm font-medium text-gray-700 bg-white"
                                        placeholder="Contoh: 100">
                                </div>
                            </div>

                            {{-- Submission Type --}}
                            <div class="mt-4">
                                <label for="submission_type" class="flex items-center gap-2 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <span class="material-symbols-outlined text-primary text-sm">assignment_turned_in</span>
                                    Tipe Pengumpulan *
                                </label>
                                <div class="grid grid-cols-3 gap-2">
                                    <label class="relative flex items-center justify-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-all group">
                                        <input type="radio" name="submission_type" value="pdf" class="sr-only peer" checked>
                                        <div class="flex flex-col items-center gap-1 text-center peer-checked:text-primary">
                                            <i class="fas fa-file-pdf text-xl group-hover:scale-110 transition-transform"></i>
                                            <span class="text-xs font-bold">PDF</span>
                                        </div>
                                        <div class="absolute inset-0 border-2 border-primary rounded-lg opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    </label>
                                    <label class="relative flex items-center justify-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-all group">
                                        <input type="radio" name="submission_type" value="word" class="sr-only peer">
                                        <div class="flex flex-col items-center gap-1 text-center peer-checked:text-primary">
                                            <i class="fas fa-file-word text-xl group-hover:scale-110 transition-transform"></i>
                                            <span class="text-xs font-bold">Word</span>
                                        </div>
                                        <div class="absolute inset-0 border-2 border-primary rounded-lg opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    </label>
                                    <label class="relative flex items-center justify-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-all group">
                                        <input type="radio" name="submission_type" value="excel" class="sr-only peer">
                                        <div class="flex flex-col items-center gap-1 text-center peer-checked:text-primary">
                                            <i class="fas fa-file-excel text-xl group-hover:scale-110 transition-transform"></i>
                                            <span class="text-xs font-bold">Excel</span>
                                        </div>
                                        <div class="absolute inset-0 border-2 border-primary rounded-lg opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    </label>
                                    <label class="relative flex items-center justify-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-all group">
                                        <input type="radio" name="submission_type" value="text" class="sr-only peer">
                                        <div class="flex flex-col items-center gap-1 text-center peer-checked:text-primary">
                                            <i class="fas fa-keyboard text-xl group-hover:scale-110 transition-transform"></i>
                                            <span class="text-xs font-bold">Teks</span>
                                        </div>
                                        <div class="absolute inset-0 border-2 border-primary rounded-lg opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    </label>
                                    <label class="relative flex items-center justify-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-all group col-span-2">
                                        <input type="radio" name="submission_type" value="any" class="sr-only peer">
                                        <div class="flex flex-col items-center gap-1 text-center peer-checked:text-primary">
                                            <i class="fas fa-file text-xl group-hover:scale-110 transition-transform"></i>
                                            <span class="text-xs font-bold">Semua Format</span>
                                        </div>
                                        <div class="absolute inset-0 border-2 border-primary rounded-lg opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Pilih format file yang harus diupload mahasiswa</p>
                            </div>

                            {{-- File Upload --}}
                            <div class="mt-4">
                                <label class="flex items-center gap-2 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <span class="material-symbols-outlined text-primary text-sm">attach_file</span>
                                    File Soal (Opsional)
                                </label>
                                <label class="relative flex flex-col items-center justify-center w-full h-44 border-2 border-dashed rounded-2xl cursor-pointer overflow-hidden group
                                              border-blue-300/40 bg-gradient-to-br from-blue-50/50 to-indigo-50/50 hover:from-blue-100/50 hover:to-indigo-100/50 transition-all duration-300">
                                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 to-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    <div class="relative flex flex-col items-center justify-center py-6 z-10 w-full px-4">
                                        <div class="size-14 rounded-full bg-white shadow-lg flex-shrink-0 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                                            <span class="material-symbols-outlined text-blue-600 text-3xl">cloud_upload</span>
                                        </div>
                                        <template x-if="!fileName">
                                            <p class="text-sm text-gray-700 font-semibold mb-1 text-center">
                                                <span class="text-blue-600">Klik untuk upload</span> atau drag & drop
                                            </p>
                                        </template>
                                        <template x-if="fileName">
                                            <p class="text-sm text-blue-700 font-semibold mb-1 text-center line-clamp-2 px-4" x-text="fileName"></p>
                                        </template>
                                        <div class="flex flex-wrap justify-center items-center gap-2 mt-2" x-show="!fileName">
                                            <span class="px-2 py-0.5 bg-white rounded-full text-[10px] font-bold text-gray-600 shadow-sm border border-gray-100">PDF</span>
                                            <span class="px-2 py-0.5 bg-white rounded-full text-[10px] font-bold text-gray-600 shadow-sm border border-gray-100">DOCX</span>
                                            <span class="px-2 py-0.5 bg-white rounded-full text-[10px] font-bold text-gray-600 shadow-sm border border-gray-100">ZIP</span>
                                        </div>
                                        <p class="text-[10px] text-gray-500 mt-2 font-medium text-center" x-show="!fileName">Maksimal 50MB</p>
                                    </div>
                                    <input name="file" type="file" id="tugas-file-input" accept=".pdf,.doc,.docx,.zip" class="hidden" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''" />
                                </label>
                            </div>
                        </div>

                            {{-- Footer --}}
                            <div class="flex flex-col sm:flex-row justify-end items-center gap-3 mt-5 pt-5 -mx-8 px-8">
                                <button type="button" @click="open = false"
                                    class="w-full sm:w-auto px-5 py-2.5 bg-white border-2 border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all order-2 sm:order-1">
                                    <span class="flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-lg">close</span>
                                        Batal
                                    </span>
                                </button>
                                <button type="submit"
                                    class="w-full sm:w-auto px-5 py-2.5 bg-maroon text-white rounded-xl text-sm font-bold hover:from-primary-hover hover:to-red-800 shadow-lg shadow-primary/30 hover:shadow-xl hover:shadow-primary/40 transition-all order-1 sm:order-2">
                                    <span class="flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-lg">task_alt</span>
                                        Simpan Tugas
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL RESCHEDULE METODE PENGAJARAN --}}
    <div x-data="{ open: false, selectedMetode: '{{ $pertemuanRecord?->metode_pengajaran ?? 'offline' }}' }"
         @open-reschedule-metode.window="open = true"
         @keydown.escape.window="open = false"
         x-show="open"
         style="display: none;"
         class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm" @click="open = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="open" x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block w-full max-w-md my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10"
                 @click.stop>
                <div class="bg-maroon px-6 py-6 rounded-t-2xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="size-12 rounded-xl bg-white/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-2xl">edit_calendar</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Reschedule Metode</h3>
                            <p class="text-xs text-white/80">Ubah metode pertemuan {{ $meeting['label'] }}</p>
                        </div>
                    </div>
                    <button @click="open = false" class="text-white/80 hover:text-white p-1">
                        <span class="material-symbols-outlined text-2xl">close</span>
                    </button>
                </div>

                <form action="{{ route('dosen.kelas.pertemuan.metode.update', ['id' => $id, 'pertemuan' => ($meeting['tipe_pertemuan'] ?? 'kuliah') . ':' . ($meeting['nomor_pertemuan'] ?? $meeting['no'])]) }}" method="POST" class="p-6">
                    @csrf @method('PATCH')

                    <fieldset class="space-y-3 mb-6">
                        <legend class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Pilih Metode Pengajaran</legend>

                        @foreach(['offline' => ['label' => 'Offline / Tatap Muka', 'icon' => 'location_on', 'desc' => 'Perkuliahan dilakukan secara langsung di ruangan'], 'online' => ['label' => 'Online / Daring', 'icon' => 'video_call', 'desc' => 'Perkuliahan via video conference (Zoom, Meet, dll)'], 'asynchronous' => ['label' => 'Asynchronous', 'icon' => 'schedule', 'desc' => 'Mahasiswa belajar mandiri (QR absensi tidak aktif)']] as $val => $info)
                            <label class="flex items-start gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all"
                                   :class="'{{ $val }}' === selectedMetode ? 'border-maroon bg-red-50' : 'border-gray-100 hover:border-gray-300'"
                                   @click="selectedMetode = '{{ $val }}'">
                                <input type="radio" name="metode_pengajaran" value="{{ $val }}"
                                       x-model="selectedMetode"
                                       class="mt-1 accent-maroon">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-gray-500 text-xl mt-0.5">{{ $info['icon'] }}</span>
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm">{{ $info['label'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $info['desc'] }}</p>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </fieldset>

                    <div x-show="selectedMetode === 'online'" x-transition class="mb-6">
                        <label for="online_meeting_link" class="block text-sm font-bold text-gray-700 mb-2">Link Meeting (Zoom/Meet/dll)</label>
                        <input type="url" name="online_meeting_link" id="online_meeting_link"
                               value="{{ $pertemuanRecord?->online_meeting_link }}"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-50 focus:border-maroon focus:ring-maroon transition-colors"
                               placeholder="https://zoom.us/j/1234567890">
                    </div>

                    <div class="flex gap-3">
                        <button type="button" @click="open = false"
                                class="flex-1 py-2.5 bg-white border-2 border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 py-2.5 bg-maroon text-white rounded-xl text-sm font-bold hover:bg-primary-hover shadow-lg shadow-primary/30 transition-all">
                            Simpan Metode
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL PASSWORD AKTIVASI QR --}}
    <div x-data="{
             open: false,
             loading: false,
             lat: null,
             lng: null,
             gpsStatus: 'prompt',
             gpsError: '',
             gpsErrCode: 0,
             getLocation() {
                 if (!navigator.geolocation) {
                     this.gpsStatus = 'unsupported';
                     return;
                 }
                 this.gpsStatus = 'getting';
                 this.gpsError = '';
                 navigator.geolocation.getCurrentPosition(
                     (pos) => {
                         this.lat = pos.coords.latitude.toFixed(7);
                         this.lng = pos.coords.longitude.toFixed(7);
                         this.gpsStatus = 'got';
                     },
                     (err) => {
                         this.gpsErrCode = err.code;
                         this.gpsStatus = err.code === 1 ? 'blocked' : 'failed';
                         this.gpsError = err.code === 2
                             ? 'Posisi tidak dapat ditentukan. Pastikan GPS perangkat aktif.'
                             : (err.code === 3 ? 'Waktu habis. Periksa sinyal GPS dan coba lagi.' : err.message);
                     },
                     { enableHighAccuracy: true, timeout: 12000 }
                 );
             }
         }"
         @open-qr-password-modal.window="open = true; gpsStatus = 'prompt'; lat = null; lng = null;"
         @keydown.escape.window="open = false"
         x-show="open"
         style="display: none;"
         class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm" @click="open = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="open" x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block w-full max-w-sm my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10"
                 @click.stop>
                <div class="bg-maroon px-6 py-6 rounded-t-2xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="size-12 rounded-xl bg-white/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-2xl">qr_code_2</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Verifikasi Identitas</h3>
                            <p class="text-xs text-white/80">Masukkan password untuk mengaktifkan QR</p>
                        </div>
                    </div>
                    <button @click="open = false" class="text-white/80 hover:text-white p-1">
                        <span class="material-symbols-outlined text-2xl">close</span>
                    </button>
                </div>

                <form action="{{ route('dosen.kelas.pertemuan.activate_qr_password', ['id' => $id, 'pertemuan' => ($meeting['tipe_pertemuan'] ?? 'kuliah') . ':' . ($meeting['nomor_pertemuan'] ?? $meeting['no'])]) }}"
                      method="POST" class="p-6"
                      @submit.prevent="if (gpsStatus !== 'got') { getLocation(); } else { loading = true; $el.submit(); }">
                    @csrf
                    <input type="hidden" name="tipe_pertemuan" value="{{ $meeting['tipe_pertemuan'] ?? 'kuliah' }}">
                    <input type="hidden" name="latitude"  :value="lat">
                    <input type="hidden" name="longitude" :value="lng">

                    {{-- GPS Status --}}
                    <div class="mb-5">
                        <p class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">location_on</span>
                            Lokasi GPS
                        </p>

                        {{-- PROMPT: belum minta izin --}}
                        <div x-show="gpsStatus === 'prompt'" class="space-y-2">
                            <p class="text-xs text-gray-500 leading-relaxed">
                                Lokasi diperlukan untuk memverifikasi kehadiran Anda. Klik tombol di bawah lalu <strong>izinkan</strong> akses lokasi ketika browser meminta.
                            </p>
                            <button type="button" @click="getLocation()"
                                    class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold flex items-center justify-center gap-2 transition-all shadow-md shadow-blue-200">
                                <span class="material-symbols-outlined text-[18px]">my_location</span>
                                Izinkan Akses Lokasi
                            </button>
                        </div>

                        {{-- GETTING: sedang mengambil --}}
                        <div x-show="gpsStatus === 'getting'"
                             class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-200 rounded-xl text-blue-700 text-sm font-medium">
                            <svg class="animate-spin h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Mendapatkan lokasi GPS&hellip; Mohon tunggu.
                        </div>

                        {{-- GOT: berhasil --}}
                        <div x-show="gpsStatus === 'got'"
                             class="flex items-center gap-3 p-3 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium">
                            <span class="material-symbols-outlined text-[20px] shrink-0">check_circle</span>
                            <div>
                                <p class="font-bold">Lokasi berhasil didapat</p>
                                <p class="font-mono text-xs text-green-600" x-text="lat + ', ' + lng"></p>
                            </div>
                        </div>

                        {{-- BLOCKED: izin ditolak oleh user --}}
                        <div x-show="gpsStatus === 'blocked'" class="space-y-3">
                            <div class="flex items-start gap-3 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium">
                                <span class="material-symbols-outlined text-[20px] shrink-0 mt-0.5">location_off</span>
                                <div>
                                    <p class="font-bold">Izin Lokasi Ditolak</p>
                                    <p class="text-xs font-normal mt-0.5">Browser memblokir akses lokasi. Ikuti langkah berikut untuk mengaktifkannya:</p>
                                </div>
                            </div>
                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-xs text-amber-800 space-y-2">
                                <p class="font-bold text-amber-900 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">help</span>
                                    Cara mengaktifkan izin lokasi:
                                </p>
                                <p><strong>Chrome:</strong> Klik ikon 🔒 / ⓘ di address bar → <em>Izin situs</em> → <em>Lokasi</em> → Izinkan → Muat ulang halaman.</p>
                                <p><strong>Firefox:</strong> Klik ikon 🔒 di address bar → <em>Informasi Halaman</em> → tab <em>Izin</em> → <em>Akses Lokasi</em> → Izinkan.</p>
                                <p><strong>Safari:</strong> Pengaturan → Safari → Lokasi → Izinkan.</p>
                                <p><strong>Android:</strong> Pengaturan → Aplikasi → Browser → Izin → Lokasi → Izinkan.</p>
                            </div>
                            <button type="button" @click="getLocation()"
                                    class="w-full py-2 bg-white border border-red-300 rounded-xl text-xs font-bold text-red-600 hover:bg-red-50 flex items-center justify-center gap-2 transition-all">
                                <span class="material-symbols-outlined text-[14px]">refresh</span>
                                Coba Lagi (setelah mengizinkan)
                            </button>
                        </div>

                        {{-- FAILED: error teknis / timeout --}}
                        <div x-show="gpsStatus === 'failed'" class="space-y-2">
                            <div class="flex items-start gap-3 p-3 bg-orange-50 border border-orange-200 rounded-xl text-orange-700 text-sm font-medium">
                                <span class="material-symbols-outlined text-[20px] shrink-0 mt-0.5">gps_off</span>
                                <div>
                                    <p class="font-bold">Gagal Mendapatkan Lokasi</p>
                                    <p class="text-xs font-normal mt-0.5" x-text="gpsError"></p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 px-1">Pastikan GPS perangkat aktif dan Anda berada di area dengan sinyal yang baik.</p>
                            <button type="button" @click="getLocation()"
                                    class="w-full py-2 bg-white border border-orange-300 rounded-xl text-xs font-bold text-orange-600 hover:bg-orange-50 flex items-center justify-center gap-2 transition-all">
                                <span class="material-symbols-outlined text-[14px]">refresh</span>
                                Coba Lagi
                            </button>
                        </div>

                        {{-- UNSUPPORTED --}}
                        <div x-show="gpsStatus === 'unsupported'"
                             class="flex items-start gap-3 p-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-600 text-sm">
                            <span class="material-symbols-outlined text-[20px] shrink-0 mt-0.5">location_disabled</span>
                            <p>Browser Anda tidak mendukung GPS. Gunakan browser modern seperti Chrome atau Firefox.</p>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">
                            Password Absensi
                        </label>
                        <div class="relative" x-data="{ showPw: false }">
                            <input :type="showPw ? 'text' : 'password'" name="password" required autofocus
                                   placeholder="Masukkan password Anda..."
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:border-maroon focus:ring-2 focus:ring-maroon/20 text-sm font-medium bg-white transition-all">
                            <button type="button" @click="showPw = !showPw"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 transition-colors">
                                <span class="material-symbols-outlined text-[20px]" x-text="showPw ? 'visibility_off' : 'visibility'"></span>
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">
                            <span class="material-symbols-outlined text-[12px] align-middle">info</span>
                            Gunakan password login Anda.
                        </p>
                    </div>

                    @if(session('error') && str_contains(session('error'), 'Password'))
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-600 text-xs font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">error</span>
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="flex gap-3">
                        <button type="button" @click="open = false"
                                class="flex-1 py-2.5 bg-white border-2 border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">
                            Batal
                        </button>
                        <button type="submit" :disabled="loading || gpsStatus !== 'got'"
                                class="flex-1 py-2.5 bg-maroon text-white rounded-xl text-sm font-bold hover:bg-primary-hover shadow-lg shadow-primary/30 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                :title="gpsStatus !== 'got' ? 'Aktifkan GPS terlebih dahulu' : ''">
                            <span class="material-symbols-outlined text-[18px]" x-show="!loading">qr_code_2</span>
                            <svg x-show="loading" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span x-text="loading ? 'Memverifikasi...' : 'Aktifkan QR'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL TUGAS --}}
    <div x-data="{ 
            open: false, 
            task: null
         }" 
         @open-detail-tugas.window="task = $event.detail; open = true"
         @keydown.escape.window="open = false"
         x-show="open"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
         
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm" @click="open = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="open" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10"
                 @click.stop>
                 
                {{-- Header --}}
                <div class="bg-maroon px-6 py-6 rounded-t-2xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="size-12 rounded-xl bg-white/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-2xl">assignment</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Detail Tugas</h3>
                            <p class="text-xs text-white/80" x-text="task?.title"></p>
                        </div>
                    </div>
                    <button @click="open = false" class="text-white/80 hover:text-white p-1">
                        <span class="material-symbols-outlined text-2xl">close</span>
                    </button>
                </div>

                {{-- Content --}}
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-50/80 p-5 rounded-xl border border-gray-100 mb-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Maksimal Nilai</p>
                            <p class="font-bold text-gray-800 text-sm" x-text="task?.maxScore"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tipe Pengumpulan</p>
                            <p class="font-bold text-gray-800 text-sm" x-text="task?.submissionType"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Batas Pengumpulan</p>
                            <p class="font-bold text-orange-600 text-sm flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">schedule</span>
                                <span x-text="task?.dueDate"></span>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">File Soal</p>
                            <template x-if="task?.filePath">
                                <a :href="task.filePath" target="_blank" download class="font-bold text-primary hover:text-primary-hover text-sm flex items-center gap-1.5 hover:underline">
                                    <span class="material-symbols-outlined text-[16px]">download</span>
                                    <span>Download Soal</span>
                                </a>
                            </template>
                            <template x-if="!task?.filePath">
                                <p class="font-bold text-gray-800 text-sm">-</p>
                            </template>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-400 text-[18px]">description</span>
                            Instruksi / Deskripsi
                        </p>
                        <div class="prose prose-sm max-w-none prose-p:my-2 prose-headings:my-3 text-gray-700 bg-white p-5 rounded-xl border border-gray-200" x-html="task?.description || 'Tidak ada deskripsi'">
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end pt-5 border-t border-gray-100">
                        <button type="button" @click="open = false"
                                class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm font-bold hover:bg-gray-200 transition-all">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Flash message handling
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#8B1538',
                timer: 3000,
                timerProgressBar: true
            }).then(() => {
                // window.location.reload(); 
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#8B1538'
            });
        @endif
        
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal!',
                html: '<ul class="text-left">' + 
                    @foreach($errors->all() as $error)
                        '<li>{{ $error }}</li>' +
                    @endforeach
                    '</ul>',
                confirmButtonColor: '#8B1538'
            });
        @endif
    </script>
    {{-- TinyMCE CDN --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            tinymce.init({
                selector: '#description',
                height: 300,
                menubar: false,
                plugins: 'lists link',
                toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link | removeformat',
                content_style: 'body { font-family:Inter,sans-serif; font-size:14px }',
                setup: function (editor) {
                    editor.on('change', function () {
                        editor.save(); 
                    });
                }
            });
        });
    </script>
    <script>
        (function(){
            const qrEnabled = {!! json_encode($qrEnabled ?? false) !!};
            const qrExpiresRaw = {!! json_encode($qrExpires ?? null) !!};
            const deactivateUrl = "{{ route('dosen.kelas.deactivate_qr', ['id' => $id]) }}";

            const qrCountdown = document.getElementById('qrCountdown');

            function setDisabledUI() {
                // Determine how to update UI when expired - for now just reload or let user refresh
                 window.location.reload();
            }

            if (qrEnabled && qrExpiresRaw) {
                const expiresAt = new Date(qrExpiresRaw);
                function tick() {
                    const now = new Date();
                    const diff = expiresAt - now;
                    if (diff <= 0) {
                        clearInterval(timer);
                        // cleanup via ajax then reload
                        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                        fetch(deactivateUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({})
                        }).finally(()=>{
                            window.location.reload();
                        });
                        return;
                    }
                    const mm = Math.floor(diff/60000);
                    const ss = Math.floor((diff%60000)/1000);
                    if (qrCountdown) qrCountdown.textContent = String(mm).padStart(2,'0')+':'+String(ss).padStart(2,'0');
                }
                tick();
                const timer = setInterval(tick, 1000);
            }
        })();
    </script>
    <script>
        (function(){
            const copyBtn = document.getElementById('copyBtn');
            const absensiLinkEl = document.getElementById('absensiLink');

            if (copyBtn && absensiLinkEl) {
                function tryCopyText(text) {
                    if (!text) return Promise.reject(new Error('no-text'));
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        return navigator.clipboard.writeText(text);
                    }
                    return new Promise(function(resolve, reject){
                        try {
                            const textarea = document.createElement('textarea');
                            textarea.value = text;
                            textarea.style.position = 'fixed';
                            textarea.style.left = '-9999px';
                            document.body.appendChild(textarea);
                            textarea.select();
                            const ok = document.execCommand('copy');
                            document.body.removeChild(textarea);
                            if (ok) resolve(); else reject(new Error('exec-failed'));
                        } catch (e) { reject(e); }
                    });
                }

                copyBtn.addEventListener('click', function(){
                    const text = absensiLinkEl.value || '';
                    if (!text || text === 'Link belum tersedia') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Link Belum Tersedia',
                            text: 'Link absensi belum tersedia. Silakan aktifkan QR absensi terlebih dahulu.',
                            confirmButtonColor: '#8B1538'
                        });
                        return;
                    }
                    
                    tryCopyText(text).then(()=>{
                        const originalHtml = copyBtn.innerHTML;
                        copyBtn.innerHTML = '<span class="material-symbols-outlined text-[18px]">check</span><span>Disalin</span>';
                        copyBtn.classList.remove('bg-[#8B1538]', 'hover:bg-[#7A1231]');
                        copyBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                        
                        setTimeout(()=> {
                            copyBtn.innerHTML = originalHtml;
                            copyBtn.classList.add('bg-[#8B1538]', 'hover:bg-[#7A1231]');
                            copyBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                        }, 2000);
                    });
                });
                
                absensiLinkEl.addEventListener('click', function() {
                   this.select(); 
                });
            }
        })();
    </script>
    <script>
        // SweetAlert2 confirmation for Deactivate QR
        document.querySelectorAll('.deactivate-qr-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Nonaktifkan QR?',
                    text: 'QR absensi akan dinonaktifkan dan mahasiswa tidak bisa scan untuk absen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#8B1538',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Nonaktifkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Use native submit to bypass the event listener
                        HTMLFormElement.prototype.submit.call(form);
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setInterval(() => {
                const url = new URL(window.location.href);
                url.searchParams.set('reload_attendance', '1');
                
                fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                     if (!response.ok) throw new Error('Network response was not ok');
                     return response.json();
                })
                .then(data => {
                    const tableBody = document.getElementById('attendance-table-body');
                    const presentCount = document.getElementById('attendance-present-count');
                    const totalCount = document.getElementById('attendance-total-count');
                    
                    if(tableBody) tableBody.innerHTML = data.html;
                    if(presentCount) presentCount.innerText = data.attended;
                    if(totalCount) totalCount.innerText = data.total;
                })
                .catch(error => console.error('Error fetching attendance:', error));
            }, 3000); // Poll every 3 seconds
        });
    </script>
    <script>
        // File format validation for Buat Tugas modal
        (function() {
            const tugasFileInput = document.getElementById('tugas-file-input');
            if (tugasFileInput) {
                tugasFileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const allowedExtensions = ['.pdf', '.doc', '.docx', '.zip'];
                        const fileName = file.name.toLowerCase();
                        const isValidExtension = allowedExtensions.some(ext => fileName.endsWith(ext));

                        if (!isValidExtension) {
                            Swal.fire({
                                icon: 'error',
                                iconColor: '#8B1538',
                                title: 'Format File Tidak Valid!',
                                html: 'File yang diizinkan: <strong>PDF, DOCX, atau ZIP</strong>.<br><br>File "<strong>' + file.name + '</strong>" tidak dapat diupload.',
                                confirmButtonColor: '#8B1538',
                                confirmButtonText: 'OK'
                            });
                            e.target.value = '';
                        }
                    }
                });
            }
        })();
    </script>
@endpush