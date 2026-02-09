<!-- Backdrop for Mobile -->
<div x-show="sidebarOpen" 
    class="fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm lg:hidden transition-opacity duration-300"
    @click="sidebarOpen = false"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">
</div>

<!-- Sidebar -->
<aside
    class="fixed inset-y-0 left-0 z-40 w-64 bg-white dark:bg-[#1f1616] border-r border-[#e6dbdb] dark:border-slate-800 transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0 flex flex-col h-full shadow-2xl lg:shadow-none"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    
    <div class="p-6 flex flex-col gap-8 h-full overflow-y-auto">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div
                    class="size-10 rounded-xl bg-gradient-to-br from-primary to-red-900 flex items-center justify-center text-white shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined">school</span>
                </div>
                <div class="flex flex-col">
                    <h1 class="text-[#111218] dark:text-white text-base font-bold leading-tight">STIH</h1>
                    <p class="text-[#616889] dark:text-slate-400 text-xs font-normal">Lecturer Portal</p>
                </div>
            </div>
            {{-- Close button for mobile --}}
            <button @click="sidebarOpen = false" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-500">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        @php
            $pendingApprovalCount = 0;
            if (auth()->check() && auth()->user()->dosen) {
                $dosen = auth()->user()->dosen;
                $pendingApprovalCount = \App\Models\JadwalProposal::where('dosen_id', $dosen->id)
                    ->where('status', 'pending_dosen')
                    ->count();
            }
        @endphp

        <!-- Navigation -->
        <nav class="flex flex-col gap-1 grow">
            <a href="{{ route('dosen.dashboard') }}" @click="if(window.innerWidth < 1024) sidebarOpen = false" class="flex items-center gap-3 px-3 py-3 rounded-lg transition-colors {{ Request::routeIs('dosen.dashboard') ? 'bg-primary/10 text-primary' : 'text-[#616889] dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-red-900/10 hover:text-primary' }}">
                <span class="material-symbols-outlined text-[20px]">dashboard</span>
                <p class="text-sm font-medium">Dashboard</p>
            </a>

            <a href="{{ route('dosen.kelas') }}" @click="if(window.innerWidth < 1024) sidebarOpen = false" class="flex items-center gap-3 px-3 py-3 rounded-lg transition-colors {{ Request::routeIs('dosen.kelas') ? 'bg-primary/10 text-primary' : 'text-[#616889] dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-red-900/10 hover:text-primary' }}">
                <span class="material-symbols-outlined text-[20px]">groups</span>
                <p class="text-sm font-medium">Kelas Saya</p>
            </a>

            @if($pendingApprovalCount > 0)
                <a href="{{ route('dosen.jadwal_approval.index') }}" @click="if(window.innerWidth < 1024) sidebarOpen = false" class="flex items-center gap-3 px-3 py-3 rounded-lg transition-colors {{ Request::routeIs('dosen.jadwal_approval.*') ? 'bg-primary/10 text-primary' : 'text-[#616889] dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-red-900/10 hover:text-primary' }}">
                    <span class="material-symbols-outlined text-[20px]">assignment</span>
                    <p class="text-sm font-medium">Approval Kelas</p>
                    <span class="ml-auto inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium leading-none text-white bg-red-600 rounded">{{ $pendingApprovalCount }}</span>
                </a>
            @endif

            <a href="{{ route('dosen.jadwal') }}" @click="if(window.innerWidth < 1024) sidebarOpen = false" class="flex items-center gap-3 px-3 py-3 rounded-lg transition-colors {{ Request::routeIs('dosen.jadwal') ? 'bg-primary/10 text-primary' : 'text-[#616889] dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-red-900/10 hover:text-primary' }}">
                <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                <p class="text-sm font-medium">Jadwal Kuliah</p>
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