<!-- Super Admin Sidebar — Central Control Center -->
<aside class="sidebar w-64 flex-shrink-0 hidden md:flex flex-col h-full z-40">
    <style>
        /* Fixed Header Area */
        .sidebar .title-bar {
            padding: 0 1.25rem;
            background: linear-gradient(135deg, #3d050c 0%, #1a0205 100%);
            height: 4.25rem;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(245, 158, 11, 0.25);
        }

        /* Scrollable Navigation Area */
        .sidebar-scroll {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .sidebar-scroll::-webkit-scrollbar {
            display: none;
        }

        /* Section Header Label */
        .sidebar-section {
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 1.25rem 1.25rem 0.5rem 1.25rem;
            color: rgba(251, 191, 36, 0.45);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .sidebar-section::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(251, 191, 36, 0.15);
        }



        @media (max-width: 768px) {
            .sidebar {
                display: flex !important;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 50;
                height: 100vh;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
        }
    </style>

    <!-- Logo / Title Bar -->
    <div class="title-bar flex items-center gap-3 shrink-0">
        <div class="w-9 h-9 rounded-lg overflow-hidden bg-amber-500/10 border border-amber-500/30 p-1 flex items-center justify-center">
            <img src="{{ asset('images/logo_stih_white.png') }}" alt="STIH" class="object-contain">
        </div>
        <div class="flex flex-col">
            <h1 class="font-bold text-white text-sm tracking-wide">SIAKAD STIH</h1>
            <p class="text-[10px] text-amber-400 font-semibold uppercase tracking-widest">Control Center</p>
        </div>
    </div>

    <!-- Navigation List -->
    <div class="flex-1 overflow-y-auto flex flex-col pb-4 sidebar-scroll">
        <nav class="px-3 flex-1">

            {{-- ═══════════════════════════════════════ --}}
            {{-- GROUP 1: DASHBOARD                      --}}
            {{-- ═══════════════════════════════════════ --}}
            <div class="sidebar-section">Dashboard</div>

            <a href="{{ route('super-admin.search') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-xl mb-1 text-sm font-medium {{ request()->routeIs('super-admin.search') ? 'active' : '' }}">
                <span class="material-symbols-outlined mr-3 text-lg">travel_explore</span>
                <span>Pencarian Global</span>
            </a>

            <a href="{{ route('super-admin.student-360-search') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-xl mb-1 text-sm font-medium {{ request()->routeIs('super-admin.student-360*') ? 'active' : '' }}">
                <span class="material-symbols-outlined mr-3 text-lg">manage_search</span>
                <span>Student 360°</span>
            </a>

            {{-- ═══════════════════════════════════════ --}}
            {{-- GROUP 2: OVERRIDE CENTER                --}}
            {{-- ═══════════════════════════════════════ --}}
            <div class="sidebar-section">Override Center</div>

            <a href="{{ route('super-admin.override.academic-center') }}"
               class="sidebar-link override-item flex items-center px-3 py-2.5 rounded-xl mb-1 text-sm font-medium {{ request()->routeIs('super-admin.override.academic-center') ? 'active' : '' }}">
                <span class="material-symbols-outlined mr-3 text-lg">school</span>
                <span>Academic Override</span>
            </a>

            <a href="{{ route('super-admin.override.financial-center') }}"
               class="sidebar-link override-item flex items-center px-3 py-2.5 rounded-xl mb-1 text-sm font-medium {{ request()->routeIs('super-admin.override.financial-center') ? 'active' : '' }}">
                <span class="material-symbols-outlined mr-3 text-lg">payments</span>
                <span>Financial Override</span>
            </a>

            <a href="{{ route('super-admin.override.internship-center') }}"
               class="sidebar-link override-item flex items-center px-3 py-2.5 rounded-xl mb-1 text-sm font-medium {{ request()->routeIs('super-admin.override.internship-center') ? 'active' : '' }}">
                <span class="material-symbols-outlined mr-3 text-lg">work</span>
                <span>Internship Override</span>
            </a>

            <a href="{{ route('super-admin.override.thesis-center') }}"
               class="sidebar-link override-item flex items-center px-3 py-2.5 rounded-xl mb-1 text-sm font-medium {{ request()->routeIs('super-admin.override.thesis-center') ? 'active' : '' }}">
                <span class="material-symbols-outlined mr-3 text-lg">menu_book</span>
                <span>Thesis Override</span>
            </a>

            <a href="{{ route('super-admin.override.graduation-center') }}"
               class="sidebar-link override-item flex items-center px-3 py-2.5 rounded-xl mb-1 text-sm font-medium {{ request()->routeIs('super-admin.override.graduation-center') ? 'active' : '' }}">
                <span class="material-symbols-outlined mr-3 text-lg">workspace_premium</span>
                <span>Graduation Override</span>
            </a>

            {{-- ═══════════════════════════════════════ --}}
            {{-- GROUP 3: SYSTEM & ACCESS                --}}
            {{-- ═══════════════════════════════════════ --}}
            <div class="sidebar-section">System & Access</div>

            <a href="{{ route('super-admin.user-management') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-xl mb-1 text-sm font-medium {{ request()->routeIs('super-admin.user-management') ? 'active' : '' }}">
                <span class="material-symbols-outlined mr-3 text-lg">manage_accounts</span>
                <span>Manajemen Pengguna</span>
            </a>

            <a href="{{ route('super-admin.role-management') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-xl mb-1 text-sm font-medium {{ request()->routeIs('super-admin.role-management') ? 'active' : '' }}">
                <span class="material-symbols-outlined mr-3 text-lg">admin_panel_settings</span>
                <span>Role Management</span>
            </a>

            <a href="{{ route('super-admin.permission-management') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-xl mb-1 text-sm font-medium {{ request()->routeIs('super-admin.permission-management') ? 'active' : '' }}">
                <span class="material-symbols-outlined mr-3 text-lg">key</span>
                <span>Permission Management</span>
            </a>

            <a href="{{ route('super-admin.impersonation-center') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-xl mb-1 text-sm font-medium {{ request()->routeIs('super-admin.impersonation-center') ? 'active' : '' }}">
                <span class="material-symbols-outlined mr-3 text-lg">switch_account</span>
                <span>Impersonation Center</span>
                @if(session()->has('impersonator_id'))
                    <span class="ml-auto w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
                @endif
            </a>

            {{-- ═══════════════════════════════════════ --}}
            {{-- GROUP 4: MONITORING                     --}}
            {{-- ═══════════════════════════════════════ --}}
            <div class="sidebar-section">Monitoring</div>

            <a href="{{ route('super-admin.audit-logs') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-xl mb-1 text-sm font-medium {{ request()->routeIs('super-admin.audit-logs') ? 'active' : '' }}">
                <span class="material-symbols-outlined mr-3 text-lg">receipt_long</span>
                <span>Audit Trail Logs</span>
            </a>

            <a href="{{ route('super-admin.activity-monitor') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-xl mb-1 text-sm font-medium {{ request()->routeIs('super-admin.activity-monitor') ? 'active' : '' }}">
                <span class="material-symbols-outlined mr-3 text-lg">monitor_heart</span>
                <span>Activity Monitor</span>
            </a>

            <a href="{{ route('super-admin.system-health') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-xl mb-1 text-sm font-medium {{ request()->routeIs('super-admin.system-health') ? 'active' : '' }}">
                <span class="material-symbols-outlined mr-3 text-lg">health_and_safety</span>
                <span>System Health</span>
            </a>

            {{-- ═══════════════════════════════════════ --}}
            {{-- GROUP 5: KONFIGURASI                    --}}
            {{-- ═══════════════════════════════════════ --}}
            <div class="sidebar-section">Konfigurasi</div>

            <a href="{{ route('super-admin.system-config') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-xl mb-1 text-sm font-medium {{ request()->routeIs('super-admin.system-config') ? 'active' : '' }}">
                <span class="material-symbols-outlined mr-3 text-lg">settings</span>
                <span>Pengaturan Sistem</span>
            </a>

            <a href="{{ route('super-admin.semester-config') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-xl mb-1 text-sm font-medium {{ request()->routeIs('super-admin.semester-config') ? 'active' : '' }}">
                <span class="material-symbols-outlined mr-3 text-lg">calendar_month</span>
                <span>Semester & T.A.</span>
            </a>

            <a href="{{ route('super-admin.backup') }}"
               class="sidebar-link flex items-center px-3 py-2.5 rounded-xl mb-1 text-sm font-medium {{ request()->routeIs('super-admin.backup') ? 'active' : '' }}">
                <span class="material-symbols-outlined mr-3 text-lg">backup</span>
                <span>Backup & Recovery</span>
            </a>

            {{-- ═══════════════════════════════════════ --}}
            {{-- GROUP 6: SESSION                        --}}
            {{-- ═══════════════════════════════════════ --}}
            <div class="sidebar-section">Sesi</div>

            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit"
                    class="w-full sidebar-link flex items-center px-3 py-2.5 rounded-xl mb-1 text-sm font-medium hover:bg-rose-950/20 hover:text-rose-400 text-left transition-all">
                    <span class="material-symbols-outlined mr-3 text-lg text-rose-500">logout</span>
                    <span class="text-rose-400">Keluar Sistem</span>
                </button>
            </form>
        </nav>

        <!-- Footer -->
        <div class="px-4 pt-4 border-t border-slate-800 text-center shrink-0">
            <span class="text-[10px] text-slate-600 font-semibold uppercase tracking-wider">
                STIH SIAKAD v2.0 · Super Admin
            </span>
        </div>
    </div>
</aside>
