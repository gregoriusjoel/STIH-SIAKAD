@extends('layouts.mahasiswa')
@section('title', 'Pendaftaran Sidang Skripsi')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8 space-y-6">

    <div>
        <a href="{{ route('mahasiswa.thesis.index') }}"
            class="text-sm text-gray-500 hover:text-red-900 flex items-center gap-1 mb-3">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        <h1 class="text-2xl font-black text-gray-900">Pendaftaran Sidang Skripsi</h1>
        <p class="text-sm text-gray-500 mt-1">Upload semua dokumen wajib sebelum mengirim pendaftaran.</p>
    </div>

    {{-- Admin rejection note --}}
    @if($reg->status === 'rejected' && $reg->admin_note)
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-800">
        <strong>Ditolak:</strong> {{ $reg->admin_note }}
    </div>
    @endif

    {{-- Status bar --}}
    @if($reg->status === 'submitted')
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-sm text-yellow-800">
        ⏳ Pendaftaran sudah dikirim. Menunggu verifikasi admin.
    </div>
    @endif

    {{-- File upload cards --}}
    @if(in_array($reg->status, ['draft', 'rejected']))
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($fileTypes as $type)
        @php
            $uploadedFile = $uploaded[$type->value] ?? null;
            $isRequired   = $type->isRequired();
        @endphp
        <div class="bg-white border {{ $isRequired ? 'border-red-100' : 'border-gray-100' }} rounded-xl p-4 shadow-sm">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-sm font-semibold text-gray-800">
                        {{ $type->label() }}
                        @if($isRequired)<span class="text-red-500 ml-0.5">*</span>@endif
                    </p>
                    @if($uploadedFile)
                    <p class="text-xs text-green-600 mt-0.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $uploadedFile->original_name }}
                    </p>
                    @endif
                </div>
                @if($uploadedFile)
                <span class="text-xs bg-green-50 text-green-600 px-2 py-0.5 rounded-full font-medium">✓ Uploaded</span>
                @elseif($isRequired)
                <span class="text-xs bg-red-50 text-red-600 px-2 py-0.5 rounded-full font-medium">Wajib</span>
                @endif
            </div>
            <form action="{{ route('mahasiswa.thesis.sidang.upload', $reg->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="file_type" value="{{ $type->value }}">
                <div class="flex gap-2">
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.png"
                        class="flex-1 text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:bg-gray-50 file:text-gray-600"
                        required>
                    <button type="submit" class="shrink-0 bg-red-900 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-red-800">
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
    <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 text-sm text-orange-800">
        Dokumen wajib yang belum diupload:
        <ul class="list-disc ml-4 mt-1">
            @foreach($missingRequired as $mt)
            <li>{{ \App\Domain\Thesis\Enums\SidangFileType::from($mt)->label() }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Submit Registration --}}
    @if($canSubmit)
    <div class="bg-white border border-green-200 rounded-xl p-5">
        <p class="font-semibold text-gray-800 mb-1">✅ Semua dokumen wajib sudah lengkap</p>
        <p class="text-sm text-gray-500 mb-3">Klik tombol di bawah untuk mengirim pendaftaran sidang ke admin.</p>
        <form action="{{ route('mahasiswa.thesis.sidang.submit', $reg->id) }}" method="POST">
            @csrf
            <button type="submit"
                onclick="return confirm('Yakin ingin mengirim pendaftaran sidang? Pastikan semua file sudah benar.')"
                class="w-full bg-green-700 text-white py-2.5 rounded-lg font-semibold text-sm hover:bg-green-600 transition">
                Kirim Pendaftaran Sidang
            </button>
        </form>
    </div>
    @endif

    @else
    {{-- Submitted view: show uploaded files read-only --}}
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5">
        <h2 class="font-bold text-gray-700 mb-4">Dokumen yang Dikirim</h2>
        <ul class="space-y-2">
            @foreach($uploaded as $file)
            <li class="flex items-center justify-between text-sm">
                <span class="text-gray-700">{{ $file->file_type->label() }}</span>
                <span class="text-gray-500 text-xs">{{ $file->original_name }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

</div>
@endsection
