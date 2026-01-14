<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Portal Dosen - SIAKAD Uni')</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-link.active {
            background-color: #fef2f2;
            /* red-50 */
            color: #b2202c;
            /* primary */
            border-right: 3px solid #b2202c;
        }

        .sidebar-link:hover {
            background-color: #f9fafb;
            color: #b2202c;
        }
    </style>
</head>

<body class="bg-[#F9FAFB] text-gray-800 antialiased h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-[#F3F4F6] flex flex-col hidden md:flex font-inter">
        <div class="h-20 flex items-center px-6 border-b border-[#F3F4F6]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[#9F1239] flex items-center justify-center text-white shadow-sm">
                    <i class="fas fa-graduation-cap text-lg"></i>
                </div>
                <div>
                    <div class="font-bold text-[#111827] text-base leading-tight">SIAKAD Uni</div>
                    <div class="text-xs text-gray-500 font-medium">Lecturer Portal</div>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto py-6">
            <nav class="space-y-1 px-4">
                <a href="{{ route('dosen.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-semibold transition-all duration-200 {{ Request::routeIs('dosen.dashboard') ? 'bg-[#9F1239] text-white shadow-md' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-th-large w-5 h-5 mr-3 {{ Request::routeIs('dosen.dashboard') ? 'text-white' : 'text-gray-400' }}"></i>
                    Dashboard
                </a>

                <a href="{{ route('dosen.kelas') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 {{ Request::routeIs('dosen.kelas') ? 'bg-[#9F1239] text-white shadow-md' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="far fa-bookmark w-5 h-5 mr-3 {{ Request::routeIs('dosen.kelas') ? 'text-white' : 'text-gray-400' }}"></i>
                    Kelas
                </a>

                <a href="#" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-[#6B7280] hover:bg-[#F9FAFB] hover:text-[#111827] transition-all duration-200">
                    <i class="fas fa-calendar-alt w-5 h-5 mr-3 text-[#9CA3AF]"></i>
                    Jadwal
                </a>

                <a href="{{ route('dosen.krs') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ Request::routeIs('dosen.krs') ? 'bg-[#8B1538] text-white shadow-[0_4px_6px_-1px_rgba(139,21,56,0.2)]' : 'text-[#6B7280] hover:bg-[#F9FAFB] hover:text-[#111827]' }}">
                    <i class="fas fa-clipboard-check w-5 h-5 mr-3 {{ Request::routeIs('dosen.krs') ? 'text-white' : 'text-[#9CA3AF]' }}"></i>
                    KRS <span class="ml-auto bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-xs font-bold">{{ $krs_approval ?? 0 }}</span>
                </a>

                <a href="{{ route('dosen.mahasiswa') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 {{ Request::routeIs('dosen.mahasiswa') ? 'bg-[#8B1538] text-white shadow-[0_4px_6px_-1px_rgba(139,21,56,0.2)]' : 'text-[#6B7280] hover:bg-[#F9FAFB] hover:text-[#111827]' }}">
                    <i class="far fa-user w-5 h-5 mr-3 {{ Request::routeIs('dosen.mahasiswa') ? 'text-white' : 'text-gray-400' }}"></i>
                    Mahasiswa
                </a>

                <a href="{{ route('dosen.input-nilai') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 {{ Request::routeIs('dosen.input-nilai') ? 'bg-[#9F1239] text-white shadow-md' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="far fa-star w-5 h-5 mr-3 {{ Request::routeIs('dosen.input-nilai') ? 'text-white' : 'text-gray-400' }}"></i>
                    Input Nilai
                </a>
            </nav>
        </div>

        <div class="p-6 border-t border-[#F3F4F6]">
            <nav class="space-y-1">
                <a href="#" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium text-gray-500 hover:text-[#9F1239] hover:bg-red-50 transition-colors">
                    <i class="far fa-user-circle w-5 h-5 mr-3 text-gray-400"></i>
                    Profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 rounded-lg text-sm font-medium text-[#DC2626] hover:bg-[#FEF2F2] transition-colors cursor-pointer">
                        <i class="fas fa-arrow-right-from-bracket w-5 h-5 mr-3"></i>
                        Logout
                    </button>
                </form>
            </nav>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Header -->
        <header class="bg-white border-b border-[#F3F4F6] h-16 flex items-center justify-between px-6 z-10">
            <div class="flex items-center">
                <!-- Mobile Menu Button -->
                <button
                    class="md:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#8B1538] mr-2">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fas fa-bars"></i>
                </button>

                <nav class="hidden sm:flex text-sm font-medium text-gray-500" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2">
                        <li><a href="#" class="hover:text-gray-700">Home</a></li>
                        <li><span class="text-gray-300">/</span></li>
                        <li><span class="text-gray-900" aria-current="page">@yield('header_title', 'Dashboard')</span>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="flex items-center gap-4">
                <!-- Semester Badge -->
                <span
                    class="hidden md:inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#FEF2F2] text-[#8B1538]">
                    Semester Ganjil 2023/2024
                </span>

                <!-- Search -->
                <div class="relative hidden lg:block">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-[#9CA3AF] text-xs"></i>
                    </div>
                    <input type="text" name="search" id="search"
                        class="block w-64 pl-9 pr-3 py-1.5 border-none rounded-lg leading-5 bg-[#F9FAFB] placeholder-[#9CA3AF] focus:outline-none focus:ring-1 focus:ring-[#8B1538] sm:text-sm transition duration-150 ease-in-out"
                        placeholder="Cari data...">
                </div>

                <div class="border-l border-gray-200 h-6 mx-2"></div>

                <!-- Notifications -->
                <button
                    class="relative p-1 rounded-full text-[#9CA3AF] hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8B1538]">
                    <span class="sr-only">View notifications</span>
                    <i class="fas fa-bell"></i>
                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-[#E11D48] ring-2 ring-white"></span>
                </button>

                <!-- Profile Dropdown -->
                <div class="flex items-center ml-3">
                    <div class="flex flex-col text-right mr-3 hidden md:flex">
                        <span class="text-sm font-medium text-[#111827]">Dr. Handoko</span>
                        <span class="text-xs text-[#6B7280]">NIDN: 0423018201</span>
                    </div>
                    <img class="h-8 w-8 rounded-full border border-gray-200"
                        src="https://ui-avatars.com/api/?name=Dr+Handoko&background=8B1538&color=fff" alt="User Avatar">
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#F9FAFB] p-6">
            @yield('content')

            <div class="mt-8 text-center text-xs text-gray-400 pb-4">
                &copy; 2024 SIAKAD University Management System. All rights reserved.
            </div>
        </main>
    </div>

    @stack('scripts')
</body>

</html>