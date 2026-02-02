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

</head>

<body class="bg-gray-50 font-inter">

    <!-- Page Wrapper -->
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 hidden lg:flex flex-col h-full shadow-sm">
            <div class="p-6 flex flex-col gap-6 h-full overflow-y-auto">
                <!-- Logo -->
                <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-maroon to-red-900 flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-graduation-cap text-xl"></i>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-gray-800 text-lg font-bold leading-tight">STIH Adhyaksa
                            <p class="text-gray-500 text-xs font-normal">Student Portal</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex flex-col gap-1 grow" x-data="{ 
                        openAkademik: {{ Request::routeIs('mahasiswa.nilai*', 'mahasiswa.kelas*', 'mahasiswa.jadwal*', 'mahasiswa.perpustakaan*', 'mahasiswa.prestasi*') ? 'true' : 'false' }},
                        openPengajuan: {{ Request::routeIs('mahasiswa.pengajuan*') ? 'true' : 'false' }} 
                    }">
                    <a class="{{ Request::routeIs('mahasiswa.dashboard') ? 'active-nav' : 'sidebar-link text-gray-600' }} flex items-center gap-3 px-4 py-3 rounded-lg"
                        href="{{ route('mahasiswa.dashboard') }}">
                        <i class="fas fa-home text-lg w-5"></i>
                        <span class="text-sm font-medium">Dashboard</span>
                    </a>

                    <a class="{{ Request::routeIs('mahasiswa.profil.manajemen') ? 'active-nav' : 'sidebar-link text-gray-600' }} flex items-center gap-3 px-4 py-3 rounded-lg"
                        href="{{ route('mahasiswa.profil.manajemen') }}">
                        <i class="fas fa-user-cog text-lg w-5"></i>
                        <span class="text-sm font-medium">Manajemen Profil</span>
                    </a>

                    <a class="{{ Request::routeIs('mahasiswa.krs*') ? 'active-nav' : 'sidebar-link text-gray-600' }} flex items-center gap-3 px-4 py-3 rounded-lg"
                        href="{{ route('mahasiswa.krs.index') }}">
                        <i class="fas fa-file-alt text-lg w-5"></i>
                        <span class="text-sm font-medium">KRS</span>
                    </a>

                    <!-- Akademik Dropdown -->
                    <div>
                        <button @click="openAkademik = !openAkademik"
                            class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-gray-600 hover:bg-red-50 hover:text-maroon transition-colors group {{ Request::routeIs('mahasiswa.nilai*', 'mahasiswa.kelas*', 'mahasiswa.jadwal*', 'mahasiswa.perpustakaan*', 'mahasiswa.prestasi*') ? 'bg-red-50 text-maroon' : '' }}">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-graduation-cap text-lg w-5 group-hover:text-maroon"></i>
                                <span class="text-sm font-medium">Akademik</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                                :class="{'rotate-180': openAkademik}"></i>
                        </button>

                        <!-- Dropdown Content -->
                        <div x-show="openAkademik" class="bg-gray-50/50 rounded-b-lg mb-2">
                            <a class="{{ Request::routeIs('mahasiswa.nilai.index') ? 'text-maroon font-semibold bg-red-50/50' : 'text-gray-500 hover:text-maroon hover:bg-gray-50' }} flex items-center gap-3 pl-12 pr-4 py-2.5 text-sm transition-colors"
                                href="{{ route('mahasiswa.nilai.index') }}">
                                <i class="fas fa-chart-bar w-4 text-center text-xs opacity-70"></i>
                                <span>Kartu Hasil Studi</span>
                            </a>
                            <a class="{{ Request::routeIs('mahasiswa.jadwal.index') ? 'text-maroon font-semibold bg-red-50/50' : 'text-gray-500 hover:text-maroon hover:bg-gray-50' }} flex items-center gap-3 pl-12 pr-4 py-2.5 text-sm transition-colors"
                                href="{{ route('mahasiswa.jadwal.index') }}">
                                <i class="fas fa-calendar-alt w-4 text-center text-xs opacity-70"></i>
                                <span>Jadwal Kelas</span>
                            </a>
                            <a class="{{ Request::routeIs('mahasiswa.kelas.index') ? 'text-maroon font-semibold bg-red-50/50' : 'text-gray-500 hover:text-maroon hover:bg-gray-50' }} flex items-center gap-3 pl-12 pr-4 py-2.5 text-sm transition-colors"
                                href="{{ route('mahasiswa.kelas.index') }}">
                                <i class="fas fa-laptop-code w-4 text-center text-xs opacity-70"></i>
                                <span>E-Learning</span>
                            </a>
                            <a class="{{ Request::routeIs('mahasiswa.perpustakaan.index') ? 'text-maroon font-semibold bg-red-50/50' : 'text-gray-500 hover:text-maroon hover:bg-gray-50' }} flex items-center gap-3 pl-12 pr-4 py-2.5 text-sm transition-colors"
                                href="{{ route('mahasiswa.perpustakaan.index') }}">
                                <i class="fas fa-book w-4 text-center text-xs opacity-70"></i>
                                <span>Perpustakaan</span>
                            </a>
                            <a class="{{ Request::routeIs('mahasiswa.prestasi.index') ? 'text-maroon font-semibold bg-red-50/50' : 'text-gray-500 hover:text-maroon hover:bg-gray-50' }} flex items-center gap-3 pl-12 pr-4 py-2.5 text-sm transition-colors"
                                href="{{ route('mahasiswa.prestasi.index') }}">
                                <i class="fas fa-trophy w-4 text-center text-xs opacity-70"></i>
                                <span>Prestasi Mahasiswa</span>
                            </a>
                        </div>
                    </div>

                    <!-- Pengajuan Dropdown -->
                    <div>
                        <button @click="openPengajuan = !openPengajuan"
                            class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-gray-600 hover:bg-red-50 hover:text-maroon transition-colors group {{ Request::routeIs('mahasiswa.pengajuan*') ? 'bg-red-50 text-maroon' : '' }}">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-file-signature text-lg w-5 group-hover:text-maroon"></i>
                                <span class="text-sm font-medium">Pengajuan</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                                :class="{'rotate-180': openPengajuan}"></i>
                        </button>

                        <!-- Dropdown Content -->
                        <div x-show="openPengajuan" class="bg-gray-50/50 rounded-b-lg mb-2">
                            <a class="{{ Request::routeIs('mahasiswa.pengajuan.sidang.index') ? 'text-maroon font-semibold bg-red-50/50' : 'text-gray-500 hover:text-maroon hover:bg-gray-50' }} flex items-center gap-3 pl-12 pr-4 py-2.5 text-sm transition-colors"
                                href="{{ route('mahasiswa.pengajuan.sidang.index') }}">
                                <i class="fas fa-gavel w-4 text-center text-xs opacity-70"></i>
                                <span>Pengajuan Sidang</span>
                            </a>
                            <a class="{{ Request::routeIs('mahasiswa.pengajuan.surat.index') ? 'text-maroon font-semibold bg-red-50/50' : 'text-gray-500 hover:text-maroon hover:bg-gray-50' }} flex items-center gap-3 pl-12 pr-4 py-2.5 text-sm transition-colors"
                                href="{{ route('mahasiswa.pengajuan.surat.index') }}">
                                <i class="fas fa-envelope-open-text w-4 text-center text-xs opacity-70"></i>
                                <span>Pengajuan Surat</span>
                            </a>
                            <a class="{{ Request::routeIs('mahasiswa.pengajuan.yudisium.index') ? 'text-maroon font-semibold bg-red-50/50' : 'text-gray-500 hover:text-maroon hover:bg-gray-50' }} flex items-center gap-3 pl-12 pr-4 py-2.5 text-sm transition-colors"
                                href="{{ route('mahasiswa.pengajuan.yudisium.index') }}">
                                <i class="fas fa-user-graduate w-4 text-center text-xs opacity-70"></i>
                                <span>Pengajuan Yudisium</span>
                            </a>
                        </div>
                    </div>

                    <a class="{{ Request::routeIs('mahasiswa.pembayaran*') ? 'active-nav' : 'sidebar-link text-gray-600' }} flex items-center gap-3 px-4 py-3 rounded-lg"
                        href="{{ route('mahasiswa.pembayaran.index') }}">
                        <i class="fas fa-credit-card text-lg w-5"></i>
                        <span class="text-sm font-medium">Pembayaran</span>
                    </a>

                    <!-- Bottom Section -->
                    <a class="{{ Request::routeIs('mahasiswa.profil.index') ? 'active-nav' : 'sidebar-link text-gray-600' }} flex items-center gap-3 px-4 py-3 rounded-lg mt-auto"
                        href="{{ route('mahasiswa.profil.index') }}">
                        <i class="fas fa-user-circle text-lg w-5"></i>
                        <span class="text-sm font-medium">Profil</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition-all">
                            <i class="fas fa-sign-out-alt text-lg w-5"></i>
                            <span class="text-sm font-medium">Logout</span>
                        </button>
                    </form>
                </nav>

            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">

            <!-- Topbar -->
            <header class="bg-white border-b border-gray-200 px-6 py-4 shadow-sm sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <!-- Mobile Menu Button -->
                    <button class="lg:hidden text-gray-600 hover:text-gray-800" id="sidebarToggle">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <!-- Page Title - Mobile Hidden -->
                    <div class="hidden lg:block">
                        <h2 class="text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    </div>

                    <!-- Right Side -->
                    <div class="flex items-center gap-4 ml-auto">
                        <!-- User Dropdown (minimal) -->
                        <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ Auth::user()->mahasiswa->nama ?? Auth::user()->name }}
                                </p>
                                <p class="text-xs text-gray-500">NIM: {{ Auth::user()->mahasiswa->nim ?? '-' }}</p>
                            </div>
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-br from-maroon to-red-900 flex items-center justify-center text-white font-bold shadow-md">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 p-6 bg-gray-50">
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
            <footer class="bg-white border-t border-gray-200 px-6 py-4">
                <div class="text-center text-sm text-gray-600">
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