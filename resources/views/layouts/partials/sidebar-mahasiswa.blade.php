<aside
    class="fixed inset-y-0 left-0 z-50 w-64
    bg-bg-sidebar border border-border-color
    flex flex-col overflow-hidden

    /* floating card style */
    lg:sticky lg:top-6
    lg:m-6
    lg:h-[calc(100vh-3rem)]
    lg:rounded-[36px]
    shadow-xl

    transition-transform duration-300 transform
    lg:translate-x-0
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    <div class="px-6 py-7 flex flex-col gap-7 h-full overflow-y-auto overflow-x-hidden scrollbar-thin">

        <!-- Logo -->
        <div class="flex items-center gap-3 px-1">
            <div
                class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#8B1538] to-[#6D1029]
                flex items-center justify-center text-white shadow-lg shrink-0">
                <i class="fas fa-graduation-cap text-lg"></i>
            </div>

            <div class="flex flex-col min-w-0">
                <h1 class="text-text-primary text-sm font-bold truncate">
                    STIH Adhyaksa
                </h1>
                <p class="text-text-muted text-[10px] font-medium tracking-wide">
                    STUDENT PORTAL
                </p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex flex-col gap-2 grow"
            x-data="{
                openAkademik: {{ Request::routeIs('mahasiswa.nilai*','mahasiswa.kelas*','mahasiswa.jadwal*','mahasiswa.perpustakaan*','mahasiswa.prestasi*') ? 'true' : 'false' }},
                openPengajuan: {{ Request::routeIs('mahasiswa.pengajuan*') ? 'true' : 'false' }}
            }">

            @php
            $navItemClass = "flex items-center gap-4 px-5 py-3.5 rounded-xl
            transition-all duration-300 hover:translate-x-1 hover:shadow-sm group";

            $activeClass = "bg-primary/10 text-primary font-semibold";
            $inactiveClass = "text-text-secondary hover:bg-bg-hover hover:text-text-primary";
            @endphp

            <!-- Dashboard -->
            <a class="{{ $navItemClass }} {{ Request::routeIs('mahasiswa.dashboard') ? $activeClass : $inactiveClass }}"
                href="{{ route('mahasiswa.dashboard') }}">
                <i class="fas fa-home w-5 text-lg
                    {{ Request::routeIs('mahasiswa.dashboard')
                        ? 'text-primary'
                        : 'text-text-muted group-hover:text-primary' }}"></i>
                <span class="text-sm">Dashboard</span>
            </a>

            <!-- Profil -->
            <a class="{{ $navItemClass }} {{ Request::routeIs('mahasiswa.profil.manajemen') ? $activeClass : $inactiveClass }}"
                href="{{ route('mahasiswa.profil.manajemen') }}">
                <i class="fas fa-user-cog w-5 text-lg
                    {{ Request::routeIs('mahasiswa.profil.manajemen')
                        ? 'text-primary'
                        : 'text-text-muted group-hover:text-primary' }}"></i>
                <span class="text-sm">Manajemen Profil</span>
            </a>

            <!-- KRS -->
            <a class="{{ $navItemClass }} {{ Request::routeIs('mahasiswa.krs*') ? $activeClass : $inactiveClass }}"
                href="{{ route('mahasiswa.krs.index') }}">
                <i class="fas fa-file-alt w-5 text-lg
                    {{ Request::routeIs('mahasiswa.krs*')
                        ? 'text-primary'
                        : 'text-text-muted group-hover:text-primary' }}"></i>
                <span class="text-sm">KRS</span>
            </a>

            <!-- Akademik Dropdown -->
            <div>
                <button @click="openAkademik=!openAkademik"
                    class="w-full flex items-center justify-between
                    {{ $navItemClass }}
                    {{ Request::routeIs('mahasiswa.nilai*','mahasiswa.kelas*','mahasiswa.jadwal*','mahasiswa.perpustakaan*','mahasiswa.prestasi*')
                        ? 'text-primary'
                        : $inactiveClass }}">

                    <div class="flex items-center gap-4">
                        <i class="fas fa-graduation-cap w-5 text-lg"></i>
                        <span class="text-sm">Akademik</span>
                    </div>

                    <i class="fas fa-chevron-down text-[10px]
                        transition-transform duration-300"
                        :class="{'rotate-180':openAkademik}"></i>
                </button>

                <div x-show="openAkademik" x-collapse
                    class="pl-14 pr-2 py-1 space-y-2">

                    @foreach([
                        'mahasiswa.nilai.index'=>'Kartu Hasil Studi',
                        'mahasiswa.jadwal.index'=>'Jadwal Kelas',
                        'mahasiswa.kelas.index'=>'E-Learning',
                        'mahasiswa.perpustakaan.index'=>'Perpustakaan',
                        'mahasiswa.prestasi.index'=>'Prestasi Mahasiswa',
                    ] as $route=>$label)

                    <a href="{{ route($route) }}"
                        class="block py-2 text-[13px]
                        {{ Request::routeIs($route)
                            ? 'text-[#8B1538] font-bold'
                            : 'text-[#6B7280] hover:text-black' }}">
                        {{ $label }}
                    </a>

                    @endforeach
                </div>
            </div>

            <!-- Pengajuan Dropdown -->
            <div>
                <button @click="openPengajuan=!openPengajuan"
                    class="w-full flex items-center justify-between
                    {{ $navItemClass }}
                    {{ Request::routeIs('mahasiswa.pengajuan*'). 
                        ? 'text-primary'
                        : $inactiveClass }}">

                    <div class="flex items-center gap-4">
                        <i class="fas fa-file-signature w-5 text-lg"></i>
                        <span class="text-sm">Pengajuan</span>
                    </div>

                    <i class="fas fa-chevron-down text-[10px]
                        transition-transform duration-300"
                        :class="{'rotate-180':openPengajuan}"></i>
                </button>

                <div x-show="openPengajuan" x-collapse
                    class="pl-14 pr-2 py-1 space-y-2">

                    @foreach([
                        'mahasiswa.pengajuan.sidang.index'=>'Pengajuan Sidang',
                        'mahasiswa.pengajuan.surat.index'=>'Pengajuan Surat',
                        'mahasiswa.pengajuan.yudisium.index'=>'Pengajuan Yudisium',
                    ] as $route=>$label)

                    <a href="{{ route($route) }}"
                        class="block py-2 text-[13px]
                        {{ Request::routeIs($route)
                            ? 'text-[#8B1538] font-bold'
                            : 'text-[#6B7280] hover:text-black' }}">
                        {{ $label }}
                    </a>

                    @endforeach
                </div>
            </div>

            <!-- Pembayaran -->
            <a class="{{ $navItemClass }} {{ Request::routeIs('mahasiswa.pembayaran*') ? $activeClass : $inactiveClass }}"
                href="{{ route('mahasiswa.pembayaran.index') }}">
                <i class="fas fa-credit-card w-5 text-lg"></i>
                <span class="text-sm">Pembayaran</span>
            </a>

            <!-- Footer -->
            <div class="mt-auto pt-5 pb-4 border-t border-border-color/60">

                <a class="{{ $navItemClass }} {{ Request::routeIs('mahasiswa.profil.index') ? $activeClass : $inactiveClass }}"
                    href="{{ route('mahasiswa.profil.index') }}">
                    <i class="fas fa-user-circle w-5 text-lg"></i>
                    <span class="text-sm">Profil</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-4 px-5 py-3.5 rounded-xl
                        text-red-600 hover:bg-red-50 transition-all hover:translate-x-1">
                        <i class="fas fa-sign-out-alt w-5 text-lg"></i>
                        <span class="text-sm">Logout</span>
                    </button>
                </form>

            </div>

        </nav>
    </div>
</aside>
