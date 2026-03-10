@extends('layouts.app')

@section('title', 'Input Nilai Akhir - ' . $class_info['name'])

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
@endpush

@section('content')
    @section('navbar_breadcrumb')
        <nav class="flex items-center gap-3 text-sm text-white/60 font-bold tracking-tight">
            <a class="hover:text-white transition-all duration-300 flex items-center group" href="{{ route('dosen.dashboard') }}">
                <span class="material-symbols-outlined text-[19px] group-hover:scale-110 opacity-80">home</span>
            </a>
            <span class="material-symbols-outlined text-[10px] text-white/20 font-normal">play_arrow</span>
            <a href="{{ route('dosen.kelas') }}" class="hover:text-white transition-all duration-300">Kelas</a>
            <span class="material-symbols-outlined text-[10px] text-white/20 font-normal">play_arrow</span>
            <a href="{{ route('dosen.kelas.detail', $class_info['id']) }}" class="hover:text-white transition-all duration-300">{{ $class_info['name'] }}</a>
            <span class="material-symbols-outlined text-[10px] text-white/20 font-normal">play_arrow</span>
            <span class="text-white font-black text-[13px] uppercase tracking-wider">Input Nilai Akhir</span>
        </nav>
    @endsection

    <div class="pt-4 px-4 md:pt-6 md:px-8 pb-8 w-full flex flex-col gap-6" 
         x-data="nilaiApp()">

        <!-- Header -->
        <div class="flex flex-col gap-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl md:text-3xl font-black text-[#111218] dark:text-white tracking-tight">Nilai Akhir Mahasiswa</h2>
                    <p class="text-sm md:text-base text-[#616889] dark:text-slate-400 mt-1">
                        <span x-text="classInfo.name"></span> (<span x-text="classInfo.code"></span>) - Kelas <span x-text="classInfo.section"></span>
                    </p>
                </div>
                <a href="{{ route('dosen.kelas.detail', $class_info['id']) }}"
                    class="flex items-center justify-center w-full md:w-auto gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-300 rounded-xl font-semibold text-sm hover:bg-gray-50 dark:hover:bg-slate-700 transition-all">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    Kembali
                </a>
            </div>
        </div>

        {{-- Periode UTS/UAS Status Banner --}}
        @if(isset($periodStatuses))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach(['uts' => 'UTS', 'uas' => 'UAS'] as $key => $label)
                @php $ps = $periodStatuses[$key] ?? null; @endphp
                @if($ps)
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl border
                    {{ $ps['status'] === 'active' ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' : ($ps['status'] === 'upcoming' ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800' : 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800') }}">
                    <span class="material-symbols-outlined text-xl
                        {{ $ps['status'] === 'active' ? 'text-green-600 dark:text-green-400' : ($ps['status'] === 'upcoming' ? 'text-blue-600 dark:text-blue-400' : 'text-amber-600 dark:text-amber-400') }}">
                        {{ $ps['status'] === 'active' ? 'check_circle' : ($ps['status'] === 'upcoming' ? 'schedule' : 'lock') }}
                    </span>
                    <div class="flex-1">
                        <p class="text-sm font-bold {{ $ps['status'] === 'active' ? 'text-green-900 dark:text-green-200' : ($ps['status'] === 'upcoming' ? 'text-blue-900 dark:text-blue-200' : 'text-amber-900 dark:text-amber-200') }}">
                            Periode {{ $label }}: {{ $ps['label'] }}
                        </p>
                        <p class="text-xs {{ $ps['status'] === 'active' ? 'text-green-700 dark:text-green-300' : ($ps['status'] === 'upcoming' ? 'text-blue-700 dark:text-blue-300' : 'text-amber-700 dark:text-amber-300') }}">
                            {{ $ps['message'] }}
                        </p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                        {{ $ps['status'] === 'active' ? 'bg-green-200 text-green-800 dark:bg-green-800 dark:text-green-200' : ($ps['status'] === 'upcoming' ? 'bg-blue-200 text-blue-800 dark:bg-blue-800 dark:text-blue-200' : 'bg-amber-200 text-amber-800 dark:bg-amber-800 dark:text-amber-200') }}">
                        {{ $ps['label'] }}
                    </span>
                </div>
                @endif
            @endforeach
        </div>
        @endif

        <!-- Bobot Penilaian Card -->
        <div class="bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-200 dark:border-slate-800 shadow-sm p-4 md:p-6"
             x-show="!bobotLocked">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-2xl text-primary">tune</span>
                    <h3 class="text-lg font-bold text-[#111218] dark:text-white">Pengaturan Bobot Penilaian</h3>
                </div>
                <span class="self-start md:self-auto text-xs font-semibold text-amber-600 bg-amber-50 dark:bg-amber-900/20 px-3 py-1 rounded-full">
                    Belum Dikunci
                </span>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-6">
                <div class="flex gap-3">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">info</span>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-blue-900 dark:text-blue-200 mb-1">Perhatian!</p>
                        <p class="text-sm text-blue-800 dark:text-blue-300">
                            Atur bobot penilaian terlebih dahulu. Total bobot harus <strong>100%</strong>. 
                            Setelah disimpan, bobot akan <strong>terkunci</strong> dan tidak dapat diubah.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">
                        Partisipatif (%)</label>
                    <input type="number" x-model.number="bobot.bobot_partisipatif" @input="updateTotal"
                        min="0" max="100" step="0.01"
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Proyek (%)</label>
                    <input type="number" x-model.number="bobot.bobot_proyek" @input="updateTotal"
                        min="0" max="100" step="0.01"
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Quiz (%)</label>
                    <input type="number" x-model.number="bobot.bobot_quiz" @input="updateTotal"
                        min="0" max="100" step="0.01"
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Tugas (%)</label>
                    <input type="number" x-model.number="bobot.bobot_tugas" @input="updateTotal"
                        min="0" max="100" step="0.01"
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">UTS (%)</label>
                    <input type="number" x-model.number="bobot.bobot_uts" @input="updateTotal"
                        min="0" max="100" step="0.01"
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">UAS (%)</label>
                    <input type="number" x-model.number="bobot.bobot_uas" @input="updateTotal"
                        min="0" max="100" step="0.01"
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-800 dark:text-white">
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pt-4 border-t border-gray-200 dark:border-slate-700">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Total Bobot:</p>
                    <p class="text-2xl font-bold"
                       :class="totalBobot === 100 ? 'text-green-600' : 'text-red-600'"
                       x-text="totalBobot.toFixed(2) + '%'"></p>
                </div>
                <button @click="saveBobot" 
                        :disabled="totalBobot !== 100 || isSaving"
                        :class="totalBobot === 100 && !isSaving ? 'bg-primary hover:bg-primary-hover' : 'bg-gray-400 cursor-not-allowed'"
                        class="flex items-center justify-center w-full md:w-auto gap-2 px-6 py-3 text-white rounded-xl font-bold text-sm shadow-lg transition-all">
                    <span class="material-symbols-outlined text-[20px]" x-show="!isSaving">lock</span>
                    <span x-show="!isSaving">Simpan & Kunci Bobot</span>
                    <span x-show="isSaving">Menyimpan...</span>
                </button>
            </div>
        </div>

        <!-- Locked Bobot Info -->
        <div class="bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-200 dark:border-slate-800 shadow-sm p-6"
             x-show="bobotLocked && !editMode" x-cloak>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-900/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl text-green-600 dark:text-green-400">check_circle</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-[#111218] dark:text-white">Bobot Penilaian Terkunci</h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400">
                            Partisipatif: <span x-text="bobot.bobot_partisipatif"></span>% | 
                            Proyek: <span x-text="bobot.bobot_proyek"></span>% | 
                            Quiz: <span x-text="bobot.bobot_quiz"></span>% | 
                            Tugas: <span x-text="bobot.bobot_tugas"></span>% | 
                            UTS: <span x-text="bobot.bobot_uts"></span>% | 
                            UAS: <span x-text="bobot.bobot_uas"></span>%
                        </p>
                    </div>
                </div>
                <button @click="editMode = true"
                        class="flex items-center gap-2 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-semibold text-sm transition-all shadow-lg">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                    Edit Bobot
                </button>
            </div>
        </div>

        <!-- Edit Bobot Mode -->
        <div class="bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-200 dark:border-slate-800 shadow-sm p-4 md:p-6"
             x-show="bobotLocked && editMode" x-cloak>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-2xl text-amber-600 dark:text-amber-400">edit_note</span>
                    <h3 class="text-lg font-bold text-[#111218] dark:text-white">Edit Bobot Penilaian</h3>
                </div>
                <span class="self-start md:self-auto text-xs font-semibold text-amber-600 bg-amber-50 dark:bg-amber-900/20 px-3 py-1 rounded-full">
                    Mode Edit
                </span>
            </div>

            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4 mb-6">
                <div class="flex gap-3">
                    <span class="material-symbols-outlined text-amber-600 dark:text-amber-400">warning</span>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-amber-900 dark:text-amber-200 mb-1">Perhatian!</p>
                        <p class="text-sm text-amber-800 dark:text-amber-300">
                            Mengubah bobot akan <strong>otomatis menghitung ulang semua nilai mahasiswa</strong> yang sudah diinput.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">
                        Partisipatif (%)</label>
                    <input type="number" x-model.number="bobot.bobot_partisipatif" @input="updateTotal"
                        min="0" max="100" step="0.01"
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Proyek (%)</label>
                    <input type="number" x-model.number="bobot.bobot_proyek" @input="updateTotal"
                        min="0" max="100" step="0.01"
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Quiz (%)</label>
                    <input type="number" x-model.number="bobot.bobot_quiz" @input="updateTotal"
                        min="0" max="100" step="0.01"
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Tugas (%)</label>
                    <input type="number" x-model.number="bobot.bobot_tugas" @input="updateTotal"
                        min="0" max="100" step="0.01"
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">UTS (%)</label>
                    <input type="number" x-model.number="bobot.bobot_uts" @input="updateTotal"
                        min="0" max="100" step="0.01"
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">UAS (%)</label>
                    <input type="number" x-model.number="bobot.bobot_uas" @input="updateTotal"
                        min="0" max="100" step="0.01"
                        class="block w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-slate-800 dark:text-white">
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pt-4 border-t border-gray-200 dark:border-slate-700">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Total Bobot:</p>
                    <p class="text-2xl font-bold"
                       :class="totalBobot === 100 ? 'text-green-600' : 'text-red-600'"
                       x-text="totalBobot.toFixed(2) + '%'"></p>
                </div>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <button @click="editMode = false" 
                            class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-xl font-semibold text-sm transition-all shadow-lg">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                        Batal
                    </button>
                    <button @click="saveBobot" 
                            :disabled="totalBobot !== 100 || isSaving"
                            :class="totalBobot === 100 && !isSaving ? 'bg-primary hover:bg-primary-hover' : 'bg-gray-400 cursor-not-allowed'"
                            class="flex-1 md:flex-none flex items-center justify-center gap-2 px-6 py-2.5 text-white rounded-xl font-bold text-sm shadow-lg transition-all">
                        <span class="material-symbols-outlined text-[20px]" x-show="!isSaving">save</span>
                        <span x-show="!isSaving">Update Bobot</span>
                        <span x-show="isSaving">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Nilai Table -->
        <div class="bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-200 dark:border-slate-800 shadow-sm overflow-hidden"
             x-show="bobotLocked" x-cloak>
            <div class="px-4 py-4 md:px-6 nav-header border-b border-gray-200 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-2xl text-primary">edit_note</span>
                    <h3 class="text-lg font-bold text-[#111218] dark:text-white">Input Nilai Mahasiswa</h3>
                </div>
                <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                    <button @click="tarikSemuaNilai"
                            :disabled="isSaving || !hasPublishedGrades"
                            x-show="hasPublishedGrades"
                            x-transition
                            class="flex items-center justify-center w-full md:w-auto gap-2 px-5 py-2.5 bg-red-50 text-red-600 border border-red-200 rounded-xl font-bold text-sm hover:bg-red-100 transition-all">
                        <span class="material-symbols-outlined text-[20px]" x-show="!isSaving">undo</span>
                        <span x-show="!isSaving">Tarik Semua Nilai</span>
                        <span x-show="isSaving">Memproses...</span>
                    </button>

                    {{-- Import / Export Buttons --}}
                    <a href="{{ route('dosen.kelas.nilai-template', $class_info['id']) }}"
                       class="flex items-center justify-center w-full md:w-auto gap-2 px-4 py-2.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl font-semibold text-sm hover:bg-emerald-100 transition-all">
                        <span class="material-symbols-outlined text-[18px]">download</span>
                        Download Template
                    </a>
                    <button @click="showImportModal = true"
                            class="flex items-center justify-center w-full md:w-auto gap-2 px-4 py-2.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-xl font-semibold text-sm hover:bg-blue-100 transition-all">
                        <span class="material-symbols-outlined text-[18px]">upload_file</span>
                        Import Nilai
                    </button>
                    
                    <!-- Auto-save spinner indicator (outside button) -->
                    <div x-show="isAutoSaving" 
                         x-transition
                         class="flex items-center justify-center gap-2 px-4 py-2.5 bg-primary/10 text-primary border border-primary/20 rounded-xl font-semibold text-sm">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Menyimpan otomatis...</span>
                    </div>

                    <button @click="saveAllNilai"
                            :disabled="isSaving"
                            class="flex items-center justify-center w-full md:w-auto gap-2 px-5 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all">
                        <span class="material-symbols-outlined text-[20px]" x-show="!isSaving">save</span>
                        <span x-show="!isSaving">Simpan Semua Nilai</span>
                        <span x-show="isSaving">Menyimpan...</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 dark:divide-slate-700">
                    <thead class="bg-gray-50 dark:bg-slate-800">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap">
                                No</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap">
                                NIM</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap w-full min-w-[200px]">
                                Nama Mahasiswa</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap">
                                Partisipatif</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap">
                                Proyek</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap">
                                Quiz</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap">
                                Tugas</th>
                            <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider whitespace-nowrap"
                                :class="periodStatuses.uts.status === 'active' ? 'text-green-700 dark:text-green-400' : (periodStatuses.uts.status === 'closed' ? 'text-amber-600 dark:text-amber-400' : 'text-gray-700 dark:text-slate-300')">
                                <div class="flex items-center justify-center gap-1">
                                    UTS
                                    <span x-show="periodStatuses.uts.status === 'active'" class="material-symbols-outlined text-[14px] text-green-500">lock_open</span>
                                    <span x-show="periodStatuses.uts.status === 'closed'" class="material-symbols-outlined text-[14px] text-amber-500">lock</span>
                                </div>
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider whitespace-nowrap"
                                :class="periodStatuses.uas.status === 'active' ? 'text-green-700 dark:text-green-400' : (periodStatuses.uas.status === 'closed' ? 'text-amber-600 dark:text-amber-400' : 'text-gray-700 dark:text-slate-300')">
                                <div class="flex items-center justify-center gap-1">
                                    UAS
                                    <span x-show="periodStatuses.uas.status === 'active'" class="material-symbols-outlined text-[14px] text-green-500">lock_open</span>
                                    <span x-show="periodStatuses.uas.status === 'closed'" class="material-symbols-outlined text-[14px] text-amber-500">lock</span>
                                </div>
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-primary uppercase tracking-wider whitespace-nowrap">
                                Nilai Akhir</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-primary uppercase tracking-wider whitespace-nowrap">
                                Grade</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-primary uppercase tracking-wider whitespace-nowrap">
                                Bobot</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-[#1a1d2e] divide-y divide-gray-200 dark:divide-slate-700">
                        <template x-for="(student, index) in students" :key="student.krs_id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors"
                                :class="student.is_internship ? 'bg-orange-50/40 dark:bg-orange-900/10' : ''"
                            >
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white" x-text="index + 1"></td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white" x-text="student.nim"></td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                    <div class="flex items-center gap-2">
                                        <span x-text="student.name"></span>
                                        <template x-if="student.is_internship">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-300">Magang</span>
                                        </template>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <template x-if="!student.is_internship">
                                        <input type="number" 
                                               x-model.number="student.nilai_partisipatif"
                                               @input="validateGrade(student, 'nilai_partisipatif')"
                                               min="0" max="100" step="0.01"
                                               class="w-20 px-2 py-1 text-center border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-700 dark:text-white">
                                    </template>
                                    <template x-if="student.is_internship">
                                        <span class="text-xs text-gray-400 dark:text-slate-500 italic">—</span>
                                    </template>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <template x-if="!student.is_internship">
                                        <input type="number" 
                                               x-model.number="student.nilai_proyek"
                                               @input="validateGrade(student, 'nilai_proyek')"
                                               min="0" max="100" step="0.01"
                                               class="w-20 px-2 py-1 text-center border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-700 dark:text-white">
                                    </template>
                                    <template x-if="student.is_internship">
                                        <span class="text-xs text-gray-400 dark:text-slate-500 italic">—</span>
                                    </template>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <template x-if="!student.is_internship">
                                        <input type="number" 
                                               x-model.number="student.nilai_quiz"
                                               @input="validateGrade(student, 'nilai_quiz')"
                                               min="0" max="100" step="0.01"
                                               class="w-20 px-2 py-1 text-center border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-700 dark:text-white">
                                    </template>
                                    <template x-if="student.is_internship">
                                        <span class="text-xs text-gray-400 dark:text-slate-500 italic">—</span>
                                    </template>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <template x-if="!student.is_internship">
                                        <input type="number" 
                                               x-model.number="student.nilai_tugas"
                                               @input="validateGrade(student, 'nilai_tugas')"
                                               min="0" max="100" step="0.01"
                                               class="w-20 px-2 py-1 text-center border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-700 dark:text-white">
                                    </template>
                                    <template x-if="student.is_internship">
                                        <span class="text-xs text-gray-400 dark:text-slate-500 italic">—</span>
                                    </template>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <template x-if="!student.is_internship">
                                        <input type="number" 
                                               x-model.number="student.nilai_uts"
                                               @input="validateGrade(student, 'nilai_uts')"
                                               min="0" max="100" step="0.01"
                                               class="w-20 px-2 py-1 text-center rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-700 dark:text-white"
                                               :class="periodStatuses.uts.status === 'active' ? 'border border-green-400 dark:border-green-600' : (periodStatuses.uts.status === 'closed' ? 'border border-amber-400 dark:border-amber-600 bg-amber-50/50 dark:bg-amber-900/10' : 'border border-gray-300 dark:border-slate-600')">
                                    </template>
                                    <template x-if="student.is_internship">
                                        <span class="text-xs text-gray-400 dark:text-slate-500 italic">—</span>
                                    </template>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <template x-if="!student.is_internship">
                                        <input type="number" 
                                               x-model.number="student.nilai_uas"
                                               @input="validateGrade(student, 'nilai_uas')"
                                               min="0" max="100" step="0.01"
                                               class="w-20 px-2 py-1 text-center rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-700 dark:text-white"
                                               :class="periodStatuses.uas.status === 'active' ? 'border border-green-400 dark:border-green-600' : (periodStatuses.uas.status === 'closed' ? 'border border-amber-400 dark:border-amber-600 bg-amber-50/50 dark:bg-amber-900/10' : 'border border-gray-300 dark:border-slate-600')">
                                    </template>
                                    <template x-if="student.is_internship">
                                        <span class="text-xs text-gray-400 dark:text-slate-500 italic">—</span>
                                    </template>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-sm font-bold text-primary" x-text="student.nilai_akhir.toFixed(2)"></span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold"
                                          :class="getGradeColor(student.grade)"
                                          x-text="student.grade"></span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white" x-text="student.bobot.toFixed(2)"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ═══ Import Nilai Modal ═══ --}}
        <div x-show="showImportModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" @click.self="showImportModal = false" x-cloak>
            <div class="bg-white dark:bg-[#1a1d2e] rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden" @click.stop>
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">upload_file</span>
                        Import Nilai dari CSV
                    </h3>
                    <button @click="showImportModal = false" class="text-gray-400 hover:text-gray-600">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                        <p class="text-sm text-blue-800 dark:text-blue-300 font-medium mb-2">Petunjuk:</p>
                        <ol class="text-xs text-blue-700 dark:text-blue-400 space-y-1 list-decimal list-inside">
                            <li>Download template CSV terlebih dahulu (tombol "Download Template")</li>
                            <li>Isi kolom nilai (0-100) untuk setiap mahasiswa berdasarkan NIM</li>
                            <li>Kolom NIM dan Nama Mahasiswa sudah terisi otomatis, <strong>jangan ubah NIM</strong></li>
                            <li>Simpan file sebagai CSV, lalu upload di sini</li>
                        </ol>
                    </div>

                    <div class="border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-xl p-6 text-center"
                         :class="importFile ? 'border-primary bg-primary/5' : ''">
                        <input type="file" accept=".csv,.txt" @change="importFile = $event.target.files[0]" x-ref="importFileInput" class="hidden">
                        <div x-show="!importFile" class="space-y-2">
                            <span class="material-symbols-outlined text-4xl text-gray-400">cloud_upload</span>
                            <p class="text-sm text-gray-600 dark:text-slate-400">Klik untuk pilih file CSV</p>
                            <button @click="$refs.importFileInput.click()" type="button"
                                    class="px-4 py-2 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 rounded-lg text-sm font-medium text-gray-700 dark:text-slate-300 transition">
                                Pilih File
                            </button>
                        </div>
                        <div x-show="importFile" class="space-y-2">
                            <span class="material-symbols-outlined text-4xl text-primary">description</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="importFile?.name"></p>
                            <button @click="importFile = null; $refs.importFileInput.value = ''" type="button"
                                    class="text-xs text-red-500 hover:text-red-700">Hapus</button>
                        </div>
                    </div>

                    {{-- Import result --}}
                    <div x-show="importResult" x-transition class="rounded-xl p-4 text-sm"
                         :class="importResult?.success ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 text-green-800 dark:text-green-300' : 'bg-red-50 dark:bg-red-900/20 border border-red-200 text-red-800 dark:text-red-300'">
                        <p class="font-semibold" x-text="importResult?.message"></p>
                        <template x-if="importResult?.errors?.length > 0">
                            <ul class="mt-2 space-y-0.5 text-xs list-disc list-inside">
                                <template x-for="err in importResult.errors" :key="err">
                                    <li x-text="err"></li>
                                </template>
                            </ul>
                        </template>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700 flex justify-end gap-3">
                    <button @click="showImportModal = false"
                            class="px-4 py-2.5 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-xl font-semibold text-sm hover:bg-gray-200 dark:hover:bg-slate-600 transition">
                        Tutup
                    </button>
                    <button @click="executeImport()"
                            :disabled="!importFile || isImporting"
                            :class="!importFile || isImporting ? 'bg-gray-300 dark:bg-slate-600 cursor-not-allowed' : 'bg-primary hover:bg-primary-hover shadow-lg shadow-primary/20'"
                            class="flex items-center gap-2 px-5 py-2.5 text-white rounded-xl font-bold text-sm transition-all">
                        <svg x-show="isImporting" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-show="!isImporting">Upload & Import</span>
                        <span x-show="isImporting">Mengimpor...</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div class="bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-200 dark:border-slate-800 shadow-sm p-12 text-center"
             x-show="!bobotLocked" x-cloak>
            <div class="w-20 h-20 bg-gray-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-4xl text-gray-400">lock_open</span>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Bobot Penilaian Belum Dikunci</h3>
            <p class="text-gray-600 dark:text-slate-400">
                Silakan atur dan kunci bobot penilaian terlebih dahulu sebelum menginput nilai mahasiswa.
            </p>
        </div>

    </div>

    @push('scripts')
    <script>
        function nilaiApp() {
            return {
                students: @json($students),
                bobot: @json($bobot),
                classInfo: @json($class_info),
                periodStatuses: @json($periodStatuses),
                bobotLocked: {{ $bobot->is_locked ? 'true' : 'false' }},
                hasPublishedGrades: {{ $has_published_grades ? 'true' : 'false' }},
                editMode: false,
                totalBobot: 0,
                isSaving: false,
                isAutoSaving: false,
                saveTimeout: null,
                showImportModal: false,
                importFile: null,
                isImporting: false,
                importResult: null,

                init() {
                    this.updateTotal();
                    // Calculate initial values for all students
                    this.students.forEach(student => {
                        // Clean up initial values to remove .00
                        ['nilai_partisipatif', 'nilai_proyek', 'nilai_quiz', 'nilai_tugas', 'nilai_uts', 'nilai_uas'].forEach(field => {
                            if (student[field] !== null && student[field] !== undefined) {
                                student[field] = parseFloat(student[field]);
                            }
                        });
                        
                        this.calculateFinal(student);
                    });
                },

                updateTotal() {
                    this.totalBobot = parseFloat(this.bobot.bobot_partisipatif) +
                                     parseFloat(this.bobot.bobot_proyek) +
                                     parseFloat(this.bobot.bobot_quiz) +
                                     parseFloat(this.bobot.bobot_tugas) +
                                     parseFloat(this.bobot.bobot_uts) +
                                     parseFloat(this.bobot.bobot_uas);
                },

                async saveBobot() {
                    if (this.totalBobot !== 100) {
                        showError('Total bobot harus 100%');
                        return;
                    }

                    this.isSaving = true;

                    try {
                        const response = await axios.post(
                            `{{ route('dosen.kelas.bobot-penilaian.save', $class_info['id']) }}`,
                            this.bobot
                        );

                        if (response.data.success) {
                            this.bobotLocked = true;
                            this.editMode = false; // Close edit mode
                            
                            // Show appropriate message
                            showSuccess(response.data.message);
                            
                            // If grades were recalculated, refresh student data
                            if (response.data.recalculated) {
                                this.students.forEach(student => {
                                    this.calculateFinal(student);
                                });
                            }
                        }
                    } catch (error) {
                        console.error('Error saving bobot:', error);
                        showError(error.response?.data?.message || 'Gagal menyimpan bobot penilaian.');
                    } finally {
                        this.isSaving = false;
                    }
                },

                calculateFinal(student) {
                    // Calculate nilai akhir
                    const nilaiAkhir = 
                        (parseFloat(student.nilai_partisipatif) || 0) * (parseFloat(this.bobot.bobot_partisipatif) / 100) +
                        (parseFloat(student.nilai_proyek) || 0) * (parseFloat(this.bobot.bobot_proyek) / 100) +
                        (parseFloat(student.nilai_quiz) || 0) * (parseFloat(this.bobot.bobot_quiz) / 100) +
                        (parseFloat(student.nilai_tugas) || 0) * (parseFloat(this.bobot.bobot_tugas) / 100) +
                        (parseFloat(student.nilai_uts) || 0) * (parseFloat(this.bobot.bobot_uts) / 100) +
                        (parseFloat(student.nilai_uas) || 0) * (parseFloat(this.bobot.bobot_uas) / 100);

                    student.nilai_akhir = nilaiAkhir;

                    // Convert to grade
                    const gradeData = this.convertToGrade(nilaiAkhir);
                    student.grade = gradeData.grade;
                    student.bobot = gradeData.bobot;
                },

                convertToGrade(nilai) {
                    if (nilai >= 80) return { grade: 'A', bobot: 4.00 };
                    if (nilai >= 76) return { grade: 'A-', bobot: 3.67 };
                    if (nilai >= 72) return { grade: 'B+', bobot: 3.33 };
                    if (nilai >= 68) return { grade: 'B', bobot: 3.00 };
                    if (nilai >= 64) return { grade: 'B-', bobot: 2.67 };
                    if (nilai >= 60) return { grade: 'C+', bobot: 2.33 };
                    if (nilai >= 56) return { grade: 'C', bobot: 2.00 };
                    if (nilai >= 45) return { grade: 'D', bobot: 1.00 };
                    return { grade: 'E', bobot: 0.00 };
                },

                getGradeColor(grade) {
                    const colors = {
                        'A': 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                        'A-': 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                        'B+': 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                        'B': 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                        'B-': 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
                        'C+': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                        'C': 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                        'D': 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                        'E': 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
                    };
                    return colors[grade] || 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400';
                },

                validateGrade(student, field) {
                    let value = parseFloat(student[field]);
                    
                    if (isNaN(value)) {
                        value = 0;
                    }

                    if (value > 100) {
                        value = 100;
                    }
                    if (value < 0) {
                        value = 0;
                    }

                    // Round to max 2 decimals, keeps as number so 90.00 becomes 90
                    value = Math.round(value * 100) / 100;
                    
                    student[field] = value;

                    // Calculate final grade immediately
                    this.calculateFinal(student);
                    
                    // Trigger auto-save debounce
                    this.autoSave();
                },

                autoSave() {
                    if (this.saveTimeout) {
                        clearTimeout(this.saveTimeout);
                    }
                    this.isAutoSaving = true;
                    this.saveTimeout = setTimeout(() => {
                        this.executeAutoSave();
                    }, 1000); // 1-second debounce
                },

                async executeAutoSave() {
                    const nilaiData = this.students.filter(s => !s.is_internship).map(student => ({
                        krs_id: student.krs_id,
                        nilai_partisipatif: parseFloat(student.nilai_partisipatif) || 0,
                        nilai_proyek: parseFloat(student.nilai_proyek) || 0,
                        nilai_quiz: parseFloat(student.nilai_quiz) || 0,
                        nilai_tugas: parseFloat(student.nilai_tugas) || 0,
                        nilai_uts: parseFloat(student.nilai_uts) || 0,
                        nilai_uas: parseFloat(student.nilai_uas) || 0,
                    }));

                    try {
                        await axios.post(
                            `{{ route('dosen.kelas.simpan-nilai', $class_info['id']) }}`,
                            { nilai: nilaiData, is_auto_save: true }
                        );
                        // Silently succeed
                    } catch (error) {
                        console.error('Error auto-saving:', error);
                    } finally {
                        this.isAutoSaving = false;
                    }
                },

                async tarikSemuaNilai() {
                    if (!confirm('Apakah Anda yakin ingin menarik/membatalkan publikasi nilai untuk kelas ini? Nilai akan disembunyikan dari mahasiswa.')) {
                        return;
                    }

                    this.isSaving = true;

                    try {
                        const response = await axios.post(
                            `{{ route('dosen.kelas.tarik-nilai', $class_info['id']) }}`
                        );

                        if (response.data.success) {
                            showSuccess(response.data.message);
                            this.hasPublishedGrades = false;
                        }
                    } catch (error) {
                        console.error('Error unpublishing nilai:', error);
                        showError(error.response?.data?.message || 'Gagal menarik nilai.');
                    } finally {
                        this.isSaving = false;
                    }
                },

                async saveAllNilai() {
                    this.isSaving = true;

                    const nilaiData = this.students.filter(s => !s.is_internship).map(student => ({
                        krs_id: student.krs_id,
                        nilai_partisipatif: parseFloat(student.nilai_partisipatif) || 0,
                        nilai_proyek: parseFloat(student.nilai_proyek) || 0,
                        nilai_quiz: parseFloat(student.nilai_quiz) || 0,
                        nilai_tugas: parseFloat(student.nilai_tugas) || 0,
                        nilai_uts: parseFloat(student.nilai_uts) || 0,
                        nilai_uas: parseFloat(student.nilai_uas) || 0,
                    }));

                    try {
                        const response = await axios.post(
                            `{{ route('dosen.kelas.simpan-nilai', $class_info['id']) }}`,
                            { nilai: nilaiData }
                        );

                        if (response.data.success) {
                            showSuccess(response.data.message);
                            this.hasPublishedGrades = true;

                            // Show period warnings if any
                            if (response.data.period_warnings && response.data.period_warnings.length > 0) {
                                setTimeout(() => {
                                    showWarning(response.data.period_warnings.join('. '));
                                }, 1500);
                            }
                        }
                    } catch (error) {
                        console.error('Error saving nilai:', error);
                        showError(error.response?.data?.message || 'Gagal menyimpan nilai.');
                    } finally {
                        this.isSaving = false;
                    }
                },

                async executeImport() {
                    if (!this.importFile) return;
                    this.isImporting = true;
                    this.importResult = null;

                    const formData = new FormData();
                    formData.append('file', this.importFile);

                    try {
                        const response = await axios.post(
                            `{{ route('dosen.kelas.import-nilai', $class_info['id']) }}`,
                            formData,
                            { headers: { 'Content-Type': 'multipart/form-data' } }
                        );

                        this.importResult = response.data;

                        if (response.data.success && response.data.students) {
                            // Live-update the table with imported data
                            response.data.students.forEach(imported => {
                                const student = this.students.find(s => s.krs_id === imported.krs_id);
                                if (student) {
                                    student.nilai_partisipatif = imported.nilai_partisipatif;
                                    student.nilai_proyek = imported.nilai_proyek;
                                    student.nilai_quiz = imported.nilai_quiz;
                                    student.nilai_tugas = imported.nilai_tugas;
                                    student.nilai_uts = imported.nilai_uts;
                                    student.nilai_uas = imported.nilai_uas;
                                    student.nilai_akhir = imported.nilai_akhir;
                                    student.grade = imported.grade;
                                    student.bobot = imported.bobot;
                                }
                            });

                            showSuccess(response.data.message);
                        }
                    } catch (error) {
                        console.error('Error importing nilai:', error);
                        this.importResult = {
                            success: false,
                            message: error.response?.data?.message || 'Gagal mengimpor nilai.',
                            errors: error.response?.data?.errors || [],
                        };
                        showError(this.importResult.message);
                    } finally {
                        this.isImporting = false;
                        this.importFile = null;
                        if (this.$refs.importFileInput) this.$refs.importFileInput.value = '';
                    }
                }
            }
        }
    </script>
    @endpush
@endsection
