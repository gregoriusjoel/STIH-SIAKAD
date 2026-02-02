@extends('layouts.blank')

@section('title', 'Terima Kasih | STIH')

@section('content')
    <div
        class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 font-sans antialiased text-gray-900">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden text-center">

                <div class="bg-[#8B1538] py-8 px-4 flex flex-col items-center justify-center">
                    <img src="{{ asset('images/logo_stih_white.png') }}" alt="STIH Logo" class="h-16 w-auto mb-2">
                </div>

                <div class="px-8 py-10 space-y-6">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                        <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <h2 class="text-3xl font-extrabold text-gray-900">Validasi Berhasil!</h2>

                    <p class="text-gray-500 text-lg">
                        Data kehadiran Anda telah berhasil disimpan ke dalam sistem.
                    </p>

                    @if(session('info'))
                        <div class="rounded-md bg-blue-50 p-4 mt-4 text-left">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm text-blue-700">{{ session('info') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(!empty($materials))
                        <div class="text-left mt-8">
                            <h3 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#8B1538]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Materi Pertemuan {{ $pertemuan }}
                            </h3>
                            <div class="grid gap-3">
                                @foreach($materials as $material)
                                    <a href="{{ $material['url'] }}" target="_blank"
                                        class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-gray-50 hover:bg-white hover:border-[#8B1538]/30 hover:shadow-sm transition-all group">
                                        <div class="w-8 h-8 rounded-lg bg-red-100 text-[#8B1538] flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-bold text-gray-800 group-hover:text-[#8B1538] transition-colors">
                                                {{ $material['name'] }}
                                            </p>
                                            <p class="text-xs text-gray-500">Klik untuk membuka</p>
                                        </div>
                                        <svg class="w-4 h-4 text-gray-300 group-hover:text-[#8B1538]" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-8 border-t border-gray-100 pt-6 space-y-4">
                        <p class="text-sm text-gray-400">Anda dapat menutup halaman ini sekarang.</p>

                        @if(isset($kelasId) && auth()->check())
                            @if(auth()->user()->role == 'dosen')
                                <a href="{{ route('dosen.kelas.detail', $kelasId) }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-[#8B1538] bg-pink-50 hover:bg-pink-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8B1538] transition">
                                    <svg class="mr-2 -ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Kembali ke Detail Kelas
                                </a>
                            @elseif(auth()->user()->mahasiswa)
                                <a href="{{ route('mahasiswa.kelas.show', $kelasId) }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-[#8B1538] bg-pink-50 hover:bg-pink-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8B1538] transition">
                                    <svg class="mr-2 -ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Kembali ke Detail Kelas
                                </a>
                            @else
                                <button onclick="history.back()"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-[#8B1538] bg-pink-50 hover:bg-pink-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8B1538] transition">
                                    <svg class="mr-2 -ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Kembali ke Form Absensi
                                </button>
                            @endif
                        @else
                            <button onclick="history.back()"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-[#8B1538] bg-pink-50 hover:bg-pink-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8B1538] transition">
                                <svg class="mr-2 -ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali ke Form Absensi
                            </button>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 px-8 py-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500">&copy; {{ date('Y') }} STIH Dashboard System.</p>
                </div>
            </div>
        </div>
    </div>
@endsection