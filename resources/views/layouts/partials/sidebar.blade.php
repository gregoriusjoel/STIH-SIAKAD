<!-- Backdrop for Mobile -->
<div x-show="sidebarOpen" 
    class="fixed inset-0 z-[40] bg-slate-900/40 backdrop-blur-sm lg:hidden transition-opacity duration-300"
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
    class="fixed inset-y-0 left-0 z-[45] w-64 bg-[#fcfcfc] transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0 flex flex-col h-full shadow-xl lg:shadow-none"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    
    <div class="flex flex-col h-full">
        <!-- Branding Section (Maroon Theme Refined) -->
        <div class="h-16 md:h-[72px] bg-primary relative overflow-hidden group/header flex items-center px-8 border-b border-white/5">
            {{-- Decorative pattern --}}
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full border-[10px] border-white"></div>
            </div>

            <div class="flex items-center justify-between w-full relative z-10">
                <div class="flex items-center gap-3.5 group cursor-default">
                    <div
                        class="size-10 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-lg transform group-hover:scale-110 transition-all duration-500 overflow-hidden p-1.5">
                        <img src="{{ asset('images/logo_stih_white.png') }}" class="w-full h-full object-contain" alt="Logo">
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-white text-[18px] font-black leading-none tracking-tighter">STIH</h1>
                        <p class="text-white/60 text-[9px] font-bold uppercase tracking-[0.2em] mt-0.5 leading-none">Lecturer Portal</p>
                    </div>
                </div>
                {{-- Close button for mobile --}}
                <button @click="sidebarOpen = false" class="lg:hidden p-1.5 rounded-xl hover:bg-white/10 text-white transition-all duration-300">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
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

        <!-- Navigation Section (Floating Pill Design) -->
        <nav class="flex-1 px-5 py-2 flex flex-col gap-2 overflow-y-auto custom-scrollbar text-slate-600 border-r border-slate-100">
            @php
                $navItems = [
                    ['route' => 'dosen.dashboard', 'icon' => 'dashboard', 'label' => 'Dashboard'],
                    ['route' => 'dosen.kelas', 'icon' => 'groups', 'label' => 'Kelas Saya'],
                ];
            @endphp

            @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}" 
                   @click="if(window.innerWidth < 1024) sidebarOpen = false" 
                   class="group relative flex items-center gap-3.5 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ Request::routeIs($item['route']) ? 'bg-white shadow-md shadow-slate-200/50 text-primary active-nav-pill hover:shadow-lg hover:-translate-y-0.5' : 'text-slate-500 hover:bg-primary/5 hover:text-primary' }}">
                    @if(Request::routeIs($item['route']))
                        <div class="absolute left-2 w-1 h-5 bg-primary rounded-full shadow-[0_0_10px_rgba(139,21,56,0.3)]"></div>
                    @endif
                    <span class="material-symbols-outlined text-[23px] transition-all duration-300 group-hover:scale-110 {{ Request::routeIs($item['route']) ? 'fill-current' : 'text-slate-400 group-hover:text-primary' }}">
                        {{ $item['icon'] }}
                    </span>
                    <p class="text-sm {{ Request::routeIs($item['route']) ? 'font-black' : 'font-semibold' }}">{{ $item['label'] }}</p>
                </a>
            @endforeach

            @if($pendingApprovalCount > 0)
                <a href="{{ route('dosen.jadwal_approval.index') }}" 
                   @click="if(window.innerWidth < 1024) sidebarOpen = false" 
                   class="group relative flex items-center gap-3.5 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ Request::routeIs('dosen.jadwal_approval.*') ? 'bg-white shadow-md shadow-slate-200/50 text-primary active-nav-pill hover:shadow-lg hover:-translate-y-0.5' : 'text-slate-500 hover:bg-primary/5 hover:text-primary' }}">
                    @if(Request::routeIs('dosen.jadwal_approval.*'))
                        <div class="absolute left-2 w-1 h-5 bg-primary rounded-full shadow-[0_0_10px_rgba(139,21,56,0.3)]"></div>
                    @endif
                    <span class="material-symbols-outlined text-[23px] transition-all duration-300 group-hover:scale-110 {{ Request::routeIs('dosen.jadwal_approval.*') ? 'fill-current' : 'text-slate-400 group-hover:text-primary' }}">
                        assignment
                    </span>
                    <p class="text-sm {{ Request::routeIs('dosen.jadwal_approval.*') ? 'font-black' : 'font-semibold' }}">Approval Kelas</p>
                    <span class="ml-auto inline-flex items-center justify-center min-w-[22px] h-5.5 px-2 text-[10px] font-black text-white bg-primary rounded-full shadow-lg shadow-primary/30">
                        {{ $pendingApprovalCount }}
                    </span>
                </a>
            @endif

            <a href="{{ route('dosen.jadwal') }}" 
               @click="if(window.innerWidth < 1024) sidebarOpen = false" 
               class="group relative flex items-center gap-3.5 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ Request::routeIs('dosen.jadwal') ? 'bg-white shadow-md shadow-slate-200/50 text-primary active-nav-pill hover:shadow-lg hover:-translate-y-0.5' : 'text-slate-500 hover:bg-primary/5 hover:text-primary' }}">
                @if(Request::routeIs('dosen.jadwal'))
                    <div class="absolute left-2 w-1 h-5 bg-primary rounded-full shadow-[0_0_10px_rgba(139,21,56,0.3)]"></div>
                @endif
                <span class="material-symbols-outlined text-[23px] transition-all duration-300 group-hover:scale-110 {{ Request::routeIs('dosen.jadwal') ? 'fill-current' : 'text-slate-400 group-hover:text-primary' }}">
                    calendar_today
                </span>
                <p class="text-sm {{ Request::routeIs('dosen.jadwal') ? 'font-black' : 'font-semibold' }}">Jadwal Kuliah</p>
            </a>

            <a href="{{ route('dosen.magang.index') }}" 
               @click="if(window.innerWidth < 1024) sidebarOpen = false" 
               class="group relative flex items-center gap-3.5 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ Request::routeIs('dosen.magang.*') ? 'bg-white shadow-md shadow-slate-200/50 text-primary active-nav-pill hover:shadow-lg hover:-translate-y-0.5' : 'text-slate-500 hover:bg-primary/5 hover:text-primary' }}">
                @if(Request::routeIs('dosen.magang.*'))
                    <div class="absolute left-2 w-1 h-5 bg-primary rounded-full shadow-[0_0_10px_rgba(139,21,56,0.3)]"></div>
                @endif
                <span class="material-symbols-outlined text-[23px] transition-all duration-300 group-hover:scale-110 {{ Request::routeIs('dosen.magang.*') ? 'fill-current' : 'text-slate-400 group-hover:text-primary' }}">
                    work
                </span>
                <p class="text-sm {{ Request::routeIs('dosen.magang.*') ? 'font-black' : 'font-semibold' }}">Bimbingan Magang</p>
            </a>

            @if(Route::has('dosen.skripsi.index'))
            <a href="{{ route('dosen.skripsi.index') }}" 
               @click="if(window.innerWidth < 1024) sidebarOpen = false" 
               class="group relative flex items-center gap-3.5 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ Request::routeIs('dosen.skripsi.*') ? 'bg-white shadow-md shadow-slate-200/50 text-primary active-nav-pill hover:shadow-lg hover:-translate-y-0.5' : 'text-slate-500 hover:bg-primary/5 hover:text-primary' }}">
                @if(Request::routeIs('dosen.skripsi.*'))
                    <div class="absolute left-2 w-1 h-5 bg-primary rounded-full shadow-[0_0_10px_rgba(139,21,56,0.3)]"></div>
                @endif
                <span class="material-symbols-outlined text-[23px] transition-all duration-300 group-hover:scale-110 {{ Request::routeIs('dosen.skripsi.*') ? 'fill-current' : 'text-slate-400 group-hover:text-primary' }}">
                    school
                </span>
                <p class="text-sm {{ Request::routeIs('dosen.skripsi.*') ? 'font-black' : 'font-semibold' }}">Bimbingan Skripsi</p>
            </a>
            @endif

            <!-- Premium Bottom Section -->
            <div class="mt-auto pt-8 pb-4 flex flex-col gap-2">
                <div class="h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent mb-4 opacity-60"></div>
                
                @if(auth()->user() && auth()->user()->role === 'dosen')
                    <a href="{{ route('dosen.profil.index') }}" class="group flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-slate-500 hover:bg-primary/5 hover:text-primary transition-all duration-300">
                        <span class="material-symbols-outlined text-[23px] transition-all duration-300 group-hover:scale-110 text-slate-400 group-hover:text-primary">
                            account_circle
                        </span>
                        <p class="text-sm font-semibold tracking-tight">Profil Saya</p>
                    </a>
                @else
                    <a href="#" class="group flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-slate-500 hover:bg-primary/5 hover:text-primary transition-all duration-300">
                        <span class="material-symbols-outlined text-[23px] transition-all duration-300 group-hover:scale-110 text-slate-400 group-hover:text-primary">
                            account_circle
                        </span>
                        <p class="text-sm font-semibold tracking-tight">Profil Saya</p>
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full group flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-red-500 hover:bg-red-50 transition-all duration-300">
                        <div class="flex items-center gap-3.5">
                            <span class="material-symbols-outlined text-[23px] transition-all duration-300 group-hover:scale-110">
                                logout
                            </span>
                            <p class="text-sm font-bold tracking-tight">Logout</p>
                        </div>
                    </button>
                </form>
            </div>
        </nav>
    </div>
</aside>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>