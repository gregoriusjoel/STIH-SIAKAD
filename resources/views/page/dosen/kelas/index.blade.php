@extends('layouts.app')

@section('title', 'Kelas Saya')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .active-nav {
            background-color: var(--color-primary);
            color: white !important;
        }
    </style>
@endpush

@section('content')
    @section('navbar_breadcrumb')
        <nav class="flex items-center gap-3 text-sm text-white/60 font-bold tracking-tight">
            <a class="hover:text-white transition-all duration-300 flex items-center group" href="{{ route('dosen.dashboard') }}">
                <span class="material-symbols-outlined text-[19px] group-hover:scale-110 opacity-80">home</span>
            </a>
            <span class="material-symbols-outlined text-[10px] text-white/20 font-normal">play_arrow</span>
            <span class="text-white font-black text-[13px] uppercase tracking-wider">Kelas Saya</span>
        </nav>
    @endsection

    <div class="px-6 md:px-8 py-6 w-full flex flex-col gap-6">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-[#111218] dark:text-white tracking-tight">Daftar Kelas Ajar</h1>
                <p class="text-[#616889] dark:text-slate-400 mt-1 text-sm">Kelola materi dan aktivitas perkuliahan untuk
                    semester ini.</p>
            </div>
            <div class="flex items-center gap-3" x-data="filterPanel()" @click.away="open = false">
                <div class="relative">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">search</span>
                    <input type="text" id="searchKelas" placeholder="Cari kelas..."
                        class="pl-10 pr-4 py-2 border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-primary/50 text-sm w-64 text-gray-700 dark:text-gray-200 placeholder-gray-400"
                        @input="applyFilters()">
                </div>
                <div class="relative">
                    <button @click="open = !open"
                        class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-300 px-4 py-2 rounded-lg font-medium text-sm hover:bg-gray-50 dark:hover:bg-slate-700 flex items-center gap-2 transition-colors"
                        :class="{ 'border-primary ring-2 ring-primary/20 text-primary': activeFilterCount > 0 }">
                        <span class="material-symbols-outlined text-[18px]">filter_list</span>
                        Filter
                        <span x-show="activeFilterCount > 0" x-text="activeFilterCount"
                            class="bg-primary text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center ml-1"></span>
                    </button>

                    {{-- Filter Dropdown Panel --}}
                    <div x-show="open" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        style="width: 500px !important; max-width: 90vw;"
                        class="absolute right-0 mt-2 bg-white dark:bg-[#1a1d2e] rounded-xl shadow-xl z-50 p-6 border border-gray-100 dark:border-slate-700">

                        <div class="flex items-center justify-between mb-5">
                            <h4 class="text-base font-bold text-gray-800 dark:text-white">Filter Kelas</h4>
                            <button @click="resetFilters()" class="text-xs text-primary hover:underline font-medium"
                                x-show="activeFilterCount > 0">Reset</button>
                        </div>

                        {{-- Filter by Hari --}}
                        <div class="mb-5">
                            <label class="text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-2.5 block">HARI</label>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="day in availableDays" :key="day">
                                    <button @click="toggleDay(day)"
                                        class="px-4 py-2 bg-white dark:bg-slate-800 border rounded-lg text-sm font-medium transition-all duration-200"
                                        :class="selectedDays.includes(day)
                                            ? 'border-primary text-primary bg-primary/5 ring-1 ring-primary/20'
                                            : 'border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-300 hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700'"
                                        x-text="day"></button>
                                </template>
                            </div>
                        </div>

                        {{-- Filter by Kelas/Section --}}
                        <div class="mb-5">
                            <label class="text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-2.5 block">KELAS</label>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="sec in availableSections" :key="sec">
                                    <button @click="toggleSection(sec)"
                                        class="px-4 py-2 bg-white dark:bg-slate-800 border rounded-lg text-sm font-medium transition-all duration-200"
                                        :class="selectedSections.includes(sec)
                                            ? 'border-primary text-primary bg-primary/5 ring-1 ring-primary/20'
                                            : 'border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-300 hover:border-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700'"
                                        x-text="sec"></button>
                                </template>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100 dark:border-slate-700 mt-2">
                            <button @click="open = false"
                                style="background-color: #A91D3C !important; color: white !important;"
                                class="w-full py-2.5 rounded-lg text-sm font-bold shadow-lg shadow-red-900/10 hover:opacity-90 transition-all">
                                Terapkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Active Filter Badges --}}
        <div id="activeFilterBadges" class="flex flex-wrap gap-2 hidden">
        </div>

        <!-- Class Grid -->
        <div id="classGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($classes as $class)
                @php
                    $searchableText = strtolower($class['name'] . ' ' . $class['code'] . ' ' . $class['section'] . ' ' . $class['day'] . ' ' . ($class['time'] ?? ''));
                @endphp
                <div class="kelas-card bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-200 dark:border-slate-800 p-6 flex flex-col gap-4 shadow-sm hover:shadow-md transition-all duration-300 group"
                    data-search="{{ $searchableText }}"
                    data-day="{{ $class['day'] }}"
                    data-section="{{ $class['section'] }}">
                    <div class="flex justify-between items-start">
                        <span
                            class="px-3 py-1 rounded-full text-[10px] font-bold bg-pink-50 text-[#8B1538] border border-pink-100 uppercase tracking-wider">{{ $class['section'] }}</span>
                        <button class="text-gray-300 hover:text-gray-500 transition-colors">
                            <span class="material-symbols-outlined text-[20px]">more_vert</span>
                        </button>
                    </div>

                    <div>
                        <h3
                            class="text-xl font-bold text-[#111218] dark:text-white mb-1 tracking-tight group-hover:text-primary transition-colors">
                            {{ $class['name'] }}
                        </h3>
                        <p class="text-sm text-[#616889] dark:text-slate-400 font-medium">{{ $class['code'] }} •
                            {{ $class['sks'] }} SKS
                        </p>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center text-[#616889] dark:text-slate-400 text-sm gap-3">
                            <div class="size-8 rounded-full bg-gray-50 dark:bg-slate-800 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[16px]">group</span>
                            </div>
                            <span>{{ $class['students'] }} Mahasiswa</span>
                        </div>
                        <div class="flex items-center text-[#616889] dark:text-slate-400 text-sm gap-3">
                            <div class="size-8 rounded-full bg-gray-50 dark:bg-slate-800 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[16px]">schedule</span>
                            </div>
                            @php
                                $time = $class['time'] ?? '-';
                                $parts = explode(' - ', $time);
                                $start = $parts[0] ?? '-';
                                $end = $parts[1] ?? '-';
                            @endphp
                            <span>{{ $class['day'] }}, {{ $start }} - {{ $end }}</span>
                        </div>
                    </div>

                    <div class="mt-2 pt-4 border-t border-gray-100 dark:border-slate-800">
                        <div class="flex justify-between text-xs mb-2">
                            <span class="text-[#616889] dark:text-slate-400 font-medium">Kapasitas Kelas</span>
                            <span class="text-primary font-bold">{{ min($class['students'] ?? 0, 40) }} / 40</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                            @php
                                $count = min($class['students'] ?? 0, 40);
                                $percent = $count * 100 / 40;
                            @endphp
                            <div class="h-full rounded-full bg-gradient-to-r from-primary to-pink-400"
                                style="width: {{ $percent }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 mt-2">
                        <a href="{{ route('dosen.kelas.detail', $class['id']) }}"
                            class="flex items-center justify-center py-2.5 rounded-xl bg-primary text-white text-sm font-bold hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all">
                            Detail Kelas
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Responsive Modal Container -->
    <div id="absensiModal" class="fixed inset-0 z-[60] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true"
                onclick="closeAbsensiModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="relative z-10 inline-block align-bottom bg-white dark:bg-[#1a1d2e] rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-100 dark:border-slate-700">
                <div class="bg-white dark:bg-[#1a1d2e] px-4 pt-5 pb-4 sm:p-6 sm:pb-4 min-h-[300px]" id="modalContent">
                    <div class="flex flex-col justify-center items-center py-20 h-full">
                        <span class="material-symbols-outlined text-4xl text-primary animate-spin">progress_activity</span>
                        <p class="mt-4 text-gray-500 font-medium">Memuat data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function filterPanel() {
                return {
                    open: false,
                    selectedDays: [],
                    selectedSections: [],
                    availableDays: [],
                    availableSections: [],
                    get activeFilterCount() {
                        return this.selectedDays.length + this.selectedSections.length;
                    },
                    init() {
                        // Extract unique days and sections from existing cards
                        const cards = document.querySelectorAll('.kelas-card');
                        const days = new Set();
                        const sections = new Set();
                        const dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                        cards.forEach(card => {
                            const day = card.dataset.day;
                            const section = card.dataset.section;
                            if (day && day !== '-') days.add(day);
                            if (section && section !== '-') sections.add(section);
                        });
                        this.availableDays = dayOrder.filter(d => days.has(d));
                        this.availableSections = [...sections].sort();
                    },
                    toggleDay(day) {
                        const idx = this.selectedDays.indexOf(day);
                        if (idx > -1) this.selectedDays.splice(idx, 1);
                        else this.selectedDays.push(day);
                        this.applyFilters();
                    },
                    toggleSection(sec) {
                        const idx = this.selectedSections.indexOf(sec);
                        if (idx > -1) this.selectedSections.splice(idx, 1);
                        else this.selectedSections.push(sec);
                        this.applyFilters();
                    },
                    resetFilters() {
                        this.selectedDays = [];
                        this.selectedSections = [];
                        this.applyFilters();
                    },
                    applyFilters() {
                        const query = (document.getElementById('searchKelas')?.value || '').toLowerCase().trim();
                        const cards = document.querySelectorAll('.kelas-card');
                        const noResultsMsg = document.getElementById('noResultsMsg');
                        const badgesContainer = document.getElementById('activeFilterBadges');
                        let visibleCount = 0;

                        cards.forEach(card => {
                            const searchData = card.dataset.search || '';
                            const day = card.dataset.day || '';
                            const section = card.dataset.section || '';

                            const matchesSearch = query === '' || searchData.includes(query);
                            const matchesDay = this.selectedDays.length === 0 || this.selectedDays.includes(day);
                            const matchesSection = this.selectedSections.length === 0 || this.selectedSections.includes(section);

                            if (matchesSearch && matchesDay && matchesSection) {
                                card.classList.remove('hidden');
                                visibleCount++;
                            } else {
                                card.classList.add('hidden');
                            }
                        });

                        if (noResultsMsg) {
                            if (visibleCount === 0 && (query !== '' || this.activeFilterCount > 0)) {
                                noResultsMsg.classList.remove('hidden');
                            } else {
                                noResultsMsg.classList.add('hidden');
                            }
                        }

                        // Update active filter badges
                        if (badgesContainer) {
                            if (this.activeFilterCount > 0) {
                                badgesContainer.classList.remove('hidden');
                                let html = '';
                                this.selectedDays.forEach(d => {
                                    html += `<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/20">
                                        <span class="material-symbols-outlined text-[14px]">calendar_today</span>${d}
                                        <button onclick="document.querySelector('[x-data]').__x.$data.toggleDay('${d}')" class="ml-1 hover:text-red-500">&times;</button>
                                    </span>`;
                                });
                                this.selectedSections.forEach(s => {
                                    html += `<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-pink-50 text-[#8B1538] border border-pink-100">
                                        <span class="material-symbols-outlined text-[14px]">school</span>${s}
                                        <button onclick="document.querySelector('[x-data]').__x.$data.toggleSection('${s}')" class="ml-1 hover:text-red-500">&times;</button>
                                    </span>`;
                                });
                                badgesContainer.innerHTML = html;
                            } else {
                                badgesContainer.classList.add('hidden');
                                badgesContainer.innerHTML = '';
                            }
                        }
                    }
                };
            }

            function openModal(url) {
                if (window.innerWidth >= 768) {
                    const modal = document.getElementById('absensiModal');
                    const content = document.getElementById('modalContent');
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');

                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(response => response.text())
                        .then(html => { content.innerHTML = html; })
                        .catch(() => { content.innerHTML = '<div class="flex flex-col items-center justify-center py-10 text-red-500"><span class="material-symbols-outlined text-4xl mb-2">error</span><p>Gagal memuat data.</p></div>'; });
                    return true;
                }
                return false; // Allow default link behavior on mobile
            }

            function closeAbsensiModal() {
                const modal = document.getElementById('absensiModal');
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                setTimeout(() => {
                    document.getElementById('modalContent').innerHTML = '<div class="flex flex-col justify-center items-center py-20 h-full"><span class="material-symbols-outlined text-4xl text-primary animate-spin">progress_activity</span><p class="mt-4 text-gray-500 font-medium">Memuat data...</p></div>';
                }, 300);
            }

            document.addEventListener('keydown', function (event) { if (event.key === 'Escape') closeAbsensiModal(); });

            // No results message
            (function() {
                const classGrid = document.getElementById('classGrid');
                const noResultsMsg = document.createElement('div');
                noResultsMsg.id = 'noResultsMsg';
                noResultsMsg.className = 'col-span-full text-center py-12 hidden';
                noResultsMsg.innerHTML = `
                    <span class="material-symbols-outlined text-5xl text-gray-300 dark:text-slate-600 mb-3">search_off</span>
                    <p class="text-gray-500 dark:text-slate-400 font-medium">Tidak ada kelas yang ditemukan</p>
                    <p class="text-gray-400 dark:text-slate-500 text-sm mt-1">Coba kata kunci atau filter lain</p>
                `;
                classGrid.appendChild(noResultsMsg);
            })();
        </script>
    @endpush
@endsection