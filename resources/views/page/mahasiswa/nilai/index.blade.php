@extends('layouts.mahasiswa')

@section('title', 'Akademik - Nilai')
@section('page-title', 'Nilai Akademik')

@section('content')
    <div class="space-y-6">

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- IPK Card --}}
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-trophy text-2xl"></i>
                    </div>
                    <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">Overall</span>
                </div>
                <h3 class="text-3xl font-bold mb-1">{{ number_format($ipk, 2) }}</h3>
                <p class="text-blue-100 text-sm">Indeks Prestasi Kumulatif</p>
            </div>

            {{-- Total SKS Card --}}
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book text-2xl"></i>
                    </div>
                    <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">Credits</span>
                </div>
                <h3 class="text-3xl font-bold mb-1">{{ $totalSks }}</h3>
                <p class="text-green-100 text-sm">Total SKS Diambil</p>
            </div>

            {{-- Total MK Card --}}
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-2xl"></i>
                    </div>
                    <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">Courses</span>
                </div>
                <h3 class="text-3xl font-bold mb-1">
                    {{ $nilaiPerSemester->sum(function ($items) {
        return $items->count(); }) }}
                </h3>
                <p class="text-purple-100 text-sm">Mata Kuliah Selesai</p>
            </div>
        </div>

        {{-- Akademik Tabs (Rangkuman Nilai / KHS) --}}
        <div class="bg-bg-card rounded-xl shadow-sm px-4 py-3 mt-6">
            <nav class="flex items-center space-x-2" id="akademikTabs">
                <button data-target="rangkuman"
                    class="tab-btn px-4 py-2 text-sm rounded-t-lg border-b-2 -mb-px border-maroon text-maroon font-semibold">Rangkuman
                    Nilai</button>
                <button data-target="khs"
                    class="tab-btn px-4 py-2 text-sm rounded-t-lg border-b-2 -mb-px border-transparent text-text-secondary hover:text-text-primary transition-colors">KHS</button>
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
                        t.classList.remove('border-transparent', 'text-text-secondary');
                        t.classList.add('border-maroon', 'text-maroon', 'font-semibold');
                    } else {
                        t.classList.remove('border-maroon', 'text-maroon', 'font-semibold');
                        t.classList.add('border-transparent', 'text-text-secondary');
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