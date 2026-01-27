@extends('layouts.blank')

@section('title', 'Isi Absensi | STIH')

@section('content')
<div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8 font-sans antialiased text-gray-900">
    <!-- Main Card Container -->
    <div class="mx-auto w-full max-w-md sm:max-w-xl md:max-w-2xl lg:max-w-3xl">
        <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-200">
            
            <!-- 1. Logo STIH Header -->
            <div class="bg-[#8B1538] py-6 px-4 flex flex-col items-center justify-center text-center">
                <div class="bg-white/10 p-2 rounded-lg backdrop-blur-sm mb-3">
                    <img src="{{ asset('images/logo_stih_white.png') }}" alt="STIH Logo" class="h-16 w-auto">
                </div>
                <h2 class="text-2xl font-bold text-white tracking-wide uppercase">Daftar Hadir</h2>
                <p class="text-pink-100 text-xs mt-1 font-medium">Silakan isi data diri Anda untuk presensi</p>
            </div>

            <!-- 2. Class Details -->
            <div class="px-6 py-5 bg-gray-50 border-b border-gray-100">
                <div class="space-y-3">
                    <!-- Title -->
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-0.5">Mata Kuliah</span>
                        <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ $kelas->mataKuliah->nama_mk ?? '-' }}</h3>
                    </div>

                    <!-- Grid Info -->
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-0.5">Kelas</span>
                            <span class="font-semibold text-gray-800">{{ $kelas->nama_kelas ?? $kelas->kode_kelas ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-0.5">Ruangan</span>
                            <span class="font-semibold text-gray-800">{{ $kelas->ruang ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 text-sm">
                         <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-0.5">Dosen Pengampu</span>
                            <span class="font-semibold text-gray-800">{{ $kelas->dosen->user->name ?? $kelas->dosen->nidn ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-0.5">Waktu</span>
                            <span class="font-semibold text-gray-800">
                                {{ $kelas->hari ?? '' }}, {{ substr($kelas->jam_mulai, 0, 5) }} - {{ substr($kelas->jam_selesai, 0, 5) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <div class="px-6 py-6">
                <form method="POST" action="{{ route('absensi.submit', ['token' => $token]) }}" class="space-y-5">
                    @csrf

                    @if(! (auth()->check() && auth()->user()->mahasiswa))
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">NPM</label>
                            <input type="text" name="npm" value="{{ old('npm') }}" placeholder="Contoh: 2023001"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-[#8B1538] focus:border-transparent transition duration-150 ease-in-out text-sm">
                            @error('npm')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Sesuai KTM"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-[#8B1538] focus:border-transparent transition duration-150 ease-in-out text-sm">
                            @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Kontak (HP/WA)</label>
                            <input type="text" name="kontak" value="{{ old('kontak') }}" placeholder="0812..."
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-[#8B1538] focus:border-transparent transition duration-150 ease-in-out text-sm">
                            @error('kontak')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    @else
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex items-start space-x-3">
                             <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-[#8B1538]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Login sebagai {{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">Data Anda akan terisi otomatis.</p>
                                <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                                @php $userPhone = auth()->user()->mahasiswa?->phone ?? auth()->user()->phone ?? null; @endphp
                                <input type="hidden" name="kontak" value="{{ $userPhone }}">
                            </div>
                        </div>
                    @endif

                    <div>
                        @php
                            $totalPertemuan = $totalPertemuan ?? $class?->total_pertemuan ?? 16;
                            $selectedPertemuan = old('pertemuan', request('pertemuan', $currentPertemuan ?? 1));
                        @endphp
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Pertemuan</label>
                        <select name="pertemuan" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-[#8B1538] focus:border-transparent transition duration-150 ease-in-out text-sm">
                            @for($i = 1; $i <= $totalPertemuan; $i++)
                                <option value="{{ $i }}" {{ (int)$selectedPertemuan === $i ? 'selected' : '' }}>Pertemuan {{ $i }}</option>
                            @endfor
                        </select>
                        @error('pertemuan')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Keterangan (Opsional)</label>
                        <textarea name="keterangan" rows="2" placeholder="Sakit / Izin / Catatan lain..."
                             class="w-full px-4 py-2.5 rounded-lg border border-gray-300 placeholder-gray-400 focus:ring-2 focus:ring-[#8B1538] focus:border-transparent transition duration-150 ease-in-out text-sm resize-none">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                            class="w-full px-6 py-3 bg-[#8B1538] hover:bg-[#72112e] text-white font-bold rounded-lg shadow-md hover:shadow-lg transition duration-200 ease-in-out transform hover:-translate-y-0.5 text-sm uppercase tracking-wider">
                            Kirim Absensi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 py-3 text-center border-t border-gray-100">
                <p class="text-[10px] text-gray-400">&copy; {{ date('Y') }} STIH Dashboard System.</p>
            </div>
        </div>
    </div>
</div>
@endsection