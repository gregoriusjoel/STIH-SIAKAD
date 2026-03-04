@php
    $s = $semesterAktif ?? \App\Models\Semester::where('status', 'aktif')->first();
    $latestPengumumans = \App\Models\Pengumuman::where('published_at', '<=', now('Asia/Jakarta')->format('Y-m-d H:i:s'))
        ->whereIn('target', ['semua', 'dosen'])
        ->orderByDesc('published_at')
        ->limit(5)
        ->get();
    $latestIds = $latestPengumumans->pluck('id')->toArray();
@endphp

<header
    class="bg-primary border-b border-white/5 px-6 md:px-8 sticky top-0 z-[50] h-16 md:h-[72px] transition-all duration-500 shadow-lg">
    {{-- Subtle Line of Light Effect --}}
    <div class="absolute bottom-0 left-0 w-full h-[0.5px] bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>

    <div class="w-full flex items-center gap-4 h-16 md:h-[72px]">
        <div class="flex items-center gap-5 flex-1 min-w-0">
            {{-- Hamburger menu for mobile --}}
            <button @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden flex items-center justify-center rounded-2xl hover:bg-slate-100 text-slate-400 w-11 h-11 md:w-12 md:h-12 transition-all duration-300">
                <span class="material-symbols-outlined text-2xl md:text-3xl">menu</span>
            </button>

            {{-- Mobile compact title --}}
            <div class="sm:hidden min-w-0">
                <div class="text-[15px] font-black text-white truncate tracking-tight">
                    {{ trim($__env->yieldContent('page-title') ?: $__env->yieldContent('title')) }}
                </div>
            </div>

            {{-- Desktop breadcrumb (Refined Slate) --}}
            <div class="hidden sm:flex items-center min-w-0">
                @hasSection('navbar_breadcrumb')
                    <div class="truncate">
                        @yield('navbar_breadcrumb')
                    </div>
                @else
                    <nav class="flex items-center gap-3 text-sm text-white/60 font-bold tracking-tight">
                        <a class="hover:text-white transition-all duration-300 flex items-center group" href="{{ route('dosen.dashboard') }}">
                            <span class="material-symbols-outlined text-[19px] group-hover:scale-110">home</span>
                        </a>
                        <span class="material-symbols-outlined text-[10px] text-white/20 font-normal">play_arrow</span>
                        <span class="text-white font-black text-[13px] uppercase tracking-wider">
                            {{ trim($__env->yieldContent('page-title') ?: ($__env->yieldContent('title') ?: 'Dashboard')) }}
                        </span>
                    </nav>
                @endif
            </div>

            <div class="h-6 w-px bg-gradient-to-b from-transparent via-white/10 to-transparent mx-3 hidden sm:block"></div>
            
            {{-- Professional Semester Badge --}}
            <div
                class="hidden sm:flex items-center gap-2.5 px-4 py-2 bg-white/10 text-white rounded-full text-xs font-black shadow-sm border border-white/10">
                <span class="material-symbols-outlined text-[16px] md:text-[18px]">event_repeat</span>
                <span class="uppercase tracking-widest leading-none">Semester {{ $s?->nama_semester ?? '-' }} {{ $s?->tahun_ajaran ?? '' }}</span>
            </div>
        </div>

        <div class="flex items-center gap-5">
            {{-- Animated Notification Dropdown --}}
            
            <div class="relative" x-data="{ 
                open: false, 
                readIds: JSON.parse(localStorage.getItem('read_announcement_ids') || '[]'),
                latestIds: {{ json_encode($latestIds ?? []) }},
                hasUnread: false,
                init() {
                    this.hasUnread = this.latestIds.some(id => !this.readIds.includes(id));
                },
                markAsRead(id) {
                    if (!this.readIds.includes(id)) {
                        this.readIds.push(id);
                        localStorage.setItem('read_announcement_ids', JSON.stringify(this.readIds));
                        this.hasUnread = this.latestIds.some(i => !this.readIds.includes(i));
                    }
                }
            }" @click.away="open = false">
                <button @click="open = !open"
                    class="group flex items-center justify-center rounded-2xl hover:bg-white/10 text-white relative w-11 h-11 md:w-12 md:h-12 transition-all duration-300 hover:scale-105"
                    :class="{ 'bg-white/20 text-white': open }">
                    <span class="material-symbols-outlined text-2xl md:text-[26px] !text-white transition-colors group-hover:animate-bell-ring {{ $latestPengumumans->count() > 0 ? 'fill-current' : '' }}"
                        :class="{ 'fill-current': open || hasUnread }">notifications</span>
                    <template x-if="hasUnread">
                        <span class="absolute top-2.5 right-2.5 flex h-3.5 w-3.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-amber-500 border-2 border-primary shadow-lg shadow-amber-500/50"></span>
                        </span>
                    </template>
                </button>

                {{-- Dropdown Menu --}}
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                     class="absolute right-0 mt-4 w-80 md:w-96 bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden z-[100]"
                     style="display: none;">
                    
                    <div class="p-5 border-b border-slate-50 bg-slate-50/50 backdrop-blur-md flex items-center justify-between">
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Pengumuman Terbaru</h3>
                        <span class="px-2 py-1 bg-primary/10 text-primary text-[10px] font-black rounded-lg">{{ $latestPengumumans->count() }} Baru</span>
                    </div>

                    <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
                        @forelse($latestPengumumans as $p)
                            <a href="{{ route('dosen.pengumuman.show', $p) }}" 
                               @click="markAsRead({{ $p->id }})"
                               class="flex flex-col gap-1 p-5 hover:bg-slate-50 transition-all border-b border-slate-50 last:border-0 group relative"
                               :class="!readIds.includes({{ $p->id }}) ? 'bg-amber-50/30' : ''">
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <template x-if="!readIds.includes({{ $p->id }})">
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full shadow-[0_0_8px_rgba(245,158,11,0.5)]"></span>
                                        </template>
                                        <h4 class="text-[15px] font-black text-slate-900 group-hover:text-primary transition-colors leading-tight line-clamp-1 truncate max-w-[180px] md:max-w-[220px]">{{ $p->judul }}</h4>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-400 whitespace-nowrap ml-4">
                                        {{ $p->published_at ? \Carbon\Carbon::parse($p->published_at)->diffForHumans() : $p->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed font-medium mt-1">
                                    {{ strip_tags($p->isi) }}
                                </p>
                            </a>
                        @empty
                            <div class="p-10 text-center">
                                <span class="material-symbols-outlined text-4xl text-slate-200 mb-3">notifications_off</span>
                                <p class="text-sm font-bold text-slate-400">Belum ada pengumuman</p>
                            </div>
                        @endforelse
                    </div>

                    <a href="{{ route('dosen.pengumuman.index') }}" class="block p-4 text-center text-xs font-black text-primary hover:bg-primary/5 transition-all border-t border-slate-50 uppercase tracking-widest">
                        Lihat Semua Pengumuman
                    </a>
                </div>
            </div>
            
            <div class="h-9 w-px bg-gradient-to-b from-transparent via-white/10 to-transparent mx-1 hidden sm:block"></div>
            
            <div class="flex items-center gap-4 pl-2 group cursor-pointer">
                <div class="text-right hidden sm:block max-w-[200px]">
                    <p class="text-sm md:text-[16px] font-black text-white leading-none max-w-full truncate tracking-tighter group-hover:text-white/80 transition-colors duration-300"
                        title="{{ session('user.name', 'Dosen User') }}">{{ session('user.name', 'Dosen User') }}</p>
                    <p class="text-[10px] md:text-[11px] text-white/50 mt-1.5 font-black uppercase tracking-[0.25em]" title="NIDN: 0423018201">
                        NIDN: 0423018201</p>
                </div>
                <div class="relative rounded-full overflow-hidden border-2 border-slate-100 flex-shrink-0 w-11 h-11 md:w-12 md:h-12 shadow-md transition-all duration-500 group-hover:scale-110 group-hover:rotate-6 group-hover:border-primary group-hover:shadow-primary/20">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(session('user.name', 'Dosen User')) }}&background=800000&color=fff&bold=true&font-size=0.4" 
                         class="w-full h-full object-cover" 
                         alt="Avatar">
                    <div class="absolute inset-0 bg-primary/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
    @keyframes bell-ring {
        0%, 100% { transform: rotate(0); }
        20% { transform: rotate(15deg); }
        40% { transform: rotate(-15deg); }
        60% { transform: rotate(10deg); }
        80% { transform: rotate(-10deg); }
    }
    .group-hover\:animate-bell-ring:hover, .group:hover .group-hover\:animate-bell-ring {
        animation: bell-ring 0.6s ease-in-out;
        transform-origin: top center;
    }
</style>