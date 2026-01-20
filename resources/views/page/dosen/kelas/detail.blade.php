@extends('layouts.app')

@section('title', 'Detail Kelas')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        /* Custom Scrollbar for Daftar Pertemuan */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #dbdde6;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #8B1538;
        }
    </style>
@endpush

@section('content')
    @php
        $meetings = [];
        for ($i = 1; $i <= ($class_info['total_pertemuan'] ?? 12); $i++) {
            $meetings[] = [
                'no' => $i,
                'label' => 'Pertemuan ' . $i,
                'date' => now()->addDays(($i - 1) * 7)->locale('id')->isoFormat('D MMM YYYY'),
                'present' => 0,
                'total' => count($students)
            ];
        }
    @endphp

    <div class="flex flex-col gap-6 w-full flex-1 px-4" x-data="detailKelas()">

        {{-- HEADER --}}
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ route('dosen.kelas') }}"
                    class="text-gray-400 hover:text-[#8B1538] transition-colors p-2 rounded-lg hover:bg-gray-100">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-2xl font-extrabold text-[#111218] tracking-tight">Detail Kelas</h1>
                    <p class="text-[#616889] text-sm">Manajemen kehadiran dan mahasiswa kelas</p>
                </div>
            </div>

            <button class="flex items-center gap-2 px-4 py-2 bg-white border border-[#8B1538] text-[#8B1538] 
                rounded-lg font-medium text-sm hover:bg-[#FEF2F2] transition-all shadow-sm">
                <span class="material-symbols-outlined text-[20px]">download</span>
                Export Data
            </button>
        </div>

        {{-- CLASS INFO --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm mt-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">

                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-[#8B1538] to-[#701230] 
                        flex items-center justify-center text-white shadow-md">
                        <span class="material-symbols-outlined text-3xl">menu_book</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h2 class="text-xl font-bold text-[#111218]">{{ $class_info['name'] }}</h2>
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-pink-50 
                                text-[#8B1538] border border-pink-100">
                                {{ $class_info['section'] }}
                            </span>
                        </div>
                        <p class="text-sm text-[#616889]">
                            {{ $class_info['code'] }} • {{ $class_info['sks'] }} SKS • Semester
                            {{ $class_info['semester'] }}
                        </p>
                    </div>
                </div>

                <div class="lg:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-[#616889]">
                    @php
                        $infos = [
                            ['icon' => 'calendar_month', 'value' => $class_info['day'] == '-' ? 'Belum terjadwal' : $class_info['day']],
                            ['icon' => 'schedule', 'value' => $class_info['time'] == '-' ? '--:--' : $class_info['time']],
                            ['icon' => 'location_on', 'value' => $class_info['room'] == '-' ? 'TBA' : $class_info['room']],
                            ['icon' => 'group', 'value' => $class_info['students_count'] . " Mahasiswa"],
                        ];
                    @endphp

                    @foreach($infos as $info)
                        <div class="flex items-center gap-2 bg-gray-50 p-2 rounded-lg border border-gray-100">
                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-sm">
                                <span class="material-symbols-outlined text-gray-400 text-lg">{{ $info['icon'] }}</span>
                            </div>
                            <span class="font-medium text-xs">{{ $info['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6">

            {{-- LEFT: DAFTAR PERTEMUAN --}}
<div class="col-span-12 lg:col-span-3">
                <div class="bg-white rounded-xl border border-gray-200 p-6 min-h-[500px]">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-[#111218]">Daftar Pertemuan</h3>
                        <span class="text-xs text-[#616889]">Total {{ count($meetings) }}</span>
                    </div>

                    <div class="flex flex-col gap-3 max-h-[620px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($meetings as $m)
                            <div x-data="{ open: false }"
                                class="border border-gray-100 rounded-lg overflow-hidden bg-white hover:shadow-sm transition-shadow">
                                <button @click="open = !open"
                                    class="w-full flex items-center justify-between px-4 py-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#8B1538] to-[#C41E3A] flex items-center justify-center text-white font-bold shadow-sm">
                                            {{ $m['no'] }}
                                        </div>
                                        <div class="text-left">
                                            <div class="text-sm font-semibold text-[#111218]">{{ $m['label'] }}</div>
                                            <div class="text-xs text-[#616889]">{{ $m['date'] }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-gray-300 transition-transform duration-200"
                                            :class="open ? 'rotate-180' : ''">expand_more</span>
                                    </div>
                                </button>

                                <div x-show="open" x-cloak x-collapse class="px-4 pb-4 pt-2 border-t border-gray-50 bg-gray-50">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="text-xs text-[#616889]">Kehadiran:
                                            <span class="font-semibold text-[#111218]">{{ $m['present'] }}</span>
                                        </div>
                                        <div class="text-xs text-[#616889]">Total:
                                            <span class="font-semibold text-[#111218]">{{ $m['total'] }}</span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="{{ route('dosen.kelas.absensi', ['id' => $id]) }}?pertemuan={{ $m['no'] }}"
                                            class="px-3 py-2 bg-[#8B1538] text-white rounded-lg text-xs font-medium text-center hover:bg-[#701230] transition-colors">
                                            Absen
                                        </a>
                                        <button
                                            class="px-3 py-2 border border-gray-200 rounded-lg text-xs text-gray-600 font-medium text-center hover:bg-white transition-colors">
                                            Rincian
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- RIGHT: SEARCH + TABLE --}}
<div class="col-span-12 lg:col-span-9 flex flex-col gap-6 w-full">


                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="relative flex-1">
                            <span
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 material-symbols-outlined text-[20px]">search</span>
                            <input type="text" x-model="searchQuery" placeholder="Cari nama atau NIM..."
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg bg-white focus:outline-none focus:border-[#8B1538] focus:ring-1 focus:ring-[#8B1538] text-sm text-gray-600 placeholder-gray-400">
                        </div>

                        <div class="flex gap-3">
                            <div class="relative w-full md:w-auto">
                                <select x-model="filterProdi"
                                    class="w-full md:w-40 appearance-none pl-4 pr-10 py-2.5 border border-gray-200 rounded-lg bg-white focus:outline-none focus:border-[#8B1538] focus:ring-1 focus:ring-[#8B1538] text-sm text-gray-600 cursor-pointer">
                                    <option value="">Semua Prodi</option>
                                    <option value="Informatika">Informatika</option>
                                    <option value="Sistem Informasi">Sistem Informasi</option>
                                </select>
                                <span
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none material-symbols-outlined text-[20px]">expand_more</span>
                            </div>

                            <div class="relative w-full md:w-auto">
                                <select x-model="filterStatus"
                                    class="w-full md:w-40 appearance-none pl-4 pr-10 py-2.5 border border-gray-200 rounded-lg bg-white focus:outline-none focus:border-[#8B1538] focus:ring-1 focus:ring-[#8B1538] text-sm text-gray-600 cursor-pointer">
                                    <option value="">Semua Status</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Cuti">Cuti</option>
                                    <option value="Non-Aktif">Non-Aktif</option>
                                </select>
                                <span
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none material-symbols-outlined text-[20px]">expand_more</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden min-h-[320px] w-full">
                    <div class="w-full overflow-x-auto">
    <table class="w-full text-sm text-left">

                            <thead class="bg-gray-50 text-[#616889] border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 font-semibold uppercase text-xs tracking-wider">Mahasiswa</th>
                                    <th class="px-6 py-4 font-semibold uppercase text-xs tracking-wider">Prodi</th>
                                    <th class="px-6 py-4 font-semibold uppercase text-xs tracking-wider text-center">
                                        Semester</th>
                                    <th class="px-6 py-4 font-semibold uppercase text-xs tracking-wider text-center">IPK
                                    </th>
                                    <th class="px-6 py-4 font-semibold uppercase text-xs tracking-wider">Status</th>
                                    <th class="px-6 py-4 font-semibold uppercase text-xs tracking-wider text-right">Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @if(count($students) > 0)
                                    @foreach($students as $student)
                                        <tr class="hover:bg-gray-50 transition-colors" x-show="filterStudent(
                                                    '{{ $student['name'] }}', 
                                                    '{{ $student['nim'] }}', 
                                                    '{{ $student['prodi'] }}', 
                                                    '{{ $student['status'] }}'
                                                )">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="size-10 rounded-full bg-gradient-to-br from-[#8B1538] to-[#C41E3A] flex items-center justify-center text-white text-xs font-bold">
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
                                            <td class="px-6 py-4 text-[#8B1538] font-bold text-center">
                                                {{ number_format($student['ipk'], 2) }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @php
                                                    $statusColor = match ($student['status']) {
                                                        'Aktif' => 'text-emerald-600',
                                                        'Cuti' => 'text-amber-600',
                                                        'Non-Aktif' => 'text-rose-600',
                                                        default => 'text-gray-600'
                                                    };
                                                @endphp
                                                <span class="{{ $statusColor }} font-medium text-sm">
                                                    {{ $student['status'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center justify-end gap-2">
                                                    <button
                                                        class="size-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-[#8B1538] transition-colors"
                                                        title="Kirim Pesan">
                                                        <span class="material-symbols-outlined text-[18px]">mail</span>
                                                    </button>
                                                    <button
                                                        class="size-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors"
                                                        title="Lainnya">
                                                        <span class="material-symbols-outlined text-[18px]">more_horiz</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="py-16 w-full text-center">
                                            <div class="flex flex-col items-center justify-center w-full">
                                                <div class="mb-3">
                                                    <span class="material-symbols-outlined text-4xl text-gray-300">group_off</span>
                                                </div>
                                                <div class="text-lg font-semibold text-gray-400 mb-1">Belum ada mahasiswa di kelas ini</div>
                                                <div class="text-sm text-gray-300">Silakan tambahkan mahasiswa ke kelas ini melalui menu KRS atau hubungi admin.</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div
                        class="px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50">
                        <p class="text-sm text-[#616889]">
                            Menampilkan
                            <span class="font-semibold text-[#111218]" x-text="filteredCount">
                                {{ count($students) }}
                            </span>
                            dari
                            <span class="font-semibold text-[#111218]">
                                {{ count($students) }}
                            </span> mahasiswa
                        </p>
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

                            if (this.searchQuery) {
                                const query = this.searchQuery.toLowerCase();
                                matchSearch = name.toLowerCase().includes(query) || nim.toLowerCase().includes(query);
                            }

                            if (this.filterProdi) {
                                matchProdi = prodi === this.filterProdi;
                            }

                            if (this.filterStatus) {
                                matchStatus = status === this.filterStatus;
                            }

                            const matches = matchSearch && matchProdi && matchStatus;

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