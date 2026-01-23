<header class="h-16 bg-white dark:bg-[#1f1616] border-b border-[#dbdde6] dark:border-slate-800 px-8 flex items-center justify-between sticky top-0 z-50 shadow-sm">
    <div class="flex items-center gap-4">
        <nav class="flex items-center gap-2 text-sm text-[#616889] dark:text-slate-400">
            <a class="hover:text-primary transition-colors" href="#">Home</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-[#111218] dark:text-white font-medium">Dashboard</span>
        </nav>
        <div class="h-4 w-[1px] bg-gray-300 dark:bg-slate-700 mx-2"></div>
        <div class="flex items-center gap-2 px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-semibold">
            <span class="material-symbols-outlined text-sm">event_repeat</span>
            Semester Ganjil 2023/2024
        </div>
    </div>
    <div class="flex items-center gap-6">
        <div class="relative hidden md:block">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#616889] text-[20px]">search</span>
            <input class="bg-gray-50 dark:bg-slate-800 border-none rounded-lg pl-10 pr-4 py-2 text-sm w-64 focus:ring-2 focus:ring-primary/50 transition-all" placeholder="Cari data..." type="text"/>
        </div>
        <div class="flex items-center gap-3">
            <button class="size-10 flex items-center justify-center rounded-lg hover:bg-red-50 dark:hover:bg-red-900/10 text-[#616889] dark:text-slate-300 relative">
                <span class="material-symbols-outlined">notifications</span>
                <span class="absolute top-2.5 right-2.5 size-2 bg-primary rounded-full border-2 border-white dark:border-[#1a1d2e]"></span>
            </button>
            <button class="size-10 flex items-center justify-center rounded-lg hover:bg-red-50 dark:hover:bg-red-900/10 text-[#616889] dark:text-slate-300">
                <span class="material-symbols-outlined">settings</span>
            </button>
            <div class="h-8 w-[1px] bg-gray-200 dark:bg-slate-700 mx-1"></div>
            <div class="flex items-center gap-3 pl-2">
                <div class="text-right hidden sm:block max-w-[160px]">
                    <p class="text-sm font-bold leading-none mt-2 max-w-full truncate" title="{{ session('user.name', 'Dosen User') }}">{{ session('user.name', 'Dosen User') }}</p>
                    <p class="text-[10px] text-[#616889] dark:text-slate-400 mt-1 truncate" title="NIDN: 0423018201">NIDN: 0423018201</p>
                </div>
                <div class="size-10 rounded-full bg-cover bg-center border-2 border-primary/20 flex-shrink-0" style="background-image: url('https://ui-avatars.com/api/?name={{ urlencode(session('user.name', 'Dosen User')) }}&background=800000&color=fff')"></div>
            </div>
        </div>
    </div>
</header>