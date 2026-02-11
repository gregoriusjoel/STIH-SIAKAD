<header class="bg-white dark:bg-[#1a1d2e] border-b border-border-color lg:border-none px-4 sm:px-6 lg:px-8 py-3 shadow-sm lg:shadow-md sticky top-0 lg:top-4 z-40 transition-colors duration-200 lg:mx-4 lg:mt-4 lg:rounded-2xl">
    <div class="flex items-center justify-between">
        <!-- Mobile Menu Button -->
        <button class="lg:hidden w-10 h-10 flex items-center justify-center text-text-secondary hover:bg-bg-hover rounded-lg transition-colors" @click="sidebarOpen = !sidebarOpen">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <!-- Page Title / Branding -->
        <div class="flex items-center gap-4">
            <h2 class="text-lg font-extrabold text-text-primary tracking-tight">@yield('page-title', 'Dashboard')</h2>
        </div>

        <!-- Right Side -->
        <div class="flex items-center gap-4">
            <!-- Dark Mode Toggle - Simple Sun/Moon -->
            <label class="theme-switch">
                <input id="theme-toggle-input" type="checkbox" />
                <span class="theme-slider">
                    <span class="theme-icon sun">☀️</span>
                    <span class="theme-icon moon">🌙</span>
                </span>
            </label>

            <!-- User Dropdown (minimal) -->
            <div class="flex items-center gap-3 pl-4 border-l border-border-color">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-text-primary">
                        
                      {{ Auth::user()->mahasiswa->nama ?? Auth::user()->name }}

                                                    </p>
                    <p class="text-[10px] text-[#9CA3AF] dark:text-slate-400 font-bold uppercase tracking-wider">
                        NIM: {{ Auth::user()->mahasiswa->nim ?? '-' }}
                    </p>
                </div>
                <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#8B1538] to-[#6D1029] flex items-center justify-center text-white font-bold shadow-md border-2 border-white">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                </div>
            </div>
        </div>
    </div>
</header>
