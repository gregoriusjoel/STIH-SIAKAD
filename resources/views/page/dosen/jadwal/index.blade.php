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
                                        {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 text-xs text-[#616889] mt-0.5">
                                    <span class="material-symbols-outlined text-[14px]">location_on</span>
                                    {{ $jadwal->ruangan ?? 'Belum ditentukan' }}
                                </div>
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
        </div>

    </div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('scheduleNavigation', () => ({
            currentDate: new Date(),
            minDate: new Date(),

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
    })
</script>
@endpush
@endsection
