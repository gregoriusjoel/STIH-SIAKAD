@extends('layouts.mahasiswa')
@section('title', 'Pengajuan Proposal Skripsi')

@section('content')
<div class="space-y-4">
    {{-- Header Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-red-500/10 to-transparent blur-3xl -mr-32 -mt-32"></div>
        <div class="p-6 sm:p-8 relative">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#8B1538] to-[#6b102b] flex items-center justify-center shadow-lg shadow-red-900/10 shrink-0">
                        <span class="material-symbols-outlined text-white text-3xl">upload_file</span>
                    </div>
                    <div>
                        <a href="{{ route('mahasiswa.thesis.index') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-[#8B1538] hover:gap-2 transition-all mb-1">
                            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                            Kembali ke Progress
                        </a>
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Pengajuan Proposal</h1>
                        <p class="text-sm text-gray-500 mt-1">Lengkapi informasi berikut untuk memulai pengajuan proposal skripsi Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($submission && $submission->status->value === 'PROPOSAL_REJECTED')
    <div class="bg-red-50 border border-red-100 rounded-2xl p-4 flex items-start gap-3">
        <span class="material-symbols-outlined text-red-500">error</span>
        <div class="flex-1">
            <h3 class="font-bold text-red-900 text-sm">Proposal Perlu Perbaikan</h3>
            <p class="text-xs text-red-700 leading-relaxed mt-0.5">{{ $submission->admin_note ?? 'Silahkan perbaiki berkas sesuai instruksi admin.' }}</p>
        </div>
    </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-6">
        {{-- LEFT: Guide & Info --}}
        <div class="w-full lg:w-[32%] space-y-4 shrink-0">
            {{-- Panduan Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <span class="material-symbols-outlined text-[22px]">lightbulb</span>
                    </div>
                    <h2 class="font-bold text-gray-800 text-sm italic">Panduan Pengajuan</h2>
                </div>
                
                <ul class="space-y-4">
                    @foreach([
                        ['t' => 'Judul yang Jelas', 'b' => 'Gunakan judul yang spesifik dan mencerminkan inti penelitian.', 'i' => 'title'],
                        ['t' => 'Dosen Pembimbing', 'b' => 'Pastikan telah berkonsultasi sebelumnya dengan calon pembimbing.', 'i' => 'account_circle_icon'],
                        ['t' => 'Berkas PDF/DOC', 'b' => 'Unggah berkas proposal dalam format yang ditentukan (Maks 10MB).', 'i' => 'description'],
                    ] as $item)
                    <li class="flex gap-3">
                        <span class="material-symbols-outlined text-[18px] text-gray-400 mt-0.5">{{ $item['i'] === 'account_circle_icon' ? 'person' : $item['i'] }}</span>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs font-bold text-gray-800 mb-0.5">{{ $item['t'] }}</h4>
                            <p class="text-[11px] text-gray-500 leading-relaxed">{{ $item['b'] }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>

                <div class="mt-6 pt-5 border-t border-gray-50">
                    <div class="flex items-center gap-2 bg-blue-50 border border-blue-100 rounded-xl px-3 py-2.5">
                        <span class="material-symbols-outlined text-[16px] text-blue-500">info</span>
                        <p class="text-[11px] text-blue-700 leading-tight font-medium">Proposal Anda akan diverifikasi oleh Admin/Prodi sebelum dilanjutkan.</p>
                    </div>
                </div>
            </div>

            {{-- Checklist --}}
            <div class="bg-[#8B1538]/5 rounded-2xl border border-[#8B1538]/10 p-5">
                <h3 class="text-xs font-bold text-[#8B1538] uppercase tracking-widest mb-4">Berkas Persyaratan</h3>
                <div class="space-y-3">
                    @foreach(['Sudah Verifikasi SKS', 'Draft Proposal Lengkap', 'Persetujuan Calon Pembimbing'] as $check)
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-500 text-[18px]">check_circle</span>
                        <span class="text-xs font-medium text-gray-700">{{ $check }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- RIGHT: Form --}}
        <div class="flex-1 min-w-0">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50 flex items-center gap-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                    <h2 class="font-bold text-gray-800 text-sm">Formulir Proposal</h2>
                </div>
                
                <form action="{{ route('mahasiswa.thesis.proposal.submit') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf

                    {{-- Judul --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 px-1">Judul Skripsi <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul', $submission?->judul) }}"
                            class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm font-medium focus:bg-white focus:ring-2 focus:ring-[#8B1538]/10 focus:border-[#8B1538] transition-all @error('judul') border-red-300 bg-red-50 @enderror"
                            placeholder="Contoh: Analisis Yuridis Penanganan Kasus...">
                        @error('judul')<p class="mt-1.5 text-xs text-red-500 px-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 px-1">Ringkasan Proposal</label>
                        <textarea name="deskripsi_proposal" rows="6"
                            class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm font-medium focus:bg-white focus:ring-2 focus:ring-[#8B1538]/10 focus:border-[#8B1538] transition-all resize-none"
                            placeholder="Jelaskan secara singkat latar belakang dan tujuan penelitian Anda...">{{ old('deskripsi_proposal', $submission?->deskripsi_proposal) }}</textarea>
                        @error('deskripsi_proposal')<p class="mt-1.5 text-xs text-red-500 px-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Dosen Pembimbing --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 px-1">Dosen Pembimbing <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <select name="requested_supervisor_id"
                                    class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm font-semibold appearance-none focus:bg-white focus:ring-2 focus:ring-[#8B1538]/10 focus:border-[#8B1538] transition-all @error('requested_supervisor_id') border-red-300 bg-red-50 @enderror">
                                    <option value="">Pilih Pembimbing</option>
                                    @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}" {{ old('requested_supervisor_id', $submission?->requested_supervisor_id) == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                                    <span class="material-symbols-outlined text-[18px]">expand_more</span>
                                </div>
                            </div>
                            @error('requested_supervisor_id')<p class="mt-1.5 text-xs text-red-500 px-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- File --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 px-1">Berkas Proposal</label>
                            <label class="relative flex flex-col items-center justify-center p-3 border-2 border-dashed border-gray-100 rounded-xl hover:border-[#8B1538]/30 hover:bg-red-50/30 transition-all cursor-pointer group">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-red-50 text-[#8B1538] flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <span class="material-symbols-outlined text-[20px]">cloud_upload</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[11px] font-bold text-gray-700 leading-none">Pilih Berkas</p>
                                        <p class="text-[10px] text-gray-400 mt-1">PDF/DOCX Max 10MB</p>
                                    </div>
                                </div>
                                <input type="file" name="proposal_file" class="hidden" accept=".pdf,.doc,.docx">
                            </label>
                            @if($submission?->proposal_file_path)
                            <div class="mt-2 flex items-center gap-1.5 px-2">
                                <span class="material-symbols-outlined text-[14px] text-green-500">check_circle</span>
                                <span class="text-[10px] font-semibold text-gray-500 italic">File tersedia (Akan diganti jika upload Baru)</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('mahasiswa.thesis.index') }}"
                            class="flex-1 bg-gray-50 text-gray-600 font-bold py-3.5 rounded-xl border border-gray-100 hover:bg-gray-100 text-center transition-all text-sm">
                            Batal
                        </a>
                        <button type="submit"
                            class="flex-[2] bg-gradient-to-r from-[#8B1538] to-[#6b102b] text-white font-bold py-3.5 rounded-xl shadow-lg shadow-red-900/20 hover:scale-[1.02] active:scale-[0.98] transition-all text-sm flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">send</span>
                            Kirim Pengajuan Proposal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
