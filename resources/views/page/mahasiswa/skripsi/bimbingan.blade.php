@extends('layouts.mahasiswa')
@section('title', 'Logbook Bimbingan Skripsi')

@section('content')
<div class="px-4 py-6 space-y-5">

    {{-- Header --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-gradient-to-br from-[#8B1538]/5 to-transparent rounded-full blur-3xl"></div>

        <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            {{-- Left: Back button + Title --}}
            <div class="flex items-center gap-4">
                <a href="{{ route('mahasiswa.skripsi.index') }}"
                    class="w-10 h-10 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-[#8B1538] transition-all shrink-0 shadow-sm hover:shadow">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-xl font-black text-gray-900 tracking-tight">Logbook Bimbingan</h1>
                    <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $submission->judul }}</p>
                </div>
            </div>

            {{-- Right: Pembimbing + Status --}}
            <div class="flex flex-wrap items-center gap-3">
                {{-- Pembimbing Info --}}
                <div class="flex items-center gap-3 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#8B1538] to-[#6D1029] flex items-center justify-center text-white font-bold text-sm shadow-sm">
                        {{ substr($submission->approvedSupervisor?->nama ?? '?', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider leading-none">Dosen Pembimbing</p>
                        <p class="text-sm font-bold text-gray-800 leading-tight mt-0.5">{{ $submission->approvedSupervisor?->nama ?? '-' }}</p>
                    </div>
                </div>
                {{-- Upload status badge --}}
                @if($submission->has_logbook)
                <div class="flex items-center gap-2 px-3 py-2.5 bg-emerald-50 border border-emerald-200 rounded-xl">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-xs font-bold text-emerald-700">Logbook Diupload</span>
                </div>
                @else
                <div class="flex items-center gap-2 px-3 py-2.5 bg-amber-50 border border-amber-200 rounded-xl">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 110 18 9 9 0 010-18z"/></svg>
                    <span class="text-xs font-bold text-amber-700">Belum Upload</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-sm text-emerald-800 flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════
         2-COLUMN GRID: DOWNLOAD TEMPLATE + UPLOAD PDF
    ════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-start">

        {{-- STEP 1: DOWNLOAD TEMPLATE --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col h-full">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-3.5">
                <div class="flex items-center gap-3 text-white">
                    <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm shrink-0">
                        <span class="material-symbols-outlined text-lg">download</span>
                    </div>
                    <div>
                        <h2 class="font-bold text-sm">Langkah 1: Download Template</h2>
                        <p class="text-[11px] text-blue-100 mt-0.5">Unduh, cetak, dan isi secara manual</p>
                    </div>
                </div>
            </div>
            <div class="p-5 flex flex-col flex-1">
                <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 mb-4">
                    <h3 class="text-xs font-bold text-blue-800 mb-2 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[14px]">info</span>
                        Petunjuk Pengisian
                    </h3>
                    <ol class="text-xs text-blue-700 space-y-1 ml-4 list-decimal leading-relaxed">
                        <li>Download template logbook dengan tombol di bawah</li>
                        <li>Cetak template yang sudah didownload</li>
                        <li>Isi logbook setiap kali melakukan bimbingan dengan dosen</li>
                        <li>Minta tanda tangan dosen pembimbing di setiap sesi</li>
                        <li>Scan/foto logbook yang sudah diisi menjadi file PDF</li>
                        <li>Upload file PDF di langkah 2</li>
                    </ol>
                </div>
                <div class="mt-auto flex flex-col sm:flex-row gap-3 items-center">
                    <a href="{{ route('mahasiswa.skripsi.logbook.template') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-600/20 transition-all hover:-translate-y-0.5 active:translate-y-0">
                        <span class="material-symbols-outlined text-[18px]">download</span>
                        Download Template (.docx)
                    </a>
                    <p class="text-[11px] text-gray-400">Format: Word (.docx)</p>
                </div>
            </div>
        </div>

        {{-- STEP 2: UPLOAD LOGBOOK PDF --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col h-full">
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-5 py-3.5">
                <div class="flex items-center gap-3 text-white">
                    <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm shrink-0">
                        <span class="material-symbols-outlined text-lg">upload_file</span>
                    </div>
                    <div>
                        <h2 class="font-bold text-sm">Langkah 2: Upload Logbook PDF</h2>
                        <p class="text-[11px] text-emerald-100 mt-0.5">Upload hasil scan logbook yang sudah ditandatangani</p>
                    </div>
                </div>
            </div>
            <div class="p-5 flex flex-col flex-1">

                {{-- Current upload status --}}
                @if($submission->has_logbook)
                <div class="bg-emerald-50/70 border border-emerald-200 rounded-xl p-4 mb-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-xl text-emerald-600">picture_as_pdf</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-bold text-emerald-800">File Terupload</p>
                                <span class="shrink-0 px-2 py-0.5 bg-emerald-600 text-white text-[9px] font-bold uppercase tracking-wider rounded-md flex items-center gap-0.5">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    OK
                                </span>
                            </div>
                            <p class="text-xs text-emerald-600 mt-0.5 truncate">{{ $submission->logbook_original_name }}</p>
                            <p class="text-[10px] text-emerald-500 mt-0.5">{{ $submission->logbook_uploaded_at?->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 mb-4 flex items-center gap-2.5">
                    <span class="material-symbols-outlined text-amber-600 shrink-0 text-[18px]">warning</span>
                    <div>
                        <p class="text-xs font-bold text-amber-800">Belum Ada Logbook</p>
                        <p class="text-[11px] text-amber-600">Upload logbook dalam bentuk PDF.</p>
                    </div>
                </div>
                @endif

                {{-- Upload form --}}
                <form action="{{ route('mahasiswa.skripsi.logbook.upload') }}" method="POST" enctype="multipart/form-data"
                    class="flex flex-col flex-1"
                    x-data="{
                        fileName: '',
                        fileSize: '',
                        dragging: false,
                        handleFile(e) {
                            const file = e.target.files[0];
                            if (file) {
                                this.fileName = file.name;
                                this.fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                            }
                        }
                    }">
                    @csrf
                    <div class="border-2 border-dashed rounded-xl p-6 text-center transition-all duration-200 flex-1 flex flex-col items-center justify-center"
                        :class="dragging ? 'border-emerald-400 bg-emerald-50/50' : (fileName ? 'border-emerald-300 bg-emerald-50/30' : 'border-gray-200 hover:border-gray-300 bg-gray-50/30')"
                        @dragover.prevent="dragging = true"
                        @dragleave.prevent="dragging = false"
                        @drop.prevent="dragging = false; $refs.fileInput.files = $event.dataTransfer.files; handleFile({target: $refs.fileInput})">

                        <template x-if="!fileName">
                            <div>
                                <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                    <span class="material-symbols-outlined text-2xl text-gray-400">cloud_upload</span>
                                </div>
                                <p class="text-xs font-semibold text-gray-700 mb-0.5">Seret & letakkan file PDF</p>
                                <p class="text-[11px] text-gray-400 mb-3">atau klik tombol di bawah</p>
                            </div>
                        </template>

                        <template x-if="fileName">
                            <div>
                                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                    <span class="material-symbols-outlined text-2xl text-emerald-600">picture_as_pdf</span>
                                </div>
                                <p class="text-xs font-bold text-emerald-800 mb-0.5" x-text="fileName"></p>
                                <p class="text-[11px] text-emerald-500" x-text="fileSize"></p>
                            </div>
                        </template>

                        <label class="inline-flex items-center gap-1.5 cursor-pointer mt-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                            <span class="material-symbols-outlined text-[16px] text-gray-500">attach_file</span>
                            <span x-text="fileName ? 'Ganti File' : 'Pilih File PDF'"></span>
                            <input type="file" name="logbook" accept=".pdf" class="hidden" x-ref="fileInput" @change="handleFile($event)">
                        </label>
                    </div>

                    @error('logbook')
                    <p class="text-xs text-red-500 mt-2 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">error</span>
                        {{ $message }}
                    </p>
                    @enderror

                    <div class="flex items-center justify-between mt-4">
                        <div class="text-[11px] text-gray-400 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[13px]">info</span>
                            PDF • Maks 10MB
                        </div>
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-600/20 transition-all hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="!fileName">
                            <span class="material-symbols-outlined text-[18px]">upload</span>
                            {{ $submission->has_logbook ? 'Upload Ulang' : 'Upload Logbook' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════
         PILIHAN LAIN: INPUT LOGBOOK DIGITAL DI WEB (OPSIONAL)
    ════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- LEFT COLUMN: FORM INPUT LOGBOOK (1/3 width) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden lg:col-span-1">
            <div class="bg-gradient-to-r from-[#8B1538] to-[#6D1029] px-5 py-4">
                <div class="flex items-center gap-3 text-white">
                    <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm shrink-0">
                        <span class="material-symbols-outlined text-lg">edit_note</span>
                    </div>
                    <div>
                        <h2 class="font-bold text-sm">Input Logbook Web</h2>
                        <p class="text-[11px] text-red-100 mt-0.5">Input aktivitas bimbingan langsung di sini</p>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('mahasiswa.skripsi.bimbingan.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4"
                x-data="{
                    note: '',
                    fileName: '',
                    fileSize: '',
                    handleFile(e) {
                        const file = e.target.files[0];
                        if (file) {
                            this.fileName = file.name;
                            this.fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                        }
                    }
                }">
                @csrf

                {{-- Tanggal Bimbingan --}}
                <div class="space-y-1">
                    <label for="tanggal_bimbingan" class="text-xs font-black text-gray-700 uppercase tracking-wider block">Tanggal Bimbingan</label>
                    <div class="relative">
                        <input type="date" id="tanggal_bimbingan" name="tanggal_bimbingan" 
                            max="{{ now()->format('Y-m-d') }}"
                            value="{{ old('tanggal_bimbingan', now()->format('Y-m-d')) }}"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:outline-none focus:bg-white focus:border-[#8B1538] focus:ring-1 focus:ring-[#8B1538] transition-all"
                            required>
                    </div>
                    @error('tanggal_bimbingan')
                    <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                        <span class="material-symbols-outlined text-[14px]">error</span>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Catatan Bimbingan --}}
                <div class="space-y-1">
                    <div class="flex justify-between items-center">
                        <label for="catatan" class="text-xs font-black text-gray-700 uppercase tracking-wider block">Catatan Bimbingan</label>
                        <span class="text-[10px] font-bold text-gray-400" :class="note.length < 20 ? 'text-red-500' : 'text-emerald-500'">
                            <span x-text="note.length"></span>/3000 (min 20)
                        </span>
                    </div>
                    <textarea id="catatan" name="catatan" rows="6" x-model="note"
                        placeholder="Tuliskan materi diskusi bimbingan dan arahan dosen pembimbing..."
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:outline-none focus:bg-white focus:border-[#8B1538] focus:ring-1 focus:ring-[#8B1538] transition-all resize-none leading-relaxed"
                        minlength="20" maxlength="3000" required>{{ old('catatan') }}</textarea>
                    @error('catatan')
                    <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                        <span class="material-symbols-outlined text-[14px]">error</span>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- File Lampiran --}}
                <div class="space-y-1">
                    <label class="text-xs font-black text-gray-700 uppercase tracking-wider block">File Lampiran (Opsional)</label>
                    <div class="border border-dashed rounded-xl p-4 text-center transition-all bg-gray-50/50 hover:bg-gray-50"
                        :class="fileName ? 'border-emerald-300 bg-emerald-50/10' : 'border-gray-200'">
                        
                        <template x-if="!fileName">
                            <div class="flex flex-col items-center">
                                <span class="material-symbols-outlined text-gray-400 text-2xl mb-1">upload_file</span>
                                <p class="text-[10px] text-gray-400 mb-2">PDF, DOC, DOCX, JPG, PNG (Maks 5MB)</p>
                            </div>
                        </template>

                        <template x-if="fileName">
                            <div class="mb-2">
                                <p class="text-xs font-bold text-emerald-800 truncate" x-text="fileName"></p>
                                <p class="text-[10px] text-emerald-600" x-text="fileSize"></p>
                            </div>
                        </template>

                        <label class="inline-flex items-center gap-1 cursor-pointer px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-[11px] font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                            <span class="material-symbols-outlined text-[14px] text-gray-500">attach_file</span>
                            <span x-text="fileName ? 'Ganti File' : 'Pilih File'"></span>
                            <input type="file" name="file_bimbingan" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden" @change="handleFile($event)">
                        </label>
                    </div>
                    @error('file_bimbingan')
                    <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                        <span class="material-symbols-outlined text-[14px]">error</span>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 bg-[#8B1538] text-white py-2.5 rounded-xl font-bold text-sm hover:bg-[#6D1029] hover:shadow-lg hover:shadow-[#8B1538]/20 transition-all hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="note.length < 20">
                    <span class="material-symbols-outlined text-[18px]">send</span>
                    Simpan Catatan
                </button>
            </form>
        </div>

        {{-- RIGHT COLUMN: DAFTAR ENTRI LOGBOOK DIGITAL (2/3 width) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden lg:col-span-2">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px] text-gray-400">format_list_bulleted</span>
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-700 text-sm">Daftar Entri Logbook Digital</h2>
                        <p class="text-[11px] text-gray-400">Seluruh riwayat bimbingan yang diinput secara digital</p>
                    </div>
                </div>
                <span class="text-xs bg-[#8B1538]/5 text-[#8B1538] px-3 py-1 rounded-full font-bold">{{ $guidances->count() }} entri</span>
            </div>

            @if($guidances->isEmpty())
            <div class="p-12 text-center flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4 text-gray-300">
                    <span class="material-symbols-outlined text-4xl">inbox</span>
                </div>
                <h3 class="text-sm font-bold text-gray-700 mb-1">Belum Ada Entri Logbook</h3>
                <p class="text-xs text-gray-400 max-w-sm leading-relaxed">
                    Silakan tambahkan catatan bimbingan baru menggunakan form di sebelah kiri untuk merekam sesi bimbingan Anda secara digital.
                </p>
            </div>
            @else
            <div class="divide-y divide-gray-100 max-h-[620px] overflow-y-auto custom-scrollbar">
                @foreach($guidances as $i => $g)
                @php
                    $statusColor = $g->status->color();
                    // Maps standard color names to specific Tailwinds class colors
                    $themeColor = match($statusColor) {
                        'green'  => 'emerald',
                        'yellow' => 'amber',
                        'red'    => 'rose',
                        'blue'   => 'blue',
                        default  => 'gray',
                    };
                @endphp
                <div class="p-5 hover:bg-gray-50/40 transition-all duration-200 relative group">
                    {{-- Status Indicator Line --}}
                    <div class="absolute top-0 left-0 w-1.5 h-full transition-all duration-200
                        @if($g->status->value === 'pending') bg-amber-400
                        @elseif($g->status->value === 'approved') bg-emerald-500
                        @elseif($g->status->value === 'rejected') bg-rose-500
                        @else bg-blue-500
                        @endif">
                    </div>

                    <div class="pl-2 space-y-3">
                        {{-- Top Metadata Row --}}
                        <div class="flex items-center justify-between gap-3 flex-wrap">
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-black text-gray-500">
                                    #{{ $guidances->count() - $i }}
                                </div>
                                <div class="flex items-center gap-1.5 text-gray-500 text-xs font-semibold">
                                    <span class="material-symbols-outlined text-[15px]">calendar_month</span>
                                    <span>{{ $g->tanggal_bimbingan->format('d M Y') }}</span>
                                </div>
                            </div>
                            
                            {{-- Status Badge --}}
                            <span class="px-2.5 py-0.5 border rounded-full text-[10px] font-black uppercase tracking-wider
                                bg-{{ $themeColor }}-50 text-{{ $themeColor }}-700 border-{{ $themeColor }}-100">
                                {{ $g->status->label() }}
                            </span>
                        </div>

                        {{-- Guidance Notes --}}
                        <div class="bg-gray-50/70 border border-gray-100 rounded-xl p-3.5 group-hover:bg-white group-hover:shadow-sm transition-all duration-200">
                            <p class="text-sm font-medium text-gray-700 leading-relaxed whitespace-pre-line">{{ $g->catatan }}</p>
                        </div>

                        {{-- Attachment Section --}}
                        @if($g->file_path)
                        <div class="flex items-center pt-0.5">
                            <a href="{{ route('mahasiswa.skripsi.download', ['type' => 'guidance', 'id' => $g->id]) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#8B1538]/5 text-[#8B1538] hover:bg-[#8B1538] hover:text-white rounded-lg text-xs font-bold transition-all shadow-sm">
                                <span class="material-symbols-outlined text-[16px]">cloud_download</span>
                                <span>Unduh File Bukti</span>
                            </a>
                        </div>
                        @endif

                        {{-- Supervisor Feedback Comment Thread --}}
                        @if($g->catatan_dosen)
                        <div class="bg-blue-50/50 border border-blue-100/50 rounded-xl p-3.5 mt-2 flex gap-3 items-start">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center text-white text-xs font-bold shrink-0 shadow-sm">
                                {{ substr($submission->approvedSupervisor?->nama ?? 'D', 0, 1) }}
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-xs font-bold text-blue-900 leading-none">{{ $submission->approvedSupervisor?->nama ?? 'Dosen Pembimbing' }}</h4>
                                    <span class="text-[9px] bg-blue-100 text-blue-800 font-bold px-1.5 py-0.5 rounded uppercase tracking-wider scale-90">Feedback</span>
                                </div>
                                <p class="text-xs text-blue-800 font-medium leading-relaxed whitespace-pre-line mt-1">{{ $g->catatan_dosen }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>

</div>
@endsection
