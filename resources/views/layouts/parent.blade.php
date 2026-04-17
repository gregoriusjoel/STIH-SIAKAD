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
            background: linear-gradient(to bottom, #7a1621 0%, #7a1621 100px, #ffffff 200px);
            background-repeat: no-repeat;
            background-color: #ffffff;
            border-right: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 10px 0 30px rgba(0, 0, 0, 0.02);
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            background: transparent;
            padding: 2.5rem 1.5rem 2.5rem 1.5rem;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sidebar-link {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: #475569;
            border-radius: 0.85rem;
            margin-bottom: 0.25rem;
            position: relative;
            font-weight: 500;
        }

        .sidebar-link i {
            color: #94a3b8;
            transition: all 0.3s ease;
            width: 1.5rem;
            text-align: center;
        }

        .sidebar-link:hover {
            color: #7a1621;
            background-color: #fff1f2;
            transform: translateX(4px);
        }

        .sidebar-link:hover i {
            color: #7a1621;
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, #7a1621 0%, #941a28 100%);
            color: #ffffff !important;
            box-shadow: 0 8px 20px rgba(122, 22, 33, 0.25);
            font-weight: 700;
        }

        .sidebar-link.active i {
            color: #ffffff !important;
        }

        .nav-section-title {
            color: #94a3b8;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            font-weight: 800;
            padding: 0 1rem;
            margin-bottom: 0.75rem;
            margin-top: 2rem;
            opacity: 0.8;
        }

        .user-card {
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 1.25rem;
            padding: 1.25rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }

        .user-card:hover {
            background: #ffffff;
            border-color: #7a162115;
            box-shadow: 0 10px 25px rgba(122, 22, 33, 0.05);
            transform: translateY(-2px);
        }

        .btn-logout {
            background: #ffffff;
            color: #64748b;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .btn-logout:hover {
            background: #fee2e2;
            color: #dc2626;
            border-color: #fecaca;
        }

        /* Custom scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800 antialiased selection:bg-maroon selection:text-white">
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile Backdrop -->
        <div id="sidebar-backdrop"
            class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-40 hidden md:hidden transition-opacity"></div>

        <!-- Sidebar -->
        <aside
            class="sidebar w-72 flex-shrink-0 overflow-y-auto fixed inset-y-0 left-0 z-50 transition-transform duration-300 transform -translate-x-full md:relative md:translate-x-0 md:static md:block flex flex-col">

            <div class="sidebar-header">
                <div
                    class="w-16 h-16 bg-white rounded-2xl shadow-xl flex items-center justify-center mb-4 transform transition-all hover:scale-110 hover:rotate-3 p-3 border border-gray-100">
                    <img src="{{ asset('images/logo_stih_color.png') }}"
                        onerror="this.src='{{ asset('images/logo_stih_white.png') }}';" alt="Logo STIH"
                        class="w-full h-full object-contain" />
                </div>
                <h1 class="text-2xl font-black text-white tracking-tight drop-shadow-md">SIAKAD STIH</h1>
                <p class="text-white/80 text-[10px] font-bold tracking-[0.3em] uppercase mt-1">Orang Tua/Wali</p>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1">
                <a href="{{ route('parent.dashboard') }}"
                    class="sidebar-link flex items-center px-4 py-3 text-sm {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home text-lg mr-3"></i>
                    <span>Dashboard</span>
                </a>

                <div class="nav-section-title">Akademik</div>

                <div class="space-y-1">
                    <a href="{{ route('parent.nilai') }}"
                        class="sidebar-link flex items-center px-4 py-3 text-sm {{ request()->routeIs('parent.nilai*') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice text-lg mr-3"></i>
                        <span>Nilai Akademik</span>
                    </a>

                    <a href="{{ route('parent.jadwal') }}"
                        class="sidebar-link flex items-center px-4 py-3 text-sm {{ request()->routeIs('parent.jadwal*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt text-lg mr-3"></i>
                        <span>Jadwal Kuliah</span>
                    </a>

                    <a href="{{ route('parent.presensi') }}"
                        class="sidebar-link flex items-center px-4 py-3 text-sm {{ request()->routeIs('parent.presensi*') ? 'active' : '' }}">
                        <i class="fas fa-clock text-lg mr-3"></i>
                        <span>Presensi Semester</span>
                    </a>
                </div>

                <div class="nav-section-title">Keuangan</div>

                <div class="space-y-1">
                    <a href="{{ route('parent.pembayaran') }}"
                        class="sidebar-link flex items-center px-4 py-3 text-sm {{ request()->routeIs('parent.pembayaran*') ? 'active' : '' }}">
                        <i class="fas fa-credit-card w-6 text-center text-lg mr-3"></i>
                        <span>Pembayaran</span>
                    </a>
                </div>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 mt-auto">
                <div class="user-card">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#7a1621] to-[#9b1c2a] flex items-center justify-center text-white text-lg font-black shadow-lg">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Online Parent</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Mobile Sidebar Toggle -->
            <button
                class="md:hidden fixed top-4 right-4 z-50 bg-white p-2 rounded-lg shadow-md text-maroon hover:bg-gray-50 focus:outline-none"
                id="sidebar-toggle">
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