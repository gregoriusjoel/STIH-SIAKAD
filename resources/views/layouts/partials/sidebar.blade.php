<!-- Sidebar -->
<aside
    class="w-64 bg-white dark:bg-[#1f1616] border-r border-[#e6dbdb] dark:border-slate-800 hidden lg:flex flex-col h-full">
    <div class="p-6 flex flex-col gap-8 h-full overflow-y-auto">
        <div class="flex items-center gap-3">
            <div
                class="size-10 rounded-xl bg-gradient-to-br from-primary to-red-900 flex items-center justify-center text-white shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined">school</span>
            </div>
            <div class="flex flex-col">
                <h1 class="text-[#111218] dark:text-white text-base font-bold leading-tight">SIAKAD Uni</h1>
                <p class="text-[#616889] dark:text-slate-400 text-xs font-normal">Lecturer Portal</p>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="flex flex-col gap-1 grow">
            <a href="{{ route('dosen.dashboard') }}" class="flex items-center gap-3 px-3 py-3 rounded-lg transition-colors {{ Request::routeIs('dosen.dashboard') ? 'bg-primary/10 text-primary' : 'text-[#616889] dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-red-900/10 hover:text-primary' }}">
                <span class="material-symbols-outlined text-[20px]">dashboard</span>
                <p class="text-sm font-medium">Dashboard</p>
            </a>

            <a href="{{ route('dosen.kelas') }}" class="flex items-center gap-3 px-3 py-3 rounded-lg transition-colors {{ Request::routeIs('dosen.kelas') ? 'bg-primary/10 text-primary' : 'text-[#616889] dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-red-900/10 hover:text-primary' }}">
                <span class="material-symbols-outlined text-[20px]">groups</span>
                <p class="text-sm font-medium">Kelas</p>
            </a>

            <a href="{{ route('dosen.jadwal') }}" class="flex items-center gap-3 px-3 py-3 rounded-lg transition-colors {{ Request::routeIs('dosen.jadwal') ? 'bg-primary/10 text-primary' : 'text-[#616889] dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-red-900/10 hover:text-primary' }}">
                <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                <p class="text-sm font-medium">Jadwal</p>
            </a>

            <a class="text-[#616889] dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-red-900/10 hover:text-primary transition-colors flex items-center gap-3 px-3 py-3 rounded-lg mt-auto" href="#">
                <span class="material-symbols-outlined text-[20px]">account_circle</span>
                <p class="text-sm font-medium">Profil</p>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-3 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                    <p class="text-sm font-medium">Logout</p>
                </button>
            </form>
        </nav>
    </div>
</aside>