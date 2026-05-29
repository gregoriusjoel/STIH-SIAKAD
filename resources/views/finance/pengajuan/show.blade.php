@extends('layouts.finance')

@section('page-title', 'Detail Pengajuan Bebas Keuangan')

@section('content')
    <div class="px-4 md:px-0 animate-fade-in pb-20 font-inter" x-data="{ openApprove: false, openReject: false }">
        {{-- Back Button --}}
        <div class="mb-8">
            <a href="{{ route('finance.pengajuan.index') }}"
                class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-800 transition-colors font-bold text-xs uppercase tracking-widest">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Daftar
            </a>
        </div>

        {{-- Header Section --}}
        <div
            class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Detail Pengajuan #{{ $pengajuan->id }}</h1>
                <p class="text-xs text-slate-400 font-medium mt-1">Diajukan pada
                    {{ $pengajuan->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm') }}</p>
            </div>
            <div>
                {!! $pengajuan->status_badge !!}
            </div>
        </div>

        @if(session('success'))
            <div
                class="mb-8 bg-emerald-50 border border-emerald-100 rounded-3xl p-6 text-emerald-800 flex items-center gap-4 animate-shake">
                <div class="size-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                    <i class="fas fa-check"></i>
                </div>
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div
                class="mb-8 bg-rose-50 border border-rose-100 rounded-3xl p-6 text-rose-800 flex items-center gap-4 animate-shake">
                <div class="size-10 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 shrink-0">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <p class="font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-8 bg-rose-50 border border-rose-100 rounded-3xl p-6 text-rose-800 space-y-2 animate-shake">
                <div class="flex items-center gap-4">
                    <div class="size-10 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600 shrink-0">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <p class="font-bold text-sm">Ada beberapa kesalahan pengisian form:</p>
                </div>
                <ul class="list-disc pl-14 text-xs font-semibold space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Column --}}
            <div class="lg:col-span-2 flex flex-col gap-8 h-full">
                {{-- Detail Pengajuan --}}
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden flex-grow flex flex-col">
                    <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100">
                        <h2 class="font-black text-slate-800 tracking-tight text-base">Informasi Pengajuan</h2>
                    </div>
                    <div class="p-8 space-y-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Jenis
                                Pengajuan</label>
                            <div
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-black bg-teal-50 text-teal-700 border border-teal-100 uppercase tracking-widest">
                                <i class="fas fa-file-invoice-dollar"></i>
                                {{ $pengajuan->jenis_label }}
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Keterangan
                                / Alasan</label>
                            <p class="text-sm font-medium text-slate-700 bg-slate-50 rounded-2xl p-6 leading-relaxed">
                                {{ $pengajuan->keterangan }}
                            </p>
                        </div>

                        @if($pengajuan->nomor_surat)
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Nomor
                                    Surat Resmi</label>
                                <p
                                    class="text-sm font-black text-slate-700 font-mono tracking-tight bg-slate-50 inline-block px-4 py-2 rounded-xl border border-slate-100">
                                    {{ $pengajuan->nomor_surat }}
                                </p>
                            </div>
                        @endif

                        @if($pengajuan->rejected_reason)
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Alasan
                                    Penolakan</label>
                                <p
                                    class="text-sm font-bold text-rose-800 bg-rose-50 border border-rose-100 rounded-2xl p-6 italic leading-relaxed">
                                    "{{ $pengajuan->rejected_reason }}"
                                </p>
                            </div>
                        @endif

                        @if($pengajuan->approved_by)
                            <div class="pt-6 border-t border-slate-100">
                                <label
                                    class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Disetujui
                                    Oleh</label>
                                <div class="flex items-center gap-3 text-sm">
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-[#8B1538]/10 flex items-center justify-center text-[#8B1538] font-bold">
                                        {{ substr($pengajuan->approver->name ?? 'F', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $pengajuan->approver->name ?? 'Staf Keuangan' }}
                                        </p>
                                        @if($pengajuan->approved_at)
                                            <p class="text-[10px] text-slate-400 font-medium uppercase mt-0.5">
                                                {{ $pengajuan->approved_at->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Revision History --}}
                @if($pengajuan->revisions->count() > 0)
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100">
                            <h2 class="font-black text-slate-800 tracking-tight text-base flex items-center gap-2">
                                <i class="fas fa-history text-slate-400"></i>
                                Riwayat Revisi ({{ $pengajuan->revisions->count() }}x)
                            </h2>
                        </div>
                        <div class="p-8">
                            <ol class="relative border-l border-slate-100 space-y-6">
                                @foreach($pengajuan->revisions as $rev)
                                    <li class="ml-6">
                                        <div class="absolute w-3.5 h-3.5 bg-slate-200 rounded-full -left-1.5 border-4 border-white">
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-2">Revisi
                                            #{{ $rev->revision_no }}</div>
                                        @if($rev->note_from_admin)
                                            <p
                                                class="text-xs font-medium bg-rose-50 border border-rose-100 rounded-xl p-4 text-rose-700 mb-3 leading-relaxed">
                                                <span class="font-black">Catatan Keuangan:</span> {{ $rev->note_from_admin }}
                                            </p>
                                        @endif
                                        @if($rev->note_from_mahasiswa)
                                            <p
                                                class="text-xs font-medium bg-blue-50 border border-blue-100 rounded-xl p-4 text-blue-700 mb-3 leading-relaxed">
                                                <span class="font-black">Catatan Mahasiswa:</span> {{ $rev->note_from_mahasiswa }}
                                            </p>
                                        @endif
                                        @if($rev->signed_doc_path)
                                            <a href="{{ Storage::url($rev->signed_doc_path) }}" target="_blank"
                                                class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-wider px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl text-slate-600 transition-colors">
                                                <i class="fas fa-paperclip"></i> Lihat Dokumen TTD
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar Column --}}
            <div class="lg:col-span-1 flex flex-col gap-8 h-full">
                {{-- Info Mahasiswa Card --}}
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden flex-grow flex flex-col">
                    <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100">
                        <h2 class="font-black text-slate-800 tracking-tight text-base">Data Mahasiswa</h2>
                    </div>
                    <div class="p-8">
                        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                            <div
                                class="size-14 rounded-2xl bg-[#8B1538]/10 text-[#8B1538] flex items-center justify-center font-black text-xl shadow-inner shrink-0">
                                {{ substr($pengajuan->mahasiswa->user->name ?? 'M', 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-black text-slate-800 tracking-tight leading-tight truncate">
                                    {{ $pengajuan->mahasiswa->user->name }}</h3>
                                <p class="text-[10px] font-black uppercase tracking-widest text-[#8B1538] mt-1">NIM:
                                    {{ $pengajuan->mahasiswa->nim }}</p>
                            </div>
                        </div>

                        <div class="space-y-4 text-xs font-semibold">
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Program
                                    Studi</label>
                                <p class="text-slate-700">{{ $pengajuan->mahasiswa->prodi ?? '-' }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Fakultas</label>
                                <p class="text-slate-700">Fakultas Hukum</p>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Semester
                                    Sekarang</label>
                                <p class="text-slate-700">Semester {{ $pengajuan->mahasiswa->semester ?? '-' }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Email
                                    Kampus</label>
                                <p class="text-slate-500 font-medium text-[10px] select-all truncate">
                                    {{ $pengajuan->mahasiswa->user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions Panel --}}
                @if($pengajuan->status === 'submitted')
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-6 space-y-3">
                        <button @click="openApprove = true"
                            class="w-full py-4 bg-emerald-600 text-white hover:bg-emerald-700 rounded-2xl flex items-center justify-center gap-2 font-black text-[10px] uppercase tracking-widest shadow-md shadow-emerald-900/10 transition-all">
                            <i class="fas fa-check-circle text-sm"></i>
                            Setujui & Terbitkan
                        </button>
                        <button @click="openReject = true"
                            class="w-full py-4 bg-rose-600 text-white hover:bg-rose-700 rounded-2xl flex items-center justify-center gap-2 font-black text-[10px] uppercase tracking-widest shadow-md shadow-rose-900/10 transition-all">
                            <i class="fas fa-times-circle text-sm"></i>
                            Tolak Pengajuan
                        </button>
                    </div>
                @endif

                {{-- Non-Actionable Info --}}
                @if(in_array($pengajuan->status, ['draft', 'generated']))
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-6 text-center">
                        <div
                            class="size-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300 mx-auto mb-4">
                            <i class="fas fa-info-circle text-lg"></i>
                        </div>
                        <h3 class="font-black text-slate-700 text-xs uppercase tracking-wider">Status Pengisian</h3>
                        <p class="text-xs text-slate-400 font-medium mt-2 leading-relaxed">
                            @if($pengajuan->status === 'draft')
                                Mahasiswa masih dalam proses mengisi formulir pengajuan.
                            @else
                                Dokumen draft telah diunduh mahasiswa. Menunggu unggahan berkas bertanda tangan.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Row 2: Document cards grid (Draft, TTD, and Approved PDF if approved) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
            {{-- Left: Draft & TTD --}}
            <div class="lg:col-span-2 h-full">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 h-full">
                    {{-- Generated Draft --}}
                    @if($pengajuan->generated_doc_path)
                        <div
                            class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-6 flex flex-col justify-between h-full min-h-[220px]">
                            <div class="mb-4">
                                <div
                                    class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-4">
                                    <i class="fas fa-file-word text-xl"></i>
                                </div>
                                <h3 class="font-black text-slate-800 text-sm tracking-tight">Draft Surat (Sistem)</h3>
                                <p class="text-xs text-slate-400 font-medium mt-1">Dokumen otomatis dengan data mahasiswa</p>
                            </div>
                            <a href="{{ route('finance.pengajuan.download-generated', $pengajuan->id) }}"
                                class="w-full text-center py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all mt-auto">
                                <i class="fas fa-download mr-2"></i> Download DOCX
                            </a>
                        </div>
                    @endif

                    {{-- Dokumen Tanda Tangan Mahasiswa --}}
                    @if($pengajuan->signed_doc_path)
                        <div
                            class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-6 flex flex-col justify-between h-full min-h-[220px]">
                            <div class="mb-4">
                                <div
                                    class="w-12 h-12 bg-yellow-50 rounded-2xl flex items-center justify-center text-yellow-600 mb-4">
                                    <i class="fas fa-file-signature text-xl"></i>
                                </div>
                                <h3 class="font-black text-slate-800 text-sm tracking-tight">Dokumen Bertanda Tangan</h3>
                                <p class="text-xs text-slate-400 font-medium mt-1">Unggahan mahasiswa untuk diperiksa</p>
                            </div>
                            <a href="{{ route('finance.pengajuan.download-signed', $pengajuan->id) }}"
                                class="w-full text-center py-3.5 bg-[#8B1538] text-white hover:bg-[#6D1029] rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all shadow-md shadow-red-900/10 mt-auto">
                                <i class="fas fa-download mr-2"></i> Download TTD
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right: Approved PDF Letter (aligned perfectly with Draft & TTD) --}}
            <div class="lg:col-span-1 h-full">
                @if($pengajuan->status === 'approved' && $pengajuan->file_surat)
                    <div
                        class="bg-white rounded-[2.5rem] border border-emerald-100 shadow-sm p-6 flex flex-col justify-between h-full min-h-[220px] animate-fade-in">
                        <div class="mb-4">
                            <div
                                class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mb-4">
                                <i class="fas fa-file-pdf text-xl"></i>
                            </div>
                            <h3 class="font-black text-slate-800 text-sm tracking-tight">Surat Bebas Keuangan Resmi</h3>
                            <p class="text-xs text-slate-400 font-medium mt-1">Surat Keterangan resmi format PDF</p>
                        </div>
                        <a href="{{ route('finance.pengajuan.download', $pengajuan->id) }}"
                            class="w-full text-center py-3.5 bg-emerald-600 text-white hover:bg-emerald-700 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all shadow-md shadow-emerald-900/10 mt-auto">
                            <i class="fas fa-download mr-2"></i> Download PDF
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════════
        MODAL: Approve & Terbitkan (Alpine-based)
        ══════════════════════════════════════════════════════════════════ --}}
        <template x-teleport="body">
            <div x-show="openApprove" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" x-transition.opacity>

                {{-- Backdrop --}}
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

                <div class="relative min-h-screen flex items-center justify-center p-4">
                    <div @click.outside="openApprove = false"
                        class="relative transform overflow-hidden rounded-[2.5rem] bg-white shadow-2xl w-full max-w-lg border border-slate-100 animate-fade-in">

                        {{-- Header --}}
                        <div class="bg-emerald-50 px-8 py-6 border-b border-emerald-100 flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-950/20">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800 tracking-tight">Setujui & Terbitkan Surat</h3>
                                <p class="text-[10px] font-black uppercase tracking-wider text-emerald-700">Layanan Bebas
                                    Keuangan</p>
                            </div>
                        </div>

                        {{-- Form --}}
                        <form action="{{ route('finance.pengajuan.approve', $pengajuan->id) }}" method="POST">
                            @csrf
                            <div class="p-8 space-y-6">
                                <p class="text-xs font-semibold text-slate-500 leading-relaxed">
                                    Apakah Anda yakin ingin menyetujui pengajuan ini? Sistem akan **menerbitkan Surat
                                    Keterangan Bebas Keuangan secara otomatis** dalam format PDF.
                                </p>

                                {{-- Catatan --}}
                                <div class="space-y-2">
                                    <label
                                        class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Catatan
                                        Internal / Tambahan <span
                                            class="text-slate-300 font-normal">(opsional)</span></label>
                                    <textarea name="admin_note" rows="3"
                                        class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 py-3 px-4 text-xs font-semibold text-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all resize-none"
                                        placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="bg-slate-50/80 px-8 py-5 flex gap-4 border-t border-slate-100">
                                <button type="submit"
                                    class="flex-1 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-md shadow-emerald-950/10 transition-all">
                                    <i class="fas fa-check mr-2"></i> Setujui & Simpan
                                </button>
                                <button type="button" @click="openApprove = false"
                                    class="flex-1 py-3.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        {{-- ══════════════════════════════════════════════════════════════════
        MODAL: Tolak Pengajuan (Alpine-based)
        ══════════════════════════════════════════════════════════════════ --}}
        <template x-teleport="body">
            <div x-show="openReject" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" x-transition.opacity>

                {{-- Backdrop --}}
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

                <div class="relative min-h-screen flex items-center justify-center p-4">
                    <div @click.outside="openReject = false"
                        class="relative transform overflow-hidden rounded-[2.5rem] bg-white shadow-2xl w-full max-w-lg border border-slate-100 animate-fade-in">

                        {{-- Header --}}
                        <div class="bg-rose-50 px-8 py-6 border-b border-rose-100 flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-rose-600 flex items-center justify-center text-white shadow-lg shadow-rose-955/20">
                                <i class="fas fa-times-circle text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800 tracking-tight">Tolak Pengajuan</h3>
                                <p class="text-[10px] font-black uppercase tracking-wider text-rose-700">Berikan Alasan
                                    Penolakan</p>
                            </div>
                        </div>

                        {{-- Form --}}
                        <form action="{{ route('finance.pengajuan.reject', $pengajuan->id) }}" method="POST">
                            @csrf
                            <div class="p-8 space-y-6">
                                <p class="text-xs font-semibold text-slate-500 leading-relaxed">
                                    Berikan alasan penolakan secara jelas. Mahasiswa akan mendapatkan notifikasi penolakan
                                    dan diminta untuk merevisi pengajuan mereka.
                                </p>

                                {{-- Alasan Penolakan --}}
                                <div class="space-y-2">
                                    <label
                                        class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Alasan
                                        Penolakan <span class="text-rose-500">*</span></label>
                                    <textarea name="rejected_reason" rows="4" required
                                        class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 py-3 px-4 text-xs font-semibold text-slate-600 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 transition-all resize-none"
                                        placeholder="Contoh: Bukti tanda tangan pada dokumen tidak jelas atau ada tagihan yang belum lunas..."></textarea>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="bg-slate-50/80 px-8 py-5 flex gap-4 border-t border-slate-100">
                                <button type="submit"
                                    class="flex-1 py-3.5 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-md shadow-rose-950/10 transition-all">
                                    <i class="fas fa-times mr-2"></i> Ya, Tolak
                                </button>
                                <button type="button" @click="openReject = false"
                                    class="flex-1 py-3.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .animate-shake {
            animation: shake 0.4s ease-in-out;
        }
    </style>
@endsection