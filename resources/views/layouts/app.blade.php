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

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- ApexCharts CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts/dist/apexcharts.css">

    <!-- Vite Assets -->
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

<body id="page-top" class="bg-gray-100 dark:bg-gray-900 font-nunito">

    <!-- Page Wrapper -->
    <div id="page-wrapper" class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Content Wrapper -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">

            <!-- Topbar -->
            @include('layouts.partials.navbar')

            <!-- Main Content -->
            <main class="w-full flex-grow p-6 bg-gray-100 dark:bg-gray-900">
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

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- ApexCharts JS -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('scripts')

    <script>
        // Scroll to top functionality
        $(document).ready(function () {
            $(window).scroll(function () {
                if ($(this).scrollTop() > 200) {
                    $('#scrollToTop').removeClass('hidden');
                } else {
                    $('#scrollToTop').addClass('hidden');
                }
            });

            $('#scrollToTop').click(function (e) {
                e.preventDefault();
                $('html, body').animate({ scrollTop: 0 }, 500);
            });
        });
    </script>

    <x-ui.preloader />
</body>

</html>