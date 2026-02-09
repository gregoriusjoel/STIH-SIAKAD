@extends('layouts.app')

@section('title', 'Detail Pertemuan')

@section('content')
    @section('navbar_breadcrumb')
        <nav class="flex items-center gap-2 text-sm text-[#616889]">
            <a href="{{ route('dosen.kelas') }}" class="hover:text-primary transition-colors">Kelas</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <a href="{{ route('dosen.kelas.detail', $kelas->id) }}" class="hover:text-primary transition-colors">Detail
                Kelas</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-[#111218] font-medium">{{ $meeting['label'] }}</span>
        </nav>
    @endsection

    <div class="px-4 py-6 max-w-[1600px] mx-auto">

        {{-- HEADER --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="size-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-3xl">event_note</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $meeting['label'] }}</h1>
                        <p class="text-gray-500">{{ $kelas->mataKuliah->nama_mk }} - Kelas {{ $kelas->section }}</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    {{-- Buttons moved to card below --}}
                </div>
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
                    <p class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-gray-400 text-[18px]">topic</span>
                        -
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
                            Hadir: {{ $attendedCount }}
                        </span>
                        <span class="px-3 py-1 rounded-full bg-gray-100 text-xs font-bold text-gray-600">
                            Total: {{ $totalStudents }}
                        </span>
                    </div>
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
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-3">
                                        <span class="material-symbols-outlined text-3xl">qr_code_2</span>
                                    </div>
                                    <h4 class="text-sm font-bold text-gray-700 mb-1">QR Absensi Belum Aktif</h4>
                                    <p class="text-xs text-gray-500 mb-4 max-w-[250px]">Aktifkan QR Code agar mahasiswa dapat melakukan absensi mandiri via aplikasi.</p>
                                    
                                    @if(isset($token) && $token)
                                        <form id="activateQrForm" action="{{ route('dosen.kelas.activate_qr', ['id' => $id]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="pertemuan" value="{{ $meeting['no'] }}">
                                            <button type="button" @click="showQr('activateQrForm')" 
                                                class="px-5 py-2.5 bg-maroon text-white rounded-xl text-sm font-bold hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                                                <span class="material-symbols-outlined text-[18px]">bolt</span>
                                                Tampilkan QR Sekarang
                                            </button>
                                        </form>
                                    @else
                                        <form id="generateQrForm" action="{{ route('dosen.kelas.generate_qr', ['id' => $id]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="pertemuan" value="{{ $meeting['no'] }}">
                                            <button type="button" @click="showQr('generateQrForm')" 
                                                class="px-5 py-2.5 bg-primary text-white rounded-xl text-sm font-bold hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                                                <span class="material-symbols-outlined text-[18px]">add_box</span>
                                                Buat QR Absensi
                                            </button>
                                        </form>
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

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 text-xs uppercase tracking-wider text-gray-500 font-bold">
                                <th class="px-6 py-4 w-16">No</th>
                                <th class="px-6 py-4">Mahasiswa</th>
                                <th class="px-6 py-4">NIM</th>
                                <th class="px-6 py-4 text-center">Status Kehadiran</th>
                                <th class="px-6 py-4 text-center">Waktu Scan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($students as $index => $student)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-gray-500 font-medium">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800">{{ $student['name'] }}</div>
                                        <div class="text-xs text-gray-500">{{ $student['prodi'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-sm text-gray-600">{{ $student['nim'] }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if(isset($student['attendance_status']) && $student['attendance_status'] === 'hadir')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                                <span class="material-symbols-outlined text-[16px] mr-1">check_circle</span>
                                                Hadir
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">
                                                <span class="material-symbols-outlined text-[16px] mr-1">cancel</span>
                                                Belum Absen
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-600 text-sm font-mono">
                                        {{ $student['attendance_time'] ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        Tidak ada mahasiswa terdaftar di kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Right: Materi & Tugas --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col h-full">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white sticky top-0 z-10">
                    <h3 class="font-bold text-gray-800 text-lg">Materi & Tugas</h3>
                    <div class="flex items-center gap-2">
                         <button x-data x-on:click="$dispatch('open-upload-materi')"
                            class="px-3 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">upload_file</span>
                            Materi
                        </button>
                        <button x-data x-on:click="$dispatch('open-create-tugas')"
                            class="px-3 py-2 bg-primary text-white hover:bg-primary-hover rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
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
                                            
                                            <div class="flex items-center gap-1 opacity-100 sm:opacity-0 group-hover:opacity-100 transition-opacity">
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
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <p class="text-sm font-bold text-gray-800">{{ $t->title }}</p>
                                                <p class="text-xs text-gray-500">Diunggah: {{ $t->created_at->format('d M Y') }}</p>
                                                @if($t->due_date)
                                                    <p class="text-xs text-orange-700 mt-1 font-medium bg-orange-50 px-2 py-0.5 rounded inline-block">Deadline:
                                                        {{ \Carbon\Carbon::parse($t->due_date)->format('d M Y H:i') }}</p>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <a href="{{ $t->file_path ? asset('storage/' . $t->file_path) : '#' }}"
                                                    class="text-xs text-primary hover:underline font-bold">Download Soal</a>
                                            </div>
                                        </div>

                                        <div class="mt-3 flex gap-2">
                                            <form method="POST"
                                                action="{{ route('kelas.pertemuan.tugas.destroy', ['id' => $kelas->id, 'pertemuan' => $meeting['no'], 'tugas' => $t->id]) }}"
                                                onsubmit="event.preventDefault(); showDeleteConfirm('tugas', () => this.submit());">
                                                @csrf
                                                @method('DELETE')
                                                <button class="px-3 py-1.5 bg-white border border-gray-200 hover:bg-red-50 hover:text-red-600 hover:border-red-200 rounded text-xs transition-colors">Hapus</button>
                                            </form>
                                            <a href="#" class="px-3 py-1.5 bg-white border border-gray-200 hover:bg-gray-50 rounded text-xs transition-colors">Detail</a>
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
    <div x-data="{ open: false }" 
         @open-upload-materi.window="open = true"
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
                <div class="bg-maroon px-8 py-8 -mx-0 -mt-0 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="size-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg">
                                <span class="material-symbols-outlined text-white text-4xl">upload_file</span>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-white mb-1">Upload Materi</h3>
                                <p class="text-sm text-white/90">Tambahkan materi pembelajaran untuk pertemuan ini</p>
                            </div>
                        </div>
                        <button type="button" @click="open = false" class="text-white/80 hover:text-white transition-colors p-2 hover:bg-white/10 rounded-lg">
                            <span class="material-symbols-outlined text-3xl">close</span>
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
                            <label class="relative flex flex-col items-center justify-center w-full h-36 border-2 border-dashed rounded-2xl cursor-pointer overflow-hidden group
                                          border-primary/30 bg-gradient-to-br from-primary/5 to-red-50/50 hover:from-primary/10 hover:to-red-100/50 transition-all duration-300">
                                <div class="absolute inset-0 bg-gradient-to-br from-primary/0 to-primary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative flex flex-col items-center justify-center pt-5 pb-6 z-10">
                                    <div class="size-14 rounded-full bg-white shadow-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                                        <span class="material-symbols-outlined text-primary text-3xl">cloud_upload</span>
                                    </div>
                                    <p class="text-sm text-gray-700 font-semibold mb-1">
                                        <span class="text-primary">Klik untuk upload</span> atau drag & drop
                                    </p>
                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="px-2 py-0.5 bg-white rounded-full text-[10px] font-bold text-gray-600 shadow-sm">PDF</span>
                                        <span class="px-2 py-0.5 bg-white rounded-full text-[10px] font-bold text-gray-600 shadow-sm">DOCX</span>
                                        <span class="px-2 py-0.5 bg-white rounded-full text-[10px] font-bold text-gray-600 shadow-sm">PPTX</span>
                                        <span class="px-2 py-0.5 bg-white rounded-full text-[10px] font-bold text-gray-600 shadow-sm">ZIP</span>
                                    </div>
                                    <p class="text-[10px] text-gray-500 mt-2 font-medium">Maksimal 50MB</p>
                                </div>
                                <input name="file" type="file" required accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar" class="hidden" />
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
    <div x-data="{ open: false }" @open-create-tugas.window="open = true">
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
                    <div class="bg-maroon px-8 py-8 -mx-6 -mt-6 rounded-t-2xl mb-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="size-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg">
                                    <span class="material-symbols-outlined text-white text-4xl">assignment</span>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-white mb-1">Buat Tugas Baru</h3>
                                    <p class="text-sm text-white/90">Tambahkan tugas untuk mahasiswa</p>
                                </div>
                            </div>
                            <button type="button" @click="open = false" class="text-white/80 hover:text-white transition-colors p-2 hover:bg-white/10 rounded-lg">
                                <span class="material-symbols-outlined text-3xl">close</span>
                            </button>
                        </div>
                    </div>

                    <div class="px-8 pb-8">
                        <div class="space-y-5">
                            {{-- Title Input --}}
                            <div>
                                <label for="title" class="flex items-center gap-2 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <span class="material-symbols-outlined text-primary text-sm">title</span>
                                    Judul Tugas
                                </label>
                                <input type="text" name="title" id="title" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm placeholder:text-gray-400 font-medium bg-white"
                                    placeholder="Contoh: Resume Pertemuan 1">
                            </div>

                            {{-- Description Input --}}
                            <div>
                                <label for="description" class="flex items-center gap-2 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <span class="material-symbols-outlined text-primary text-sm">description</span>
                                    Deskripsi / Instruksi
                                </label>
                                <textarea name="description" id="description" rows="3"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm placeholder:text-gray-400 font-medium bg-white resize-none"
                                    placeholder="Jelaskan instruksi pengerjaan tugas..."></textarea>
                            </div>

                            {{-- Due Date --}}
                            <div>
                                <label for="due_date" class="flex items-center gap-2 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <span class="material-symbols-outlined text-primary text-sm">schedule</span>
                                    Deadline
                                </label>
                                <input type="datetime-local" name="due_date" id="due_date"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm font-medium text-gray-700 bg-white">
                            </div>

                            {{-- File Upload --}}
                            <div>
                                <label class="flex items-center gap-2 text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    <span class="material-symbols-outlined text-primary text-sm">attach_file</span>
                                    File Soal (Opsional)
                                </label>
                                <label class="relative flex flex-col items-center justify-center w-full h-36 border-2 border-dashed rounded-2xl cursor-pointer overflow-hidden group
                                              border-blue-300/40 bg-gradient-to-br from-blue-50/50 to-indigo-50/50 hover:from-blue-100/50 hover:to-indigo-100/50 transition-all duration-300">
                                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 to-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    <div class="relative flex flex-col items-center justify-center pt-5 pb-6 z-10">
                                        <div class="size-14 rounded-full bg-white shadow-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                                            <span class="material-symbols-outlined text-blue-600 text-3xl">cloud_upload</span>
                                        </div>
                                        <p class="text-sm text-gray-700 font-semibold mb-1">
                                            <span class="text-blue-600">Klik untuk upload</span> atau drag & drop
                                        </p>
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="px-2 py-0.5 bg-white rounded-full text-[10px] font-bold text-gray-600 shadow-sm">PDF</span>
                                            <span class="px-2 py-0.5 bg-white rounded-full text-[10px] font-bold text-gray-600 shadow-sm">DOCX</span>
                                            <span class="px-2 py-0.5 bg-white rounded-full text-[10px] font-bold text-gray-600 shadow-sm">ZIP</span>
                                        </div>
                                        <p class="text-[10px] text-gray-500 mt-2 font-medium">Maksimal 50MB</p>
                                    </div>
                                    <input name="file" type="file" class="hidden" />
                                </label>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="flex flex-col sm:flex-row justify-end gap-3 px-8 pb-8 pt-6 border-t border-gray-100">
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
                    </form>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" integrity="sha512-6JR4bbn8rCKvrkOGMcleNghLnmwDKb8oQn6eBNZpbhaOQCSytnzeXrePOCtqhRs/qfpzjlgrYbrVuZxvni1GkWg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            tinymce.init({
                selector: '#description',
                height: 300,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar: 'undo redo | formatselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
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
                    if (!text || text === 'Link belum tersedia') return;
                    
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
@endpush