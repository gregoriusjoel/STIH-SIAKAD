@extends('layouts.app')

@section('title', 'Detail Kelas')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
</style>
@endpush

@section('content')
<div class="flex flex-col gap-6 max-w-[1400px] mx-auto w-full flex-1" x-data="detailKelas()">
    
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('dosen.kelas') }}" class="text-gray-400 hover:text-[#8B1538] transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-black text-[#111218] tracking-tight">Detail Kelas</h1>
            </div>
            <p class="text-[#616889] text-sm">Daftar mahasiswa perwalian dan mahasiswa ajar.</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="flex items-center gap-2 px-4 py-2 border border-[#8B1538] text-[#8B1538] rounded-lg font-medium text-sm hover:bg-[#FEF2F2] transition-colors">
                <i class="fas fa-download"></i>
                Export
            </button>
        </div>
    </div>

    {{-- Class Info Card --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-start gap-4">
                    <div class="size-14 rounded-xl bg-gradient-to-br from-[#8B1538] to-[#701230] flex items-center justify-center text-white">
                        <i class="fas fa-book text-xl"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h2 class="text-xl font-bold text-[#111218]">{{ $class_info['name'] }}</h2>
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-pink-50 text-[#8B1538] border border-pink-100">
                                {{ $class_info['section'] }}
                            </span>
                        </div>
                        <p class="text-sm text-[#616889]">{{ $class_info['code'] }} • {{ $class_info['sks'] }} SKS • Semester {{ $class_info['semester'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-6 text-sm text-[#616889]">
                <div class="flex items-center gap-2">
                    <div class="size-8 rounded-lg bg-gray-100 flex items-center justify-center">
                        <i class="far fa-calendar text-gray-400"></i>
                    </div>
                    <span>{{ $class_info['day'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="size-8 rounded-lg bg-gray-100 flex items-center justify-center">
                        <i class="far fa-clock text-gray-400"></i>
                    </div>
                    <span>{{ $class_info['time'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="size-8 rounded-lg bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                    </div>
                    <span>{{ $class_info['room'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="size-8 rounded-lg bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-users text-gray-400"></i>
                    </div>
                    <span>{{ $class_info['students_count'] }} Mahasiswa</span>
                </div>
            </div>
        </div>

        {{-- Total Mahasiswa Bar --}}
        @php
            $totalMhs = count($students);
            $aktif = collect($students)->where('status', 'Aktif')->count();
            $cuti = collect($students)->where('status', 'Cuti')->count();
            $nonAktif = collect($students)->where('status', 'Non-Aktif')->count();
            $aktifPercent = $totalMhs > 0 ? ($aktif / $totalMhs) * 100 : 0;
            $cutiPercent = $totalMhs > 0 ? ($cuti / $totalMhs) * 100 : 0;
            $nonAktifPercent = $totalMhs > 0 ? ($nonAktif / $totalMhs) * 100 : 0;
        @endphp
        <div class="mt-6 pt-4 border-t border-gray-100">
            <div class="flex justify-between text-sm mb-2">
                <span class="text-[#111218] font-medium">Total Mahasiswa</span>
                <span class="text-[#8B1538] font-bold">{{ $totalMhs }} Mahasiswa</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-3 flex overflow-hidden">
                <div class="bg-emerald-500 h-3" style="width: {{ $aktifPercent }}%" title="Aktif: {{ $aktif }}"></div>
                <div class="bg-amber-500 h-3" style="width: {{ $cutiPercent }}%" title="Cuti: {{ $cuti }}"></div>
                <div class="bg-rose-500 h-3" style="width: {{ $nonAktifPercent }}%" title="Non-Aktif: {{ $nonAktif }}"></div>
            </div>
            <div class="flex items-center gap-4 mt-2 text-xs text-[#616889]">
                <div class="flex items-center gap-1.5">
                    <span class="size-2.5 rounded-full bg-emerald-500"></span>
                    <span>Aktif ({{ $aktif }})</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="size-2.5 rounded-full bg-amber-500"></span>
                    <span>Cuti ({{ $cuti }})</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="size-2.5 rounded-full bg-rose-500"></span>
                    <span>Non-Aktif ({{ $nonAktif }})</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Search and Filter Section --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            {{-- Search --}}
            <div class="relative flex-1 max-w-md">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-300"></i>
                <input 
                    type="text" 
                    x-model="searchQuery"
                    placeholder="Cari nama atau NIM..." 
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:outline-none focus:border-[#8B1538] focus:ring-1 focus:ring-[#8B1538] text-sm text-gray-600 placeholder-gray-300"
                >
            </div>

            {{-- Filters --}}
            <div class="flex items-center gap-3">
                {{-- Prodi Filter --}}
                <div class="relative">
                    <select 
                        x-model="filterProdi"
                        class="appearance-none pl-4 pr-10 py-2.5 border border-gray-200 rounded-lg bg-white focus:outline-none focus:border-[#8B1538] focus:ring-1 focus:ring-[#8B1538] text-sm text-gray-600 cursor-pointer"
                    >
                        <option value="">Semua Prodi</option>
                        <option value="Informatika">Informatika</option>
                        <option value="Sistem Informasi">Sistem Informasi</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                </div>

                {{-- Status Filter --}}
                <div class="relative">
                    <select 
                        x-model="filterStatus"
                        class="appearance-none pl-4 pr-10 py-2.5 border border-gray-200 rounded-lg bg-white focus:outline-none focus:border-[#8B1538] focus:ring-1 focus:ring-[#8B1538] text-sm text-gray-600 cursor-pointer"
                    >
                        <option value="">Semua Status</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Cuti">Cuti</option>
                        <option value="Non-Aktif">Non-Aktif</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Students Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-[#616889] border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold uppercase text-xs tracking-wider">Mahasiswa</th>
                        <th class="px-6 py-4 font-semibold uppercase text-xs tracking-wider">Prodi</th>
                        <th class="px-6 py-4 font-semibold uppercase text-xs tracking-wider text-center">Semester</th>
                        <th class="px-6 py-4 font-semibold uppercase text-xs tracking-wider text-center">IPK</th>
                        <th class="px-6 py-4 font-semibold uppercase text-xs tracking-wider">Status</th>
                        <th class="px-6 py-4 font-semibold uppercase text-xs tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($students as $student)
                    <tr 
                        class="hover:bg-gray-50 transition-colors"
                        x-show="filterStudent('{{ $student['name'] }}', '{{ $student['nim'] }}', '{{ $student['prodi'] }}', '{{ $student['status'] }}')"
                    >
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="size-10 rounded-full bg-gradient-to-br from-[#8B1538] to-[#C41E3A] flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($student['name'], 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-[#111218] font-semibold">{{ $student['name'] }}</p>
                                    <p class="text-[#616889] text-xs">{{ $student['nim'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-[#616889]">{{ $student['prodi'] }}</td>
                        <td class="px-6 py-4 text-[#616889] text-center">{{ $student['semester'] }}</td>
                        <td class="px-6 py-4 text-[#8B1538] font-bold text-center">{{ number_format($student['ipk'], 2) }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusColor = match($student['status']) {
                                    'Aktif' => 'text-emerald-600',
                                    'Cuti' => 'text-amber-600',
                                    'Non-Aktif' => 'text-rose-600',
                                    default => 'text-gray-600'
                                };
                            @endphp
                            <span class="{{ $statusColor }} font-medium text-sm">{{ $student['status'] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <button class="size-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-[#8B1538] transition-colors" title="Kirim Pesan">
                                    <i class="far fa-envelope text-sm"></i>
                                </button>
                                <button class="size-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors" title="Lainnya">
                                    <i class="fas fa-ellipsis-h text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Table Footer --}}
        <div class="px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50">
            <p class="text-sm text-[#616889]">
                Menampilkan <span class="font-semibold text-[#111218]" x-text="filteredCount">{{ count($students) }}</span> dari <span class="font-semibold text-[#111218]">{{ count($students) }}</span> mahasiswa
            </p>
            <div class="flex items-center gap-2">
                <button class="px-4 py-2 text-sm border border-gray-200 rounded-lg text-gray-600 hover:bg-white transition-colors disabled:opacity-50" disabled>
                    Sebelumnya
                </button>
                <button class="px-4 py-2 text-sm border border-gray-200 rounded-lg text-gray-600 hover:bg-white transition-colors disabled:opacity-50" disabled>
                    Selanjutnya
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function detailKelas() {
        return {
            searchQuery: '',
            filterProdi: '',
            filterStatus: '',
            filteredCount: {{ count($students) }},
            
            filterStudent(name, nim, prodi, status) {
                let matchSearch = true;
                let matchProdi = true;
                let matchStatus = true;
                
                // Search filter
                if (this.searchQuery) {
                    const query = this.searchQuery.toLowerCase();
                    matchSearch = name.toLowerCase().includes(query) || nim.toLowerCase().includes(query);
                }
                
                // Prodi filter
                if (this.filterProdi) {
                    matchProdi = prodi === this.filterProdi;
                }
                
                // Status filter
                if (this.filterStatus) {
                    matchStatus = status === this.filterStatus;
                }
                
                const matches = matchSearch && matchProdi && matchStatus;
                
                // Update count (simple approximation for demo)
                this.$nextTick(() => {
                    this.updateCount();
                });
                
                return matches;
            },
            
            updateCount() {
                const visibleRows = document.querySelectorAll('tbody tr:not([style*="display: none"])').length;
                this.filteredCount = visibleRows;
            }
        }
    }
</script>
@endpush

@endsection