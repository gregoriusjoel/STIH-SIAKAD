@extends('layouts.app')

@section('title', 'Jadwal Mengajar')

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
    <div class="flex flex-col min-w-0 h-full" x-data="scheduleNavigation()">
        <div class="p-6 md:p-8 max-w-[1400px] mx-auto w-full flex flex-col gap-6">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-black text-[#111218] dark:text-white">Jadwal Mengajar</h1>
                    <p class="text-[#616889] dark:text-slate-400 text-sm">Jadwal perkuliahan Semester Ganjil 2023/2024.</p>
                </div>
                
                <!-- Week Navigator -->
                <div class="flex items-center gap-2 bg-white dark:bg-[#1a1d2e] p-1 rounded-lg border border-[#dbdde6] dark:border-slate-800 shadow-sm">
                    <button @click="prevWeek()" :disabled="!canGoBack" :class="{'opacity-50 cursor-not-allowed': !canGoBack, 'hover:bg-gray-100 dark:hover:bg-slate-800': canGoBack}" class="p-1.5 rounded-md text-[#616889]">
                        <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                    </button>
                    <span class="text-sm font-semibold px-2 text-[#111218] dark:text-white" x-text="displayText"></span>
                    <button @click="nextWeek()" class="p-1.5 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-md text-[#616889]">
                        <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                    </button>
                </div>
            </div>

            <!-- Schedule Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                @php
                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                @endphp

                @foreach($days as $day)
                <div class="bg-white dark:bg-[#1a1d2e] rounded-xl border border-[#dbdde6] dark:border-slate-800 p-5 flex flex-col gap-4 shadow-sm {{ !isset($schedulesByDay[$day]) ? 'min-h-[200px]' : '' }}">
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-slate-800">
                        <h3 class="font-bold text-[#111218] dark:text-white">{{ $day }}</h3>
                        @if(isset($schedulesByDay[$day]))
                            <span class="text-xs font-medium text-[#616889]">{{ $schedulesByDay[$day]->count() }} Kelas</span>
                        @endif
                    </div>
                    @if(isset($schedulesByDay[$day]) && $schedulesByDay[$day]->count() > 0)
                    <!-- ...existing code... -->
                    @endif
                    
                    @if(isset($schedulesByDay[$day]) && $schedulesByDay[$day]->count() > 0)
                        @foreach($schedulesByDay[$day] as $jadwal)
                        <!-- Class Item -->
                        <div class="flex gap-3 relative pl-3 hover:bg-gray-50/50 rounded-r-lg transition-colors p-2 -mx-2">
                            <div class="absolute left-0 top-1 bottom-1 w-1 bg-red-900 rounded-full"></div>
                            <div class="flex-1 flex flex-col gap-1">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-bold text-[#111218] dark:text-white text-sm">{{ $jadwal->kelas->mataKuliah->nama_mk }}</h4>
                                    <span class="bg-{{ ($jadwal->kelas->mataKuliah->jenis ?? 'Teori') === 'Praktikum' ? 'blue' : 'orange' }}-50 text-{{ ($jadwal->kelas->mataKuliah->jenis ?? 'Teori') === 'Praktikum' ? 'blue' : 'orange' }}-600 px-2 py-0.5 rounded text-[10px] font-bold">{{ $jadwal->kelas->mataKuliah->jenis ?? 'Teori' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-[#616889]">
                                    <span class="bg-red-50 text-red-700 px-1.5 rounded font-bold">{{ $jadwal->kelas->section }}</span>
                                    <span>•</span>
                                    <span class="font-semibold">{{ $jadwal->kelas->mataKuliah->sks ?? 3 }} SKS</span>
                                    <span>•</span>
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">schedule</span>
                                        @php
                                            $hari = $jadwal->approvedReschedule->new_hari ?? $jadwal->hari;
                                            $jamMulai = $jadwal->approvedReschedule->new_jam_mulai ?? $jadwal->jam_mulai;
                                            $jamSelesai = $jadwal->approvedReschedule->new_jam_selesai ?? $jadwal->jam_selesai;
                                        @endphp

                                        {{ substr($jamMulai, 0, 5) }} - {{ substr($jamSelesai, 0, 5) }}

                                        @if($jadwal->approvedReschedule)
                                            <span class="ml-2 text-[10px] text-amber-600 font-semibold">
                                                Reschedule
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 text-xs text-[#616889] mt-0.5">
                                    <span class="material-symbols-outlined text-[14px]">location_on</span>
                                    {{ $jadwal->ruangan ?? 'Belum ditentukan' }}
                                </div>
                                    @if($loop->first)
                                    <div class="mt-3">
                                        <button type="button"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-[#8B1538] text-white rounded-md text-xs font-semibold shadow hover:opacity-90"
                                            @click="openRescheduleModal
                                                id: {{ $schedulesByDay[$day][0]->id }},
                                                mata_kuliah: '{{ $schedulesByDay[$day][0]->kelas->mataKuliah->nama_mk }}',
                                                hari: '{{ $schedulesByDay[$day][0]->hari }}',
                                                jam_mulai: '{{ substr($schedulesByDay[$day][0]->jam_mulai,0,5) }}',
                                                jam_selesai: '{{ substr($schedulesByDay[$day][0]->jam_selesai,0,5) }}'
                                            })">
                                            <span class="material-symbols-outlined">calendar_month</span>
                                            Reschedule Jadwal
                                        </button>
                                    </div>
                                    @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="flex-1 flex flex-col items-center justify-center text-center gap-2 text-[#616889]">
                            <p class="text-sm">Tidak ada jadwal mengajar</p>
                        </div>
                    @endif
                </div>
                @endforeach

            </div>

            <!-- Buttons to Redirect -->
                        <!-- Reschedule Modal -->
                        <!-- Reschedule Modal -->
<div
    x-show="showRescheduleModal"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>

    <!-- Backdrop -->
    <div
        class="absolute inset-0 bg-black/40 backdrop-blur"
        style="backdrop-filter: blur(2px); -webkit-backdrop-filter: blur(2px);"
        @click="showRescheduleModal = false">
    </div>

    <!-- Modal Card -->
    <div
        x-show="showRescheduleModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-3"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative bg-white dark:bg-[#1a1d2e] rounded-2xl shadow-2xl max-w-md w-full mx-4 p-6 z-50"
    >

        <!-- Header -->
        <div class="mb-5">
            <h3 class="text-lg font-black text-[#111218] dark:text-white">
                Reschedule Jadwal
            </h3>
            <p class="text-sm text-[#616889] dark:text-slate-400 mt-1">
                Ajukan perubahan hari atau jam mengajar
            </p>
        </div>

        <!-- Form -->
        <form method="POST" :action="rescheduleFormAction" class="space-y-4">
            @csrf
            <input type="hidden" name="jadwal_id" :value="rescheduleData.id">

            <!-- Mata Kuliah -->
            <div>
                <label class="block text-sm font-semibold mb-2 text-[#111218] dark:text-white">
                    Mata Kuliah
                </label>
                <input type="text"
                    class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 
                    rounded-lg bg-gray-100 dark:bg-slate-800 text-sm"
                    :value="rescheduleData.mata_kuliah"
                    readonly>
            </div>

            <!-- Hari -->
            <div>
                <label class="block text-sm font-semibold mb-2 text-[#111218] dark:text-white">
                    Hari
                </label>
                <select name="new_hari" x-model="rescheduleData.hari"
                    class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 
                    rounded-lg bg-white dark:bg-slate-800 text-sm
                    focus:outline-none focus:ring-1 focus:ring-[#8B1538]">
                    <template x-for="h in ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']" :key="h">
                        <option :value="h" x-text="h"></option>
                    </template>
                </select>
            </div>

            <!-- Jam -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-2 text-[#111218] dark:text-white">
                        Jam Mulai
                    </label>
                    <input type="time" name="new_jam_mulai"
                        x-model="rescheduleData.jam_mulai"
                        required
                        class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 
                        rounded-lg bg-white dark:bg-slate-800 text-sm
                        focus:outline-none focus:ring-1 focus:ring-[#8B1538]">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2 text-[#111218] dark:text-white">
                        Jam Selesai
                    </label>
                    <input type="time" name="new_jam_selesai"
                        x-model="rescheduleData.jam_selesai"
                        required
                        class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 
                        rounded-lg bg-white dark:bg-slate-800 text-sm
                        focus:outline-none focus:ring-1 focus:ring-[#8B1538]">
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-4">
                <button type="button"
                    @click="showRescheduleModal = false"
                    class="px-4 py-2 rounded-lg border text-sm font-semibold 
                    hover:bg-gray-100 dark:hover:bg-slate-800">
                    Batal
                </button>

                <button type="submit"
                    class="px-5 py-2 rounded-lg bg-[#8B1538] text-white text-sm font-semibold shadow hover:opacity-90">
                    Kirim Permintaan
                </button>
            </div>
        </form>
    </div>
</div>

            <!-- Creation and pending submission UI removed -->
            <div class="mt-6"></div>
        </div>

    </div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('scheduleNavigation', () => ({
            currentDate: new Date(),
            minDate: new Date(),

            // Modal state
            showRescheduleModal: false,
            rescheduleData: {
                id: '',
                mata_kuliah: '',
                hari: '',
                jam_mulai: '',
                jam_selesai: ''
            },
            get rescheduleFormAction() {
                return '/dosen/jadwal/reschedule';
            },
            openRescheduleModal(jadwal) {
                this.rescheduleData = { ...jadwal };
                this.showRescheduleModal = true;
            },
            init() {
                // Set to current week's Monday
                this.normalizeToMonday(this.currentDate);
                this.normalizeToMonday(this.minDate);
            },
            normalizeToMonday(date) {
                const day = date.getDay();
                const diff = date.getDate() - day + (day == 0 ? -6 : 1); // adjust when day is sunday
                date.setDate(diff);
                date.setHours(0, 0, 0, 0);
            },
            nextWeek() {
                this.currentDate.setDate(this.currentDate.getDate() + 7);
                this.currentDate = new Date(this.currentDate);
            },
            prevWeek() {
                if (this.canGoBack) {
                    this.currentDate.setDate(this.currentDate.getDate() - 7);
                    this.currentDate = new Date(this.currentDate);
                }
            },
            get canGoBack() {
                return this.currentDate.getTime() > this.minDate.getTime();
            },
            get displayText() {
                const start = new Date(this.currentDate);
                const end = new Date(this.currentDate);
                end.setDate(end.getDate() + 5); // Saturday
                const weekNum = Math.ceil(start.getDate() / 7);
                const monthName = new Intl.DateTimeFormat('id-ID', { month: 'short' }).format(start);
                return `Minggu ke-${weekNum} (${start.getDate()} - ${end.getDate()} ${monthName})`;
            }
        }))
    });
    // pending check removed
</script>
@endpush
@endsection