@extends('layouts.dosen')

@section('title', 'Kelas Saya')
@section('header_title', 'Kelas Saya')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Kelas Saya</h1>
            <p class="text-gray-500 mt-2 text-base">Kelola kelas aktif dan pantau progress perkuliahan semester ini.</p>
        </div>
        <div class="flex items-center gap-3">
             <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Cari kelas..." class="pl-10 pr-4 py-2 border-none rounded-lg bg-white shadow-sm focus:ring-2 focus:ring-[#8B1538] text-sm w-64">
            </div>
            <button class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium text-sm hover:bg-gray-50 flex items-center shadow-sm">
                <i class="fas fa-filter mr-2 text-gray-400"></i> Filter
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($classes as $class)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 relative hover:shadow-md transition-shadow duration-300">
                <div class="flex justify-between items-start mb-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-pink-50 text-[#8B1538]">
                        {{ $class['section'] }}
                    </span>
                    <button class="text-gray-300 hover:text-gray-500 transition-colors">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
                
                <h3 class="text-lg font-bold text-[#8B1538] mb-1 tracking-tight">{{ $class['name'] }}</h3>
                <p class="text-sm text-gray-500 mb-5 font-medium">{{ $class['code'] }} • {{ $class['sks'] }} SKS</p>

                <div class="space-y-3 mb-6">
                    <div class="flex items-center text-gray-500 text-sm font-medium">
                        <div class="w-6 flex justify-center mr-2"><i class="far fa-user text-gray-400"></i></div>
                        <span>{{ $class['students'] }} Mahasiswa</span>
                    </div>
                        <div class="flex items-center text-gray-500 text-sm font-medium">
                        <div class="w-6 flex justify-center mr-2"><i class="far fa-clock text-gray-400"></i></div>
                        <span>{{ $class['day'] }}, {{ explode(' - ', $class['time'])[0] }}</span>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="flex justify-between text-xs mb-2 font-bold">
                        <span class="text-gray-500">Total Mahasiswa</span>
                        <span class="text-[#8B1538]">{{ $class['students'] }} / 40</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-[#8B1538] h-1.5 rounded-full" style="width: {{ ($class['students'] / 40) * 100 }}%"></div>
                    </div>
                </div>

                <div class="flex gap-3">
                        <a href="{{ route('dosen.kelas.absensi', $class['id']) }}" onclick="event.preventDefault(); if(!openModal(this.href)) window.location.href = this.href;" class="flex-1 text-center py-2.5 rounded-lg border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50 transition-colors">
                        Absensi
                    </a>
                    <a href="{{ route('dosen.kelas.detail', $class['id']) }}" onclick="event.preventDefault(); if(!openModal(this.href)) window.location.href = this.href;" class="flex-1 text-center py-2.5 rounded-lg border border-[#8B1538] text-[#8B1538] text-sm font-bold hover:bg-[#FEF2F2] transition-colors">
                        Detail
                    </a>
                </div>
            </div>
        @endforeach
    </div>


    <!-- Responsive Modal Container -->
    <div id="absensiModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeModal()"></div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4" id="modalContent">
                    <!-- Content will be loaded here via AJAX -->
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
            // Check if desktop (width >= 768px for md breakpoint)
            if (window.innerWidth >= 768) {
                // Show modal
                const modal = document.getElementById('absensiModal');
                const content = document.getElementById('modalContent');
                
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden'); // Prevent background scrolling

                // Fetch content
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    content.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    content.innerHTML = '<p class="text-center text-red-600">Terjadi kesalahan memuat data.</p>';
                });

                return true; // Prevent default link behavior handled by caller returning false
            }
            return false; // Allow default link behavior (navigation) on mobile
        }

        function closeModal() {
            const modal = document.getElementById('absensiModal');
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            // Reset content to loader for next time
            document.getElementById('modalContent').innerHTML = `
                <div class="flex justify-center items-center py-10">
                    <i class="fas fa-circle-notch fa-spin text-3xl text-[#8B1538]"></i>
                </div>
            `;
        }

        // Close on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeModal();
            }
        });
    </script>
    @endpush
@endsection