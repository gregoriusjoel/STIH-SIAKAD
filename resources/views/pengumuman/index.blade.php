@extends(auth()->user()->role === 'mahasiswa' ? 'layouts.mahasiswa' : 'layouts.app')

@section('title', 'Pengumuman')

@section('page-title', 'Pengumuman')

@section('navbar_breadcrumb')
    <nav class="flex items-center gap-3 text-sm text-white/60 font-bold tracking-tight">
        <a class="hover:text-white transition-all duration-300 flex items-center group"
            href="{{ auth()->user()->role == 'dosen' ? route('dosen.dashboard') : route('mahasiswa.dashboard') }}">
            <span class="material-symbols-outlined text-[19px] group-hover:scale-110">home</span>
        </a>
        <span class="material-symbols-outlined text-[10px] text-white/20 font-normal">play_arrow</span>
        <span class="text-white font-black text-[13px] uppercase tracking-wider">Pengumuman</span>
    </nav>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8" x-data="{ 
        readIds: JSON.parse(localStorage.getItem('read_announcement_ids') || '[]'),
        markAsRead(id) {
            if (!this.readIds.includes(id)) {
                this.readIds.push(id);
                localStorage.setItem('read_announcement_ids', JSON.stringify(this.readIds));
            }
        }
    }">
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-black text-[#111218] dark:text-white tracking-tight uppercase">Pusat Informasi</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium">Informasi terbaru dan pengumuman resmi STIH Adhyaksa</p>
        </div>

        <div class="grid gap-6">
            @forelse($pengumumans as $p)
                @php
                    $showRoute = auth()->user()->role == 'dosen' ? route('dosen.pengumuman.show', $p) : route('mahasiswa.pengumuman.show', $p);
                @endphp
                <div class="group relative bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 hover:shadow-xl hover:shadow-primary/5 hover:-translate-y-1 transition-all duration-300 overflow-hidden"
                     :class="!readIds.includes({{ $p->id }}) ? 'border-l-4 border-l-amber-500' : ''">
                    
                    {{-- Glass Decorative Element --}}
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary/5 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity"></div>

                    <div class="flex flex-col md:flex-row md:items-start gap-6 relative z-10">
                        <div class="flex-shrink-0">
                            <div class="size-14 rounded-2xl bg-primary/5 flex flex-col items-center justify-center text-primary border border-primary/10 shadow-sm">
                                <span class="text-lg font-black leading-none">{{ \Carbon\Carbon::parse($p->published_at)->format('d') }}</span>
                                <span class="text-[10px] font-bold uppercase tracking-tighter mt-0.5">{{ \Carbon\Carbon::parse($p->published_at)->format('M') }}</span>
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-2">
                                <template x-if="!readIds.includes({{ $p->id }})">
                                    <span class="flex h-2 w-2 rounded-full bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.5)]"></span>
                                </template>
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-700 rounded-lg text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest leading-none">
                                    {{ $p->target == 'semua' ? 'Global' : ($p->target == 'dosen' ? 'Dosen' : 'Mahasiswa') }}
                                </span>
                            </div>

                            <a href="{{ $showRoute }}" 
                               @click="markAsRead({{ $p->id }})"
                               class="block group-hover:text-primary transition-colors">
                                <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white tracking-tight leading-snug line-clamp-2">
                                    {{ $p->judul }}
                                </h2>
                            </a>

                            <p class="mt-3 text-slate-600 dark:text-slate-400 leading-relaxed font-medium line-clamp-2">
                                {{ strip_tags($p->isi) }}
                            </p>

                            <div class="mt-5 flex items-center gap-6">
                                <div class="flex items-center gap-2 text-xs font-bold text-slate-400">
                                    <span class="material-symbols-outlined text-sm">schedule</span>
                                    {{ \Carbon\Carbon::parse($p->published_at)->format('H:i') }} WIB
                                </div>
                                <div class="flex items-center gap-2 text-xs font-bold text-slate-400">
                                    <span class="material-symbols-outlined text-sm">person_outline</span>
                                    Administrator
                                </div>
                                <a href="{{ $showRoute }}" 
                                   @click="markAsRead({{ $p->id }})"
                                   class="ml-auto flex items-center gap-1.5 text-xs font-black text-primary group/link uppercase tracking-wider">
                                    Baca Selengkapnya
                                    <span class="material-symbols-outlined text-sm group-hover/link:translate-x-1 transition-transform">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center bg-white dark:bg-slate-800 rounded-3xl border border-dashed border-slate-200 dark:border-slate-700">
                    <div class="size-20 bg-slate-50 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100 dark:border-slate-600 shadow-sm">
                        <span class="material-symbols-outlined text-4xl text-slate-300">campaign</span>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-widest">Belum Ada Pengumuman</h3>
                    <p class="text-slate-500 dark:text-slate-400 mt-1">Kami akan mengabari Anda jika ada informasi terbaru.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $pengumumans->links() }}
        </div>
    </div>
@endsection
