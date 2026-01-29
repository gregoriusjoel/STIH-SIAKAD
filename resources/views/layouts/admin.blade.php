<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIAKAD STIH') - Admin Panel</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/nim/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .sidebar {
            background-color: #7a1621; /* maroon */
        }
        .sidebar-link:hover {
            background-color: rgba(255,255,255,0.06);
        }
        .sidebar-link.active {
            background-color: rgba(255,255,255,0.08);
            border-right: 4px solid rgba(255,255,255,0.12);
        }
        .btn-maroon {
            background-color: #800020;
            color: white;
        }
        .btn-maroon:hover { background-color: #5a0015; }
        .text-maroon { color: #800020; }
        .bg-maroon { background-color: #800020; }
        .border-maroon { border-color: #800020; }
        /* Header on maroon background adjustments */
        .header-maroon { background-color: #7a1621; color: #fff; }
        .header-maroon .breadcrumb { color: rgba(255,255,255,0.9); }
        .header-maroon .breadcrumb .muted { color: rgba(255,255,255,0.75); }
        .header-maroon .search-input { background: #fff; color: #111827; }
        .header-maroon .user-name { color: #ffffff; }
        /* Header tweaks */
        .top-badge { background: linear-gradient(90deg,#ff7b7b,#b22222); }
       /* Modern search suggestions UI */
        #search-suggestions {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            color: #0f172a;
            border: 1px solid rgba(0,0,0,0.06);
            box-shadow:
                0 10px 25px -5px rgba(0,0,0,0.08),
                0 4px 10px -2px rgba(0,0,0,0.05);
            border-radius: 14px;
            overflow: hidden;
            animation: fadeInScale 0.15s ease-out;
        }

        /* Entry item */
        #search-suggestions a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            color: #0f172a;
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.15s ease;
        }

        /* Hover effect */
        #search-suggestions a:hover {
            background: rgba(128, 0, 32, 0.08);
            transform: translateX(2px);
        }

        /* Active (keyboard selection) */
        #search-suggestions a[aria-selected="true"],
        #search-suggestions a.bg-gray-100 {
            background: rgba(128, 0, 32, 0.12);
            box-shadow: inset 3px 0 0 #800020;
        }

        /* Section headers (Fitur, Users, dll) */
        #search-suggestions .border-b,
        #search-suggestions .border-t {
            background: linear-gradient(to right, #f8fafc, #f1f5f9);
            color: #334155;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            padding: 8px 14px;
        }

        /* Badge text (Buka / Lihat) */
        #search-suggestions .text-gray-500 {
            color: #64748b !important;
            font-size: 0.7rem;
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 999px;
        }

        /* Empty result */
        #search-suggestions .no-results {
            padding: 12px 14px;
            color: #94a3b8;
            font-style: italic;
        }

        /* Smooth animation */
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
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('admin.sidebar-admin')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="header-maroon shadow-sm z-10">
                <div class="flex items-center justify-between px-6 py-3">
                    <div class="flex items-center gap-4">
                        <button class="text-white md:hidden focus:outline-none" id="sidebar-toggle">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <nav class="text-sm breadcrumb">
                            <span class="mr-2 muted">Home</span>
                            <i class="fas fa-chevron-right text-xs mr-2"></i>
                            <span class="font-semibold">@yield('page-title', 'Dashboard')</span>
                        </nav>
                        <div class="ml-4">
                            @php
                                $activeSemester = null;
                                try {
                                    $activeSemester = \App\Models\Semester::where('status', 'aktif')->latest()->first();
                                } catch (\Throwable $e) {
                                    $activeSemester = null;
                                }
                            @endphp
                            @if($activeSemester)
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium text-white top-badge">{{ $activeSemester->nama_semester }} {{ $activeSemester->tahun_ajaran }}</span>
                            @else
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium text-white top-badge">Semester Belum Ditetapkan</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex-1 px-6">
                        <div class="max-w-2xl mx-auto">
                            <form action="{{ route('admin.search') }}" method="GET" class="relative" id="header-search-form" data-search-url="{{ route('admin.search') }}">
                                <div class="relative">
                                    <input id="header-search-input" name="q" value="{{ request('q') }}" type="text" placeholder="Cari data..." aria-label="Cari data" autocomplete="off" class="w-full border rounded-full px-4 py-2 pl-10 focus:ring-2 focus:ring-maroon focus:border-transparent search-input" />
                                    <button type="submit" class="absolute left-0 top-0 h-full pl-3 pr-2 text-gray-600">
                                        <i class="fas fa-search"></i>
                                    </button>

                                    <!-- Suggestions dropdown -->
                                    <div id="search-suggestions" class="hidden absolute left-0 right-0 mt-2 bg-white rounded shadow-lg z-50 text-sm overflow-hidden"></div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex items-center space-x-3">
                            <div class="text-right mr-2 hidden sm:block">
                                <div class="text-sm font-medium user-name">{{ auth()->user()->name }}</div>
                                
                            </div>
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center font-bold text-maroon shadow-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Auto-hide flash messages after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('[role="alert"]').forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
        // Sidebar toggle for small screens
        document.getElementById('sidebar-toggle')?.addEventListener('click', function() {
            const aside = document.querySelector('aside.sidebar');
            if (!aside) return;
            aside.classList.toggle('hidden');
        });

        // Header search typeahead (AJAX)
        (function() {
            const input = document.getElementById('header-search-input');
            const suggestions = document.getElementById('search-suggestions');
            const form = document.getElementById('header-search-form');
            if (!input || !suggestions || !form) return;

            const url = form.getAttribute('data-search-url');

            let debounceTimer = null;
            let items = [];
            let selected = -1;

            function clearSuggestions() {
                suggestions.innerHTML = '';
                suggestions.classList.add('hidden');
                selected = -1;
            }

            function render(data) {
                suggestions.innerHTML = '';
                const frag = document.createDocumentFragment();

                if (data.features && data.features.length) {
                    const header = document.createElement('div');
                    header.className = 'px-3 py-2 border-b bg-gray-50 font-medium';
                    header.textContent = 'Fitur';
                    frag.appendChild(header);

                    data.features.forEach(function(f, idx) {
                        const el = document.createElement('a');
                        el.href = f.url || '#';
                        el.className = 'block px-3 py-2 hover:bg-gray-100 flex items-center justify-between';
                        el.innerHTML = '<span>' + f.label + '</span>' + (f.url ? '<span class="text-xs text-gray-500">Buka</span>' : '');
                        frag.appendChild(el);
                    });
                }

                if (data.results) {
                    for (const [table, list] of Object.entries(data.results)) {
                        if (!list || !list.length) continue;
                        const theader = document.createElement('div');
                        theader.className = 'px-3 py-2 border-t bg-gray-50 font-medium';
                        theader.textContent = table.replace(/_/g, ' ');
                        frag.appendChild(theader);

                        list.forEach(function(it) {
                            const el = document.createElement('a');
                            el.href = it.url || (url + '?q=' + encodeURIComponent(it.display));
                            el.className = 'block px-3 py-2 hover:bg-gray-100 flex items-center justify-between';
                            el.innerHTML = '<span class="truncate">' + it.display + '</span>' + (it.url ? '<span class="text-xs text-gray-500">Lihat</span>' : '');
                            frag.appendChild(el);
                        });
                    }
                }

                if (!frag.childNodes.length) {
                    const no = document.createElement('div');
                    no.className = 'px-3 py-2 text-gray-600';
                    no.textContent = 'Tidak ada hasil';
                    frag.appendChild(no);
                }

                suggestions.appendChild(frag);
                suggestions.classList.remove('hidden');
            }

            input.addEventListener('input', function(e) {
                const v = input.value.trim();
                if (debounceTimer) clearTimeout(debounceTimer);
                if (!v) {
                    clearSuggestions();
                    return;
                }
                debounceTimer = setTimeout(function() {
                    fetch(url + '?q=' + encodeURIComponent(v), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
                        .then(function(res) { return res.json(); })
                        .then(function(json) {
                            render(json);
                        }).catch(function() {
                            clearSuggestions();
                        });
                }, 250);
            });

            // Hide when clicking outside
            document.addEventListener('click', function(e) {
                if (!form.contains(e.target)) {
                    clearSuggestions();
                }
            });

            // keyboard navigation
            input.addEventListener('keydown', function(e) {
                const links = Array.from(suggestions.querySelectorAll('a'));
                if (!links.length) return;
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    selected = Math.min(selected + 1, links.length - 1);
                    links.forEach((a,i) => a.classList.toggle('bg-gray-100', i===selected));
                    links[selected].scrollIntoView({ block: 'nearest' });
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    selected = Math.max(selected - 1, 0);
                    links.forEach((a,i) => a.classList.toggle('bg-gray-100', i===selected));
                    links[selected].scrollIntoView({ block: 'nearest' });
                } else if (e.key === 'Enter') {
                    if (selected >= 0 && links[selected]) {
                        e.preventDefault();
                        window.location = links[selected].href;
                    }
                }
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>