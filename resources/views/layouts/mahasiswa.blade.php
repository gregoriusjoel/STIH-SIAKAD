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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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

        /* Thin scrollbar */
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

    <!-- Theme Initialization -->
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

<body class="bg-bg-body font-inter text-text-primary transition-colors duration-200 overflow-x-hidden"
      x-data="{ sidebarOpen: false }">

    <!-- Page Wrapper -->
    <div class="flex min-h-screen bg-bg-body">

        <!-- Mobile Backdrop -->
        <div x-show="sidebarOpen"
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/80 z-40 md:hidden">
        </div>

        <!-- Sidebar -->
        @include('layouts.partials.sidebar-mahasiswa')

        <!-- Content Wrapper -->
        <!-- ✅ overflow-hidden DIHAPUS supaya scroll natural -->
        <div class="relative flex flex-col flex-1">

            <!-- Topbar -->
            @include('layouts.partials.navbar-mahasiswa')

            <!-- Theme Toggle Script -->
            <script>
                (function() {
                    var toggle = document.getElementById('theme-toggle-input');
                    var html = document.documentElement;

                    if(toggle){
                        toggle.checked = html.classList.contains('dark');

                        toggle.addEventListener('change', function(e) {
                            if(e.target.checked){
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
            <!-- ✅ min-h-screen supaya halaman tinggi natural -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-bg-body transition-colors duration-200">

                @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 mb-6">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-6">
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
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('scripts')

    <x-ui.preloader />

</body>

</html>
