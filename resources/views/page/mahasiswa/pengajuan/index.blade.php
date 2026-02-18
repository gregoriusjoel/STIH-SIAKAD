@extends('layouts.mahasiswa')

@section('title', 'Pengajuan Mahasiswa')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto font-inter">

        {{-- Page Header --}}
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl font-bold text-text-primary tracking-tight">Pengajuan Mahasiswa</h1>
                <p class="text-sm text-text-secondary mt-1">Ajukan cuti akademik atau surat keterangan aktif kuliah dengan mudah.</p>
            </div>
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <button @click="$dispatch('open-modal', 'pengajuan-modal')" 
                    class="group relative flex items-center h-10 bg-primary text-white rounded-full transition-[width] duration-300 ease-in-out w-10 hover:w-50 overflow-hidden shadow-lg active:scale-95">
                    {{-- Icon Container --}}
                    <div class="flex items-center justify-center w-10 h-10 flex-shrink-0">
                        <i class="fas fa-plus"></i>
                    </div>
                    
                    {{-- Sliding Text --}}
                    <span class="opacity-0 group-hover:opacity-100 transform translate-x-4 group-hover:translate-x-0 transition-all duration-300 ease-in-out font-bold text-sm whitespace-nowrap pr-4">
                        Buat Pengajuan Baru
                    </span>
                </button>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Total Pengajuan --}}
            <div class="relative overflow-hidden bg-white dark:bg-bg-card rounded-2xl p-6 shadow-sm border border-border-color group hover:border-blue-500/30 transition-colors">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xs font-bold text-blue-600 uppercase tracking-wider bg-blue-50 dark:bg-blue-900/20 px-2 py-1 rounded-md">Total Pengajuan</h3>
                    </div>
                    <div class="text-3xl font-bold text-text-primary group-hover:scale-105 transition-transform origin-left">{{ $allPengajuans->count() }}</div>
                    <p class="text-xs text-text-muted mt-1">Seluruh riwayat pengajuan</p>
                </div>
                <div class="absolute right-0 bottom-0 opacity-[0.03] transform translate-y-2 translate-x-2 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-file-alt text-9xl text-blue-600"></i>
                </div>
            </div>

            {{-- Menunggu Persetujuan --}}
            <div class="relative overflow-hidden bg-white dark:bg-bg-card rounded-2xl p-6 shadow-sm border border-border-color group hover:border-yellow-500/30 transition-colors">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xs font-bold text-yellow-600 uppercase tracking-wider bg-yellow-50 dark:bg-yellow-900/20 px-2 py-1 rounded-md">Menunggu</h3>
                    </div>
                    <div class="text-3xl font-bold text-text-primary group-hover:scale-105 transition-transform origin-left">{{ $allPengajuans->where('status', 'pending')->count() }}</div>
                    <p class="text-xs text-text-muted mt-1">Sedang diproses admin</p>
                </div>
                <div class="absolute right-0 bottom-0 opacity-[0.03] transform translate-y-2 translate-x-2 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-clock text-9xl text-yellow-600"></i>
                </div>
            </div>

            {{-- Disetujui --}}
            <div class="relative overflow-hidden bg-white dark:bg-bg-card rounded-2xl p-6 shadow-sm border border-border-color group hover:border-green-500/30 transition-colors">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xs font-bold text-green-600 uppercase tracking-wider bg-green-50 dark:bg-green-900/20 px-2 py-1 rounded-md">Disetujui</h3>
                    </div>
                    <div class="text-3xl font-bold text-text-primary group-hover:scale-105 transition-transform origin-left">{{ $allPengajuans->where('status', 'disetujui')->count() }}</div>
                    <p class="text-xs text-text-muted mt-1">Permohonan diterima</p>
                </div>
                <div class="absolute right-0 bottom-0 opacity-[0.03] transform translate-y-2 translate-x-2 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-check-circle text-9xl text-green-600"></i>
                </div>
            </div>
        </div>

        {{-- History Table --}}
        <div class="bg-white dark:bg-bg-card border border-border-color rounded-2xl shadow-sm overflow-hidden">
            <header class="px-6 py-5 border-b border-border-color flex items-center justify-between bg-gray-50/30 dark:bg-transparent">
                <h2 class="font-bold text-text-primary text-lg">Riwayat Pengajuan</h2>
                <div class="text-xs text-text-muted">Menampilkan {{ $pengajuans->count() }} data terakhir</div>
            </header>
            <div class="overflow-x-auto">
                <table class="table-auto w-full" style="min-width: 900px;">
                    <thead class="bg-gray-50/50 dark:bg-bg-hover/30 border-b border-border-color">
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-text-secondary text-left w-1/6">Jenis Pengajuan</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-text-secondary text-left w-1/8">Tanggal</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-text-secondary text-left w-1/4">Keterangan</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-text-secondary text-center w-1/8">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-text-secondary text-left w-1/6">Catatan Admin</th>
                            <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-text-secondary text-center w-1/8">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-color bg-white dark:bg-bg-card">
                        @forelse ($pengajuans as $p)
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-bg-hover/40 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0
                                            {{ $p->jenis == 'cuti' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }}">
                                            <i class="fas {{ $p->jenis == 'cuti' ? 'fa-pause' : 'fa-file-signature' }} text-sm"></i>
                                        </div>
                                        <span class="font-medium text-text-primary text-sm">
                                            {{ $p->jenis == 'cuti' ? 'Cuti Akademik' : 'Surat Keterangan Aktif' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-text-secondary font-medium">{{ $p->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-text-muted">{{ $p->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-text-secondary line-clamp-2 max-w-sm group-hover:text-text-primary transition-colors">
                                        {{ $p->keterangan }}
                                    </div>
                                    @if($p->file_path)
                                        <a href="{{ Storage::url($p->file_path) }}" target="_blank" class="inline-flex items-center gap-1 mt-1 text-xs text-primary hover:underline">
                                            <i class="fas fa-paperclip"></i> Lihat Dokumen
                                        </a>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($p->status == 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-600 animate-pulse"></span>
                                            Menunggu
                                        </span>
                                    @elseif($p->status == 'disetujui')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20">
                                            <i class="fas fa-check-circle text-[10px]"></i>
                                            Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20">
                                            <i class="fas fa-times-circle text-[10px]"></i>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($p->admin_note)
                                        <div class="text-sm text-text-secondary italic bg-gray-50 dark:bg-bg-hover rounded-md px-3 py-2 border border-border-color/50">
                                            "{{ $p->admin_note }}"
                                        </div>
                                    @else
                                        <span class="text-xs text-text-muted">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                <td class="px-6 py-4 text-center">
                                    @if($p->status === 'disetujui' && $p->file_surat)
                                        <div class="flex items-center justify-center gap-2">
                                            <button @click="$dispatch('open-preview-modal', '{{ route('mahasiswa.pengajuan.preview', $p->id) }}')" 
                                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs font-medium transition-colors shadow-sm">
                                                <i class="fas fa-eye"></i>
                                                <span class="hidden sm:inline">Lihat</span>
                                            </button>
                                            
                                            <a href="{{ route('mahasiswa.pengajuan.download', $p->id) }}" 
                                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 text-xs font-medium transition-colors shadow-sm">
                                                <i class="fas fa-download"></i>
                                                <span class="hidden sm:inline">Unduh</span>
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-xs text-text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-text-secondary">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-bg-hover rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-inbox text-2xl text-text-muted"></i>
                                        </div>
                                        <h3 class="text-base font-medium text-text-primary">Belum ada pengajuan</h3>
                                        <p class="text-sm text-text-muted mt-1 max-w-xs mx-auto">Anda belum pernah membuat pengajuan surat atau cuti. Mulai dengan klik tombol di atas.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($pengajuans->hasPages())
                <div class="border-t border-border-color px-6 py-4 bg-gray-50/50">
                    {{ $pengajuans->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Create Modal --}}
    <div x-data="{ open: false }" 
         x-show="open" 
         @open-modal.window="if ($event.detail === 'pengajuan-modal') open = true" 
         @keydown.escape.window="open = false"
         class="relative z-50" 
         style="display: none;">
        
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" 
             x-show="open" 
             x-transition.opacity></div>

        <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="open"
                     x-transition:enter="ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     @click.outside="open = false"
                     class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-bg-card text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-border-color">
                    
                    {{-- Modal Header --}}
                    <div class="bg-primary/5 px-6 py-5 border-b border-primary/10 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center shrink-0 text-primary">
                            <i class="fas fa-pen-nib text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-text-primary" id="modal-title">Buat Pengajuan Baru</h3>
                            <p class="text-xs text-text-secondary mt-0.5">Isi formulir berikut untuk mengajukan permohonan.</p>
                        </div>
                        <button @click="open = false" class="ml-auto text-text-muted hover:text-text-primary transition-colors">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>

                    <form action="{{ route('mahasiswa.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="px-6 py-6 space-y-6">
                            
                            {{-- Jenis Pengajuan --}}
                            <div class="space-y-2">
                                <label for="jenis" class="block text-sm font-semibold text-text-primary">Jenis Pengajuan <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select id="jenis" name="jenis" class="block w-full rounded-xl border-border-color py-3 pl-4 pr-10 text-text-primary bg-bg-input focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm transition-shadow shadow-sm appearance-none">
                                        <option value="surat_aktif">Surat Keterangan Aktif Kuliah</option>
                                        <option value="cuti">Cuti Akademik</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-text-muted">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- Keterangan --}}
                            <div class="space-y-2">
                                <label for="keterangan" class="block text-sm font-semibold text-text-primary">Keterangan / Alasan <span class="text-red-500">*</span></label>
                                <textarea id="keterangan" name="keterangan" rows="4" 
                                    class="block w-full rounded-xl border-border-color py-3 text-text-primary bg-bg-input focus:ring-2 focus:ring-primary focus:border-primary placeholder:text-text-muted sm:text-sm shadow-sm transition-shadow resize-none" 
                                    required placeholder="Contoh: Saya mengajukan surat aktif kuliah untuk keperluan beasiswa..."></textarea>
                            </div>

                            {{-- File Pendukung --}}
                            <div x-data="{ fileName: '' }">
                                <label class="block text-sm font-semibold text-text-primary mb-2">Dokumen Pendukung <span class="font-normal text-text-muted text-xs">(Opsional)</span></label>
                                <div class="group relative flex justify-center rounded-xl border-2 border-dashed border-border-color px-6 py-8 hover:bg-gray-50 dark:hover:bg-bg-hover hover:border-primary/50 transition-all cursor-pointer" @click="$refs.fileInput.click()">
                                    <div class="text-center space-y-2">
                                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                                            <i class="fas fa-cloud-upload-alt text-2xl text-primary"></i>
                                        </div>
                                        <div class="flex text-sm text-text-secondary justify-center">
                                            <span class="font-semibold text-primary hover:text-primary-hover">Upload file</span>
                                            <span class="pl-1">atau drag & drop</span>
                                        </div>
                                        <p class="text-xs text-text-muted">PDF, PNG, JPG (Maks. 2MB)</p>
                                        <p x-show="fileName" x-text="fileName" class="text-sm text-primary font-medium bg-primary/5 py-1 px-3 rounded-md mt-2 inline-block border border-primary/20"></p>
                                    </div>
                                    <input id="file_pendukung" name="file_pendukung" type="file" class="sr-only" x-ref="fileInput" @change="fileName = $refs.fileInput.files[0].name">
                                </div>
                            </div>

                        </div>

                        {{-- Footer Actions --}}
                        <div class="bg-gray-50 dark:bg-bg-hover/30 px-6 py-4 flex flex-col sm:flex-row-reverse sm:gap-3 gap-3 border-t border-border-color">
                            <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-primary-hover sm:w-auto transition-all transform hover:scale-[1.02] focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                <i class="fas fa-paper-plane mr-2 mt-0.5"></i> Kirim Pengajuan
                            </button>
                            <button type="button" class="inline-flex w-full justify-center rounded-xl bg-white dark:bg-bg-card px-5 py-2.5 text-sm font-semibold text-text-secondary shadow-sm ring-1 ring-inset ring-border-color hover:bg-gray-50 dark:hover:bg-bg-hover sm:mt-0 sm:w-auto transition-colors" @click="open = false">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Preview Modal --}}
    <div x-data="{ openConfig: false, previewUrl: '' }" 
         @open-preview-modal.window="openConfig = true; previewUrl = $event.detail"
         @keydown.escape.window="openConfig = false"
         style="display: none;"
         x-show="openConfig"
         class="relative z-[60]">
        
        <div class="fixed inset-0 bg-gray-900/90 backdrop-blur-md transition-opacity" 
             x-show="openConfig" x-transition.opacity></div>

        <div class="fixed inset-0 z-[60] overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                <div x-show="openConfig" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.outside="openConfig = false"
                     class="relative w-full max-w-5xl h-[85vh] bg-transparent rounded-xl overflow-hidden shadow-2xl flex flex-col">
                    
                    {{-- Header / Close --}}
                    <div class="absolute top-0 right-0 p-4 z-50">
                        <button @click="openConfig = false" class="text-white/70 hover:text-white transition-colors bg-black/20 rounded-full p-2 hover:bg-black/40">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    {{-- Loading State --}}
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
                         <i class="fas fa-circle-notch fa-spin text-4xl text-white/30"></i>
                    </div>

                    {{-- Iframe --}}
                    <iframe :src="previewUrl" class="w-full h-full bg-white relative z-10"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection
