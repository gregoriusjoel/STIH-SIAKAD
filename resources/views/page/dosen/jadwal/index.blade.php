@extends('layouts.app')

@section('title', 'Jadwal Mengajar')

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
    <div class="flex flex-col min-w-0 h-full" x-data="scheduleNavigation()">
        <div class="p-6 md:p-8 max-w-[1400px] mx-auto w-full flex flex-col gap-6">

            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-black text-[#111218] dark:text-white">Jadwal Mengajar</h1>
                    <p class="text-[#616889] dark:text-slate-400 text-sm">Jadwal perkuliahan Semester Ganjil 2023/2024.</p>
                </div>

                <!-- Week Navigator -->
                <div
                    class="flex items-center gap-2 bg-white dark:bg-[#1a1d2e] p-1 rounded-lg border border-[#dbdde6] dark:border-slate-800 shadow-sm">
                    <button @click="prevWeek()" :disabled="!canGoBack"
                        :class="{'opacity-50 cursor-not-allowed': !canGoBack, 'hover:bg-gray-100 dark:hover:bg-slate-800': canGoBack}"
                        class="p-1.5 rounded-md text-[#616889]">
                        <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                    </button>
                    <span class="text-sm font-semibold px-2 text-[#111218] dark:text-white" x-text="displayText"></span>
                    <button @click="nextWeek()"
                        class="p-1.5 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-md text-[#616889]">
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


@endsection