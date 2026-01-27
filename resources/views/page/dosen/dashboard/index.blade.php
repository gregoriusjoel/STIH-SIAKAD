@extends('layouts.app')

@section('title', 'Dashboard')

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
    <div class="p-6 md:p-8 max-w-[1400px] mx-auto w-full flex flex-col gap-6" x-data="calendarApp(@js($all_schedules ?? []))">
        <!-- Force Tailwind JIT: grid grid-cols-7 gap-1 -->

        <!-- Welcome Section -->
        <div class="flex flex-col gap-1">
            <h2 class="text-3xl md:text-4xl font-black text-[#111218] dark:text-white tracking-tight">
                Selamat Pagi, {{ Auth::user()->name }}
            </h2>
            <p class="text-base text-[#616889] dark:text-slate-400">
                Berikut adalah ringkasan aktivitas akademik dan jadwal Anda hari ini.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">

            <!-- LEFT COLUMN: CALENDAR -->
            <div
                class="bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-200 dark:border-slate-800 p-6 shadow-sm flex flex-col h-full">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white" x-text="monthName + ' ' + year"></h3>
                    <div class="flex gap-1">
                        <button @click="prevMonth"
                            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-400">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </button>
                        <button @click="nextMonth"
                            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-400">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <div class="grid grid-cols-7 gap-1 mb-2"
                    style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr));">
                    <template x-for="day in ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']">
                        <div class="text-center text-xs font-bold text-gray-400 uppercase py-2" x-text="day"></div>
                    </template>
                </div>

                <div class="grid grid-cols-7 gap-1 flex-1 auto-rows-fr"
                    style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr));">
                    <!-- Empty cells for start of month -->
                    <template x-for="blank in blankDays">
                        <div class="p-2 border border-transparent"></div>
                    </template>

                    <!-- Days -->
                    <template x-for="date in daysInMonth">
                        <div class="border border-gray-100 dark:border-slate-700 rounded-lg p-2 pb-10 min-h-[80px] flex flex-col gap-1 transition-all relative"
                            :class="{ 'bg-blue-50 dark:bg-blue-900/20 ring-2 ring-primary ring-inset': isToday(date), 'hover:border-primary/30': !isToday(date) }">
                            <span class="relative z-30 text-sm font-semibold text-gray-700 dark:text-gray-300"
                                :class="{ 'text-primary font-black text-lg': isToday(date) }" x-text="date"></span>

                            <!-- Day marker: small dot when there are events on this date (click to open day's events) -->
                            <div class="absolute bottom-3 right-3 z-10 transform translate-y-1" x-show="getEvents(date).length > 0" @click.stop="openDay(date)">
                                <span class="inline-block w-2 h-2 bg-primary rounded-full"></span>
                            </div>

                            <!-- Events -->
                            <div class="flex flex-col gap-1 overflow-y-auto max-h-[40px] custom-scrollbar">
                                <template x-for="event in getEvents(date)">
                                    <div @click.stop="openEvent(event)"
                                        class="text-[10px] bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded leading-tight truncate cursor-pointer hover:opacity-90"
                                        :title="event.title + ' (' + event.time + ')'">
                                        <span x-text="event.title.substr(0, 20) + (event.title.length>20? '...' : '')"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- RIGHT COLUMN: STATS & TODAY'S SCHEDULE -->
            <div class="flex flex-col gap-8 lg:col-span-2">

                <!-- Stats Grid (2x2) -->
                <div class="grid grid-cols-2 gap-6">

                    <!-- Stat Card 1 -->
                    <div
                        class="bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-200 dark:border-slate-800 p-6 flex flex-col gap-4 shadow-sm hover:shadow-md transition-all duration-200">
                        <div class="flex items-start justify-between">
                            <div
                                class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-900/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-2xl">menu_book</span>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-[#616889] dark:text-slate-400 text-sm font-medium mb-1">Total Mata Kuliah</p>
                            <p class="text-3xl font-black text-[#111218] dark:text-white">{{ $total_mata_kuliah }}</p>
                        </div>
                    </div>

                    <!-- Stat Card 2 -->
                    <div
                        class="bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-200 dark:border-slate-800 p-6 flex flex-col gap-4 shadow-sm hover:shadow-md transition-all duration-200">
                        <div class="flex items-start justify-between">
                            <div
                                class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 dark:bg-purple-900/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-2xl">meeting_room</span>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-[#616889] dark:text-slate-400 text-sm font-medium mb-1">Total Kelas Aktif</p>
                            <p class="text-3xl font-black text-[#111218] dark:text-white">{{ $total_kelas_aktif }}</p>
                        </div>
                    </div>

                    <!-- Stat Card 3 -->
                    <div
                        class="bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-200 dark:border-slate-800 p-6 flex flex-col gap-4 shadow-sm hover:shadow-md transition-all duration-200">
                        <div class="flex items-start justify-between">
                            <div
                                class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 dark:bg-orange-900/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-2xl">workspace_premium</span>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-[#616889] dark:text-slate-400 text-sm font-medium mb-1">Total Beban SKS</p>
                            <p class="text-3xl font-black text-[#111218] dark:text-white">{{ $sks_load }}</p>
                        </div>
                    </div>

                    <!-- Stat Card 4 (Urgent) -->
                    <div
                        class="bg-white dark:bg-[#1a1d2e] rounded-2xl border border-transparent dark:border-transparent p-6 flex flex-col gap-4 shadow-sm hover:shadow-md transition-all duration-200">
                        <div class="flex items-start justify-between">
                            <div
                                class="w-12 h-12 rounded-xl bg-pink-50 text-pink-600 dark:bg-pink-900/30 flex items-center justify-center">
                                <span class="material-symbols-outlined text-2xl">assignment_late</span>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-[#616889] dark:text-slate-400 text-sm font-medium mb-1">KRS Approval</p>
                            <p class="text-3xl font-black text-pink-600">{{ $krs_approval }}</p>
                        </div>
                    </div>
                </div>

                <!-- Schedule Table -->
                <div
                    class="bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-200 dark:border-slate-800 shadow-sm overflow-hidden flex-1">

                    <!-- Table Header Section -->
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-2xl text-primary">event_note</span>
                            <h3 class="text-lg font-black text-[#111218] dark:text-white">Jadwal Hari Ini</h3>
                        </div>
                        <a href="{{ route('dosen.jadwal') }}"
                            class="inline-flex items-center gap-1 text-primary text-sm font-semibold hover:underline transition-colors">
                            Lihat Semua
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-slate-800/50 border-b border-gray-200 dark:border-slate-800">
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-[#616889] dark:text-slate-400 uppercase tracking-wider">
                                        Mata Kuliah</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-[#616889] dark:text-slate-400 uppercase tracking-wider">
                                        Waktu</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-[#616889] dark:text-slate-400 uppercase tracking-wider">
                                        Ruangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-slate-800">
                                @forelse($schedules as $schedule)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col gap-0.5">
                                                <span
                                                    class="font-semibold text-[#111218] dark:text-white">{{ $schedule['subject'] }}</span>
                                                <span class="text-xs text-[#616889] dark:text-slate-400">{{ $schedule['code'] }}
                                                    • Kelas {{ $schedule['class'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2 text-sm text-[#111218] dark:text-white">
                                                <span class="material-symbols-outlined text-lg text-[#616889]">schedule</span>
                                                <span>{{ $schedule['time'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2 text-sm text-[#111218] dark:text-white">
                                                <span
                                                    class="material-symbols-outlined text-lg text-primary/70">location_on</span>
                                                <span>{{ $schedule['room'] }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-8 text-center text-gray-500 italic">
                                            Tidak ada jadwal mengajar hari ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                <!-- Event Detail Modal (inside x-data so Alpine has access) -->
                <div x-cloak x-show="showModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center">
                    <div class="absolute inset-0 bg-black/40" @click="closeModal"></div>
                    <div x-transition class="bg-white dark:bg-[#0b1220] rounded-xl shadow-xl w-full max-w-md mx-4 p-6 z-50">
                        <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-[#111218] dark:text-white" x-text="selectedEvent?.title || ''"></h3>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="text-sm font-semibold text-gray-600 dark:text-slate-400" x-text="selectedEvent?.section ? ('Kelas ' + selectedEvent.section) : ''"></span>
                                        <span class="text-sm text-gray-500 dark:text-slate-400" x-text="selectedEvent ? (selectedEvent.time || '') : ''"></span>
                                    </div>
                                </div>
                            <button @click="closeModal" class="text-gray-500 hover:text-gray-700 dark:text-slate-400">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>

                        <div class="space-y-2 text-sm text-[#111218] dark:text-white">
                            <!-- If a single event selected, show details -->
                            <template x-if="selectedEvent">
                                <div class="space-y-2">
                                    <div><strong>Matakuliah:</strong> <span x-text="selectedEvent?.title || '-' "></span></div>
                                    <div><strong>Kode:</strong> <span x-text="selectedEvent?.code || '-' "></span></div>
                                    <div><strong>Kelas:</strong> <span x-text="selectedEvent?.section || '-' "></span></div>
                                    <div><strong>Ruangan:</strong> <span x-text="selectedEvent?.room || '-' "></span></div>
                                    <div><strong>Waktu:</strong> <span x-text="selectedEvent?.time || '-' "></span></div>
                                </div>
                            </template>

                            <!-- If multiple events on the day, list them -->
                            <template x-if="!selectedEvent && dayEvents.length > 0">
                                <div class="space-y-2">
                                    <template x-for="(e, i) in dayEvents" :key="i">
                                        <div @click="openEvent(e)" class="p-2 rounded hover:bg-gray-50 dark:hover:bg-slate-800 cursor-pointer">
                                            <div class="font-semibold" x-text="e.title"></div>
                                            <div class="text-xs text-gray-500" x-text="e.time + ' • ' + (e.room || '-')"></div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div class="mt-6 text-right">
                            <button @click="closeModal"
                                class="px-4 py-2 bg-gray-100 dark:bg-slate-700 rounded-lg text-sm font-semibold">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function calendarApp(schedules) {
                return {
                    showModal: false,
                    selectedEvent: null,
                    dayEvents: [],
                    month: new Date().getMonth(),
                    year: new Date().getFullYear(),
                    schedules: schedules,
                    monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    dayNames: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],

                    get monthName() {
                        return this.monthNames[this.month];
                    },

                    get blankDays() {
                        let firstDay = new Date(this.year, this.month, 1).getDay();
                        // Adjust because Sunday is 0, but we want Monday as start (0)
                        // If Sunday (0), it should be 6 blanks. If Monday (1), 0 blanks.
                        return firstDay === 0 ? 6 : firstDay - 1;
                    },

                    get daysInMonth() {
                        return new Date(this.year, this.month + 1, 0).getDate();
                    },

                    isToday(date) {
                        const today = new Date();
                        return date === today.getDate() &&
                            this.month === today.getMonth() &&
                            this.year === today.getFullYear();
                    },

                    prevMonth() {
                        if (this.month === 0) {
                            this.month = 11;
                            this.year--;
                        } else {
                            this.month--;
                        }
                    },

                    nextMonth() {
                        if (this.month === 11) {
                            this.month = 0;
                            this.year++;
                        } else {
                            this.month++;
                        }
                    },

                    getEvents(date) {
                        const currentDayName = this.getDayName(date);
                        return this.schedules.filter(s => s.day === currentDayName);
                    },

                    openEvent(event) {
                        this.selectedEvent = event;
                        this.dayEvents = [];
                        this.showModal = true;
                    },

                    openDay(date) {
                        const events = this.getEvents(date);
                        this.dayEvents = events;
                        if (events.length === 1) {
                            this.openEvent(events[0]);
                        } else {
                            this.selectedEvent = null;
                            this.showModal = true;
                        }
                    },

                    closeModal() {
                        this.showModal = false;
                        this.selectedEvent = null;
                    },

                    getDayName(date) {
                        const d = new Date(this.year, this.month, date);
                        return this.dayNames[d.getDay()];
                    }
                }
            }
        </script>
    @endpush
@endsection