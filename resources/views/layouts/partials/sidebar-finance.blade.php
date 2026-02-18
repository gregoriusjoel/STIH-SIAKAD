<!-- Backdrop for Mobile -->
<div x-show="sidebarOpen"
     class="fixed inset-0 z-[45] bg-slate-900/40 backdrop-blur-sm md:hidden transition-opacity duration-300"
     @click="sidebarOpen = false"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"></div>

<aside
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-[#1f1616] flex flex-col md:sticky md:top-0 md:h-screen shadow-xl md:shadow-none transition-transform duration-300 transform md:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    {{-- Header --}}
    <div class="h-16 md:h-[72px] flex items-center gap-3 px-6 border-b border-white/10 bg-[#8B1538] dark:bg-[#3a0a1a]">
        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white shadow-sm border border-white/20 shrink-0">
             <img src="{{ asset('images/logo_stih_white.png') }}" alt="STIH" class="w-6 h-6 object-contain">
        </div>
        <div class="flex flex-col min-w-0">
            <h1 class="text-white text-sm font-bold truncate leading-tight drop-shadow-sm">
                Keuangan
            </h1>
            <p class="text-white/70 text-[10px] font-medium tracking-wider uppercase">
                Finance Panel
            </p>
        </div>
        <button @click="sidebarOpen = false" class="md:hidden ml-auto text-white hover:text-white/80 transition">
            <i class="fas fa-times text-lg"></i>
        </button>
    </div>

    {{-- Navigation --}}
    <div class="flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar py-6 px-4 space-y-1 border-r border-gray-100 dark:border-gray-800">
        
        @php
            $navItemClass = "group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 font-medium text-sm relative overflow-hidden";
            $activeClass = "bg-gradient-to-r from-[#8B1538] to-[#6D1029] text-white shadow-md shadow-red-900/20";
            $inactiveClass = "text-gray-600 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/10 hover:text-[#8B1538] dark:hover:text-red-400";
            
            $navItems = [
                ['route' => 'finance.invoices.index', 'active' => 'finance.invoices.*', 'icon' => 'fa-file-invoice-dollar', 'label' => 'Daftar Tagihan'],
                ['route' => 'finance.installment-requests.index', 'active' => 'finance.installment-requests.*', 'icon' => 'fa-hand-holding-usd', 'label' => 'Pengajuan Cicilan'],
                ['route' => 'finance.payment-proofs.index', 'active' => 'finance.payment-proofs.*', 'icon' => 'fa-check-double', 'label' => 'Konfirmasi Bukti'],
                ['route' => '#', 'active' => '#', 'icon' => 'fa-chart-line', 'label' => 'Laporan'],
            ];
        @endphp

        <div class="space-y-1">
            @foreach($navItems as $item)
                @php $isActive = $item['active'] !== '#' && Request::routeIs($item['active']); @endphp
                <a href="{{ $item['route'] === '#' ? '#' : route($item['route']) }}"
                   class="{{ $navItemClass }} {{ $isActive ? $activeClass : $inactiveClass }}">
                    <i class="fas {{ $item['icon'] }} w-5 text-center transition-transform group-hover:scale-110"></i>
                    <span class="tracking-wide">{{ $item['label'] }}</span>
                    @if($isActive)
                        <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-white/20 rounded-l-full"></div>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    {{-- Footer --}}
    <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-black/20 border-r border-gray-100 dark:border-gray-800">
        
        {{-- Profile Card --}}
        <a href="#" class="flex items-center gap-3 mb-3 px-3 py-2 rounded-xl hover:bg-white dark:hover:bg-white/5 transition border border-transparent hover:border-gray-200 dark:hover:border-gray-700 group">
            <div class="size-10 rounded-full bg-gradient-to-br from-[#8B1538] to-[#6D1029] text-white flex items-center justify-center font-bold shadow-sm shrink-0">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-bold text-gray-700 dark:text-gray-200 truncate group-hover:text-[#8B1538] dark:group-hover:text-red-400 transition">
                    {{ Auth::user()->name }}
                </p>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Online
                </p>
            </div>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="group w-full flex items-center gap-3 px-4 py-3 rounded-xl border border-red-100 dark:border-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-900/80 transition-all shadow-sm hover:shadow-red-600/20">
                <i class="fas fa-sign-out-alt w-5 text-center transition-transform group-hover:-translate-x-1"></i>
                <span class="font-semibold text-sm">Logout</span>
            </button>
        </form>
    </div>

</aside>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>