@php
    $s = $semesterAktif ?? \App\Models\Semester::where('status', 'aktif')->first();
@endphp

<header class="bg-primary border-b border-white/5 px-6 md:px-8 sticky top-0 z-[50] h-16 md:h-[72px] transition-all duration-500 shadow-lg">
    <div class="absolute bottom-0 left-0 w-full h-[0.5px] bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
    <div class="w-full flex items-center gap-4 h-16 md:h-[72px]">
        <div class="flex items-center gap-5 flex-1 min-w-0">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden flex items-center justify-center rounded-2xl hover:bg-slate-100 text-slate-400 w-11 h-11 md:w-12 md:h-12 transition-all duration-300">
                <i class="fas fa-bars text-xl md:text-2xl"></i>
            </button>

            <div class="hidden sm:flex items-center min-w-0">
                <nav class="flex items-center gap-3 text-sm text-white/60 font-bold tracking-tight">
                    <a class="hover:text-white transition-all duration-300 flex items-center group" href="{{ route('finance.invoices.index') }}">
                        <i class="fas fa-home text-[17px] group-hover:scale-110 transition-transform"></i>
                    </a>
                    <i class="fas fa-chevron-right text-[9px] text-white/20"></i>
                    <span class="text-white font-black text-[13px] uppercase tracking-wider">
                        @yield('page-title', 'Keuangan')
                    </span>
                </nav>
            </div>

            <div class="h-6 w-px bg-gradient-to-b from-transparent via-white/10 to-transparent mx-3 hidden sm:block"></div>

            <div class="hidden sm:flex items-center gap-2.5 px-4 py-2 bg-white/10 text-white rounded-full text-xs font-black shadow-sm border border-white/10">
                <i class="fas fa-calendar-alt text-[14px] md:text-[16px]"></i>
                <span class="uppercase tracking-widest leading-none">Semester {{ $s?->nama_semester ?? '-' }} {{ $s?->tahun_ajaran ?? '' }}</span>
            </div>
        </div>

        <div class="flex items-center gap-5">
            <button class="group flex items-center justify-center rounded-2xl hover:bg-white/10 text-white relative w-11 h-11 md:w-12 md:h-12 transition-all duration-300">
                <i class="fas fa-bell text-xl md:text-[22px]"></i>
            </button>

            <div class="h-9 w-px bg-gradient-to-b from-transparent via-white/10 to-transparent mx-1 hidden sm:block"></div>

            <div class="flex items-center gap-4 pl-2 group cursor-pointer">
                <div class="text-right hidden sm:block max-w-[200px]">
                    <p class="text-sm md:text-[16px] font-black text-white leading-none max-w-full truncate tracking-tighter" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</p>
                </div>
                <div class="relative rounded-full overflow-hidden border-2 border-slate-100 flex-shrink-0 w-11 h-11 md:w-12 md:h-12 shadow-md transition-all duration-500 group-hover:scale-110 group-hover:rotate-6 group-hover:border-primary group-hover:shadow-primary/20">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=800000&color=fff&bold=true&font-size=0.4" class="w-full h-full object-cover" alt="Avatar">
                    <div class="absolute inset-0 bg-primary/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>
            </div>
        </div>
    </div>
</header>
