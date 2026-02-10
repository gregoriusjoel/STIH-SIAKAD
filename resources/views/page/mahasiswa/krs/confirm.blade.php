@extends('layouts.mahasiswa')

@section('title', 'Konfirmasi KRS')

@section('content')
    <div class="max-w-[1600px] w-full mx-auto space-y-6">

        <!-- Title Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Konfirmasi KRS</h1>
                <p class="text-sm text-gray-500">Finalisasi rencana studi Anda untuk semester ini.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Profile Card -->
            <div class="lg:col-span-1 h-fit">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 flex flex-col items-center border-b border-gray-100">
                        <div
                            class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4 overflow-hidden shadow-inner ring-4 ring-gray-50">
                            @php $foto = $mahasiswa->foto ?? null; @endphp
                            @if(!empty($foto))
                                <img src="{{ asset('storage/' . $foto) }}" alt="Profile" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-user text-4xl text-gray-300"></i>
                            @endif
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 text-center leading-tight mb-1">
                            {{ $mahasiswa->nama ?? $mahasiswa->user->name ?? '-' }}
                        </h3>
                        <p class="text-sm text-gray-500 font-medium">{{ $mahasiswa->nim ?? ($mahasiswa->user->nim ?? '-') }}
                        </p>
                    </div>
                    <div class="p-6 space-y-4 bg-gray-50/50">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Program Studi</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $mahasiswa->prodi ?? '-' }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Angkatan</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $mahasiswa->angkatan ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Status</p>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded textxs font-bold bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Action & Info -->
            <div class="lg:col-span-2 space-y-6">
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8 flex flex-col h-full relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-50 to-transparent rounded-bl-full -mr-8 -mt-8 opacity-50 pointer-events-none">
                    </div>

                    <div class="relative z-10">
                        <div class="mb-8">
                            <span
                                class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold uppercase tracking-wide mb-2">
                                Pengisian KRS
                            </span>
                            <h2 class="text-3xl font-bold text-gray-900 capitalize mb-1">
                                Semester {{ $semesterAktif->semester_number ?? 1 }}
                            </h2>
                            <p class="text-gray-500">
                                {{ $semesterAktif->nama_semester ?? 'Ganjil' }} {{ $semesterAktif->tahun_ajaran }}
                            </p>
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
                                <a href="{{ route('mahasiswa.krs.index', ['view_only' => 1]) }}"
                                    class="flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg transition-colors shadow-md">
                                    Review KRS
                                </a>
                                <a href="{{ route('mahasiswa.dashboard') }}"
                                    class="flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 md:py-4 md:text-lg transition-colors">
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
                                <a href="{{ route('mahasiswa.krs.index') }}"
                                    class="flex items-center justify-center px-6 py-3 border border- bg-maroon text-base font-medium rounded-lg text-white bg-yellow-600 hover:bg-yellow-700 md:py-4 md:text-lg transition-colors shadow-md">
                                    Lanjutkan Pengisian
                                </a>
                                <a href="{{ route('mahasiswa.dashboard') }}"
                                    class="flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 md:py-4 md:text-lg transition-colors">
                                    Kembali ke Dashboard
                                </a>
                            </div>
                        @else
                            <div class="bg-gray-50 p-4 rounded-lg mb-6 text-center">
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    Silakan klik <strong>Isi KRS Sekarang</strong> untuk memulai pengisian.
                                    Setelah menyelesaikan pengisian dan mengajukan KRS, Anda dapat mengunduh/cetak KRS untuk
                                    semester terkait.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-auto">
                                <a href="{{ route('mahasiswa.krs.index') }}?start=1"
                                    class="flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg transition-colors shadow-md">
                                    Isi KRS Sekarang
                                </a>
                                <a href="{{ route('mahasiswa.dashboard') }}"
                                    class="flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 md:py-4 md:text-lg transition-colors">
                                    Kembali ke Dashboard
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom: History -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4 px-1">Download KRS Semester Lalu</h3>



            <div class="border border-gray-200 dark:border-slate-700 rounded-xl overflow-hidden shadow-sm bg-white dark:bg-[#1a1d2e]">
                {{-- Desktop View --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                        <thead class="bg-gray-50 dark:bg-slate-800">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">
                                    Semester
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">
                                    Tahun Ajaran
                                </th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-[#1a1d2e] divide-y divide-gray-200 dark:divide-slate-700">
                            @forelse($semesterList as $sem)
                                @php
                                    $canDownload = in_array($sem->semester_number ?? $sem->id, $downloadable);
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white capitalize">
                                        Semester {{ $sem->semester_number ?? 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-slate-300">
                                        {{ $sem->tahun_ajaran ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if($canDownload)
                                            <a href="{{ route('mahasiswa.krs.print', ['semester_id' => $sem->semester_number ?? $sem->id]) }}" 
                                               target="_blank"
                                               class="inline-flex items-center px-4 py-2 border border-maroon text-sm font-medium rounded-lg text-maroon bg-white hover:bg-maroon hover:text-white transition-all duration-200 shadow-sm whitespace-nowrap group">
                                                <i class="fas fa-print mr-2 group-hover:text-white transition-colors"></i> Download KRS
                                            </a>
                                        @else
                                            <span class="inline-flex items-center px-4 py-2 border border-gray-200 dark:border-slate-700 text-sm font-medium rounded-lg text-gray-400 dark:text-slate-600 bg-gray-50 dark:bg-slate-800 cursor-not-allowed whitespace-nowrap">
                                                <i class="fas fa-lock mr-2"></i> Belum Tersedia
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-gray-500 dark:text-slate-400 whitespace-nowrap">
                                        <div class="flex flex-col items-center gap-2">
                                            <i class="fas fa-file-invoice text-4xl text-gray-300 dark:text-slate-600"></i>
                                            <p>Belum ada riwayat KRS.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile View (Card Layout) --}}
                <div class="md:hidden">
                    <div class="divide-y divide-gray-200 dark:divide-slate-700">
                        @forelse($semesterList as $sem)
                            @php
                                $canDownload = in_array($sem->semester_number ?? $sem->id, $downloadable);
                            @endphp
                            <div class="p-5 space-y-4 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-base font-bold text-gray-900 dark:text-white">Semester {{ $sem->semester_number ?? 1 }}</h4>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 border border-gray-200 dark:border-slate-700">
                                                TA {{ $sem->tahun_ajaran ?? '-' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="w-8 h-8 rounded-full bg-maroon/10 flex items-center justify-center text-maroon">
                                        <i class="fas fa-history text-sm"></i>
                                    </div>
                                </div>
                                
                                <div>
                                    @if($canDownload)
                                        <a href="{{ route('mahasiswa.krs.print', ['semester_id' => $sem->semester_number ?? $sem->id]) }}" 
                                           target="_blank"
                                           class="flex items-center justify-center w-full px-4 py-2.5 border border-maroon text-sm font-bold rounded-xl text-maroon bg-white hover:bg-maroon hover:text-white transition-all duration-200 shadow-sm group">
                                            <i class="fas fa-print mr-2"></i> Download KRS
                                        </a>
                                    @else
                                        <button disabled class="flex items-center justify-center w-full px-4 py-2.5 border border-gray-200 dark:border-slate-700 text-sm font-medium rounded-xl text-gray-400 dark:text-slate-600 bg-gray-50 dark:bg-slate-800 cursor-not-allowed">
                                            <i class="fas fa-lock mr-2"></i> Belum Tersedia
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center text-gray-500 dark:text-slate-400">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-slate-800 flex items-center justify-center text-gray-400 dark:text-slate-500">
                                        <i class="fas fa-file-invoice text-xl"></i>
                                    </div>
                                    <p class="text-sm">Belum ada riwayat KRS.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection