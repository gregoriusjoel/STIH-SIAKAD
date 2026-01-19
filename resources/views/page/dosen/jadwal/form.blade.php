@extends('layouts.app')

@section('title', 'Input Jadwal Mengajar')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
</style>
@endpush

@section('content')
<div class="flex flex-col min-w-0 h-full">
    <div class="p-0 w-full flex flex-col gap-0">
        <!-- Full Page Card -->
        <div class="bg-white dark:bg-[#1a1d2e] rounded-xl border border-[#dbdde6] dark:border-slate-800 p-0 shadow-none w-full min-h-[calc(100vh-80px)] flex flex-col justify-center">
            <!-- Header inside Card -->
            <div class="text-center mb-6 pt-8 md:pt-0">
                <div class="size-16 rounded-full bg-gradient-to-br from-[#8B1538] to-[#701230] flex items-center justify-center bg-white mx-auto mb-4">
                    <span class="material-symbols-outlined text-3xl text-black">calendar_add_on</span>
                </div>
                <h1 class="text-2xl font-black text-[#111218] dark:text-white">Input Jadwal Mengajar</h1>
                <p class="text-[#616889] dark:text-slate-400 text-sm mt-1">Anda belum memiliki jadwal. Silakan ajukan jadwal mengajar baru.</p>
            </div>

            <!-- Alert -->
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2 mb-4 mx-auto max-w-xl">
                <span class="material-symbols-outlined text-lg">check_circle</span>
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 mx-auto max-w-xl">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Form -->
            <form action="{{ route('dosen.jadwal.store') }}" method="POST" class="space-y-6 w-full pb-12 px-6 md:px-10">
                @csrf

                <!-- If admin created kelas for this dosen, show those first -->
                @if(!empty($kelasMatKuliahs) && $kelasMatKuliahs->count())
                <div>
                    <label class="block text-sm font-semibold text-[#111218] dark:text-white mb-2">
                        Kelas (ditetapkan admin) <span class="text-red-500">*</span>
                    </label>
                    <select name="kelas_mata_kuliah_id" class="w-full px-6 py-4 border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-[#111218] dark:text-white focus:outline-none focus:border-[#8B1538] focus:ring-1 focus:ring-[#8B1538]">
                        <option value="">Pilih Kelas</option>
                        @foreach($kelasMatKuliahs as $k)
                            <option value="{{ $k->id }}" {{ old('kelas_mata_kuliah_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->mataKuliah->kode_mk }} - {{ $k->mataKuliah->nama_mk }} (Kls {{ $k->kode_kelas ?? '-' }}) - {{ $k->semester?->nama_semester ?? '' }} {{ $k->semester?->tahun_ajaran ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-2">Anda juga bisa memilih mata kuliah langsung jika belum ada kelas yang ditetapkan admin.</p>
                </div>
                @endif


                <div class="space-y-5">
                    <!-- Mata Kuliah (fallback) -->
                    <div>
                        <label class="block text-sm font-semibold text-[#111218] dark:text-white mb-2">
                            Mata Kuliah <span class="text-red-500">*</span>
                        </label>
                        <select name="mata_kuliah_id" {{ (!empty($kelasMatKuliahs) && $kelasMatKuliahs->count()) ? '' : 'required' }}
                            class="w-full px-6 py-4 border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-[#111218] dark:text-white focus:outline-none focus:border-[#8B1538] focus:ring-1 focus:ring-[#8B1538]">
                            <option value="">Pilih Mata Kuliah</option>
                            @foreach($mataKuliahs as $mk)
                                <option value="{{ $mk->id }}" {{ old('mata_kuliah_id') == $mk->id ? 'selected' : '' }}>
                                    {{ $mk->kode_mk }} - {{ $mk->nama_mk }} ({{ $mk->sks }} SKS)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Hari -->
                    <div>
                        <label class="block text-sm font-semibold text-[#111218] dark:text-white mb-2">
                            Hari <span class="text-red-500">*</span>
                        </label>
                        <select name="hari" required 
                            class="w-full px-6 py-4 border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-[#111218] dark:text-white focus:outline-none focus:border-[#8B1538] focus:ring-1 focus:ring-[#8B1538]">
                            <option value="">Pilih Hari</option>
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                                <option value="{{ $hari }}" {{ old('hari') == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Time Range -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#111218] dark:text-white mb-2">
                                Jam Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="jam_mulai" required value="{{ old('jam_mulai') }}"
                                class="w-full px-6 py-4 border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-[#111218] dark:text-white focus:outline-none focus:border-[#8B1538] focus:ring-1 focus:ring-[#8B1538]">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#111218] dark:text-white mb-2">
                                Jam Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="jam_selesai" required value="{{ old('jam_selesai') }}"
                                class="w-full px-6 py-4 border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-[#111218] dark:text-white focus:outline-none focus:border-[#8B1538] focus:ring-1 focus:ring-[#8B1538]">
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-semibold text-[#111218] dark:text-white mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea name="catatan_dosen" rows="3" placeholder="Tambahkan catatan untuk admin..."
                            class="w-full px-6 py-4 border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-[#111218] dark:text-white focus:outline-none focus:border-[#8B1538] focus:ring-1 focus:ring-[#8B1538] resize-none">{{ old('catatan_dosen') }}</textarea>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 flex gap-3 mb-4 mt-4">
                    <span class="material-symbols-outlined text-yellow-500">info</span>
                    <div class="text-sm text-yellow-800">
                        <p class="font-bold mb-1">Perhatian</p>
                        <p>
                            Setelah mengajukan jadwal, Anda perlu menunggu approval dari admin.
                            Admin akan menetapkan <strong>kelas</strong> dan <strong>ruangan</strong>
                            setelah jadwal disetujui.
                        </p>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                    class="w-full py-3 bg-gradient-to-r from-primary to-red-900 text-white font-semibold rounded-lg hover:opacity-90 transition-opacity flex items-center justify-center gap-2 mt-4">
                    <span class="material-symbols-outlined">send</span>
                    Ajukan Jadwal
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
