<header class="bg-gradient-to-r from-[#8B1538] to-[#6D1029] dark:from-[#3a0a1a] dark:to-[#1a050d] border-b border-white/10 px-4 sm:px-6 md:px-8 shadow-md sticky top-0 z-40 transition-colors duration-200 h-16">
    <div class="flex items-center justify-between h-full">
        <!-- Mobile Menu Button -->
        <button class="md:hidden w-10 h-10 flex items-center justify-center text-white/80 hover:bg-white/10 rounded-lg transition-colors" @click="sidebarOpen = !sidebarOpen">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <!-- Page Title / Branding -->
        <div class="flex items-center gap-4">
            <h2 class="text-lg font-extrabold text-white tracking-tight drop-shadow-sm">
                @yield('page-title', 'Dashboard')
            </h2>
        </div>

        <!-- Right Side -->
        <div class="flex items-center gap-4">


            <!-- User Dropdown (minimal) -->
            <div class="flex items-center gap-3 pl-4 border-l border-white/20">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-white leading-tight">
                        {{ Auth::user()->mahasiswa->nama ?? Auth::user()->name }}
                    </p>
                    <p class="text-[10px] text-white/70 font-bold uppercase tracking-wider">
                        NIM: {{ Auth::user()->mahasiswa->nim ?? '-' }}
                    </p>
                </div>
                <div class="relative group cursor-pointer">
                    @if(Auth::user()->mahasiswa && Auth::user()->mahasiswa->foto)
                        <img src="{{ asset('storage/' . Auth::user()->mahasiswa->foto) }}" 
                             alt="Profile Photo" 
                             class="w-10 h-10 rounded-full object-cover border-2 border-white/20 shadow-md">
                    @else
                        <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold shadow-sm border border-white/30 transition-transform group-hover:scale-105">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-[#8B1538] rounded-full"></div>
                </div>
            </div>
        </div>
    </div>
</header>
