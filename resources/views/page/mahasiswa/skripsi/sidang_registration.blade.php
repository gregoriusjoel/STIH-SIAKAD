@extends('layouts.mahasiswa')
@section('title', 'Pendaftaran Sidang Skripsi')

@section('content')
<div class="px-4 py-6 space-y-5">

    {{-- Header --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-gradient-to-br from-teal-500/5 to-transparent rounded-full blur-3xl"></div>

        <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('mahasiswa.skripsi.index') }}"
                    class="w-10 h-10 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-[#8B1538] transition-all shrink-0 shadow-sm hover:shadow">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-xl font-black text-gray-900 tracking-tight">Pendaftaran Sidang Skripsi</h1>
                    <p class="text-xs text-gray-500 mt-0.5">Upload semua dokumen wajib sebelum mengirim pendaftaran.</p>
                </div>
            </div>
            @if($reg->status === 'submitted')
            <div class="flex items-center gap-2 px-3 py-2 bg-amber-50 border border-amber-200 rounded-xl shrink-0">
                <span class="material-symbols-outlined text-amber-600 text-[18px]">schedule</span>
                <span class="text-xs font-bold text-amber-700">Menunggu Verifikasi</span>
            </div>
            @elseif($reg->status === 'rejected')
            <div class="flex items-center gap-2 px-3 py-2 bg-red-50 border border-red-200 rounded-xl shrink-0">
                <span class="material-symbols-outlined text-red-600 text-[18px]">error</span>
                <span class="text-xs font-bold text-red-700">Ditolak</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Admin rejection note --}}
    @if($reg->status === 'rejected' && $reg->admin_note)
    <div class="bg-red-50 border border-red-200 rounded-2xl p-5 flex items-start gap-3">
        <span class="material-symbols-outlined text-red-600 mt-0.5 shrink-0">report</span>
        <div>
            <p class="text-sm font-bold text-red-800 mb-1">Alasan Penolakan</p>
            <p class="text-sm text-red-700">{{ $reg->admin_note }}</p>
        </div>
    </div>
    @endif

    {{-- Status bar --}}
    @if($reg->status === 'submitted')
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 flex items-start gap-3">
        <span class="material-symbols-outlined text-amber-600 mt-0.5 shrink-0">hourglass_top</span>
        <div>
            <p class="text-sm font-bold text-amber-800 mb-0.5">Pendaftaran Sudah Dikirim</p>
            <p class="text-xs text-amber-600">Menunggu verifikasi dari admin. Pantau halaman ini untuk update status.</p>
        </div>
    </div>
    @endif

    {{-- File upload cards --}}
    @if(in_array($reg->status, ['draft', 'rejected']))
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @foreach($fileTypes as $type)
        @php
            $uploadedFile = $uploaded[$type->value] ?? null;
            $isRequired   = $type->isRequired();
        @endphp
        <div class="bg-white border rounded-2xl shadow-sm overflow-hidden transition-all hover:shadow-md
            {{ $isRequired && !$uploadedFile ? 'border-red-100' : ($uploadedFile ? 'border-emerald-100' : 'border-gray-100') }}">

            {{-- Card header --}}
            <div class="px-5 py-4 flex items-start justify-between gap-3 border-b {{ $uploadedFile ? 'border-emerald-50 bg-emerald-50/30' : 'border-gray-50' }}">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                        {{ $uploadedFile ? 'bg-emerald-100 text-emerald-600' : ($isRequired ? 'bg-red-50 text-red-400' : 'bg-gray-100 text-gray-400') }}">
                        <span class="material-symbols-outlined text-xl">{{ $uploadedFile ? 'check_circle' : 'description' }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">
                            {{ $type->label() }}
                            @if($isRequired)<span class="text-red-500 ml-0.5">*</span>@endif
                        </p>
                        @if($uploadedFile)
                        <p class="text-xs text-emerald-600 mt-0.5 flex items-center gap-1 truncate max-w-[250px]">
                            <span class="material-symbols-outlined text-[13px]">attach_file</span>
                            {{ $uploadedFile->original_name }}
                        </p>
                        @endif
                    </div>
                </div>
                @if($uploadedFile)
                <span class="text-[10px] bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-lg font-bold uppercase tracking-wider shrink-0 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Uploaded
                </span>
                @elseif($isRequired)
                <span class="text-[10px] bg-red-50 text-red-600 px-2.5 py-1 rounded-lg font-bold uppercase tracking-wider shrink-0">Wajib</span>
                @endif
            </div>

            {{-- Upload form --}}
            <form action="{{ route('mahasiswa.skripsi.sidang.upload', $reg->id) }}" method="POST" enctype="multipart/form-data"
                class="px-5 py-4">
                @csrf
                <input type="hidden" name="file_type" value="{{ $type->value }}">
                <div class="flex items-center gap-3">
                    <label class="flex-1 relative">
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.png"
                            class="block w-full text-xs text-gray-500
                                file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0
                                file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700
                                hover:file:bg-gray-200 file:cursor-pointer file:transition-colors"
                            required>
                    </label>
                    <button type="submit"
                        class="shrink-0 inline-flex items-center gap-1.5 bg-[#8B1538] text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-[#6D1029] transition-all shadow-sm hover:shadow">
                        <span class="material-symbols-outlined text-[16px]">upload</span>
                        Upload
                    </button>
                </div>
            </form>
        </div>
        @endforeach
    </div>

    {{-- Completeness check --}}
    @php
        $requiredTypes    = ['file_skripsi', 'file_ppt', 'form_sidang', 'transkrip'];
        $uploadedTypes    = $uploaded->keys()->toArray();
        $missingRequired  = array_diff($requiredTypes, $uploadedTypes);
        $canSubmit        = empty($missingRequired);
    @endphp

    @if(!$canSubmit)
    <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5 flex items-start gap-3">
        <span class="material-symbols-outlined text-orange-600 mt-0.5 shrink-0">warning</span>
        <div>
            <p class="text-sm font-bold text-orange-800 mb-1.5">Dokumen wajib yang belum diupload:</p>
            <ul class="space-y-1">
                @foreach($missingRequired as $mt)
                <li class="text-sm text-orange-700 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-orange-400 shrink-0"></span>
                    {{ \App\Domain\Thesis\Enums\SidangFileType::from($mt)->label() }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- Submit Registration --}}
    @if($canSubmit)
    <div class="bg-white border border-emerald-200 rounded-2xl p-6 shadow-sm">
        <div class="flex items-start gap-4 mb-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-2xl text-emerald-600">verified</span>
            </div>
            <div>
                <p class="text-base font-bold text-gray-900">Semua Dokumen Wajib Sudah Lengkap</p>
                <p class="text-sm text-gray-500 mt-0.5">Klik tombol di bawah untuk mengirim pendaftaran sidang ke admin.</p>
            </div>
        </div>
        <form action="{{ route('mahasiswa.skripsi.sidang.submit', $reg->id) }}" method="POST">
            @csrf
            <button type="submit"
                onclick="return confirm('Yakin ingin mengirim pendaftaran sidang? Pastikan semua file sudah benar.')"
                class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 text-white py-3 rounded-xl font-bold text-sm hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-600/20 transition-all hover:-translate-y-0.5 active:translate-y-0">
                <span class="material-symbols-outlined text-[20px]">send</span>
                Kirim Pendaftaran Sidang
            </button>
        </form>
    </div>
    @endif

    @else
    {{-- Submitted view: show uploaded files read-only --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px] text-gray-400">folder</span>
            </div>
            <h2 class="font-bold text-gray-700 text-sm">Dokumen yang Dikirim</h2>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($uploaded as $file)
            <div class="px-5 py-3.5 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-500">
                        <span class="material-symbols-outlined text-[18px]">description</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-800">{{ $file->file_type->label() }}</span>
                </div>
                <span class="text-xs text-gray-400 truncate max-w-[200px]">{{ $file->original_name }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
