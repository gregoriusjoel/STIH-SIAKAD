@extends('layouts.mahasiswa')

@section('title', 'Akademik - Nilai')
@section('page-title', 'Nilai Akademik')

@section('content')
    <div class="space-y-6">

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
            {{-- IPK Card --}}
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-4 md:p-6 shadow-sm border border-blue-100 dark:border-blue-900/30 group hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="size-10 md:size-12 bg-blue-50 dark:bg-blue-900/20 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-xl md:text-2xl fill-current">trophy</span>
                    </div>
                </div>
                <h3 class="text-2xl md:text-4xl font-black text-slate-900 dark:text-white mb-1">{{ number_format($ipk, 2) }}</h3>
                <p class="text-[10px] md:text-xs font-black text-slate-400 uppercase tracking-widest leading-none">IP Kumulatif</p>
            </div>

            {{-- Total SKS Card --}}
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-4 md:p-6 shadow-sm border border-green-100 dark:border-green-900/30 group hover:shadow-xl hover:shadow-green-500/5 transition-all duration-300">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="size-10 md:size-12 bg-green-50 dark:bg-green-900/20 rounded-2xl flex items-center justify-center text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-xl md:text-2xl fill-current">menu_book</span>
                    </div>
                </div>
                <h3 class="text-2xl md:text-4xl font-black text-slate-900 dark:text-white mb-1">{{ $totalSks }}</h3>
                <p class="text-[10px] md:text-xs font-black text-slate-400 uppercase tracking-widest leading-none">Total SKS</p>
            </div>

            {{-- IPS Card --}}
            @php
                $latestSemester = $ipsPerSemester ? collect($ipsPerSemester)->last() : null;
                $latestIps = $latestSemester['ips'] ?? 0;
            @endphp
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-4 md:p-6 shadow-sm border border-amber-100 dark:border-amber-900/30 group hover:shadow-xl hover:shadow-amber-500/5 transition-all duration-300">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="size-10 md:size-12 bg-amber-50 dark:bg-amber-900/20 rounded-2xl flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-xl md:text-2xl fill-current">trending_up</span>
                    </div>
                </div>
                <h3 class="text-2xl md:text-4xl font-black text-slate-900 dark:text-white mb-1">{{ number_format($latestIps, 2) }}</h3>
                <p class="text-[10px] md:text-xs font-black text-slate-400 uppercase tracking-widest leading-none">IP Semester</p>
            </div>

            {{-- Total MK Card --}}
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-4 md:p-6 shadow-sm border border-purple-100 dark:border-purple-900/30 group hover:shadow-xl hover:shadow-purple-500/5 transition-all duration-300">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <div class="size-10 md:size-12 bg-purple-50 dark:bg-purple-900/20 rounded-2xl flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-xl md:text-2xl fill-current">school</span>
                    </div>
                </div>
                <h3 class="text-2xl md:text-4xl font-black text-slate-900 dark:text-white mb-1">
                    {{ $nilaiPerSemester->sum(function ($items) {
                        return $items->count(); }) }}
                </h3>
                <p class="text-[10px] md:text-xs font-black text-slate-400 uppercase tracking-widest leading-none">MK Selesai</p>
            </div>
        </div>

        {{-- Akademik Tabs (Rangkuman Nilai / KHS) --}}
        <div class="bg-white dark:bg-slate-800 rounded-[32px] shadow-sm px-8 py-5 mt-8 border border-slate-100 dark:border-slate-700/50">
            <nav class="flex items-center gap-3" id="akademikTabs">
                <button data-target="rangkuman"
                    class="tab-btn px-8 py-3 text-[14px] rounded-2xl border-2 transition-all duration-300 tracking-wider uppercase font-black">
                    Rangkuman Nilai
                </button>
                <button data-target="khs"
                    class="tab-btn px-8 py-3 text-[14px] rounded-2xl border-2 transition-all duration-300 tracking-wider uppercase font-black">
                    KHS
                </button>
            </nav>
        </div>

        {{-- Content Panels --}}
        <div id="rangkuman">
            @include('page.mahasiswa.nilai._content')
        </div>

        <div id="khs" class="hidden">
            @include('page.mahasiswa.khs._content')
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const tabs = document.querySelectorAll('#akademikTabs .tab-btn');
            const panels = {
                rangkuman: document.getElementById('rangkuman'),
                khs: document.getElementById('khs')
            };

            function setActive(target) {
                // toggle buttons
                tabs.forEach(t => {
                    if (t.dataset.target === target) {
                        t.classList.remove('border-transparent', 'text-slate-400', 'bg-transparent');
                        t.classList.add('border-primary/20', 'bg-primary/5', 'text-primary');
                    } else {
                        t.classList.remove('border-primary/20', 'bg-primary/5', 'text-primary');
                        t.classList.add('border-transparent', 'text-slate-400', 'bg-transparent');
                    }
                });

                // toggle panels
                Object.keys(panels).forEach(k => {
                    if (k === target) panels[k].classList.remove('hidden'); else panels[k].classList.add('hidden');
                });
            }

            tabs.forEach(t => t.addEventListener('click', (e) => {
                e.preventDefault();
                setActive(t.dataset.target);
            }));

            // default
            setActive('rangkuman');
        })();
    </script>
@endpush