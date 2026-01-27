<!-- Admin Sidebar (collapsible sections) -->
<aside class="sidebar w-64 flex-shrink-0 hidden md:block overflow-y-auto">
    <style>
        .sidebar { background-color: #ffffff; }
        .sidebar .title { padding: 0 1rem; background-color: #7a1621; color: #fff; height: 4.1rem; display: flex; align-items: center; }

        /* Logo size to match navbar avatar */
        .sidebar .title .logo { width: 40px; height: 40px; padding: 0.25rem; }
        .sidebar .title h1 { font-size: 1.125rem; line-height: 1; margin: 0; }
        .sidebar .title p { font-size: 0.875rem; margin: 0; }

        /* Increase link text and icon sizing */
        .sidebar .sidebar-link { color: #374151; font-size: 1rem; }
        .sidebar .sidebar-link i { width: 1.25rem; font-size: 1.05rem; margin-right: 0.6rem; }
        .sidebar .sidebar-link:not(.active):hover { background-color: rgba(139,21,56,0.06); color: #7a1621; }
        .sidebar-link.active { background-color: #7a1621; color: #ffffff !important; }
        .sidebar .section-toggle { color: #6b1620; font-size: 0.95rem; }
        .rotate-180 { transform: rotate(180deg); }
    </style>

    <div class="title flex items-center gap-4">
        <div class="w-10 h-10 rounded-md overflow-hidden bg-white p-1 shadow-sm flex-shrink-0 logo">
            <img src="{{ asset('images/logo_stih_white.png') }}" alt="STIH" class="w-full h-full object-contain">
        </div>
        <div class="flex flex-col">
            <h1 class="font-bold text-white">SIAKAD STIH</h1>
            <p class="text-white">Admin Panel</p>
        </div>
    </div>

    <nav class="px-4 pb-4 mt-4">
    <a href="@if(Route::has('admin.dashboard')){{ route('admin.dashboard') }}@else{{ url('/admin') }}@endif" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-home w-5 mr-3"></i>
        <span class="text-sm font-medium">Dashboard</span>
    </a>
    <!-- Manajemen Data -->
    <div class="mt-3">
        <button type="button" data-toggle="sidebar-section" data-section="manajemen-data" class="w-full flex items-center justify-between px-4 py-2 text-xs font-semibold section-toggle uppercase tracking-wide opacity-80">
            <span>Manajemen Data</span>
            <svg class="w-4 h-4 transition-transform" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
        </button>
        <ul class="mt-1 space-y-1 pb-2 sidebar-section hidden">
            <li>
                @if(Route::has('admin.mahasiswa.index'))
                    <a href="{{ route('admin.mahasiswa.index') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-800/40 {{ request()->routeIs('admin.mahasiswa.*') ? 'active' : '' }}">
                        <i class="fas fa-user-graduate w-5 mr-3"></i>
                        <span class="text-sm font-medium">Data Mahasiswa</span>
                    </a>
                @else
                    <a href="{{ url('/admin/mahasiswa') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-800/40">
                        <i class="fas fa-user-graduate w-5 mr-3"></i>
                        <span class="text-sm font-medium">Data Mahasiswa</span>
                    </a>
                @endif
            </li>
            <li>
                @if(Route::has('admin.dosen.index'))
                    <a href="{{ route('admin.dosen.index') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-800/40 {{ request()->routeIs('admin.dosen.*') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard-teacher w-5 mr-3"></i>
                        <span class="text-sm font-medium">Data Dosen</span>
                    </a>
                @else
                    <a href="{{ url('/admin/dosen') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-800/40">
                        <i class="fas fa-chalkboard-teacher w-5 mr-3"></i>
                        <span class="text-sm font-medium">Data Dosen</span>
                    </a>
                @endif
            </li>
            <li>
                @if(Route::has('admin.dosen-pa.index'))
                    <a href="{{ route('admin.dosen-pa.index') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-800/40 {{ request()->routeIs('admin.dosen-pa.*') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard-teacher w-5 mr-3"></i>
                        <span class="text-sm font-medium">Data Dosen PA</span>
                    </a>
                @else
                    <a href="{{ url('/admin/dosen-pa') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-800/40">
                        <i class="fas fa-chalkboard-teacher w-5 mr-3"></i>
                        <span class="text-sm font-medium">Data Dosen PA</span>
                    </a>
                @endif
            </li>
            <li>
                @if(Route::has('admin.parents.index'))
                    <a href="{{ route('admin.parents.index') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-800/40 {{ request()->routeIs('admin.parents.*') ? 'active' : '' }}">
                        <i class="fas fa-user-friends w-5 mr-3"></i>
                        <span class="text-sm font-medium">Data Parent</span>
                    </a>
                @else
                    <a href="{{ url('/admin/parent') }}" class="sidebar-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-red-800/40">
                        <i class="fas fa-user-friends w-5 mr-3"></i>
                        <span class="text-sm font-medium">Data Parent</span>
                    </a>
                @endif
            </li>
        </ul>
    </div>

    <!-- Akademik -->
    <div class="mt-2">
        <button type="button" data-toggle="sidebar-section" data-section="akademik" class="w-full flex items-center justify-between px-4 py-2 text-xs font-semibold section-toggle uppercase tracking-wide opacity-80">
            <span>Akademik</span>
            <svg class="w-4 h-4 transition-transform" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
        </button>
        <ul class="mt-1 space-y-1 pb-2 sidebar-section hidden">
            <li>
                    @if(Route::has('admin.mata-kuliah.index'))
                    <a href="{{ route('admin.mata-kuliah.index') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg hover:bg-red-800/40 {{ request()->routeIs('admin.mata-kuliah.*') ? 'active' : '' }}">
                        <i class="fas fa-book w-5 mr-3"></i>
                        <span class="text-sm font-medium">Mata Kuliah</span>
                    </a>
                @else
                    <a href="{{ url('/admin/mata-kuliah') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg hover:bg-red-800/40">
                        <i class="fas fa-book w-5 mr-3"></i>
                        <span class="text-sm font-medium">Mata Kuliah</span>
                    </a>
                @endif
            </li>
            <li>
                    @if(Route::has('admin.kelas-mata-kuliah.index'))
                    <a href="{{ route('admin.kelas-mata-kuliah.index') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg hover:bg-red-800/40 {{ request()->routeIs('admin.kelas-mata-kuliah.*') ? 'active' : '' }}">
                        <i class="fas fa-building w-5 mr-3"></i>
                        <span class="text-sm font-medium">Kelas Mata Kuliah</span>
                    </a>
                @else
                    <a href="{{ url('/admin/kelas') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg hover:bg-red-800/40">
                        <i class="fas fa-building w-5 mr-3"></i>
                        <span class="text-sm font-medium">Kelas Mata Kuliah</span>
                    </a>
                @endif
            </li>
            <li>
                @if(Route::has('admin.jadwal.index'))
                    <a href="{{ route('admin.jadwal.index') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg hover:bg-red-800/40 {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt w-5 mr-3"></i>
                        <span class="text-sm font-medium">Jadwal Perkuliahan</span>
                    </a>
                @else
                    <a href="{{ url('/admin/jadwal') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg hover:bg-red-800/40">
                        <i class="fas fa-calendar-alt w-5 mr-3"></i>
                        <span class="text-sm font-medium">Jadwal Perkuliahan</span>
                    </a>
                @endif
            </li>
            <li>
                @if(Route::has('admin.krs.index'))
                    <a href="{{ route('admin.krs.index') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg hover:bg-red-800/40 {{ request()->routeIs('admin.krs.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt w-5 mr-3"></i>
                        <span class="text-sm font-medium">Manajemen KRS</span>
                    </a>
                @else
                    <a href="{{ url('/admin/krs') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg hover:bg-red-800/40">
                        <i class="fas fa-file-alt w-5 mr-3"></i>
                        <span class="text-sm font-medium">Manajemen KRS</span>
                    </a>
                @endif
            </li>
        </ul>
    </div>

    <!-- Sistem -->
    <div class="mt-4">
        <button type="button" data-toggle="sidebar-section" data-section="sistem" class="w-full flex items-center justify-between px-4 py-2 text-xs font-semibold section-toggle uppercase tracking-wide opacity-80">
            <span>Sistem</span>
            <svg class="w-4 h-4 transition-transform" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
        </button>
        <ul class="mt-1 space-y-1 pb-2 sidebar-section hidden">
            <li>
                @if(Route::has('admin.users.index'))
                    <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg hover:bg-red-800/40 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog w-5 mr-3"></i>
                        <span class="text-sm font-medium">Manajemen User</span>
                    </a>
                @else
                    <a href="{{ url('/admin/users') }}" class="sidebar-link flex items-center px-4 py-3 text-white rounded-lg hover:bg-red-800/40">
                        <i class="fas fa-users-cog w-5 mr-3"></i>
                        <span class="text-sm font-medium">Manajemen User</span>
                    </a>
                @endif
            </li>
        </ul>
    </div>
    </nav>

    <!-- Logout (separate from 'Sistem') -->
    <div class="px-4 pb-4 mt-auto">
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-3 text-gray-700 rounded-lg sidebar-link hover:bg-red-800/40">
                <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                <span class="text-sm font-medium">Logout</span>
            </button>
        </form>
    </div>

    <script>
        (function(){
            document.addEventListener('DOMContentLoaded', function(){
                var navEntries = performance.getEntriesByType && performance.getEntriesByType('navigation');
                var navType = (navEntries && navEntries[0] && navEntries[0].type) || (performance && performance.navigation && performance.navigation.type === 1 ? 'reload' : 'navigate');

                // If this page load is a reload, clear stored sidebar state so sections start closed
                var isReload = (navType === 'reload');

                var buttons = document.querySelectorAll('[data-toggle="sidebar-section"]');
                buttons.forEach(function(btn){
                    var key = btn.getAttribute('data-section') || btn.textContent.trim().toLowerCase().replace(/\s+/g,'-');
                    var storageKey = 'sidebar_section_' + key;
                    var next = btn.nextElementSibling;
                    var svg = btn.querySelector('svg');

                    // If reload, remove stored state for this section
                    if(isReload) {
                        try { sessionStorage.removeItem(storageKey); } catch(e){}
                    }

                    // Initialize: if stored as 'open', show; otherwise keep hidden
                    try {
                        var state = sessionStorage.getItem(storageKey);
                        if(state === 'open') {
                            if(next) next.classList.remove('hidden');
                            if(svg) svg.classList.add('rotate-180');
                        } else {
                            if(next && !next.classList.contains('hidden')) next.classList.add('hidden');
                            if(svg) svg.classList.remove('rotate-180');
                        }
                    } catch(e) {
                        // sessionStorage might be unavailable; fallback to default collapsed
                        if(next && !next.classList.contains('hidden')) next.classList.add('hidden');
                        if(svg) svg.classList.remove('rotate-180');
                    }

                    btn.addEventListener('click', function(){
                        if(!next) return;
                        next.classList.toggle('hidden');
                        var nowOpen = !next.classList.contains('hidden');
                        if(svg) svg.classList.toggle('rotate-180');
                        try { sessionStorage.setItem(storageKey, nowOpen ? 'open' : 'closed'); } catch(e){}
                    });
                });
            });
        })();
    </script>
</aside>
