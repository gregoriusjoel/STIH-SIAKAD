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
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
            {{-- SKS Card --}}
            <div class="stat-card-hover bg-white dark:bg-[#1a1c23] p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fas fa-book text-4xl md:text-6xl text-blue-600"></i>
                </div>
                <div class="flex items-center gap-3 md:gap-4 mb-2">
                    <div class="size-10 md:size-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 flex items-center justify-center">
                        <i class="fas fa-book-open text-lg md:text-xl"></i>
                    </div>
                    <span class="text-[10px] md:text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total SKS</span>
                </div>
                <div class="mt-2">
                    <span class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">{{ $totalSks }}</span>
                    <span class="text-[10px] md:text-sm text-gray-500 dark:text-gray-400 ml-1">SKS Diambil</span>
                </div>
            </div>

            {{-- IPK Card --}}
             <div class="stat-card-hover bg-white dark:bg-[#1a1c23] p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fas fa-graduation-cap text-4xl md:text-6xl text-purple-600"></i>
                </div>
                <div class="flex items-center gap-3 md:gap-4 mb-2">
                    <div class="size-10 md:size-12 rounded-xl bg-purple-50 dark:bg-purple-900/20 text-purple-600 flex items-center justify-center">
                        <i class="fas fa-award text-lg md:text-xl"></i>
                    </div>
                    <span class="text-[10px] md:text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">IPK Saat Ini</span>
                </div>
                <div class="mt-2">
                    <span class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($ipk, 2) }}</span>
                    <span class="text-[10px] md:text-sm text-gray-500 dark:text-gray-400 ml-1">Indeks Prestasi</span>
                </div>
            </div>

            {{-- Semester Card --}}
            <div class="stat-card-hover bg-white dark:bg-[#1a1c23] p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fas fa-layer-group text-4xl md:text-6xl text-orange-600"></i>
                </div>
                <div class="flex items-center gap-3 md:gap-4 mb-2">
                    <div class="size-10 md:size-12 rounded-xl bg-orange-50 dark:bg-orange-900/20 text-orange-600 flex items-center justify-center">
                        <i class="fas fa-layer-group text-lg md:text-xl"></i>
                    </div>
                    <span class="text-[10px] md:text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Semester</span>
                </div>
                <div class="mt-2">
                    <span class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">{{ $studentSemester }}</span>
                    <span class="text-[10px] md:text-sm text-gray-500 dark:text-gray-400 ml-1">Semester Aktif</span>
                </div>
            </div>

            {{-- KRS Status Card --}}
            <div class="stat-card-hover bg-white dark:bg-[#1a1c23] p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 relative overflow-hidden group">
               <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fas fa-file-signature text-4xl md:text-6xl text-teal-600"></i>
                </div>
                <div class="flex items-center gap-3 md:gap-4 mb-2">
                    <div class="size-10 md:size-12 rounded-xl bg-teal-50 dark:bg-teal-900/20 text-teal-600 flex items-center justify-center">
                        <i class="fas fa-file-contract text-lg md:text-xl"></i>
                    </div>
                    <span class="text-[10px] md:text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status KRS</span>
                </div>
                <div class="mt-2">
                    <div class="flex items-center gap-2">
                        @if($krsStatus == 'Disetujui')
                             <span class="text-lg md:text-xl font-bold text-green-600 dark:text-green-400">Disetujui</span>
                             <i class="fas fa-check-circle text-green-500 text-sm md:text-base"></i>
                        @elseif($krsStatus == 'Belum Di Isi')
                             <span class="text-lg md:text-xl font-bold text-red-600 dark:text-red-400">Belum Diisi</span>
                             <i class="fas fa-times-circle text-red-500 text-sm md:text-base"></i>
                        @else
                             <span class="text-lg md:text-xl font-bold text-yellow-600 dark:text-yellow-400">{{ $krsStatus }}</span>
                        @endif
                    </div>
                    <span class="text-[10px] md:text-sm text-gray-500 dark:text-gray-400">KRS Semester Ini</span>
                </div>
            </div>
        </div>

        {{-- Content Grid: Announcements & Support --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Announcements Column (Span 2) --}}
            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
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
                            $pengumuman = \App\Models\Pengumuman::where('published_at', '<=', now('Asia/Jakarta')->format('Y-m-d H:i:s'))->orderByDesc('published_at')->get();
                        }
                    @endphp

                    @if(isset($pengumuman) && $pengumuman->count())
                        <div class="space-y-4">
                            @foreach($pengumuman->take(3) as $p)
                                <div class="group relative bg-gray-50 dark:bg-white/5 p-4 rounded-xl hover:bg-white dark:hover:bg-white/10 hover:shadow-md transition-all duration-300 border border-transparent hover:border-gray-100 dark:hover:border-gray-700">
                                    <div class="flex items-start gap-4">
                                        <div class="shrink-0">
                                            <div class="w-12 h-12 rounded-lg bg-[#8B1538]/10 text-[#8B1538] dark:bg-red-900/20 dark:text-red-400 flex flex-col items-center justify-center">
                                                <span class="text-xs font-bold uppercase">{{ $p->published_at ? \Carbon\Carbon::parse($p->published_at)->format('M') : 'N/A' }}</span>
                                                <span class="text-lg font-bold leading-none">{{ $p->published_at ? \Carbon\Carbon::parse($p->published_at)->format('d') : '--' }}</span>
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

                {{-- Tugas Dosen Card --}}
                <div x-data="{ openTugasModal: false, selectedTugas: {} }" class="bg-white dark:bg-[#1a1c23] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col h-full">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-1 h-6 bg-maroon rounded-full"></span>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tugas Kuliah</h3>
                    </div>
                    
                    {{-- Assignments List --}}
                    <div class="space-y-3">
                        @forelse($tugasKuliah as $tugas)
                            @php
                                $dueDate = \Carbon\Carbon::parse($tugas->due_date);
                                $isPast = $dueDate->isPast();
                                $isUrgent = !$isPast && $dueDate->diffInDays(now()) <= 2;
                                
                                $isSubmitted = $tugas->submissions && $tugas->submissions->isNotEmpty();
                                
                                if ($isSubmitted) {
                                    $deadlineLabel = 'Sudah mengumpulkan';
                                    $tenggatColor = 'text-green-600';
                                } else {
                                    $deadlineLabel = $isPast ? 'Terlewat - Tidak mengumpulkan' : $dueDate->format('d M Y, H:i');
                                    $tenggatColor = $isPast ? 'text-red-600' : ($isUrgent ? 'text-red-500' : 'text-maroon');
                                }
                                
                                $tugasData = [
                                    'judul' => $tugas->title,
                                    'deskripsi' => $tugas->description ?? 'Tidak ada deskripsi.',
                                    'matkul' => ($tugas->mataKuliah->nama_mk ?? '-') . ' - ' . ($tugas->dosen->user->name ?? '-'),
                                    'deadline' => $deadlineLabel,
                                    'link' => route('mahasiswa.kelas.show', $tugas->kelas_id) . '?tab=tugas'
                                ];
                            @endphp
                            <div @click="selectedTugas = @js($tugasData); openTugasModal = true" 
                                 class="group cursor-pointer p-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-gray-800 hover:border-maroon/30 transition-colors">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-maroon transition-colors line-clamp-1">{{ $tugas->title }}</h4>
                                    @if($isUrgent)
                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-red-100 text-red-600 rounded-full shrink-0">Urgent</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mb-2 line-clamp-1">{{ $tugas->mataKuliah->nama_mk ?? '-' }} - {{ $tugas->dosen->user->name ?? '-' }}</p>
                                <div class="flex items-center text-xs {{ $tenggatColor }} font-medium">
                                    <i class="far fa-clock mr-1"></i> Tenggat: {{ $deadlineLabel }}
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-8 text-center bg-gray-50 dark:bg-white/5 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center mb-2">
                                    <i class="fas fa-check text-xl text-green-500"></i>
                                </div>
                                <p class="text-xs text-gray-500 font-medium">Tidak ada tugas aktif saat ini</p>
                            </div>
                        @endforelse
                    </div>
                    
                    {{-- Modal --}}
                    <template x-teleport="body">
                        <div x-show="openTugasModal" 
                             style="display: none;"
                             class="fixed inset-0 z-[9999] overflow-y-auto" 
                             aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                <div x-show="openTugasModal" 
                                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                     class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" 
                                     @click="openTugasModal = false" aria-hidden="true"></div>

                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                <div x-show="openTugasModal" 
                                     x-transition:enter="ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                     x-transition:leave="ease-in duration-200 transform" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                     class="relative z-50 inline-block align-bottom bg-white dark:bg-[#1a1c23] rounded-2xl text-left shadow-2xl transform transition-all sm:my-8 sm:align-middle md:max-w-3xl lg:max-w-4xl sm:w-full min-h-[50vh] border border-gray-100 dark:border-gray-800">
                                    
                                    <div class="flex flex-col h-full min-h-[50vh]">
                                        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-6 relative flex-1 overflow-y-auto">
                                        <button @click="openTugasModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
                                            <i class="fas fa-times text-xl"></i>
                                        </button>
                                        
                                        <div class="sm:flex sm:items-start h-full">
                                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-maroon/10 dark:bg-maroon/20 text-maroon sm:mx-0 sm:h-10 sm:w-10">
                                                <i class="fas fa-tasks text-lg"></i>
                                            </div>
                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full flex flex-col">
                                                <h3 class="text-xl leading-6 font-bold text-gray-900 dark:text-white" id="modal-title" x-text="selectedTugas.judul"></h3>
                                                
                                                <div class="mt-4 text-sm text-gray-500 dark:text-gray-400 space-y-3 flex-1">
                                                    <div>
                                                        <span class="font-semibold text-gray-700 dark:text-gray-300">Deskripsi:</span> 
                                                        <div class="mt-2 prose prose-sm max-w-none text-gray-500 dark:text-gray-400 prose-p:my-1 prose-headings:my-2 bg-gray-50 dark:bg-white/5 p-4 rounded-xl border border-gray-100 dark:border-gray-800" x-html="selectedTugas.deskripsi"></div>
                                                    </div>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                                                        <p><span class="font-semibold text-gray-700 dark:text-gray-300 block mb-1">Mata Kuliah:</span> <span x-text="selectedTugas.matkul" class="text-gray-900 dark:text-gray-100"></span></p>
                                                        <p><span class="font-semibold text-gray-700 dark:text-gray-300 block mb-1">Tenggat Waktu:</span> <span class="text-red-500 font-medium bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded-lg inline-block" x-text="selectedTugas.deadline"></span></p>
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-900/30">
                                                    <p class="text-sm text-blue-800 dark:text-blue-300 flex items-start gap-3">
                                                        <i class="fas fa-info-circle mt-0.5 shrink-0 text-base"></i>
                                                        <span class="leading-relaxed">Ketuk tombol di bawah untuk diarahkan ke portal E-Learning guna melihat atau mengumpulkan tugas ini.</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gray-50 dark:bg-white/5 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100 dark:border-gray-800 rounded-b-2xl">
                                        <a :href="selectedTugas.link" class="w-full inline-flex justify-center items-center rounded-xl border border-transparent shadow-sm px-6 py-2.5 bg-maroon text-base font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-maroon sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                            <i class="fas fa-external-link-alt mr-2"></i> Buka E-Learning
                                        </a>
                                        <button type="button" @click="openTugasModal = false" class="mt-3 w-full inline-flex justify-center items-center rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-6 py-2.5 bg-white dark:bg-[#1a1c23] text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                            Tutup
                                        </button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

            </div>

            {{-- Support & Info Column (Span 1) --}}
            <div class="space-y-6">

                {{-- Active Academic Periods --}}
                @if(!empty($activePeriods))
                <div class="bg-white dark:bg-[#1a1c23] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-1 h-6 bg-green-500 rounded-full"></span>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Periode Aktif</h3>
                    </div>
                    <div class="space-y-2">
                        @foreach($activePeriods as $period)
                        <div class="flex items-center gap-3 p-3 rounded-xl {{ $period['colors']['bg'] }} border {{ $period['colors']['border'] }}">
                            <i class="{{ $period['icon'] }} {{ $period['colors']['text'] }} text-lg"></i>
                            <div class="flex-1 min-w-0">
                                <span class="text-sm font-bold {{ $period['colors']['text'] }}">{{ $period['label'] }}</span>
                                <span class="block text-[10px] {{ $period['colors']['text'] }} opacity-75">{{ $period['title'] }}</span>
                            </div>
                            <div class="text-right shrink-0">
                                @if($period['days_left'] > 0)
                                    <span class="text-xs font-bold {{ $period['colors']['text'] }}">{{ $period['days_left'] }}</span>
                                    <span class="text-[10px] {{ $period['colors']['text'] }} opacity-75 block">hari lagi</span>
                                @else
                                    <span class="text-xs font-bold {{ $period['colors']['text'] }}">Hari ini</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                {{-- Info Box --}}
                <div class="bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-900/30 rounded-2xl p-5 shadow-sm">
                     <div class="flex gap-4">
                        <div class="shrink-0 mt-1">
                            <i class="fas fa-info-circle text-xl text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-blue-900 dark:text-blue-300 mb-1">Informasi KRS</h4>
                            @if(isset($krsperiodStatus) && $krsperiodStatus['status'] === 'active')
                                <p class="text-sm text-green-700 dark:text-green-300 leading-relaxed font-semibold">
                                    <i class="fas fa-check-circle mr-1"></i> Pengisian KRS sedang dibuka! {{ $krsperiodStatus['message'] }}
                                </p>
                            @elseif(isset($krsperiodStatus) && $krsperiodStatus['status'] === 'upcoming')
                                <p class="text-sm text-blue-800 dark:text-blue-200/80 leading-relaxed">
                                    <i class="fas fa-clock mr-1"></i> {{ $krsperiodStatus['message'] }}
                                </p>
                            @else
                                <p class="text-sm text-blue-800 dark:text-blue-200/80 leading-relaxed">
                                    Pastikan pengisian KRS dilakukan sebelum batas waktu yang ditentukan. Hubungi bagian akademik jika ada kendala.
                                </p>
                            @endif
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