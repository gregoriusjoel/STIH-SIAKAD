<header class="bg-white dark:bg-[#1f1616] border-b border-[#dbdde6] dark:border-slate-800 px-6 md:px-8 sticky top-0 z-50 shadow-sm">
    <div class="w-full flex items-center gap-4 py-3 sm:py-0">
        <div class="flex items-center gap-4 flex-1 min-w-0">
            {{-- Hamburger menu for mobile --}}
            <button @click="sidebarOpen = !sidebarOpen" 
                class="lg:hidden flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 text-[#616889] dark:text-slate-300" 
                style="width:40px;height:40px;">
                <span class="material-symbols-outlined text-2xl">menu</span>
            </button>

            {{-- Mobile compact title --}}
            <div class="sm:hidden min-w-0">
                <div class="text-sm font-medium text-gray-700 dark:text-white truncate">{{ trim($__env->yieldContent('page-title') ?: $__env->yieldContent('title')) }}</div>
            </div>

            {{-- Desktop breadcrumb --}}
            <div class="hidden sm:flex items-center min-w-0">
                @hasSection('navbar_breadcrumb')
                    <div class="truncate">
                        @yield('navbar_breadcrumb')
                    </div>
                @else
                    <nav class="flex items-center gap-2 text-sm text-[#616889] dark:text-slate-400">
                        <a class="hover:text-primary transition-colors" href="#">Home</a>
                        <span class="material-symbols-outlined text-xs">chevron_right</span>
                        <span class="text-[#111218] dark:text-white font-medium">Dashboard</span>
                    </nav>
                @endif
            </div>

            <div class="h-4 w-[1px] bg-gray-300 dark:bg-slate-700 mx-2 hidden sm:block"></div>
            @php($s = $semesterAktif ?? \App\Models\Semester::where('status', 'aktif')->first())
            <div class="hidden sm:flex items-center gap-2 px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-semibold">
                <span class="material-symbols-outlined text-sm">event_repeat</span>
                Semester {{ $s?->nama_semester ?? '-' }} {{ $s?->tahun_ajaran ?? '' }}
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button class="flex items-center justify-center rounded-lg hover:bg-red-50 dark:hover:bg-red-900/10 text-[#616889] dark:text-slate-300 relative" style="width:44px;height:44px;">
                <span class="material-symbols-outlined text-lg">notifications</span>
                <span class="absolute top-2 right-2 w-2 h-2 bg-primary rounded-full border-2 border-white dark:border-[#1a1d2e]"></span>
            </button>
            <div class="h-8 w-px bg-gray-200 dark:bg-slate-700 mx-1 hidden sm:block"></div>
            <div class="flex items-center gap-3 pl-2">
                <div class="text-right hidden sm:block max-w-[160px]">
                    <p class="text-sm font-bold leading-none max-w-full truncate" title="{{ session('user.name', 'Dosen User') }}">{{ session('user.name', 'Dosen User') }}</p>
                    <p class="text-[10px] text-[#616889] dark:text-slate-400 mt-1 truncate" title="NIDN: 0423018201">NIDN: 0423018201</p>
                </div>
                <div class="rounded-full bg-cover bg-center border-2 border-primary/20 flex-shrink-0" style="width:40px;height:40px;background-image: url('https://ui-avatars.com/api/?name={{ urlencode(session('user.name', 'Dosen User')) }}&background=800000&color=fff')"></div>
            </div>
        </div>
    </div>
</header>