@extends('layouts.admin')

@section('title', 'Detail Pengajuan')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full font-inter">

        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('admin.pengajuan.index') }}" 
                class="inline-flex items-center gap-2 text-text-secondary hover:text-text-primary transition-colors">
                <i class="fas fa-arrow-left"></i>
                <span class="font-medium">Kembali ke Daftar</span>
            </a>
        </div>

        {{-- Header --}}
        <div class="bg-white dark:bg-bg-card rounded-2xl shadow-sm border border-border-color p-6 mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-text-primary mb-2">Detail Pengajuan #{{ $pengajuan->id }}</h1>
                    <p class="text-text-secondary">Diajukan pada {{ $pengajuan->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm') }}</p>
                </div>
                <div>
                    {!! $pengajuan->status_badge !!}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Pengajuan Info --}}
                <div class="bg-white dark:bg-bg-card rounded-2xl shadow-sm border border-border-color overflow-hidden">
                    <div class="px-6 py-4 bg-primary/5 border-b border-primary/10">
                        <h2 class="font-bold text-text-primary">Informasi Pengajuan</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-text-muted uppercase mb-1">Jenis Pengajuan</label>
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium 
                                {{ $pengajuan->jenis === 'cuti' ? 'bg-orange-50 text-orange-700' : 'bg-blue-50 text-blue-700' }}">
                                <i class="fas {{ $pengajuan->jenis === 'cuti' ? 'fa-pause' : 'fa-file-signature' }}"></i>
                                {{ $pengajuan->jenis_label }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-text-muted uppercase mb-1">Keterangan / Alasan</label>
                            <p class="text-text-primary bg-gray-50 dark:bg-bg-hover rounded-lg p-4 leading-relaxed">
                                {{ $pengajuan->keterangan }}
                            </p>
                        </div>

                        @if($pengajuan->file_path)
                            <div>
                                <label class="block text-xs font-semibold text-text-muted uppercase mb-2">Dokumen Pendukung</label>
                                <a href="{{ Storage::url($pengajuan->file_path) }}" target="_blank" 
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium text-text-primary transition-colors">
                                    <i class="fas fa-paperclip text-primary"></i>
                                    Lihat Dokumen
                                </a>
                            </div>
                        @endif

                        @if($pengajuan->nomor_surat)
                            <div>
                                <label class="block text-xs font-semibold text-text-muted uppercase mb-1">Nomor Surat</label>
                                <p class="text-text-primary font-mono font-semibold">{{ $pengajuan->nomor_surat }}</p>
                            </div>
                        @endif

                        @if($pengajuan->admin_note)
                            <div>
                                <label class="block text-xs font-semibold text-text-muted uppercase mb-1">Catatan Admin</label>
                                <p class="text-text-primary bg-yellow-50 border-l-4 border-yellow-400 rounded p-4 italic">
                                    "{{ $pengajuan->admin_note }}"
                                </p>
                            </div>
                        @endif

                        @if($pengajuan->approved_by)
                            <div class="pt-4 border-t border-border-color">
                                <label class="block text-xs font-semibold text-text-muted uppercase mb-2">Informasi Approval</label>
                                <div class="flex items-center gap-3 text-sm">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">
                                        {{ substr($pengajuan->approver->name ?? '', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-text-primary">{{ $pengajuan->approver->name ?? 'Admin' }}</p>
                                        <p class="text-xs text-text-muted">{{ $pengajuan->approved_at->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Letter Preview (if approved) --}}
                @if($pengajuan->status === 'disetujui' && $pengajuan->file_surat)
                    <div class="bg-white dark:bg-bg-card rounded-2xl shadow-sm border border-green-200">
                        <div class="px-6 py-4 bg-green-50 border-b border-green-200">
                            <h2 class="font-bold text-green-800 flex items-center gap-2">
                                <i class="fas fa-file-pdf"></i>
                                Surat Hasil Generate
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex gap-3">
                                <a href="{{ route('admin.pengajuan.download', $pengajuan->id) }}" 
                                    class="btn bg-green-600 text-white hover:bg-green-700 rounded-xl px-5 py-2.5 flex items-center gap-2">
                                    <i class="fas fa-download"></i>
                                    Download Surat PDF
                                </a>
                                <a href="{{ route('admin.pengajuan.preview', $pengajuan->id) }}" target="_blank"
                                    class="btn bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl px-5 py-2.5 flex items-center gap-2">
                                    <i class="fas fa-eye"></i>
                                    Preview
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                
                {{-- Student Info --}}
                <div class="bg-white dark:bg-bg-card rounded-2xl shadow-sm border border-border-color overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-border-color">
                        <h2 class="font-bold text-text-primary">Data Mahasiswa</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4 pb-4 border-b border-border-color">
                            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-lg">
                                {{ substr($pengajuan->mahasiswa->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-text-primary">{{ $pengajuan->mahasiswa->user->name }}</h3>
                                <p class="text-xs text-text-muted">{{ $pengajuan->mahasiswa->nim }}</p>
                            </div>
                        </div>
                        
                        <div class="space-y-3 text-sm">
                            <div>
                                <label class="block text-xs font-semibold text-text-muted mb-0.5">Program Studi</label>
                                <p class="text-text-primary">{{ $pengajuan->mahasiswa->prodi ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-text-muted mb-0.5">Fakultas</label>
                                <p class="text-text-primary">{{ $pengajuan->mahasiswa->fakultas ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-text-muted mb-0.5">Semester</label>
                                <p class="text-text-primary">{{ $pengajuan->mahasiswa->semester ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-text-muted mb-0.5">Email</label>
                                <p class="text-text-primary text-xs">{{ $pengajuan->mahasiswa->user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                @if($pengajuan->status === 'pending')
                    <div class="bg-white dark:bg-bg-card rounded-2xl shadow-sm border border-border-color overflow-hidden" x-data>
                        <div class="px-6 py-4 bg-yellow-50 border-b border-yellow-200">
                            <h2 class="font-bold text-yellow-800">Tindakan</h2>
                        </div>
                        <div class="p-6 space-y-3">
                            <button @click="$dispatch('open-modal', 'approve-modal')" 
                                class="w-full btn bg-green-600 text-white hover:bg-green-700 rounded-xl px-5 py-3 flex items-center justify-center gap-2 font-medium">
                                <i class="fas fa-check-circle"></i>
                                Setujui & Generate Surat
                            </button>
                            <button @click="$dispatch('open-modal', 'reject-modal')" 
                                class="w-full btn bg-red-600 text-white hover:bg-red-700 rounded-xl px-5 py-3 flex items-center justify-center gap-2 font-medium">
                                <i class="fas fa-times-circle"></i>
                                Tolak Pengajuan
                            </button>
                            <a href="{{ route('admin.pengajuan.preview', $pengajuan->id) }}" target="_blank"
                                class="w-full btn bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-xl px-5 py-3 flex items-center justify-center gap-2 font-medium">
                                <i class="fas fa-eye"></i>
                                Preview Surat
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Approve Modal --}}
    <div x-data="{ open: false }" 
         x-show="open" 
         @open-modal.window="if ($event.detail === 'approve-modal') open = true" 
         @keydown.escape.window="open = false"
         class="relative z-50" 
         style="display: none;">
        
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" x-show="open" x-transition.opacity></div>

        <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="open"
                     x-transition
                     @click.outside="open = false"
                     class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-bg-card shadow-2xl w-full max-w-md border border-green-200">
                    
                    <div class="bg-green-50 px-6 py-5 border-b border-green-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-green-600 flex items-center justify-center text-white">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-green-900">Setujui Pengajuan</h3>
                                <p class="text-xs text-green-700">Surat akan otomatis digenerate</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.pengajuan.approve', $pengajuan->id) }}" method="POST">
                        @csrf
                        <div class="p-6">
                            <p class="text-text-secondary mb-4">Anda yakin ingin menyetujui pengajuan ini? Sistem akan otomatis membuat surat resmi.</p>
                            
                            <div>
                                <label class="block text-sm font-medium text-text-secondary mb-2">Catatan (Opsional)</label>
                                <textarea name="admin_note" rows="3" 
                                    class="w-full rounded-xl border-border-color py-2.5 px-4 text-sm focus:ring-2 focus:ring-green-500"
                                    placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex gap-3 border-t border-border-color">
                            <button type="submit" class="flex-1 btn bg-green-600 text-white hover:bg-green-700 rounded-xl px-5 py-2.5 font-semibold">
                                <i class="fas fa-check mr-2"></i> Ya, Setujui
                            </button>
                            <button type="button" @click="open = false" class="flex-1 btn bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl px-5 py-2.5 font-semibold">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div x-data="{ open: false }" 
         x-show="open" 
         @open-modal.window="if ($event.detail === 'reject-modal') open = true" 
         @keydown.escape.window="open = false"
         class="relative z-50" 
         style="display: none;">
        
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" x-show="open" x-transition.opacity></div>

        <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="open"
                     x-transition
                     @click.outside="open = false"
                     class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-bg-card shadow-2xl w-full max-w-md border border-red-200">
                    
                    <div class="bg-red-50 px-6 py-5 border-b border-red-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-red-600 flex items-center justify-center text-white">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-red-900">Tolak Pengajuan</h3>
                                <p class="text-xs text-red-700">Mahasiswa akan menerima notifikasi</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.pengajuan.reject', $pengajuan->id) }}" method="POST">
                        @csrf
                        <div class="p-6">
                            <p class="text-text-secondary mb-4">Berikan alasan penolakan yang jelas agar mahasiswa memahami keputusan Anda.</p>
                            
                            <div>
                                <label class="block text-sm font-medium text-text-secondary mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                                <textarea name="admin_note" rows="4" required
                                    class="w-full rounded-xl border-border-color py-2.5 px-4 text-sm focus:ring-2 focus:ring-red-500"
                                    placeholder="Contoh: Dokumen pendukung tidak lengkap..."></textarea>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex gap-3 border-t border-border-color">
                            <button type="submit" class="flex-1 btn bg-red-600 text-white hover:bg-red-700 rounded-xl px-5 py-2.5 font-semibold">
                                <i class="fas fa-times mr-2"></i> Ya, Tolak
                            </button>
                            <button type="button" @click="open = false" class="flex-1 btn bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl px-5 py-2.5 font-semibold">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
