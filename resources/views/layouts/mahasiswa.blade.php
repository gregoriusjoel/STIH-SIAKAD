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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

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



</head>

<body class="bg-bg-body font-inter text-text-primary transition-colors duration-200 overflow-x-hidden"
      x-data="{ sidebarOpen: false }"
      @open-sidebar.window="sidebarOpen = true">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let touchStartX = 0;
            let touchStartY = 0;
            
            console.log('Swipe gesture initialized');
            
            document.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].clientX;
                touchStartY = e.changedTouches[0].clientY;
                console.log('Touch start:', touchStartX, touchStartY);
            }, false);
            
            document.addEventListener('touchend', function(e) {
                const touchEndX = e.changedTouches[0].clientX;
                const touchEndY = e.changedTouches[0].clientY;
                
                console.log('Touch end:', touchEndX, touchEndY);
                console.log('Window width:', window.innerWidth);
                
                // Only on mobile devices
                if (window.innerWidth >= 768) {
                    console.log('Not mobile, skipping');
                    return;
                }
                
                const deltaX = touchEndX - touchStartX;
                const deltaY = touchEndY - touchStartY;
                
                console.log('Delta X:', deltaX, 'Delta Y:', deltaY);
                console.log('Touch start X:', touchStartX);
                
                // Check if swipe starts from left edge (0-80px for easier triggering)
                if (touchStartX > 80) {
                    console.log('Not from left edge');
                    return;
                }
                
                // Check if movement is primarily horizontal
                if (Math.abs(deltaX) < Math.abs(deltaY)) {
                    console.log('Movement is vertical, not horizontal');
                    return;
                }
                
                // Check if swipe is to the right and meets minimum threshold
                if (deltaX > 50) {
                    console.log('Valid swipe detected! Opening sidebar...');
                    window.dispatchEvent(new CustomEvent('open-sidebar'));
                } else {
                    console.log('Swipe distance too short:', deltaX);
                }
            }, false);
        });
    </script>

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
        <div class="relative flex flex-col flex-1">

            <!-- Topbar -->
            @include('layouts.partials.navbar-mahasiswa')



            <!-- Main Content -->
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
