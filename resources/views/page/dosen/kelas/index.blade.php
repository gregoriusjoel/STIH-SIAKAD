@extends('layouts.app')

@section('title', 'Kelas Saya')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
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
    <div class="flex flex-col gap-8 max-w-[1200px] mx-auto w-full flex-1">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-[#111218] tracking-tight">Daftar Kelas Ajar</h1>
            <p class="text-[#616889] mt-1 text-sm">Kelola materi dan aktivitas perkuliahan untuk semester ini.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-300"></i>
                <input type="text" placeholder="Cari kelas..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg bg-white focus:outline-none focus:border-[#8B1538] focus:ring-1 focus:ring-[#8B1538] text-sm w-64 text-gray-600 placeholder-gray-300">
            </div>
            <button class="bg-white border border-gray-300 text-gray-600 px-4 py-2 rounded-lg font-medium text-sm hover:bg-gray-50 flex items-center gap-2">
                <i class="fas fa-filter text-gray-500 text-xs"></i> Filter
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($classes as $class)
            <div class="bg-white rounded-xl border border-gray-200 p-6 relative hover:shadow-sm transition-shadow duration-300">
                <div class="flex justify-between items-start mb-4">
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-pink-50 text-[#8B1538] border border-pink-100">{{ $class['section'] }}</span>
                    <button class="text-gray-300 hover:text-gray-500 transition-colors"><i class="fas fa-ellipsis-v text-xs"></i></button>
                </div>

                <h3 class="text-lg font-bold text-[#111218] mb-1 tracking-tight">{{ $class['name'] }}</h3>
                <p class="text-sm text-[#616889] mb-5 font-normal">{{ $class['code'] }} • {{ $class['sks'] }} SKS</p>

                <div class="space-y-2 mb-6">
                    <div class="flex items-center text-[#616889] text-sm">
                        <div class="w-5 flex justify-center mr-2"><i class="far fa-user text-gray-400 text-xs"></i></div>
                        <span>{{ $class['students'] }} Mahasiswa</span>
                    </div>
                    <div class="flex items-center text-[#616889] text-sm">
                        <div class="w-5 flex justify-center mr-2"><i class="far fa-clock text-gray-400 text-xs"></i></div>
                        <span>{{ $class['day'] }}, {{ explode(' - ', $class['time'])[0] }}</span>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="flex justify-between text-xs mb-2">
                        <span class="text-[#616889] font-medium">Jumlah Mahasiswa</span>
                        <span class="text-[#8B1538] font-bold">{{ min($class['students'] ?? 0, 40) }} / 40</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        @php
                            $count = min($class['students'] ?? 0, 40);
                            $percent = $count * 100 / 40;
                        @endphp
                        <div class="h-2 rounded-full" style="background: linear-gradient(90deg,#8B1538,#F16A8B); width: {{ $percent }}%"></div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('dosen.kelas.absensi', $class['id']) }}" class="flex-1 text-center py-2 rounded-lg border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-50 transition-colors">Absensi</a>
                    <a href="{{ route('dosen.kelas.detail', $class['id']) }}" class="flex-1 text-center py-2 rounded-lg border border-[#8B1538] text-[#8B1538] text-sm font-medium hover:bg-[#FEF2F2] transition-colors">Detail</a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Responsive Modal Container -->
    <div id="absensiModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4" id="modalContent">
                    <div class="flex justify-center items-center py-10">
                        <i class="fas fa-circle-notch fa-spin text-3xl text-[#8B1538]"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openModal(url) {
                if (window.innerWidth >= 768) {
                    const modal = document.getElementById('absensiModal');
                    const content = document.getElementById('modalContent');
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(response => response.text())
                    .then(html => { content.innerHTML = html; })
                    .catch(() => { content.innerHTML = '<p class="text-center text-red-600">Terjadi kesalahan memuat data.</p>'; });
                    return true;
                }
                return false;
            }

            function closeModal() {
                const modal = document.getElementById('absensiModal');
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                document.getElementById('modalContent').innerHTML = '<div class="flex justify-center items-center py-10"><i class="fas fa-circle-notch fa-spin text-3xl text-[#8B1538]"></i></div>';
            }

            document.addEventListener('keydown', function(event) { if (event.key === 'Escape') closeModal(); });
        </script>
    @endpush
@endsection