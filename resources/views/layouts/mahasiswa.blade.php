<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SIAKAD STIH - Mahasiswa')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo_stih_white.png') }}">

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        .active-nav {
            background: linear-gradient(90deg, rgba(139, 21, 56, 0.1) 0%, rgba(139, 21, 56, 0.05) 100%);
            color: #8B1538;
            font-weight: 600;
            border-left: 3px solid #8B1538;
        }

        .sidebar-link {
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            background: rgba(139, 21, 56, 0.05);
            transform: translateX(2px);
        }

        /* Custom Scrollbar - Small/Thin */
        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #8B1538;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #6D1029;
        }
    </style>

    @stack('styles')

    <!-- Theme Initialization Script -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('color-theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            if (savedTheme === 'dark' || (!savedTheme && systemTheme)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

</head>

<body class="bg-bg-body font-inter text-text-primary transition-colors duration-200" x-data="{ sidebarOpen: false }">

    <!-- Page Wrapper -->
    <div class="flex h-screen overflow-hidden">

        <!-- Mobile Backdrop -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/80 z-40 lg:hidden" style="display: none;"></div>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-bg-sidebar border-r border-border-color flex flex-col h-full shadow-sm transition-transform duration-300 transform lg:static lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="px-5 py-6 flex flex-col gap-8 h-full overflow-y-auto overflow-x-hidden scrollbar-thin">
                <!-- Logo -->
                <div class="flex items-center gap-3 px-1">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#8B1538] to-[#6D1029] flex items-center justify-center text-white shadow-lg shadow-maroon/20 shrink-0">
                        <i class="fas fa-graduation-cap text-lg"></i>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <h1 class="text-text-primary text-sm font-bold leading-tight truncate">STIH Adhyaksa</h1>
                        <p class="text-text-muted text-[10px] font-medium tracking-wide">STUDENT PORTAL</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex flex-col gap-1.5 grow" x-data="{ 
                        openAkademik: {{ Request::routeIs('mahasiswa.nilai*', 'mahasiswa.kelas*', 'mahasiswa.jadwal*', 'mahasiswa.perpustakaan*', 'mahasiswa.prestasi*') ? 'true' : 'false' }},
                        openPengajuan: {{ Request::routeIs('mahasiswa.pengajuan*') ? 'true' : 'false' }} 
                    }">

                    {{-- Nav Item Template --}}
                    @php
                        $navItemClass = "flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200 group";
                        $activeClass = "bg-primary/10 text-primary font-semibold";
                        $inactiveClass = "text-text-secondary hover:bg-bg-hover hover:text-text-primary";
                    @endphp

                    <a class="{{ $navItemClass }} {{ Request::routeIs('mahasiswa.dashboard') ? $activeClass : $inactiveClass }}"
                        href="{{ route('mahasiswa.dashboard') }}">
                        <i
                            class="fas fa-home text-lg w-5 {{ Request::routeIs('mahasiswa.dashboard') ? 'text-primary' : 'text-text-muted group-hover:text-primary' }}"></i>
                        <span class="text-sm">Dashboard</span>
                    </a>

                    <a class="{{ $navItemClass }} {{ Request::routeIs('mahasiswa.profil.manajemen') ? $activeClass : $inactiveClass }}"
                        href="{{ route('mahasiswa.profil.manajemen') }}">
                        <i
                            class="fas fa-user-cog text-lg w-5 {{ Request::routeIs('mahasiswa.profil.manajemen') ? 'text-primary' : 'text-text-muted group-hover:text-primary' }}"></i>
                        <span class="text-sm">Manajemen Profil</span>
                    </a>

                    <a class="{{ $navItemClass }} {{ Request::routeIs('mahasiswa.krs*') ? $activeClass : $inactiveClass }}"
                        href="{{ route('mahasiswa.krs.index') }}">
                        <i
                            class="fas fa-file-alt text-lg w-5 {{ Request::routeIs('mahasiswa.krs*') ? 'text-primary' : 'text-text-muted group-hover:text-primary' }}"></i>
                        <span class="text-sm">KRS</span>
                    </a>

                    <!-- Akademik Dropdown -->
                    <div class="relative">
                        <button @click="openAkademik = !openAkademik"
                            class="w-full flex items-center justify-between {{ $navItemClass }} {{ Request::routeIs('mahasiswa.nilai*', 'mahasiswa.kelas*', 'mahasiswa.jadwal*', 'mahasiswa.perpustakaan*', 'mahasiswa.prestasi*') ? 'text-primary' : $inactiveClass }}">
                            <div class="flex items-center gap-3">
                                <i
                                    class="fas fa-graduation-cap text-lg w-5 {{ Request::routeIs('mahasiswa.nilai*', 'mahasiswa.kelas*', 'mahasiswa.jadwal*', 'mahasiswa.perpustakaan*', 'mahasiswa.prestasi*') ? 'text-primary' : 'text-text-muted group-hover:text-primary' }}"></i>
                                <span class="text-sm">Akademik</span>
                            </div>
                            <i class="fas fa-chevron-down text-[10px] transition-transform duration-300"
                                :class="{'rotate-180': openAkademik}"></i>
                        </button>

                        <div x-show="openAkademik" x-collapse class="pl-12 pr-2 py-1 space-y-1">
                            @foreach([
                                    'mahasiswa.nilai.index' => ['label' => 'Kartu Hasil Studi', 'icon' => 'fa-chart-bar'],
                                    'mahasiswa.jadwal.index' => ['label' => 'Jadwal Kelas', 'icon' => 'fa-calendar-alt'],
                                    'mahasiswa.kelas.index' => ['label' => 'E-Learning', 'icon' => 'fa-laptop-code'],
                                    'mahasiswa.perpustakaan.index' => ['label' => 'Perpustakaan', 'icon' => 'fa-book'],
                                    'mahasiswa.prestasi.index' => ['label' => 'Prestasi Mahasiswa', 'icon' => 'fa-trophy'],
                                ] as $route => $data)
                                    <a class="block py-1.5 text-[13px] {{ Request::routeIs($route) ? 'text-[#8B1538] dark:text-red-400 font-bold' : 'text-[#6B7280] dark:text-slate-400 hover:text-[#1A1A1A] dark:hover:text-white' }} transition-colors"
                                        href="{{ route($route) }}">
                                        {{ $data['label'] }}
                                    </a>
                            @endforeach
                        </div>
                    </div>


                                                       <!-- Pengajuan Dropdown -->
                    <div class="relative">
                        <button @click="openPengajuan = !openPengajuan"
                            class="w-full flex items-center justify-between {{ $navItemClass }} {{ Request::routeIs('mahasiswa.pengajuan*') ? 'text-[#8B1538]' : $inactiveClass }}">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-file-signature text-lg w-5 {{ Request::routeIs('mahasiswa.pengajuan*') ? 'text-[#8B1538]' : 'text-[#9CA3AF] dark:text-slate-500 group-hover:text-[#8B1538]' }}"></i>
                                <span class="text-sm">Pengajuan</span>
                            </div>
                        <i class="fas fa-chevron-down text-[10px] transition-transform duration-300"
                            :class="{'rotate-180': openPengajuan}"></i>
                    </button>
                        <div x-show="openPengajuan" x-collapse class="pl-12 pr-2 py-1 space-y-1">
                            @foreach([
                                    'mahasiswa.pengajuan.sidang.index' => 'Pengajuan Sidang',
                                    'mahasiswa.pengajuan.surat.index' => 'Pengajuan Surat',
                                    'mahasiswa.pengajuan.yudisium.index' => 'Pengajuan Yudisium',
                                ] as $route => $label)

                                     <a class="block py-1.5 text-[13px] {{ Request::routeIs($route) ? 'text-[#8B1538] dark:text-red-400 font-bold' : 'text-[#6B7280] dark:text-slate-400 hover:text-[#1A1A1A] dark:hover:text-white' }} transition-colors"
                                        href="{{ route($route) }}">
                                        {{ $label }}
                                    </a>
                            @endforeach
                        </div>
                    </div>

                    <a class="
    {{ $navItemClass }} {{ Request::routeIs('mahasiswa.pembayaran*') ? $activeClass : $inactiveClass }}"
                        href="{{ route('mahasiswa.pembayaran.index') }}">
                        <i class="fas fa-credit-card text-lg w-5 {{ Request::routeIs('mahasiswa.pembayaran*') ? 'text-[#8B1538]' : 'text-[#9CA3AF] dark:text-slate-500 group-hover:text-[#8B1538]' }}"></i>
                        <span class="text-sm">Pembayaran</span>
                    </a>

                    <!-- Top-level Pengajuan removed: use dropdown Pengajuan -> Pengajuan Surat -->

                    <!-- Spacer -->
                    <div class="mt-auto pt-6 pb-2 border-t border-gray-100 dark:border-slate-700">
                        <a class="{{ $navItemClass }} {{ Request::routeIs('mahasiswa.profil.index') ? $activeClass : $inactiveClass }}"
                            href="{{ route('mahasiswa.profil.index') }}">
                            <i class="fas fa-user-circle text-lg w-5 {{ Request::routeIs('mahasiswa.profil.index') ? 'text-[#8B1538]' : 'text-[#9CA3AF] dark:text-slate-500 group-hover:text-[#8B1538]' }}"></i>
                            <span class="text-sm">Profil</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-[#DC2626] dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all font-medium">
                                <i class="fas fa-sign-out-alt text-lg w-5"></i>
                                <span class="text-sm">Logout</span>
                            </button>
                        </form>
                    </div>
                </nav>
            </div>

                       
                               </aside>

        <!-- Content Wrapper -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">

            <!-- Topbar -->

                                        <header class="bg-bg-card border-b border-border-color px-4 sm:px-6 lg:px-8 py-3 shadow-sm sticky top-0 z-40 transition-colors duration-200">
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

            <script>
                (function() {
                    var toggle = document.getElementById('theme-toggle-input');
                    var html = document.documentElement;
                    
                    // Sync input state with current theme
                    if (html.classList.contains('dark')) {
                        if(toggle) toggle.checked = true;
                    } else {
                        if(toggle) toggle.checked = false;
                    }

                    if(toggle) {
                        toggle.addEventListener('change', function(e) {
                            if(e.target.checked) {
                                html.classList.add('dark');
                                localStorage.setItem('color-theme', 'dark');
                            } else {
                                html.classList.remove('dark');
                                localStorage.setItem('color-theme', 'light');
                            }
                        });
                    }
                })();
            </script>

            <!-- Main Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-bg-body transition-colors duration-200">
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 mb-6 animate-fade-in">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-6 animate-fade-in">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                            <p class="text-red-800 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-bg-card border-t border-border-color px-6 py-4 transition-colors duration-200">
                <div class="text-center text-sm text-text-secondary">
                    <p>&copy; {{ date('Y') }} SIAKAD STIH. All rights reserved.</p>
                </div>
            </footer>

        </div>
    </div>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('scripts')

    <x-ui.preloader />
</body>

</html>