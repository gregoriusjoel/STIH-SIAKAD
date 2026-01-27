@extends('layouts.mahasiswa')

@section('title', 'Konfirmasi KRS')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-4xl w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl">
        
        <!-- Header Title -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Konfirmasi Pengisian KRS</h1>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
            <p class="font-bold">Berhasil</p>
            <p>{{ session('success') }}</p>
        </div>
        @endif

        <div class="flex flex-col md:flex-row gap-8 mb-10">
            <!-- Left: Profile Card -->
            <div class="w-full md:w-1/3 flex flex-col items-center justify-center p-6 border border-gray-200 rounded-xl bg-white shadow-sm">
                <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mb-4 overflow-hidden">
                    @if($mahasiswa->foto_profil)
                        <img src="{{ asset('storage/' . $mahasiswa->foto_profil) }}" alt="Profile" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-user text-4xl text-gray-400"></i>
                    @endif
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Nama</p>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">{{ $mahasiswa->nama }}</h3>
                    
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">NPM</p>
                    <p class="text-md font-medium text-gray-800">{{ $mahasiswa->npm }}</p>
                </div>
            </div>

            <!-- Right: Action & Info -->
            <div class="w-full md:w-2/3 flex flex-col justify-between border border-gray-200 rounded-xl p-6 bg-white shadow-sm">
                <div class="text-center mb-6">
                    <p class="text-sm text-gray-500 mb-1">Pengisian KRS {{ $semesterAktif->tahun_ajaran }}</p>
                    <h2 class="text-xl font-bold text-gray-900 capitalize">
                        {{ $semesterAktif->nama_semester == 'Ganjil' ? 'Semester 1' : ($semesterAktif->nama_semester == 'Genap' ? 'Semester 2' : $semesterAktif->nama_semester) }} 
                        {{ $semesterAktif->tahun_ajaran }}
                    </h2>
                </div>

                @if(isset($alreadySubmitted) && $alreadySubmitted)
                <div class="bg-green-50 p-4 rounded-lg mb-6 text-center border border-green-200">
                    <p class="text-sm text-green-700 leading-relaxed font-medium">
                        <i class="fas fa-check-circle mr-1"></i>
                        Anda telah menyelesaikan pengisian KRS untuk semester ini.
                        Silakan unduh KRS Anda pada tabel di bawah ini atau tinjau kembali pilihan Anda.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-auto">
                    <a href="{{ route('mahasiswa.krs.index', ['view_only' => 1]) }}" class="flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg transition-colors shadow-md">
                        Review KRS
                    </a>
                    <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 md:py-4 md:text-lg transition-colors">
                        Kembali ke Dashboard
                    </a>
                </div>
                @elseif(isset($hasDraft) && $hasDraft)
                <div class="bg-yellow-50 p-4 rounded-lg mb-6 text-center border border-yellow-200">
                    <p class="text-sm text-yellow-800 leading-relaxed">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        Anda memiliki <strong>Draft KRS</strong> yang belum diajukan. 
                        Silakan lanjutkan pengisian untuk memfinalisasi rencana studi Anda.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-auto">
                    <a href="{{ route('mahasiswa.krs.index') }}" class="flex items-center justify-center px-6 py-3 border border- bg-maroon text-base font-medium rounded-lg text-white bg-yellow-600 hover:bg-yellow-700 md:py-4 md:text-lg transition-colors shadow-md">
                        Lanjutkan Pengisian
                    </a>
                    <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 md:py-4 md:text-lg transition-colors">
                        Kembali ke Dashboard
                    </a>
                </div>
                @else
                <div class="bg-gray-50 p-4 rounded-lg mb-6 text-center">
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Silakan klik <strong>Isi KRS Sekarang</strong> untuk memulai pengisian. 
                        Setelah menyelesaikan pengisian dan mengajukan KRS, Anda dapat mengunduh/cetak KRS untuk semester terkait.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-auto">
                    <a href="{{ route('mahasiswa.krs.index') }}?start=1" class="flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg transition-colors shadow-md">
                        Isi KRS Sekarang
                    </a>
                    <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 md:py-4 md:text-lg transition-colors">
                        Kembali ke Dashboard
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Bottom: History -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4 px-1">Download KRS Semester Lalu</h3>
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Semester
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tahun Ajaran
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($semesterList as $sem)
                            @php
                                $canDownload = in_array($sem->id, $downloadable);
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 capitalize">
                                    {{ $sem->nama_semester == 'Ganjil' ? 'Semester 1' : ($sem->nama_semester == 'Genap' ? 'Semester 2' : $sem->nama_semester) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $sem->tahun_ajaran }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($canDownload)
                                        <a href="{{ route('mahasiswa.krs.print', ['semester_id' => $sem->id]) }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-yellow-900 bg-yellow-400 hover:bg-yellow-500 shadow-sm">
                                            Download/Cetak KRS
                                        </a>
                                    @else
                                        <span class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                                            Download/Cetak KRS
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada riwayat KRS.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>
@endsection