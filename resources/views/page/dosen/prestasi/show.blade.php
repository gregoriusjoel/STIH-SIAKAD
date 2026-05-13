@extends('layouts.app')

@section('title', 'Detail ' . ($prestasi->tipe === 'pelaporan' ? 'Laporan Prestasi' : 'Pengajuan Kegiatan'))
@section('page-title', 'Detail ' . ($prestasi->tipe === 'pelaporan' ? 'Pelaporan' : 'Pengajuan'))

@section('content')
<div class="px-4 py-6 max-w-[1600px] mx-auto font-inter" x-data="{ showUploadModal: false }">

    {{-- Back + Flash --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">
        <a href="{{ route('dosen.prestasi.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-[#7a1621] transition-colors group w-fit">
            <span class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center border border-gray-100 group-hover:border-red-100 group-hover:bg-red-50 transition-colors">
                <i class="fas fa-arrow-left text-xs"></i>
            </span>
            Kembali ke Daftar
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50/80 border border-green-200/60 rounded-2xl flex items-start gap-3 shadow-sm">
            <span class="material-symbols-outlined text-green-600 mt-0.5">check_circle</span>
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50/80 border border-red-200/60 rounded-2xl flex items-start gap-3 shadow-sm">
            <span class="material-symbols-outlined text-red-600 mt-0.5">error</span>
            <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Hero Header Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 relative overflow-hidden mb-6">
        <div class="absolute top-0 right-0 -mt-8 -mr-8 w-40 h-40 bg-gradient-to-br from-[#7a1621]/5 to-transparent rounded-full blur-3xl pointer-events-none"></div>
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6">
            <div class="flex items-start gap-5">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 flex items-center justify-center shrink-0 text-gray-400">
                    <span class="material-symbols-outlined text-4xl">emoji_events</span>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold tracking-widest uppercase bg-gray-100 text-gray-600">{{ ucfirst($prestasi->tipe) }}</span>
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold tracking-widest uppercase bg-blue-50 text-blue-600">{{ $prestasi->tingkat_label }}</span>
                        @if($prestasi->pengaju_type === \App\Models\Mahasiswa::class)
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold tracking-widest uppercase bg-purple-50 text-purple-600">Mahasiswa Dampingan</span>
                        @endif
                    </div>
                    <h1 class="text-2xl font-black text-gray-900 leading-tight">{{ $prestasi->nama_kegiatan }}</h1>
                    @if($prestasi->pengaju_type === \App\Models\Mahasiswa::class)
                        <p class="text-sm text-gray-500 font-medium mt-1">Diajukan oleh: {{ $prestasi->pengaju_name }}</p>
                    @endif
                    <div class="flex flex-wrap items-center gap-3 mt-3 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px] text-gray-400">corporate_fare</span>
                            {{ $prestasi->penyelenggara }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px] text-gray-400">calendar_month</span>
                            {{ $prestasi->tanggal_mulai?->format('d M Y') }}
                            @if($prestasi->tanggal_selesai)
                                – {{ $prestasi->tanggal_selesai->format('d M Y') }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            <div class="shrink-0 flex flex-col items-end gap-2">
                {!! $prestasi->status_badge !!}
                <div class="text-[10px] text-gray-400">Diperbarui: {{ $prestasi->updated_at->diffForHumans() }}</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Aksi Dosen (hanya jika dia sendiri yang mengajukan, bukan dampingan) --}}
            @if($prestasi->pengaju_type === \App\Models\Dosen::class && ($prestasi->isEditable() || in_array($prestasi->status, [\App\Models\Prestasi::STATUS_DRAFT, \App\Models\Prestasi::STATUS_DITOLAK])))
            <div class="bg-indigo-50/80 border border-indigo-200/60 rounded-2xl p-6 shadow-sm">
                <h3 class="text-xs font-bold text-indigo-700 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">task_alt</span> Tindakan
                </h3>
                <div class="flex flex-wrap gap-3">
                    @if($prestasi->isEditable())
                        <a href="{{ route('dosen.prestasi.edit', $prestasi) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-xl transition shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">edit</span> Edit Data
                        </a>
                    @endif
                    
                    @if(in_array($prestasi->status, [\App\Models\Prestasi::STATUS_DRAFT, \App\Models\Prestasi::STATUS_DITOLAK]))
                        <form method="POST" action="{{ route('dosen.prestasi.submit', $prestasi) }}" class="inline">
                            @csrf
                            <button onclick="return confirm('Kirim pengajuan ini ke admin? Data yang sudah dikirim tidak bisa diedit kecuali ditolak.')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition shadow-sm">
                                <span class="material-symbols-outlined text-[16px]">send</span> Ajukan ke Admin
                            </button>
                        </form>
                    @endif
                </div>
                
                @if($prestasi->status === \App\Models\Prestasi::STATUS_DITOLAK)
                    <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <p class="text-xs font-bold text-red-600 mb-1">Alasan Penolakan:</p>
                        <p class="text-sm text-red-800">{{ $prestasi->rejected_reason }}</p>
                    </div>
                @endif
            </div>
            @endif

            {{-- Detail Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">info</span> Informasi Kegiatan
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Tempat Kegiatan</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $prestasi->tempat_kegiatan }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Jenis Kegiatan</p>
                        <p class="text-sm font-semibold text-gray-800">{{ ucfirst($prestasi->jenis_kegiatan) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Jenis Prestasi/Peran</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $prestasi->jenis_prestasi ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Nomor Sertifikat</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $prestasi->nomor_sertifikat ?? '-' }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Deskripsi / Abstrak</p>
                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $prestasi->deskripsi ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Dokumen --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">folder</span> Dokumen Lampiran
                    </h3>
                    @if($prestasi->pengaju_type === \App\Models\Dosen::class && in_array($prestasi->status, [\App\Models\Prestasi::STATUS_DRAFT, \App\Models\Prestasi::STATUS_DITOLAK, \App\Models\Prestasi::STATUS_DIPROSES_ADMIN]))
                        <button @click="showUploadModal = true" class="text-xs font-bold text-[#7a1621] hover:underline flex items-center gap-1">
                            <i class="fas fa-plus"></i> Tambah Dokumen
                        </button>
                    @endif
                </div>

                @if($prestasi->dokumens->isEmpty())
                    <div class="text-center py-6 text-sm text-gray-400">Belum ada dokumen yang diunggah.</div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($prestasi->dokumens as $dok)
                            <a href="{{ \App\Helpers\FileHelper::filePrivateUrl($dok->file_path) }}" target="_blank" class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-gray-300 hover:bg-gray-50 transition-colors group">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center group-hover:bg-[#7a1621] group-hover:text-white transition-colors">
                                    <span class="material-symbols-outlined">{{ $dok->icon }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $dok->jenis_label }}</p>
                                    <p class="text-xs text-gray-500">{{ $dok->human_size }} • {{ $dok->original_name }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Generated Surats List --}}
            @if($prestasi->surats->count() > 0)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">mark_email_read</span> Surat Resmi dari Kampus
                </h3>
                <div class="space-y-3">
                    @foreach($prestasi->surats as $surat)
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $surat->jenis_surat_label }}</p>
                                <p class="text-xs text-gray-500 font-mono">{{ $surat->nomor_surat }}</p>
                                <p class="text-[10px] text-gray-400 mt-1">Digenerate: {{ $surat->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <a href="{{ route('dosen.prestasi.surat.download', [$prestasi, $surat]) }}" class="shrink-0 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-bold text-gray-700 hover:text-[#7a1621] hover:border-[#7a1621] transition-colors shadow-sm flex items-center gap-2">
                                <i class="fas fa-download"></i> Unduh
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Sidebar: Timeline --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">history</span> Riwayat Aktivitas
                </h3>
                <div class="relative pl-3 border-l border-gray-100 space-y-6">
                    @foreach($prestasi->logs()->orderByDesc('created_at')->get() as $log)
                    <div class="relative">
                        <div class="absolute -left-[1.1rem] top-0 w-8 h-8 rounded-full bg-{{ $log->action_color }}-50 flex items-center justify-center border border-{{ $log->action_color }}-100 shadow-sm">
                            <span class="material-symbols-outlined text-[16px] text-{{ $log->action_color }}-600">{{ $log->action_icon }}</span>
                        </div>
                        <div class="pl-8">
                            <p class="text-sm font-bold text-gray-800">{{ $log->action_label }}</p>
                            <div class="flex items-center gap-2 mt-0.5 mb-1.5">
                                <p class="text-[10px] text-gray-400 uppercase tracking-wider">{{ $log->created_at->format('d M Y, H:i') }}</p>
                                <span class="text-[10px] bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded">{{ $log->user?->name ?? 'Sistem' }}</span>
                            </div>
                            
                            @if($log->metadata)
                                @if(isset($log->metadata['note']))
                                    <p class="text-xs text-gray-600 mt-1 italic border-l-2 border-gray-200 pl-2">{{ $log->metadata['note'] }}</p>
                                @endif
                                @if(isset($log->metadata['reason']))
                                    <p class="text-xs text-red-600 mt-1 bg-red-50 p-2 rounded-lg border border-red-100">{{ $log->metadata['reason'] }}</p>
                                @endif
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- Upload Dokumen Tambahan Modal --}}
    @if($prestasi->pengaju_type === \App\Models\Dosen::class)
    <div x-show="showUploadModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900/50 backdrop-blur-sm p-4">
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl" @click.away="showUploadModal = false">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/50 rounded-t-2xl">
                <h3 class="text-lg font-bold text-gray-900">Upload Dokumen Tambahan</h3>
                <button @click="showUploadModal = false" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
            </div>
            <form method="POST" action="{{ route('dosen.prestasi.upload-dokumen', $prestasi) }}" enctype="multipart/form-data">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Dokumen</label>
                        <select name="jenis" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-[#7a1621] focus:border-[#7a1621] sm:text-sm">
                            <option value="pendukung">Dokumen Pendukung Lainnya</option>
                            <option value="dokumentasi">Foto Dokumentasi</option>
                            @if($prestasi->tipe === 'pelaporan')
                                <option value="sertifikat">Sertifikat Tambahan</option>
                            @endif
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih File</label>
                        <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#7a1621] file:text-white hover:file:bg-[#63101a] transition cursor-pointer">
                        <p class="text-xs text-gray-500 mt-1">Maksimal 10MB.</p>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-2xl">
                    <button type="button" @click="showUploadModal = false" class="px-5 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-100 rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold bg-[#7a1621] text-white rounded-xl shadow-sm hover:bg-[#63101a]">Upload File</button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
@endsection
