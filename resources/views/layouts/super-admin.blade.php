<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Control Center') - Super Admin</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo_stih_white.png') }}">

    <!-- Google Fonts: Outfit & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24
        }
        .material-symbols-outlined.block {
            display: block !important;
            text-align: center;
        }
    </style>

    <!-- Font Awesome & App Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html,
        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden !important;
            font-family: 'Outfit', sans-serif !important;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Super Admin Dark Royal Crimson/Maroon Theme Palette */
        .sidebar {
            background: linear-gradient(180deg, #3d050c 0%, #170104 100%);
            border-right: 1px solid rgba(122, 22, 33, 0.2);
        }

        .sidebar-link {
            color: #cbd5e1; /* Clear slate-300 text */
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-link .material-symbols-outlined {
            color: #94a3b8; /* Slate-400 icons */
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), color 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Hover state: elegant gold translucent shimmer, white text, gold icon */
        .sidebar-link:hover {
            background: rgba(245, 158, 11, 0.1); /* Gold tint */
            color: #ffffff !important;
            transform: translateX(4px);
        }

        .sidebar-link:hover .material-symbols-outlined {
            color: #f59e0b !important;
            transform: scale(1.12);
        }

        /* Active state for Standard items: crisp premium white background with deep maroon text & icon */
        .sidebar-link.active {
            background: #ffffff !important;
            color: #7a1621 !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15), 0 2px 4px rgba(0, 0, 0, 0.1);
            font-weight: 700;
        }

        .sidebar-link.active span {
            color: #7a1621 !important;
        }

        .sidebar-link.active .material-symbols-outlined {
            color: #7a1621 !important;
            transform: scale(1.05);
        }

        /* Special Override Center item hovers */
        .sidebar-link.override-item:hover {
            background: rgba(239, 68, 68, 0.12) !important; /* Coral tint */
            color: #ffffff !important;
            transform: translateX(4px);
        }

        .sidebar-link.override-item:hover .material-symbols-outlined {
            color: #ef4444 !important;
            transform: scale(1.12);
        }

        /* Active state for Override Center items: crisp premium white background with vibrant red text & icon */
        .sidebar-link.override-item.active {
            background: #ffffff !important;
            color: #dc2626 !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15), 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            font-weight: 700;
        }

        .sidebar-link.override-item.active span {
            color: #dc2626 !important;
        }

        .sidebar-link.override-item.active .material-symbols-outlined {
            color: #dc2626 !important;
            transform: scale(1.05);
        }

        .btn-gold {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            color: white;
            box-shadow: 0 4px 10px -2px rgba(217, 119, 6, 0.3);
            transition: all 0.25s ease;
        }

        .btn-gold:hover {
            background: linear-gradient(135deg, #b45309 0%, #92400e 100%);
            box-shadow: 0 6px 14px -2px rgba(217, 119, 6, 0.4);
            transform: translateY(-1px);
        }

        .btn-maroon {
            background: linear-gradient(135deg, #7a1621 0%, #4c0810 100%);
            color: white;
            box-shadow: 0 4px 10px -2px rgba(122, 22, 33, 0.3);
            transition: all 0.25s ease;
        }

        .btn-maroon:hover {
            background: linear-gradient(135deg, #5e1019 0%, #350409 100%);
            box-shadow: 0 6px 14px -2px rgba(122, 22, 33, 0.4);
            transform: translateY(-1px);
        }

        .text-gold {
            color: #f59e0b;
        }

        .bg-gold {
            background-color: #f59e0b;
        }

        .border-gold {
            border-color: #f59e0b;
        }

        .text-maroon {
            color: #7a1621;
        }

        .bg-maroon {
            background-color: #7a1621;
        }

        .border-maroon {
            border-color: #7a1621;
        }

        /* Topbar styling */
        .header-super {
            background: linear-gradient(90deg, #3d050c 0%, #240206 100%);
            color: #f8fafc;
            border-bottom: 1px solid rgba(245, 158, 11, 0.25);
        }

        .topbar-row {
            height: 4.25rem;
        }

        .search-input {
            background: rgba(0, 0, 0, 0.2);
            color: #f8fafc;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(4px);
            transition: all 0.2s ease;
        }

        .search-input:focus {
            background: rgba(0, 0, 0, 0.4);
            border-color: #f59e0b;
            box-shadow: 0 0 12px rgba(245, 158, 11, 0.25);
        }

        /* Impersonation Banner */
        .impersonation-banner {
            background: linear-gradient(90deg, #b45309, #d97706);
            color: white;
            font-weight: 600;
            padding: 0.5rem 1rem;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .impersonation-banner form button:hover {
            text-decoration: underline;
        }

        /* Glassmorphism Cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(122, 22, 33, 0.08);
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
            border-radius: 16px;
        }

        /* Scrollbar styles */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: translateY(-4px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    </style>
    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-900 overflow-hidden flex flex-col" style="height: 100vh !important; min-height: 100vh !important; max-height: 100vh !important; margin: 0 !important; padding: 0 !important;">
    <!-- Impersonation Active Banner -->
    @if(session()->has('impersonator_id'))
        <div class="impersonation-banner relative z-50 shrink-0">
            <span class="flex items-center gap-2 text-sm">
                <span class="material-symbols-outlined text-lg animate-pulse">security</span>
                Sesi Impersonasi Aktif: Anda sedang mengakses sebagai <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})
            </span>
            <form action="{{ route('super-admin.impersonate-stop') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-white text-amber-900 px-3 py-1 rounded-full text-xs font-bold shadow-md hover:bg-slate-100 transition">
                    Kembali ke Super Admin
                </button>
            </form>
        </div>
    @endif

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 hidden md:hidden transition-opacity duration-300 opacity-0"></div>

    <div class="flex flex-1 overflow-hidden relative" style="height: 100% !important; min-height: 100% !important;">
        <!-- Sidebar -->
        @include('super-admin.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="header-super relative z-40">
                <div class="topbar-row flex items-center justify-between px-6">
                    <div class="flex items-center gap-4 min-h-11">
                        <button class="text-white md:hidden focus:outline-none" id="sidebar-toggle">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <nav class="text-sm flex items-center leading-none text-slate-300">
                            <span class="mr-2">Super Admin</span>
                            <i class="fas fa-chevron-right text-xs mr-2"></i>
                            <span class="font-semibold text-white">@yield('page-title', 'Dashboard')</span>
                        </nav>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                            Control Center
                        </span>
                    </div>

                    <!-- Global Search Omnibar -->
                    <div class="flex-1 px-12">
                        <div class="relative w-full max-w-2xl mx-auto">
                            <form action="{{ route('super-admin.search') }}" method="GET" class="relative">
                                <input type="text" name="query" value="{{ request('query') }}" placeholder="Cari Mahasiswa, Dosen, Invoice, KRS..." 
                                    class="w-full h-10 rounded-full px-4 pl-10 text-sm focus:outline-none search-input" autocomplete="off" />
                                <span class="absolute left-3 top-2.5 text-slate-400">
                                    <i class="fas fa-search"></i>
                                </span>
                            </form>
                        </div>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:flex sm:flex-col sm:justify-center leading-tight">
                            <div class="text-sm font-semibold text-white">{{ auth()->user()->name }}</div>
                            <div class="text-[10px] text-amber-400 font-bold uppercase tracking-wider">Super Admin</div>
                        </div>
                        <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center font-bold text-slate-900 shadow-md">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="relative flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6">
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 shadow-sm" role="alert">
                            <div class="flex items-center">
                                <span class="material-symbols-outlined text-emerald-600 mr-3">check_circle</span>
                                <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
                                <button type="button" onclick="this.closest('[role=alert]').parentElement.remove();" class="ml-auto text-emerald-400 hover:text-emerald-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6">
                        <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 shadow-sm" role="alert">
                            <div class="flex items-center">
                                <span class="material-symbols-outlined text-rose-600 mr-3">error</span>
                                <p class="text-sm font-semibold text-rose-800">{{ session('error') }}</p>
                                <button type="button" onclick="this.closest('[role=alert]').parentElement.remove();" class="ml-auto text-rose-400 hover:text-rose-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-xl mb-6 shadow-sm" role="alert">
                        <div class="flex items-start">
                            <span class="material-symbols-outlined text-rose-600 mr-3">warning</span>
                            <div>
                                <h4 class="font-bold text-sm text-rose-900 mb-1">Terjadi kesalahan input:</h4>
                                <ul class="list-disc list-inside text-xs space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Sidebar toggle for mobile screen
        const sidebarToggleBtn = document.getElementById('sidebar-toggle');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const asideSidebar = document.querySelector('aside.sidebar');

        function toggleSidebar() {
            if (!asideSidebar) return;
            const isOpen = asideSidebar.classList.contains('mobile-open');
            if (isOpen) {
                asideSidebar.classList.remove('mobile-open');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.remove('opacity-100');
                    sidebarOverlay.classList.add('opacity-0');
                    setTimeout(() => {
                        sidebarOverlay.classList.add('hidden');
                    }, 300);
                }
            } else {
                asideSidebar.classList.add('mobile-open');
                asideSidebar.classList.remove('hidden');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.remove('hidden');
                    void sidebarOverlay.offsetWidth;
                    sidebarOverlay.classList.remove('opacity-0');
                    sidebarOverlay.classList.add('opacity-100');
                }
            }
        }

        sidebarToggleBtn?.addEventListener('click', toggleSidebar);
        sidebarOverlay?.addEventListener('click', toggleSidebar);

        // Live Search / Instant Filter for GET Forms
        document.addEventListener('DOMContentLoaded', () => {
            let debounceTimeout;

            const performAjaxSearch = (form) => {
                const resultsContainer = document.getElementById('search-results');
                if (!resultsContainer) return;

                const formData = new FormData(form);
                const params = new URLSearchParams(formData);
                const actionUrl = form.getAttribute('action') || window.location.pathname;
                const finalUrl = `${actionUrl}?${params.toString()}`;

                resultsContainer.style.opacity = '0.5';
                resultsContainer.style.pointerEvents = 'none';
                resultsContainer.style.transition = 'opacity 0.15s ease';

                fetch(finalUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newResults = doc.getElementById('search-results');

                    if (newResults) {
                        resultsContainer.innerHTML = newResults.innerHTML;
                    }

                    resultsContainer.style.opacity = '1';
                    resultsContainer.style.pointerEvents = 'auto';

                    window.history.replaceState({ path: finalUrl }, '', finalUrl);
                })
                .catch(() => {
                    resultsContainer.style.opacity = '1';
                    resultsContainer.style.pointerEvents = 'auto';
                });
            };

            // Event delegation for input fields
            document.addEventListener('input', (e) => {
                const input = e.target;
                if (!input.matches('input[type="text"], input[type="search"]')) return;

                const form = input.closest('form[method="GET"]');
                if (!form) return;

                // If it is the top navbar search, only auto-submit if on the search page itself
                if (input.classList.contains('search-input')) {
                    if (!window.location.pathname.includes('/super-admin/search')) {
                        return;
                    }
                }

                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(() => performAjaxSearch(form), 300);
            });

            // Event delegation for keydown (Enter key)
            document.addEventListener('keydown', (e) => {
                const input = e.target;
                if (e.key === 'Enter' && input.matches('input[type="text"], input[type="search"]')) {
                    const form = input.closest('form[method="GET"]');
                    if (!form) return;

                    if (input.classList.contains('search-input')) {
                        if (!window.location.pathname.includes('/super-admin/search')) {
                            return;
                        }
                    }

                    e.preventDefault();
                    clearTimeout(debounceTimeout);
                    performAjaxSearch(form);
                }
            });

            // Event delegation for select, date, checkbox controls
            document.addEventListener('change', (e) => {
                const control = e.target;
                if (!control.matches('select, input[type="date"], input[type="checkbox"]')) return;

                const form = control.closest('form[method="GET"]');
                if (!form) return;

                performAjaxSearch(form);
            });

            // Event delegation for pagination links
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a[href*="page="]');
                if (!link) return;

                const container = document.getElementById('search-results');
                if (!container || !container.contains(link)) return;

                e.preventDefault();
                const url = link.getAttribute('href');

                container.style.opacity = '0.5';
                container.style.pointerEvents = 'none';

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newResults = doc.getElementById('search-results');

                    if (newResults) {
                        container.innerHTML = newResults.innerHTML;
                    }

                    container.style.opacity = '1';
                    container.style.pointerEvents = 'auto';

                    window.history.pushState({ path: url }, '', url);
                    container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                })
                .catch(() => {
                    container.style.opacity = '1';
                    container.style.pointerEvents = 'auto';
                });
            });
        });
    </script>
    @stack('scripts')
    <x-ui.preloader />
</body>

</html>
