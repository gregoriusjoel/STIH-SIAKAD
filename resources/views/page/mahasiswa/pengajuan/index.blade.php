@extends('layouts.mahasiswa')

@section('title', 'Pengajuan Mahasiswa')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        {{-- Page Header --}}
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl font-bold text-text-primary">Pengajuan Mahasiswa</h1>
                <p class="text-sm text-text-secondary mt-1">Ajukan cuti atau surat keterangan aktif kuliah di sini.</p>
            </div>
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <button @click="$dispatch('open-modal', 'pengajuan-modal')" 
                    class="btn bg-maroon text-white hover:bg-maroon-hover">
                    <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="hidden xs:block ml-2">Buat Pengajuan</span>
                </button>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Total Pengajuan --}}
            <div class="bg-bg-card border border-border-color rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-text-secondary uppercase">Total Pengajuan</h3>
                    <div class="p-2 bg-blue-100 rounded-full">
                        <i class="fas fa-file-alt text-blue-600"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold text-text-primary">{{ $pengajuans->count() }}</div>
            </div>

            {{-- Menunggu Persetujuan --}}
            <div class="bg-bg-card border border-border-color rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-text-secondary uppercase">Menunggu</h3>
                    <div class="p-2 bg-yellow-100 rounded-full">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold text-text-primary">{{ $pengajuans->where('status', 'pending')->count() }}</div>
            </div>

            {{-- Disetujui --}}
            <div class="bg-bg-card border border-border-color rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-text-secondary uppercase">Disetujui</h3>
                    <div class="p-2 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold text-text-primary">{{ $pengajuans->where('status', 'disetujui')->count() }}</div>
            </div>
        </div>

        {{-- History Table --}}
        <div class="bg-bg-card border border-border-color rounded-xl shadow-sm overflow-hidden">
            <header class="px-5 py-4 border-b border-border-color">
                <h2 class="font-semibold text-text-primary">Riwayat Pengajuan</h2>
            </header>
            <div class="p-3">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                        <thead class="text-xs font-semibold uppercase text-text-secondary bg-bg-hover">
                            <tr>
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-left">Jenis</div>
                                </th>
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-left">Tanggal</div>
                                </th>
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-left">Keterangan</div>
                                </th>
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-center">Status</div>
                                </th>
                                <th class="p-2 whitespace-nowrap">
                                    <div class="font-semibold text-left">Catatan Admin</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-border-color">
                            @forelse ($pengajuans as $p)
                                <tr class="hover:bg-bg-hover/50 transition duration-150">
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="font-medium text-text-primary">
                                                {{ $p->jenis == 'cuti' ? 'Cuti Akademik' : 'Surat Keterangan Aktif' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-text-secondary">{{ $p->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-text-secondary line-clamp-2 max-w-xs">{{ $p->keterangan }}</div>
                                    </td>
                                    <td class="p-2 whitespace-nowrap">
                                        <div class="text-center">
                                            @if($p->status == 'pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @elseif($p->status == 'disetujui')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Disetujui
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Ditolak
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-2">
                                        <div class="text-text-muted italic">{{ $p->admin_note ?? '-' }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-4 text-center text-text-secondary">
                                        Belum ada pengajuan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div x-data="{ open: false }" 
         x-show="open" 
         @open-modal.window="if ($event.detail === 'pengajuan-modal') open = true" 
         @keydown.escape.window="open = false"
         class="relative z-50" 
         style="display: none;">
        
        <div class="fixed inset-0 bg-black/50 transition-opacity" x-show="open" x-transition.opacity></div>

        <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="open"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     @click.outside="open = false"
                     class="relative transform overflow-hidden rounded-lg bg-bg-card text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-border-color">
                    
                    <form action="{{ route('mahasiswa.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-bg-card px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-maroon/10 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-pen-nib text-maroon"></i>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-base font-semibold leading-6 text-text-primary" id="modal-title">Buat Pengajuan Baru</h3>
                                    <div class="mt-4 space-y-4">
                                        
                                        {{-- Jenis Pengajuan --}}
                                        <div>
                                            <label for="jenis" class="block text-sm font-medium leading-6 text-text-primary">Jenis Pengajuan</label>
                                            <select id="jenis" name="jenis" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-text-primary bg-bg-input ring-1 ring-inset ring-border-color focus:ring-2 focus:ring-maroon sm:text-sm sm:leading-6">
                                                <option value="surat_aktif">Surat Keterangan Aktif Kuliah</option>
                                                <option value="cuti">Cuti Akademik</option>
                                            </select>
                                        </div>

                                        {{-- Keterangan --}}
                                        <div>
                                            <label for="keterangan" class="block text-sm font-medium leading-6 text-text-primary">Keterangan / Alasan</label>
                                            <div class="mt-2">
                                                <textarea id="keterangan" name="keterangan" rows="3" class="block w-full rounded-md border-0 py-1.5 text-text-primary bg-bg-input ring-1 ring-inset ring-border-color placeholder:text-text-muted focus:ring-2 focus:ring-maroon sm:text-sm sm:leading-6" required placeholder="Jelaskan keperluan pengajuan Anda..."></textarea>
                                            </div>
                                        </div>

                                        {{-- File Pendukung --}}
                                        <div x-data="{ fileName: '' }">
                                            <label for="file_pendukung" class="block text-sm font-medium leading-6 text-text-primary">Dokumen Pendukung (Opsional)</label>
                                            <div class="mt-2 flex justify-center rounded-lg border border-dashed border-border-color px-6 py-6 hover:bg-bg-hover transition-colors cursor-pointer" @click="$refs.fileInput.click()">
                                                <div class="text-center">
                                                    <i class="fas fa-cloud-upload-alt text-3xl text-text-muted mb-2"></i>
                                                    <div class="mt-1 flex text-sm leading-6 text-text-secondary justify-center">
                                                        <span class="relative cursor-pointer rounded-md bg-transparent font-semibold text-maroon focus-within:outline-none focus-within:ring-2 focus-within:ring-maroon focus-within:ring-offset-2 hover:text-maroon-hover">
                                                            <span>Upload a file</span>
                                                            <input id="file_pendukung" name="file_pendukung" type="file" class="sr-only" x-ref="fileInput" @change="fileName = $refs.fileInput.files[0].name">
                                                        </span>
                                                        <p class="pl-1">or drag and drop</p>
                                                    </div>
                                                    <p class="text-xs leading-5 text-text-muted">PDF, PNG, JPG up to 2MB</p>
                                                    <p x-show="fileName" x-text="fileName" class="text-sm text-maroon mt-2 font-medium"></p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-maroon px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-maroon-hover sm:ml-3 sm:w-auto">Kirim Pengajuan</button>
                            <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="open = false">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
