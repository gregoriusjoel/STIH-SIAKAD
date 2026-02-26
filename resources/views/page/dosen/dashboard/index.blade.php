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
    <div class="pt-6 px-6 md:px-8 pb-8 w-full flex flex-col gap-6" x-data="dashboardApp(@js($all_schedules ?? []), @js($meeting_dates ?? []), @js($schedules ?? []), @js($upcomingSchedules ?? []))">

        <!-- Welcome Section -->
        <div class="flex flex-col gap-1">
            <h2 class="text-3xl font-black text-[#111218] dark:text-white tracking-tight">
                Selamat Pagi, {{ Auth::user()->name }}
            </h2>
            <p class="text-base text-[#616889] dark:text-slate-400">
                Berikut adalah ringkasan aktivitas akademik dan jadwal Anda hari ini.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">

            <!-- CALENDAR SECTION -->
            <div class="bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-200 dark:border-slate-800 p-6 shadow-sm flex flex-col relative overflow-hidden w-full"
                style="min-height: 540px;">

                <!-- Calendar Header -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <button
                            @click="view = (view === 'calendar' ? 'months' : (view === 'months' ? 'years' : 'calendar'))"
                            class="text-lg font-bold text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-800 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1 group">
                            <span x-text="monthName + ' ' + year"></span>
                            <span
                                class="material-symbols-outlined text-sm text-gray-400 group-hover:text-gray-600 dark:text-slate-500 dark:group-hover:text-slate-300 transition-transform"
                                :class="view !== 'calendar' ? 'rotate-180' : ''">arrow_drop_down</span>
                        </button>
                    </div>
                    <div class="flex items-center gap-1" x-show="view === 'calendar'">
                        <button @click="prevMonth"
                            class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-400 transition-colors">
                            <span class="material-symbols-outlined text-xl">chevron_left</span>
                        </button>
                        <button @click="nextMonth"
                            class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-400 transition-colors">
                            <span class="material-symbols-outlined text-xl">chevron_right</span>
                        </button>
                    </div>
                    <div class="flex items-center gap-1" x-show="view === 'years'">
                        <button @click="year -= 12"
                            class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-400 transition-colors">
                            <span class="material-symbols-outlined text-xl">chevron_left</span>
                        </button>
                        <button @click="year += 12"
                            class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-400 transition-colors">
                            <span class="material-symbols-outlined text-xl">chevron_right</span>
                        </button>
                    </div>
                </div>

                <div x-show="view === 'calendar'" class="flex flex-col h-full pb-16">

                    <!-- Weekdays -->
                    <div class="grid grid-cols-7 mb-2">
                        <template x-for="day in ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab']">
                            <div class="text-center text-sm text-gray-400 font-medium py-2" x-text="day"></div>
                        </template>

                    </div>


                    <!-- Days Grid -->
                    <div class="grid grid-cols-7 gap-1 lg:gap-2 items-center">
                        <!-- Empty cells -->
                        <template x-for="blank in blankDays">
                            <div class="w-full" style="height: 48px;"></div>
                        </template>

                        <!-- Days -->
                        <template x-for="date in daysInMonth">
                            <div class="w-full relative flex items-center justify-center" style="height: 48px;">
                                <button @click="openDay(date)"
                                    class="rounded-full flex flex-col items-center justify-center text-sm transition-all duration-200 relative group"
                                    style="width: 40px; height: 40px;"
                                    :class="{
                                                                                                                                                    'bg-primary text-white shadow-lg shadow-primary/30 font-bold': isToday(date),
                                                                                                                                                    'bg-primary/15 text-primary font-black border-2 border-primary/20': !isToday(date) && getEvents(date).length > 0,
                                                                                                                                                    'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-800 font-medium': !isToday(date) && getEvents(date).length === 0,
                                                                                                                                                    'ring-2 ring-primary ring-offset-2 dark:ring-offset-slate-900': selectedDate === date
                                                                                                                                                }">
                                    <span x-text="date"></span>

                                    <!-- Event Indicator Label (Optional/Micro) -->
                                    <div x-show="getEvents(date).length > 0"
                                        class="absolute -bottom-1 left-1/2 -translate-x-1/2 flex gap-0.5">
                                        <template x-for="i in Math.min(getEvents(date).length, 3)">
                                            <div class="w-1.5 h-1.5 rounded-full shadow-sm"
                                                :class="isToday(date) ? 'bg-white' : 'bg-primary'"></div>
                                        </template>
                                    </div>
                                </button>
                            </div>
                        </template>


                    </div>

                    <!-- Legend -->
                    <div
                        class="absolute bottom-0 left-0 w-full p-6 flex items-center gap-4 text-[10px] uppercase tracking-wider font-bold text-gray-500 bg-white dark:bg-[#1a1d2e] z-10">
                        <div class="flex items-center gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-primary"></div>
                            <span>Hari Ini</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-primary/20 border border-primary/30"></div>
                            <span>Ada Jadwal</span>
                        </div>
                    </div>


                </div>

                <!-- Months View -->
                <div x-show="view === 'months'" class="grid grid-cols-3 gap-4 h-full content-center"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">
                    <template x-for="(m, index) in monthNames">
                        <button @click="month = index; view = 'calendar'"
                            class="p-4 rounded-xl text-sm font-semibold transition-all"
                            :class="month === index ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-gray-300'">
                            <span x-text="m.substr(0, 3)"></span>
                        </button>
                    </template>
                </div>

                <!-- Years View -->
                <div x-show="view === 'years'"
                    class="grid grid-cols-4 gap-2 h-full content-center overflow-y-auto max-h-[400px]"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">
                    <template x-for="y in yearsList">
                        <button @click="year = y; view = 'months'"
                            class="p-3 rounded-xl text-sm font-semibold transition-all"
                            :class="year === y ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-gray-300'">
                            <span x-text="y"></span>
                        </button>
                    </template>
                </div>

            </div>

            <!-- SCHEDULES WRAPPER -->
            <div class="md:col-span-2 flex flex-col gap-6">

                <!-- JADWAL HARI INI SECTION -->
                <div
                    class="bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-200 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col w-full h-[280px]">

                    <!-- Header -->
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-xl text-primary">event_note</span>
                            <h3 class="text-base font-bold text-[#111218] dark:text-white">Jadwal Mengajar</h3>
                        </div>
                        <a href="{{ route('dosen.jadwal') }}"
                            class="text-xs font-semibold text-primary hover:text-primary/80 transition-colors">
                            Lihat Semua
                        </a>
                    </div>

                    <!-- List Content -->
                    <div class="flex-1 min-h-0 flex flex-col">
                        <div class="flex-1">
                            <template x-if="todaySchedules.length === 0">
                                <div class="flex flex-col items-center justify-center h-full text-center px-6">
                                    <div
                                        class="w-16 h-16 bg-gray-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-3">
                                        <span class="material-symbols-outlined text-3xl text-gray-300">event_busy</span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Tidak ada jadwal</p>
                                    <p class="text-xs text-gray-500 mt-1">Anda tidak memiliki jadwal mengajar hari ini.</p>
                                </div>
                            </template>

                            <template x-if="todaySchedules.length > 0">
                                <div>
                                    <template x-for="(schedule, index) in paginatedTodaySchedules" :key="index">
                                        <a :href="schedule.url"
                                            class="block p-4 border-b border-gray-50 dark:border-slate-800/50 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors flex items-start gap-3 group cursor-pointer"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 transform translate-y-2"
                                            x-transition:enter-end="opacity-100 transform translate-y-0">
                                            <!-- Time Column -->
                                            <div class="flex flex-col items-center min-w-12 pt-1">
                                                <span
                                                    class="text-sm font-bold text-[#111218] dark:text-white"
                                                    x-text="schedule.time.substring(0, 5)"></span>
                                                <span class="text-[10px] text-gray-400" x-text="schedule.time.substring(8, 5)"></span>
                                            </div>

                                            <!-- Content Column -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-0.5">
                                                    <h4 class="text-base font-bold text-[#111218] dark:text-white truncate group-hover:text-primary transition-colors"
                                                        x-text="schedule.subject"
                                                        :title="schedule.subject"></h4>
                                                    <template x-if="schedule.is_rescheduled">
                                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 rounded-md">RESCHEDULE</span>
                                                    </template>
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-slate-400 truncate mb-1.5">
                                                    <span x-text="schedule.code"></span> • Kelas <span x-text="schedule.class"></span>
                                                </p>
                                                <div class="flex items-center gap-4">
                                                    <div
                                                        class="flex items-center gap-1 text-[10px] font-medium text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-slate-800 px-2 py-0.5 rounded-full">
                                                        <span class="material-symbols-outlined text-[10px]">location_on</span>
                                                        <span x-text="schedule.room"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <!-- Pagination Controls -->
                        <div x-show="todaySchedules.length > todayPerPage" 
                            class="px-5 py-3 border-t border-gray-100 dark:border-slate-800 flex items-center justify-between bg-gray-50 dark:bg-slate-800/50">
                            <button @click="todayPage = Math.max(1, todayPage - 1)"
                                :disabled="todayPage === 1"
                                :class="todayPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-200 dark:hover:bg-slate-700'"
                                class="p-2 rounded-lg transition-colors text-gray-600 dark:text-slate-400">
                                <span class="material-symbols-outlined text-lg">chevron_left</span>
                            </button>
                            <span class="text-xs font-semibold text-gray-600 dark:text-slate-400">
                                <span x-text="todayPage"></span> / <span x-text="todayTotalPages"></span>
                            </span>
                            <button @click="todayPage = Math.min(todayTotalPages, todayPage + 1)"
                                :disabled="todayPage === todayTotalPages"
                                :class="todayPage === todayTotalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-200 dark:hover:bg-slate-700'"
                                class="p-2 rounded-lg transition-colors text-gray-600 dark:text-slate-400">
                                <span class="material-symbols-outlined text-lg">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- JADWAL MENDATANG SECTION -->
                <div
                    class="bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-200 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col w-full h-[280px]">

                    <!-- Header -->
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-xl text-primary">upcoming</span>
                            <h3 class="text-base font-bold text-[#111218] dark:text-white">Jadwal Mendatang</h3>
                        </div>
                    </div>

                    <!-- List Content -->
                    <div class="flex-1 min-h-0 flex flex-col">
                        <div class="flex-1">
                            <template x-if="upcomingSchedulesList.length === 0">
                                <div class="flex flex-col items-center justify-center h-full text-center px-6">
                                    <div
                                        class="w-16 h-16 bg-gray-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-3">
                                        <span class="material-symbols-outlined text-3xl text-gray-300">event_busy</span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Tidak ada jadwal mendatang</p>
                                    <p class="text-xs text-gray-500 mt-1">Jadwal mengajar minggu depan belum tersedia.</p>
                                </div>
                            </template>

                            <template x-if="upcomingSchedulesList.length > 0">
                                <div>
                                    <template x-for="(schedule, index) in paginatedUpcomingSchedules" :key="index">
                                        <a :href="schedule.url"
                                            class="block p-4 border-b border-gray-50 dark:border-slate-800/50 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors flex items-start gap-3 group cursor-pointer"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 transform translate-y-2"
                                            x-transition:enter-end="opacity-100 transform translate-y-0">
                                            <!-- Time/Day Column -->
                                            <div class="flex flex-col items-center min-w-15 pt-1">
                                                <span
                                                    class="text-[10px] font-bold text-primary uppercase tracking-wide mb-0.5"
                                                    x-text="schedule.day"></span>
                                                <span
                                                    class="text-sm font-bold text-[#111218] dark:text-white"
                                                    x-text="schedule.time.substring(0, 5)"></span>
                                            </div>

                                            <!-- Content Column -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-0.5">
                                                    <h4 class="text-base font-bold text-[#111218] dark:text-white truncate group-hover:text-primary transition-colors"
                                                        x-text="schedule.subject"
                                                        :title="schedule.subject"></h4>
                                                    <template x-if="schedule.is_rescheduled">
                                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 rounded-md">RESCHEDULE</span>
                                                    </template>
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-slate-400 truncate mb-1.5">
                                                    <span x-text="schedule.code"></span> • Kelas <span x-text="schedule.class"></span>
                                                </p>
                                                <div class="flex items-center gap-4">
                                                    <div
                                                        class="flex items-center gap-1 text-[10px] font-medium text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-slate-800 px-2 py-0.5 rounded-full">
                                                        <span class="material-symbols-outlined text-[10px]">location_on</span>
                                                        <span x-text="schedule.room"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <!-- Pagination Controls -->
                        <div x-show="upcomingSchedulesList.length > upcomingPerPage" 
                            class="px-5 py-3 border-t border-gray-100 dark:border-slate-800 flex items-center justify-between bg-gray-50 dark:bg-slate-800/50">
                            <button @click="upcomingPage = Math.max(1, upcomingPage - 1)"
                                :disabled="upcomingPage === 1"
                                :class="upcomingPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-200 dark:hover:bg-slate-700'"
                                class="p-2 rounded-lg transition-colors text-gray-600 dark:text-slate-400">
                                <span class="material-symbols-outlined text-lg">chevron_left</span>
                            </button>
                            <span class="text-xs font-semibold text-gray-600 dark:text-slate-400">
                                <span x-text="upcomingPage"></span> / <span x-text="upcomingTotalPages"></span>
                            </span>
                            <button @click="upcomingPage = Math.min(upcomingTotalPages, upcomingPage + 1)"
                                :disabled="upcomingPage === upcomingTotalPages"
                                :class="upcomingPage === upcomingTotalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-200 dark:hover:bg-slate-700'"
                                class="p-2 rounded-lg transition-colors text-gray-600 dark:text-slate-400">
                                <span class="material-symbols-outlined text-lg">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div x-cloak x-show="showModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/40" @click="closeModal"></div>
            <div x-transition class="bg-white dark:bg-[#0b1220] rounded-xl shadow-xl w-full max-w-md mx-4 p-6 z-50">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-[#111218] dark:text-white" x-text="selectedEvent?.title || ''">
                        </h3>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-sm font-semibold text-gray-600 dark:text-slate-400"
                                x-text="selectedEvent?.section ? ('Kelas ' + selectedEvent.section) : ''"></span>
                            <span class="text-sm text-gray-500 dark:text-slate-400"
                                x-text="selectedEvent ? (selectedEvent.time || '') : ''"></span>
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
                            <div><strong>Kelas:</strong> <span x-text="selectedEvent?.section || '-' "></span>
                            </div>
                            <div><strong>Ruangan:</strong> <span x-text="selectedEvent?.room || '-' "></span>
                            </div>
                            <div><strong>Waktu:</strong> <span x-text="selectedEvent?.time || '-' "></span>
                            </div>
                            <div class="pt-3">
                                <a :href="selectedEvent?.url || '#'" 
                                   class="inline-block bg-primary text-white font-bold text-xs py-2 px-4 rounded-lg shadow hover:bg-primary/90 transition-colors">
                                    Detail Kelas
                                </a>
                            </div>
                        </div>
                    </template>

                    <!-- If multiple events on the day, list them -->
                    <template x-if="!selectedEvent && dayEvents.length > 0">
                        <div class="space-y-2">
                            <template x-for="(e, i) in dayEvents" :key="i">
                                <div @click="openEvent(e)"
                                    class="p-2 rounded hover:bg-gray-50 dark:hover:bg-slate-800 cursor-pointer">
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

    @push('scripts')
        <script>
            function dashboardApp(schedules, meetingDates, todaySchedulesData, upcomingSchedulesData) {
                return {
                    view: 'calendar', // calendar, months, years
                    showModal: false,
                    selectedEvent: null,
                    selectedDate: new Date().getDate(),
                    dayEvents: [],
                    month: new Date().getMonth(),
                    year: new Date().getFullYear(),
                    schedules: schedules,
                    meetingDates: meetingDates,
                    monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    
                    // Today schedules pagination
                    todaySchedules: todaySchedulesData,
                    todayPage: 1,
                    todayPerPage: 3,
                    
                    // Upcoming schedules pagination
                    upcomingSchedulesList: upcomingSchedulesData,
                    upcomingPage: 1,
                    upcomingPerPage: 3,

                    get monthName() {
                        return this.monthNames[this.month];
                    },
                    
                    // Today schedules computed properties
                    get todayTotalPages() {
                        return Math.ceil(this.todaySchedules.length / this.todayPerPage) || 1;
                    },
                    
                    get paginatedTodaySchedules() {
                        const start = (this.todayPage - 1) * this.todayPerPage;
                        const end = start + this.todayPerPage;
                        return this.todaySchedules.slice(start, end);
                    },
                    
                    // Upcoming schedules computed properties
                    get upcomingTotalPages() {
                        return Math.ceil(this.upcomingSchedulesList.length / this.upcomingPerPage) || 1;
                    },
                    
                    get paginatedUpcomingSchedules() {
                        const start = (this.upcomingPage - 1) * this.upcomingPerPage;
                        const end = start + this.upcomingPerPage;
                        return this.upcomingSchedulesList.slice(start, end);
                    },

                    get yearsList() {
                        let years = [];
                        for (let i = this.year - 11; i <= this.year; i++) {
                            years.push(i);
                        }
                        return years;
                    },

                    get blankDays() {
                        let firstDay = new Date(this.year, this.month, 1).getDay();
                        // Adjust because Sunday is 0, but we want Monday as start (0)
                        // If Sunday (0), it should be 6 blanks. If Monday (1), 0 blanks.
                        // Wait, user wants standard calendar, typically Sunday start? 
                        // HeroUI calendar starts on Sunday by default usually. 
                        // Let's stick to Sunday start for HeroUI look, so no adjustment needed except ensuring grid header matches.
                        // Original code had Monday start array: ['Sen', 'Sel'...]
                        // My new code has ['Min', 'Sen'...] (Sunday start)
                        // So straightforward Sunday = 0
                        return firstDay;
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
                        // Only show events if this date is within the 16 meeting dates
                        const d = new Date(this.year, this.month, date);
                        const dateStr = d.getFullYear() + '-' + 
                                      String(d.getMonth() + 1).padStart(2, '0') + '-' + 
                                      String(d.getDate()).padStart(2, '0');
                        
                        // Check if this date is in the meeting dates list
                        if (!this.meetingDates.includes(dateStr)) {
                            return [];
                        }
                        
                        // Map date to day name for current schedule logic
                        const dayName = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][d.getDay()];
                        return this.schedules.filter(s => s.day === dayName);
                    },

                    openEvent(event) {
                        this.selectedEvent = event;
                        this.dayEvents = [];
                        this.showModal = true;
                    },

                    openDay(date) {
                        this.selectedDate = date;
                        // Optional: if double click or specific UI requirement, show modal.
                        // For now, we show the list below the calendar as per new design.
                    },

                    closeModal() {
                        this.showModal = false;
                        this.selectedEvent = null;
                    },
                }
            }
        </script>
    @endpush
@endsection