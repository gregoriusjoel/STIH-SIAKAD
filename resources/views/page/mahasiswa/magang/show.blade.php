@extends('layouts.mahasiswa')

@section('title', 'Detail Magang')
@section('page-title', 'Detail Magang')

@section('content')
<div class="max-w-[1600px] mx-auto px-4 py-8 space-y-6" x-data="{ showLogbookForm: false }">

    {{-- Back + Flash --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
        <a href="{{ route('mahasiswa.magang.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-[#8B1538] transition-colors group w-fit">
            <span class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center border border-gray-100 group-hover:border-red-100 group-hover:bg-red-50 transition-colors">
                <i class="fas fa-arrow-left text-xs"></i>
            </span>
            Kembali ke Daftar Magang
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50/80 border border-green-200/60 rounded-2xl flex items-start gap-3 shadow-sm">
            <span class="material-symbols-outlined text-green-600 mt-0.5">check_circle</span>
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50/80 border border-red-200/60 rounded-2xl flex items-start gap-3 shadow-sm">
            <span class="material-symbols-outlined text-red-600 mt-0.5">error</span>
            <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Hero Header Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-6 -mr-6 w-32 h-32 bg-gradient-to-br from-[#8B1538]/5 to-transparent rounded-full blur-2xl pointer-events-none"></div>
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6">
            <div class="flex items-start gap-5">
                <div class="w-16 h-16 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0 text-gray-400">
                    <span class="material-symbols-outlined text-4xl">corporate_fare</span>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-gray-900 leading-tight tracking-tight">{{ $internship->instansi }}</h1>
                    <p class="text-sm text-gray-500 font-medium mt-1">{{ $internship->posisi ?? 'Posisi belum ditentukan' }}</p>
                    <div class="flex flex-wrap items-center gap-3 mt-3 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px] text-gray-400">calendar_month</span>
                            {{ $internship->periode_mulai?->format('d M Y') }} – {{ $internship->periode_selesai?->format('d M Y') }}
                        </span>
                        @if($internship->semester)
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px] text-purple-400">school</span>
                            {{ $internship->semester->nama }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="shrink-0">
                {!! $internship->status_badge !!}
            </div>
        </div>

        @if($internship->rejected_reason)
            <div class="mt-5 p-4 bg-red-50 border border-red-200 rounded-2xl text-sm text-red-700 flex items-start gap-3">
                <span class="material-symbols-outlined text-red-500 mt-0.5">cancel</span>
                <div><span class="font-bold">Alasan Penolakan:</span> {{ $internship->rejected_reason }}</div>
            </div>
        @endif
    </div>

    {{-- Data Magang --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">info</span> Detail Data Magang
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[18px] text-gray-400">corporate_fare</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Instansi</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $internship->instansi }}</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[18px] text-gray-400">location_on</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Alamat</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $internship->alamat_instansi ?? '-' }}</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[18px] text-gray-400">work</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Posisi / Bagian</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $internship->posisi ?? '-' }}</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[18px] text-gray-400">description</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Deskripsi</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $internship->deskripsi ?? '-' }}</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[18px] text-orange-400">badge</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-orange-400/70 uppercase tracking-widest mb-0.5">Pembimbing Lapangan</p>
                    <p class="text-sm font-semibold text-gray-800">
                        {{ $internship->pembimbing_lapangan_nama ?? '-' }}
                        @if($internship->pembimbing_lapangan_telp)
                            <span class="text-gray-400 font-normal">({{ $internship->pembimbing_lapangan_telp }})</span>
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[18px] text-blue-400">supervisor_account</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-blue-400/70 uppercase tracking-widest mb-0.5">Dosen Pembimbing</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $internship->supervisorDosen?->user?->name ?? 'Belum ditentukan' }}</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[18px] text-purple-400">library_books</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-purple-400/70 uppercase tracking-widest mb-0.5">Total SKS Konversi</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $internship->total_mapped_sks }} SKS <span class="text-gray-400 font-normal">(maks 16)</span></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5 flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">touch_app</span> Aksi
        </h3>
        <div class="flex flex-wrap gap-3 items-start">

            @if($internship->isEditable())
                <a href="{{ route('mahasiswa.magang.edit', $internship) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-amber-500/20">
                    <span class="material-symbols-outlined text-[16px]">edit</span> Edit Data
                </a>
            @endif

            @if($internship->status === \App\Models\Internship::STATUS_DRAFT)
                <form method="POST" action="{{ route('mahasiswa.magang.submit', $internship) }}" class="inline">
                    @csrf
                    <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-blue-600/20">
                        <span class="material-symbols-outlined text-[16px]">send</span> Submit Pengajuan
                    </button>
                </form>
            @endif

            @if($internship->status === \App\Models\Internship::STATUS_WAITING_REQUEST_LETTER)
                <a href="{{ route('mahasiswa.magang.generate-letter', $internship) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-indigo-600/20">
                    <span class="material-symbols-outlined text-[16px]">download</span> Download Surat Permohonan
                </a>

                {{-- Upload Signed - styled --}}
                <div x-data="{ fileName: '' }" class="w-full mt-2">
                    <p class="text-xs font-bold text-gray-500 mb-2 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[14px] text-green-500">upload_file</span>
                        Upload Surat yang Sudah Diisi dan Ditandatangani
                    </p>
                    <form method="POST" action="{{ route('mahasiswa.magang.upload-signed', $internship) }}" enctype="multipart/form-data"
                          class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        @csrf
                        <label class="flex items-center gap-3 px-4 py-2.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl cursor-pointer transition group flex-1 min-w-0">
                            <span class="w-8 h-8 rounded-lg bg-white border border-gray-100 shadow-sm flex items-center justify-center shrink-0 group-hover:border-green-200 transition">
                                <span class="material-symbols-outlined text-[18px] text-gray-400 group-hover:text-green-500 transition">attach_file</span>
                            </span>
                            <span class="text-sm text-gray-500 font-medium truncate" x-text="fileName || 'Pilih file (PDF, DOCX, JPG, PNG)'"></span>
                            <input type="file" name="signed_letter" accept=".pdf,.docx,.jpg,.png" required class="hidden"
                                   @change="fileName = $event.target.files[0]?.name ?? ''">
                        </label>
                        <button type="submit"
                                class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-green-600/20">
                            <span class="material-symbols-outlined text-[16px]">upload</span> Upload
                        </button>
                    </form>
                    <p class="text-[11px] text-gray-400 mt-1.5 ml-1">Format: PDF, DOCX, JPG, PNG · Maks 5MB</p>
                </div>
            @endif

            @if($internship->status === \App\Models\Internship::STATUS_REQUEST_LETTER_UPLOADED)
                <form method="POST" action="{{ route('mahasiswa.magang.submit-review', $internship) }}" class="inline">
                    @csrf
                    <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-blue-600/20">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span> Kirim untuk Review Admin
                    </button>
                </form>
            @endif

            {{-- Surat resmi yang sudah di-TTD admin (tersedia setelah admin kirim) --}}
            @if($internship->admin_signed_pdf_path && in_array($internship->status, [
                \App\Models\Internship::STATUS_SENT_TO_STUDENT,
                \App\Models\Internship::STATUS_SUPERVISOR_ASSIGNED,
                \App\Models\Internship::STATUS_ACCEPTANCE_LETTER_READY,
                \App\Models\Internship::STATUS_ONGOING,
                \App\Models\Internship::STATUS_COMPLETED,
            ]))
                <a href="{{ route('admin.magang.download-signed-pdf', $internship) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-sky-600/20">
                    <span class="material-symbols-outlined text-[16px]">forward_to_inbox</span> Download Surat Resmi (TTD)
                </a>
            @endif

            @if($internship->acceptance_letter_path)
                <a href="{{ route('mahasiswa.magang.download-acceptance', $internship) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-teal-600/20">
                    <span class="material-symbols-outlined text-[16px]">download</span> Download Surat Penerimaan
                </a>
            @endif

            @if(in_array($internship->status, [\App\Models\Internship::STATUS_DRAFT, \App\Models\Internship::STATUS_REJECTED]))
                <form method="POST" action="{{ route('mahasiswa.magang.destroy', $internship) }}" class="inline"
                      onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?')">
                    @csrf @method('DELETE')
                    <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-red-200 hover:bg-red-50 text-red-600 text-sm font-bold rounded-xl transition shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">delete</span> Hapus
                    </button>
                </form>
            @endif
        </div>
    </div>


    {{-- MK Konversi --}}
    @if($internship->courseMappings->isNotEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">library_books</span> Mata Kuliah Konversi
        </h3>
        <div class="overflow-x-auto rounded-2xl border border-gray-100">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kode MK</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nama Mata Kuliah</th>
                        <th class="px-5 py-3.5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">SKS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($internship->courseMappings as $mapping)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-xs text-gray-700">{{ $mapping->mataKuliah?->kode_mk ?? '-' }}</td>
                        <td class="px-5 py-3.5 font-semibold text-gray-800">{{ $mapping->mataKuliah?->nama_mk ?? '-' }}</td>
                        <td class="px-5 py-3.5 text-center font-bold text-purple-600">{{ $mapping->sks }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 border-t border-gray-100">
                        <td class="px-5 py-3.5 font-bold text-gray-700" colspan="2">Total SKS</td>
                        <td class="px-5 py-3.5 text-center font-black text-purple-600">{{ $internship->courseMappings->sum('sks') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif

    {{-- Logbook --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px]">assignment</span> Logbook Magang
            </h3>
            @if($internship->isOngoing())
                <button @click="showLogbookForm = !showLogbookForm"
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-[#8B1538] bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">
                    <span class="material-symbols-outlined text-[14px]">add</span> Tambah Entri
                </button>
            @endif
        </div>

        <div x-show="showLogbookForm" x-cloak class="mb-6 p-5 bg-slate-50 border border-slate-100 rounded-2xl">
            <form method="POST" action="{{ route('mahasiswa.magang.logbook.store', $internship) }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                               class="w-full rounded-xl border-gray-200 bg-white text-sm px-4 py-2.5 focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538]">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 mb-1.5">Kegiatan Hari Ini</label>
                        <textarea name="kegiatan" rows="2" required
                                  class="w-full rounded-xl border-gray-200 bg-white text-sm px-4 py-2.5 focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538]"
                                  placeholder="Deskripsi singkat kegiatan magang hari ini..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#8B1538] text-white text-sm font-bold rounded-xl shadow-sm shadow-red-900/20 hover:bg-[#6D1029] transition">
                        <span class="material-symbols-outlined text-[16px]">save</span> Simpan
                    </button>
                </div>
            </form>
        </div>

        @if($internship->logbooks->isEmpty())
            <div class="text-center py-10">
                <span class="material-symbols-outlined text-4xl text-gray-200 mb-2 block">menu_book</span>
                <p class="text-sm text-gray-400 font-medium">Belum ada entri logbook.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($internship->logbooks->sortByDesc('tanggal') as $log)
                    <div class="p-4 border border-gray-100 hover:border-gray-200 rounded-2xl transition-colors bg-gray-50/30">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="text-xs font-bold text-gray-600">{{ \Carbon\Carbon::parse($log->tanggal)->isoFormat('dddd, D MMMM Y') }}</span>
                            <span class="text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide {{ $log->created_by_role === 'dosen' ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-600' }}">
                                {{ $log->created_by_role === 'dosen' ? 'Dosen' : 'Mahasiswa' }}
                            </span>
                        </div>
                        @if($log->kegiatan)
                            <p class="text-sm text-gray-700">{{ $log->kegiatan }}</p>
                        @endif
                        @if($log->catatan_dosen)
                            <div class="mt-3 pl-3 border-l-2 border-blue-200">
                                <p class="text-xs font-bold text-blue-500 mb-0.5">Catatan Dosen</p>
                                <p class="text-sm text-blue-700">{{ $log->catatan_dosen }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Revisions --}}
    @if($internship->revisions->isNotEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">history</span> Riwayat Revisi
        </h3>
        <div class="space-y-3">
            @foreach($internship->revisions->sortByDesc('revision_no') as $rev)
                <div class="p-5 border border-gray-100 rounded-2xl text-sm bg-gray-50/30">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-7 h-7 rounded-full bg-gray-100 text-gray-600 font-black text-xs flex items-center justify-center">#{{ $rev->revision_no }}</span>
                        <p class="text-xs text-gray-400">{{ $rev->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if($rev->note_from_admin)
                        <div class="mb-2"><span class="text-xs font-bold text-red-500">Admin:</span> <span class="text-gray-700">{{ $rev->note_from_admin }}</span></div>
                    @endif
                    @if($rev->note_from_mahasiswa)
                        <div><span class="text-xs font-bold text-gray-500">Mahasiswa:</span> <span class="text-gray-700">{{ $rev->note_from_mahasiswa }}</span></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

@endsection
