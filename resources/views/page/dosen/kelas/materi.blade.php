@extends('layouts.app')

@section('title', 'Materi Pertemuan')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
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
            <a href="{{ route('dosen.kelas.detail', $kelas->id) }}" class="hover:text-white transition-all duration-300">Detail
                Kelas</a>
            <span class="material-symbols-outlined text-[10px] text-white/20 font-normal">play_arrow</span>
            <a href="{{ route('dosen.kelas.pertemuan.detail', ['id' => $kelas->id, 'pertemuan' => $meeting['no']]) }}"
                class="hover:text-white transition-all duration-300 uppercase tracking-wider text-[13px]">{{ $meeting['label'] }}</a>
            <span class="material-symbols-outlined text-[10px] text-white/20 font-normal">play_arrow</span>
            <span class="text-white font-black text-[13px] uppercase tracking-wider">Materi</span>
        </nav>
    @endsection

    <div class="px-4 py-6 max-w-[1200px] mx-auto">


        {{-- HEADER --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="size-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-3xl">folder_open</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Materi {{ $meeting['label'] }}</h1>
                        <p class="text-gray-500">{{ $kelas->mataKuliah->nama_mk }} - Kelas {{ $kelas->section }}</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button x-data @click="$dispatch('open-upload-materi')"
                        class="px-5 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">upload_file</span>
                        Upload Materi
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6 pt-6 border-t border-gray-100">
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
            </div>
        </div>

        {{-- MATERIALS LIST --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 text-lg">Daftar Materi</h3>
                @if(isset($materis) && $materis->count() > 0)
                    <span class="px-3 py-1 rounded-full bg-primary/10 text-xs font-bold text-primary">
                        {{ $materis->count() }} Materi
                    </span>
                @endif
            </div>

            @if(isset($materis) && $materis->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($materis as $materi)
                        <div class="p-6 hover:bg-gray-50/50 transition-colors">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-4 flex-1">
                                    <div class="size-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                        @php
                                            $iconMap = [
                                                'pdf' => 'picture_as_pdf',
                                                'doc' => 'description',
                                                'docx' => 'description',
                                                'ppt' => 'slideshow',
                                                'pptx' => 'slideshow',
                                                'xls' => 'table_chart',
                                                'xlsx' => 'table_chart',
                                                'zip' => 'folder_zip',
                                                'rar' => 'folder_zip',
                                            ];
                                            $icon = $iconMap[$materi->file_type] ?? 'insert_drive_file';
                                        @endphp
                                        <span class="material-symbols-outlined text-2xl">{{ $icon }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-800 mb-1">{{ $materi->judul }}</h4>
                                        @if($materi->deskripsi)
                                            <p class="text-sm text-gray-600 mb-2">{{ $materi->deskripsi }}</p>
                                        @endif
                                        <div class="flex items-center gap-4 text-xs text-gray-500">
                                            <span class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">person</span>
                                                {{ $materi->dosen->user->name ?? 'Dosen' }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                                {{ $materi->created_at->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">folder</span>
                                                {{ $materi->file_size_human }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('dosen.materi.download', $materi->id) }}" target="_blank"
                                        class="px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-all text-sm font-medium flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[18px]">download</span>
                                        Download
                                    </a>
                                    <form action="{{ route('dosen.kelas.pertemuan.materi.destroy', ['id' => $kelas->id, 'pertemuan' => $meeting['no'], 'materi' => $materi->id]) }}" 
                                          method="POST" 
                                          onsubmit="event.preventDefault(); showDeleteConfirm('materi', () => this.submit());">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-all text-sm font-medium">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center bg-gray-50/50">
                    <div class="size-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-3xl">folder_off</span>
                    </div>
                    <h3 class="font-bold text-gray-700">Belum Ada Materi</h3>
                    <p class="text-gray-500 text-sm mt-1 max-w-xs mx-auto">
                        Anda belum mengunggah materi apapun untuk pertemuan ini. Silakan klik tombol "Upload Materi" untuk
                        menambahkan file.
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- UPLOAD MATERI MODAL --}}
    <div x-data="{ open: false }" 
         @open-upload-materi.window="open = true"
         @close-upload-materi.window="open = false"
         @keydown.escape.window="open = false"
         x-show="open"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" @click="open = false"></div>

            <!-- Modal content - add @click.stop to prevent closing when clicking inside -->
            <div class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl relative z-10"
                 @click.stop>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Upload Materi</h3>
                    <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form action="{{ route('dosen.kelas.pertemuan.materi.store', ['id' => $kelas->id, 'pertemuan' => $meeting['no']]) }}" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Materi *</label>
                            <input type="text" name="judul" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Contoh: Pengenalan Hukum Tata Negara">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi (Opsional)</label>
                            <textarea name="deskripsi" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                      placeholder="Jelaskan singkat tentang materi ini..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">File Materi *</label>
                            <input type="file" name="file" required accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">
                                Format: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, ZIP, RAR (Max: 10MB)
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                        <button type="button" @click="open = false"
                                class="px-5 py-2.5 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition font-medium">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-hover transition font-bold shadow-lg shadow-primary/20">
                            Upload Materi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            window.location.reload();
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
@endpush