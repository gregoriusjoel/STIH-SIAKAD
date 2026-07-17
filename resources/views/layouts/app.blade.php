<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'STIH')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo_stih_white.png') }}">

    {{-- Google Fonts (Material Symbols + Nunito) — masih CDN, tidak tersedia di npm dengan font-loading yang setara. --}}
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0"
        crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet"
        crossorigin="anonymous">

    {{-- FontAwesome / Flatpickr / ApexCharts / SweetAlert2 / jQuery / AlpineJS di-bundle lokal via Vite. --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .modern-navbar {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-hover) 50%, var(--color-primary-dark) 100%);
        }

        [x-cloak] {
            display: none !important;
        }

        /* Global Modal Styles */
        .modal-backdrop {
            z-index: 99999 !important;
        }

        .modal-content {
            z-index: 100000 !important;
        }

        /* Prevent body scroll when modal is open */
        body.modal-open {
            overflow: hidden !important;
        }
    </style>

    @stack('styles')



</head>

<body id="page-top" class="bg-gray-100 font-nunito">

    <!-- Page Wrapper -->
    <div id="page-wrapper" class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Content Wrapper -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">

            <!-- Topbar -->
            @include('layouts.partials.navbar')

            <!-- Main Content -->
            <main class="w-full flex-grow bg-gray-100 p-6">
                @yield('content')
            </main>

            <!-- Footer -->
            @include('layouts.partials.footer')

        </div>
    </div>

    <!-- Global Modal Container -->
    <div id="globalModalContainer"></div>

    <!-- Scroll to Top Button -->
    <a class="fixed bottom-4 right-4 w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center shadow-lg hover:bg-primary-hover transition-all duration-200 hidden"
        href="#page-top" id="scrollToTop">
        <i class="fas fa-angle-up"></i>
    </a>

    @stack('scripts')

    <script>
        // Scroll to top functionality
        document.addEventListener('DOMContentLoaded', () => {
            const scrollToTop = document.getElementById('scrollToTop');
            if (scrollToTop) {
                window.addEventListener('scroll', () => {
                    if (window.scrollY > 200) {
                        scrollToTop.classList.remove('hidden');
                    } else {
                        scrollToTop.classList.add('hidden');
                    }
                });

                scrollToTop.addEventListener('click', (e) => {
                    e.preventDefault();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }
        });
    </script>

    <x-ui.preloader />
</body>

</html>