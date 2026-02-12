@extends('layouts.mahasiswa')

@section('title', 'Dashboard Mahasiswa')
@section('page-title', 'Dashboard')

@push('styles')
    <style>
        .stat-card-hover {
            transition: all 0.3s ease;
        }
        .stat-card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.1);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        .dark .glass-effect {
            background: rgba(30, 41, 59, 0.9);
        }
    </style>
@endpush

@section('content')
    @php
        $isProfileComplete = $mahasiswa->isProfileComplete();
        $studentSemester = $mahasiswa->semester ?? $mahasiswa->getCurrentSemester();
    @endphp

    <div class="space-y-8 animate-fade-in-up">

        {{-- Profile Incomplete Warning --}}
        @if(!$isProfileComplete)
            <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-xl p-6 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-red-100 dark:bg-red-900/40 rounded-full text-red-600 dark:text-red-400">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-red-900 dark:text-red-300 mb-1">Profil Belum Lengkap!</h4>
                        <p class="text-sm text-red-700 dark:text-red-400 mb-4 leading-relaxed">
                            Mohon lengkapi data profil Anda untuk membuka akses ke fitur akademik seperti KRS, Kartu Hasil Studi, dan Jadwal Kuliah.
                        </p>
                        <a href="{{ route('mahasiswa.profil.manajemen') }}"
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-sm font-medium shadow-md shadow-red-600/20">
                            <i class="fas fa-user-edit mr-2"></i>
                            Lengkapi Profil Sekarang
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- Hero Welcome Card --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#8B1538] via-[#6D1029] to-[#4a0b1d] shadow-xl text-white">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-64 h-64 rounded-full bg-black/20 blur-3xl"></div>
            
            <!-- Pattern/Watermark -->
            <div class="absolute right-0 bottom-0 opacity-5 transform translate-x-10 translate-y-10 pointer-events-none">
                <i class="fas fa-university text-[15rem]"></i>
            </div>
            
            <div class="relative p-8 md:p-10 flex flex-col md:flex-row items-center gap-8 z-10">
                <div class="relative group">
                    <div class="w-24 h-24 md:w-28 md:h-28 rounded-full p-1 bg-white/20 backdrop-blur-sm shadow-inner">
                         @if(!empty($mahasiswa->foto))
                            <img src="{{ asset('storage/' . $mahasiswa->foto) }}" alt="Foto Profil"
                                class="w-full h-full rounded-full object-cover border-2 border-white/50 shadow-lg">
                        @else
                            <div class="w-full h-full rounded-full bg-white/10 flex items-center justify-center border-2 border-white/30 text-3xl font-bold">
                                {{ strtoupper(substr($mahasiswa->user->name ?? 'M', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="absolute bottom-1 right-1 w-6 h-6 bg-green-400 border-2 border-[#8B1538] rounded-full" title="Status Aktif"></div>
                </div>

                <div class="flex-1 text-center md:text-left space-y-2">
                    <h1 class="text-2xl md:text-3xl font-bold tracking-tight">
                        Halo, {{ $mahasiswa->user->name ?? 'Mahasiswa' }}!
                    </h1>
                    <p class="text-white/80 text-sm md:text-base font-medium max-w-2xl">
                        Selamat datang kembali di Portal Akademik. Tetap semangat menjalani perkuliahan di semester ini!
                    </p>
                    
                    <div class="flex flex-wrap justify-center md:justify-start gap-3 mt-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-xs font-medium">
                            <i class="fas fa-id-card mr-2 opacity-70"></i> {{ $mahasiswa->nim }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-xs font-medium">
                            <i class="fas fa-university mr-2 opacity-70"></i> {{ $mahasiswa->prodi ?? '-' }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-xs font-medium">
                            <i class="fas fa-calendar-check mr-2 opacity-70"></i> Angkatan {{ $mahasiswa->angkatan ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Academic Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- SKS Card --}}
            <div class="stat-card-hover bg-white dark:bg-[#1a1c23] p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fas fa-book text-6xl text-blue-600"></i>
                </div>
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 flex items-center justify-center">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total SKS</span>
                </div>
                <div class="mt-2">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalSks }}</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-1">SKS Diambil</span>
                </div>
            </div>

            {{-- IPK Card --}}
             <div class="stat-card-hover bg-white dark:bg-[#1a1c23] p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fas fa-graduation-cap text-6xl text-purple-600"></i>
                </div>
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/20 text-purple-600 flex items-center justify-center">
                        <i class="fas fa-award"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">IPK Saat Ini</span>
                </div>
                <div class="mt-2">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($ipk, 2) }}</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-1">Indeks Prestasi</span>
                </div>
            </div>

            {{-- Semester Card --}}
            <div class="stat-card-hover bg-white dark:bg-[#1a1c23] p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fas fa-layer-group text-6xl text-orange-600"></i>
                </div>
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-900/20 text-orange-600 flex items-center justify-center">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Semester</span>
                </div>
                <div class="mt-2">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $studentSemester }}</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-1">Semester Aktif</span>
                </div>
            </div>

            {{-- KRS Status Card --}}
            <div class="stat-card-hover bg-white dark:bg-[#1a1c23] p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 relative overflow-hidden group">
               <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fas fa-file-signature text-6xl text-teal-600"></i>
                </div>
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-900/20 text-teal-600 flex items-center justify-center">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status KRS</span>
                </div>
                <div class="mt-2">
                    <div class="flex items-center gap-2">
                        @if($krsStatus == 'Disetujui')
                             <span class="text-xl font-bold text-green-600 dark:text-green-400">Disetujui</span>
                             <i class="fas fa-check-circle text-green-500"></i>
                        @elseif($krsStatus == 'Belum Di Isi')
                             <span class="text-xl font-bold text-red-600 dark:text-red-400">Belum Diisi</span>
                             <i class="fas fa-times-circle text-red-500"></i>
                        @else
                             <span class="text-xl font-bold text-yellow-600 dark:text-yellow-400">{{ $krsStatus }}</span>
                        @endif
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">KRS Semester Ini</span>
                </div>
            </div>
        </div>

        {{-- Content Grid: Announcements & Support --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Announcements Column (Span 2) --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-[#1a1c23] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col h-full">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <span class="w-1 h-6 bg-[#8B1538] rounded-full"></span>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pengumuman Terbaru</h3>
                        </div>
                        @php
                            $pengumumanIndexUrl = Route::has('mahasiswa.pengumuman.index') ? route('mahasiswa.pengumuman.index') : '#';
                        @endphp
                        <a href="{{ $pengumumanIndexUrl }}" class="group flex items-center text-sm font-medium text-[#8B1538] dark:text-red-400 hover:underline">
                            Lihat Semua 
                            <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>

                    @php
                        if (!isset($pengumuman)) {
                            $pengumuman = \App\Models\Pengumuman::whereNotNull('published_at')->orderByDesc('published_at')->get();
                        }
                    @endphp

                    @if(isset($pengumuman) && $pengumuman->count())
                        <div class="space-y-4">
                            @foreach($pengumuman->take(3) as $p)
                                <div class="group relative bg-gray-50 dark:bg-white/5 p-4 rounded-xl hover:bg-white dark:hover:bg-white/10 hover:shadow-md transition-all duration-300 border border-transparent hover:border-gray-100 dark:hover:border-gray-700">
                                    <div class="flex items-start gap-4">
                                        <div class="shrink-0">
                                            <div class="w-12 h-12 rounded-lg bg-[#8B1538]/10 text-[#8B1538] dark:bg-red-900/20 dark:text-red-400 flex flex-col items-center justify-center">
                                                <span class="text-xs font-bold uppercase">{{ $p->created_at ? $p->created_at->format('M') : 'N/A' }}</span>
                                                <span class="text-lg font-bold leading-none">{{ $p->created_at ? $p->created_at->format('d') : '--' }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            @php $showUrl = Route::has('mahasiswa.pengumuman.show') ? route('mahasiswa.pengumuman.show', $p->id) : '#'; @endphp
                                            <a href="{{ $showUrl }}" class="block">
                                                <h4 class="text-base font-semibold text-gray-900 dark:text-white group-hover:text-[#8B1538] dark:group-hover:text-red-400 transition-colors line-clamp-1 mb-1">
                                                    {{ $p->judul }}
                                                </h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                                                    {{ \Illuminate\Support\Str::limit(strip_tags($p->isi ?? ''), 120) }}
                                                </p>
                                            </a>
                                            <div class="mt-2 flex items-center gap-3 text-xs text-gray-400">
                                                <span class="flex items-center"><i class="far fa-clock mr-1"></i> {{ $p->created_at ? $p->created_at->format('H:i') : '' }} WIB</span>
                                                @if($p->category)
                                                    <span class="px-2 py-0.5 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300">{{ $p->category }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="shrink-0 self-center opacity-0 group-hover:opacity-100 transition-opacity -translate-x-2 group-hover:translate-x-0 transform duration-300">
                                             <a href="{{ $showUrl }}" class="text-gray-400 hover:text-[#8B1538]">
                                                 <i class="fas fa-chevron-right"></i>
                                             </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-10 text-center">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-white/5 rounded-full flex items-center justify-center mb-3">
                                <i class="far fa-newspaper text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada pengumuman terbaru</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Support & Info Column (Span 1) --}}
            <div class="space-y-6">
                
                {{-- Info Box --}}
                <div class="bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-900/30 rounded-2xl p-5 shadow-sm">
                     <div class="flex gap-4">
                        <div class="shrink-0 mt-1">
                            <i class="fas fa-info-circle text-xl text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-blue-900 dark:text-blue-300 mb-1">Informasi KRS</h4>
                            <p class="text-sm text-blue-800 dark:text-blue-200/80 leading-relaxed">
                                Pastikan pengisian KRS dilakukan sebelum batas waktu yang ditentukan. Hubungi bagian akademik jika ada kendala.
                            </p>
                        </div>
                     </div>
                </div>

                {{-- PA Contact --}}
                <div class="bg-white dark:bg-[#1a1c23] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-5">
                     <div class="flex items-center gap-3 mb-4">
                        <span class="w-1 h-6 bg-orange-500 rounded-full"></span>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Dosen PA</h3>
                    </div>

                    @php
                        $paList = $mahasiswa->dosenPa()->with('user')->get();
                    @endphp

                    @if($paList->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($paList as $pa)
                                 @php
                                    $phoneRaw = $pa->phone ?? $pa->user->phone ?? $pa->user->no_hp ?? '';
                                    $phoneDigits = preg_replace('/\D+/', '', $phoneRaw);
                                    if (str_starts_with($phoneDigits, '0')) $waNumber = '62' . ltrim($phoneDigits, '0');
                                    elseif (str_starts_with($phoneDigits, '8')) $waNumber = '62' . $phoneDigits;
                                    else $waNumber = $phoneDigits;
                                    $waLink = $waNumber ? ('https://wa.me/' . $waNumber) : null;
                                @endphp
                                <div class="flex flex-col gap-3 p-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-gray-800">
                                    <div class="flex gap-3 items-center">
                                        <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/20 text-orange-600 flex items-center justify-center shrink-0 font-bold">
                                            {{ strtoupper(substr($pa->user->name ?? 'D', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h5 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-1">
                                                {{ $pa->user->name ?? $pa->nidn }}
                                            </h5>
                                            <p class="text-xs text-gray-500">NIDN: {{ $pa->nidn ?? '-' }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($waLink)
                                        <a href="{{ $waLink }}" target="_blank" class="w-full flex items-center justify-center gap-2 py-2 rounded-lg bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-300 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors text-sm font-medium">
                                            <i class="fab fa-whatsapp"></i> Chat WhatsApp
                                        </a>
                                    @else
                                        <button disabled class="w-full py-2 text-center text-xs text-gray-400 bg-gray-100 dark:bg-white/5 rounded-lg">
                                            Kontak Tidak Tersedia
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <i class="fas fa-user-slash text-gray-300 text-3xl mb-2"></i>
                            <p class="text-sm text-gray-500">Belum ada Dosen PA assigned.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        console.log('Dashboard Mahasiswa Loaded');
    </script>
@endpush