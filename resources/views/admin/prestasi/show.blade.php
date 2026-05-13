@extends('layouts.admin')

@section('title', 'Detail Prestasi – ' . $prestasi->pengaju_name)

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-[1600px] mx-auto font-inter" x-data="{ showRejectForm: false, showPdfPanel: window.location.hash === '#pdf-panel' }">

    {{-- Back + Flash --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">
        <a href="{{ route('admin.prestasi.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-[#7a1621] transition-colors group w-fit">
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
                    </div>
                    <h1 class="text-2xl font-black text-gray-900 leading-tight">{{ $prestasi->nama_kegiatan }}</h1>
                    <p class="text-sm text-gray-500 font-medium mt-1">{{ $prestasi->pengaju_name }} &bull; {{ $prestasi->pengaju_identifier }} ({{ ucfirst($prestasi->pengaju_role) }})</p>
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
                    @if($prestasi->dosenPendamping)
                    <div class="sm:col-span-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Dosen Pendamping</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $prestasi->dosenPendamping->user->name ?? '-' }} ({{ $prestasi->dosenPendamping->nidn }})</p>
                    </div>
                    @endif
                    <div class="sm:col-span-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Deskripsi / Abstrak</p>
                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $prestasi->deskripsi ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Dokumen --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">folder</span> Dokumen Lampiran
                </h3>
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

            {{-- Admin Actions --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">admin_panel_settings</span> Aksi Admin
                </h3>
                <div class="flex flex-wrap gap-3">

                    @if($prestasi->status === \App\Models\Prestasi::STATUS_DIAJUKAN)
                        <form method="POST" action="{{ route('admin.prestasi.approve', $prestasi) }}" class="inline">
                            @csrf
                            <button onclick="return confirm('Mulai proses pengajuan ini?')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition shadow-sm">
                                <span class="material-symbols-outlined text-[16px]">play_circle</span> Proses Pengajuan
                            </button>
                        </form>
                        <button @click="showRejectForm = !showRejectForm" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-red-200 hover:bg-red-50 text-red-600 text-sm font-bold rounded-xl transition shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">cancel</span> Tolak
                        </button>
                    @endif

                    @if(in_array($prestasi->status, [\App\Models\Prestasi::STATUS_DIPROSES_ADMIN, \App\Models\Prestasi::STATUS_SURAT_DITERBITKAN, \App\Models\Prestasi::STATUS_SELESAI]))
                        <button @click="showPdfPanel = !showPdfPanel" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span> Generate Surat Resmi / Backdate
                        </button>

                        @if(in_array($prestasi->status, [\App\Models\Prestasi::STATUS_DIPROSES_ADMIN, \App\Models\Prestasi::STATUS_SURAT_DITERBITKAN]))
                            <form method="POST" action="{{ route('admin.prestasi.selesai', $prestasi) }}" class="inline">
                                @csrf
                                <button onclick="return confirm('Tandai kegiatan ini selesai?')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]">check_circle</span> Tandai Selesai
                                </button>
                            </form>
                        @endif
                    @endif

                </div>

                {{-- Reject Form --}}
                <div x-show="showRejectForm" x-cloak class="mt-5 p-5 bg-red-50/80 border border-red-200/60 rounded-2xl">
                    <form method="POST" action="{{ route('admin.prestasi.reject', $prestasi) }}" class="space-y-3">
                        @csrf
                        <label class="block text-xs font-bold text-red-600 uppercase tracking-widest mb-1.5">Alasan Penolakan</label>
                        <textarea name="rejected_reason" rows="3" required class="w-full rounded-xl border-red-200 bg-white text-sm px-4 py-3 focus:ring-[#7a1621] focus:border-[#7a1621]" placeholder="Jelaskan alasan penolakan..."></textarea>
                        <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl shadow-sm">
                            Kirim Penolakan
                        </button>
                    </form>
                </div>

                {{-- Generate Surat Form --}}
                <div id="pdf-panel" x-show="showPdfPanel" x-cloak class="mt-5 p-5 bg-indigo-50/80 border border-indigo-200/60 rounded-2xl space-y-4"
                     x-data="{ jenis: 'surat_tugas', updateNomor() { fetch(`{{ route('admin.prestasi.preview-nomor') }}?jenis_surat=${this.jenis}`).then(r=>r.json()).then(d=>{ $refs.nomor.value = d.nomor_surat; }) } }">
                    <h4 class="text-xs font-bold text-indigo-600 uppercase tracking-widest flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-[15px]">picture_as_pdf</span> Form Generate Surat
                    </h4>
                    <form method="POST" action="{{ route('admin.prestasi.generate-surat', $prestasi) }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">Jenis Surat</label>
                                <select name="jenis_surat" x-model="jenis" @change="updateNomor()" required class="w-full rounded-xl border-gray-300 text-sm px-4 py-2 focus:ring-[#7a1621] focus:border-[#7a1621]">
                                    @foreach(\App\Models\Prestasi::JENIS_SURAT_LABELS as $val => $label)
                                        <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">Tanggal Surat</label>
                                <input type="date" name="tanggal_surat" value="{{ now()->format('Y-m-d') }}" required class="w-full rounded-xl border-gray-300 text-sm px-4 py-2 focus:ring-[#7a1621] focus:border-[#7a1621]">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-600 mb-1">Nomor Surat (Auto-preview)</label>
                                <input type="text" name="nomor_surat_manual" x-ref="nomor" value="{{ $previewNomors['surat_tugas'] ?? '' }}" class="w-full rounded-xl border-gray-300 bg-white text-sm px-4 py-2 focus:ring-[#7a1621] focus:border-[#7a1621]">
                                <p class="text-[10px] text-gray-500 mt-1">Bisa diubah manual jika diperlukan (misal untuk surat mundur/backdate).</p>
                            </div>
                        </div>

                        <div class="p-4 bg-white border border-gray-200 rounded-xl space-y-3">
                            <p class="text-xs font-bold text-gray-600">Penandatangan</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <select name="penandatangan_id" onchange="
                                        if(this.value){
                                            const opt = this.options[this.selectedIndex];
                                            document.getElementById('p_nama').value = opt.dataset.nama;
                                            document.getElementById('p_nip').value = opt.dataset.nip;
                                        }
                                    " class="w-full rounded-xl border-gray-300 text-sm px-4 py-2 mb-2">
                                        <option value="">-- Pilih Template Pejabat (Opsional) --</option>
                                        @foreach($dosens as $d)
                                            <option value="{{ $d->id }}" data-nama="{{ $d->user?->name }}" data-nip="{{ $d->nidn }}">{{ $d->user?->name }} ({{ $d->nidn }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <input type="text" name="penandatangan_nama" id="p_nama" placeholder="Nama Lengkap & Gelar" required class="w-full rounded-xl border-gray-300 text-sm px-4 py-2 focus:ring-[#7a1621] focus:border-[#7a1621]">
                                </div>
                                <div>
                                    <input type="text" name="penandatangan_jabatan" placeholder="Jabatan (Ketua STIH / Kaprodi)" required class="w-full rounded-xl border-gray-300 text-sm px-4 py-2 focus:ring-[#7a1621] focus:border-[#7a1621]">
                                </div>
                                <div class="md:col-span-2">
                                    <input type="text" name="penandatangan_nip" id="p_nip" placeholder="NIP / NIDN (Opsional)" class="w-full rounded-xl border-gray-300 text-sm px-4 py-2 focus:ring-[#7a1621] focus:border-[#7a1621]">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-sm transition w-full sm:w-auto">
                            Buat & Simpan Surat
                        </button>
                    </form>
                </div>
            </div>

            {{-- Generated Surats List --}}
            @if($prestasi->surats->count() > 0)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">mark_email_read</span> Surat Resmi
                </h3>
                <div class="space-y-3">
                    @foreach($prestasi->surats as $surat)
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $surat->jenis_surat_label }}</p>
                                <p class="text-xs text-gray-500 font-mono">{{ $surat->nomor_surat }}</p>
                                <p class="text-[10px] text-gray-400 mt-1">Digenerate: {{ $surat->created_at->format('d M Y H:i') }} oleh {{ $surat->generator?->name ?? 'Admin' }}</p>
                            </div>
                            <a href="{{ route('admin.prestasi.surat.download', [$prestasi, $surat]) }}" class="shrink-0 w-10 h-10 bg-white border border-gray-300 rounded-lg flex items-center justify-center text-gray-600 hover:text-[#7a1621] hover:border-[#7a1621] transition-colors shadow-sm" title="Unduh PDF">
                                <i class="fas fa-download"></i>
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
                                @if(isset($log->metadata['admin_note']) && $log->metadata['admin_note'])
                                    <p class="text-xs text-indigo-600 mt-1 bg-indigo-50 p-2 rounded-lg border border-indigo-100">{{ $log->metadata['admin_note'] }}</p>
                                @endif
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
