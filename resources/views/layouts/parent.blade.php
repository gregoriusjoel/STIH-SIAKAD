<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIAKAD STIH') - Parent Panel</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo_stih_white.png') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .sidebar {
            background-color: #ffffff;
            border-right: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.02);
        }

        .sidebar-header {
            background: transparent;
            border-bottom: none;
        }

        .sidebar-link {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: #4b5563;
            border-radius: 0.75rem;
            margin-bottom: 0.5rem;
            border: 1px solid transparent;
        }

        .sidebar-link i {
            color: #9ca3af;
            transition: color 0.3s ease;
        }

        .sidebar-link:hover {
            background-color: #fce7f3; /* Light pink/maroon tint */
            color: #800020;
            transform: translateX(4px);
            border-color: rgba(128, 0, 32, 0.1);
        }

        .sidebar-link:hover i {
            color: #800020;
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, #800020 0%, #a00028 100%);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(128, 0, 32, 0.2);
            font-weight: 500;
        }

        .sidebar-link.active i {
            color: #ffffff;
        }

        .nav-section-title {
            color: #9ca3af;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
            padding: 0 1rem;
            margin-bottom: 0.75rem;
            margin-top: 1.5rem;
        }

        .glass-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .top-badge {
            background: linear-gradient(135deg, #800020 0%, #a00028 100%);
            box-shadow: 0 2px 10px rgba(178, 34, 34, 0.2);
        }

        .btn-maroon {
            background-color: #800020;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-maroon:hover {
            background-color: #5a0015;
            box-shadow: 0 4px 12px rgba(90, 0, 21, 0.2);
            transform: translateY(-1px);
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
        
        /* Custom scrollbar for sidebar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 4px;
        }
        .sidebar:hover::-webkit-scrollbar-thumb {
            background: #d1d5db;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800 antialiased selection:bg-maroon selection:text-white">
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile Backdrop -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-40 hidden md:hidden transition-opacity"></div>

        <!-- Sidebar -->
        <aside
            class="sidebar w-72 flex-shrink-0 overflow-y-auto fixed inset-y-0 left-0 z-50 transition-transform duration-300 transform -translate-x-full md:relative md:translate-x-0 md:static md:block flex flex-col">
            
            <div class="sidebar-header p-6 flex flex-col items-center justify-center min-h-[140px] relative overflow-hidden bg-white">
                <div class="w-14 h-14 bg-white border border-gray-100 rounded-xl shadow-sm flex items-center justify-center mb-2 transform transition-transform hover:scale-105 p-2">
                    <img src="{{ asset('images/logo_stih_color.png') }}" onerror="this.src='{{ asset('images/logo_stih_white.png') }}';" alt="Logo STIH" class="w-full h-full object-contain filter drop-shadow-sm" />
                </div>
                <h1 class="text-xl font-bold text-gray-800 tracking-wide z-10">SIAKAD STIH</h1>
                <p class="text-maroon text-xs font-bold tracking-widest uppercase mt-1 z-10">Orang Tua/Wali</p>
            </div>

            <nav class="flex-1 px-4 py-6">
                <a href="{{ route('parent.dashboard') }}"
                    class="sidebar-link flex items-center px-4 py-3 text-sm {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-border-all w-6 text-center text-lg mr-3"></i>
                    <span>Dashboard</span>
                </a>

                <div class="nav-section-title">Akademik</div>

                <a href="{{ route('parent.nilai') }}"
                    class="sidebar-link flex items-center px-4 py-3 text-sm {{ request()->routeIs('parent.nilai*') ? 'active' : '' }}">
                    <i class="fas fa-award w-6 text-center text-lg mr-3"></i>
                    <span>Nilai Akademik</span>
                </a>

                <a href="{{ route('parent.jadwal') }}"
                    class="sidebar-link flex items-center px-4 py-3 text-sm {{ request()->routeIs('parent.jadwal*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-day w-6 text-center text-lg mr-3"></i>
                    <span>Jadwal Kuliah</span>
                </a>

                <a href="{{ route('parent.presensi') }}"
                    class="sidebar-link flex items-center px-4 py-3 text-sm {{ request()->routeIs('parent.presensi*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-user w-6 text-center text-lg mr-3"></i>
                    <span>Presensi</span>
                </a>

                <div class="nav-section-title">Keuangan</div>

                <a href="{{ route('parent.pembayaran') }}"
                    class="sidebar-link flex items-center px-4 py-3 text-sm {{ request()->routeIs('parent.pembayaran*') ? 'active' : '' }}">
                    <i class="fas fa-wallet w-6 text-center text-lg mr-3"></i>
                    <span>Pembayaran</span>
                </a>

            </nav>
            
            <!-- Sidebar Footer -->
            <div class="mt-auto bg-gray-50/50">
                <div class="p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-maroon to-red-600 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 font-medium truncate">Orang Tua/Wali</p>
                    </div>
                </div>
                
                <div class="px-4 pb-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors group border border-red-100/50">
                            <i class="fas fa-sign-out-alt text-red-500 group-hover:text-red-700 transition-colors"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Mobile Sidebar Toggle -->
            <button class="md:hidden fixed top-4 right-4 z-50 bg-white p-2 rounded-lg shadow-md text-maroon hover:bg-gray-50 focus:outline-none" id="sidebar-toggle">
                <i class="fas fa-bars text-xl"></i>
            </button>



            <!-- Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50/30 p-4 md:p-6 lg:p-8">
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
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.querySelector('aside.sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');

        function toggleMobileSidebar() {
            if (!sidebar) return;
            sidebar.classList.toggle('-translate-x-full');

            if (backdrop) {
                backdrop.classList.toggle('hidden');
            }
        }

        sidebarToggle?.addEventListener('click', toggleMobileSidebar);
        backdrop?.addEventListener('click', toggleMobileSidebar);
    </script>

    @stack('scripts')
    <x-ui.preloader />
</body>

</html>