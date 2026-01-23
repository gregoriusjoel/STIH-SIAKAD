<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIAKAD STIH') - Parent Panel</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .sidebar {
            background-color: #7a1621;
            /* maroon */
        }

        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.06);
        }

        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.08);
            border-right: 4px solid rgba(255, 255, 255, 0.12);
        }

        .btn-maroon {
            background-color: #800020;
            color: white;
        }

        .btn-maroon:hover {
            background-color: #5a0015;
        }

        .text-maroon {
            color: #800020;
        }

        .bg-maroon {
            background-color: #800020;
        }

        .border-maroon {
            border-color: #800020;
        }

        /* Header tweaks */
        .top-badge {
            background: linear-gradient(90deg, #ff7b7b, #b22222);
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="sidebar w-64 flex-shrink-0 hidden md:block overflow-y-auto">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-white text-center">SIAKAD STIH</h1>
                <p class="text-white text-center text-sm mt-1">Parent Panel</p>
            </div>

            <nav class="px-4 pb-4">
                <a href="{{ route('parent.dashboard') }}"
                    class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg mb-2 {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home w-5 mr-3"></i>
                    Dashboard
                </a>

                <div class="mt-4">
                    <p class="text-gray-300 text-xs uppercase px-4 mb-2">Akademik</p>

                    <a href="{{ route('parent.nilai') }}"
                        class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg mb-2 {{ request()->routeIs('parent.nilai*') ? 'active' : '' }}">
                        <i class="fas fa-graduation-cap w-5 mr-3"></i>
                        Nilai Akademik
                    </a>

                    <a href="{{ route('parent.jadwal') }}"
                        class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg mb-2 {{ request()->routeIs('parent.jadwal*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt w-5 mr-3"></i>
                        Jadwal Kuliah
                    </a>

                    <a href="{{ route('parent.presensi') }}"
                        class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg mb-2 {{ request()->routeIs('parent.presensi*') ? 'active' : '' }}">
                        <i class="fas fa-user-check w-5 mr-3"></i>
                        Presensi
                    </a>
                </div>

                <div class="mt-4">
                    <p class="text-gray-300 text-xs uppercase px-4 mb-2">Keuangan</p>

                    <a href="{{ route('parent.pembayaran') }}"
                        class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg mb-2 {{ request()->routeIs('parent.pembayaran*') ? 'active' : '' }}">
                        <i class="fas fa-credit-card w-5 mr-3"></i>
                        Pembayaran
                    </a>
                </div>

                <div class="mt-4">
                    <p class="text-gray-300 text-xs uppercase px-4 mb-2">Sistem</p>

                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit"
                            class="sidebar-link w-full flex items-center px-4 py-3 text-white rounded-lg hover:bg-red-900/50 transition-colors">
                            <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between px-6 py-3">
                    <div class="flex items-center gap-4">
                        <button class="text-gray-500 md:hidden focus:outline-none focus:text-gray-700"
                            id="sidebar-toggle">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <nav class="text-sm text-gray-500">
                            <span class="mr-2">Home</span>
                            <i class="fas fa-chevron-right text-xs mr-2"></i>
                            <span class="font-semibold text-gray-800">@yield('page-title', 'Dashboard')</span>
                        </nav>
                        <div class="ml-4 hidden sm:block">
                            <span
                                class="inline-block px-3 py-1 rounded-full text-xs font-medium text-white top-badge">Ganjil
                                2025/2026</span>
                        </div>
                    </div>

                    <div class="flex-1 px-6 hidden md:block">
                        <div class="max-w-xl mx-auto">
                            <div class="relative">
                                <input type="text" placeholder="Cari data..."
                                    class="w-full border rounded-full px-4 py-2 pl-10 focus:ring-2 focus:ring-maroon focus:border-transparent text-sm" />
                                <div
                                    class="absolute left-0 top-0 h-full pl-3 pr-2 flex items-center pointer-events-none text-gray-500">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex items-center space-x-3">
                            <div class="text-right mr-2 hidden sm:block">
                                <div class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">Orang Tua</div>
                            </div>
                            <div
                                class="w-10 h-10 bg-maroon rounded-full flex items-center justify-center text-white font-bold shadow-md">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Auto-hide flash messages after 5 seconds
        setTimeout(function () {
            document.querySelectorAll('[role="alert"]').forEach(function (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function () {
                    alert.remove();
                }, 500);
            });
        }, 5000);

        // Sidebar toggle for small screens
        document.getElementById('sidebar-toggle')?.addEventListener('click', function () {
            const aside = document.querySelector('aside.sidebar');
            if (!aside) return;
            aside.classList.toggle('hidden');
            aside.classList.toggle('fixed');
            aside.classList.toggle('inset-0');
            aside.classList.toggle('z-50');
            aside.classList.toggle('w-64');
        });
    </script>

    @stack('scripts')
</body>

</html>