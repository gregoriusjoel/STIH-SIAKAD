@extends('layouts.app')

@section('title', 'Input Nilai - ' . $class_info['name'])

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
@endpush

@section('content')
    <div class="pt-6 px-6 md:px-8 pb-8 w-full flex flex-col gap-6" 
         x-data="nilaiApp(@js($students), @js($bobot), @js($class_info))">

        <!-- Header -->
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-black text-[#111218] dark:text-white tracking-tight">Input Nilai Mahasiswa</h2>
                    <p class="text-base text-[#616889] dark:text-slate-400 mt-1">
                        <span x-text="classInfo.name"></span> (<span x-text="classInfo.code"></span>) - Kelas <span x-text="classInfo.section"></span>
                    </p>
                </div>
                <a href="{{ route('dosen.kelas.detail', $class_info['id']) }}"
                    class="flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-300 rounded-xl font-semibold text-sm hover:bg-gray-50 dark:hover:bg-slate-700 transition-all">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Bobot Penilaian Card -->
        <div class="bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-200 dark:border-slate-800 shadow-sm p-6"
             x-show="!bobotLocked">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-2xl text-primary">tune</span>
                    <h3 class="text-lg font-bold text-[#111218] dark:text-white">Pengaturan Bobot Penilaian</h3>
                </div>
                <span class="text-xs font-semibold text-amber-600 bg-amber-50 dark:bg-amber-900/20 px-3 py-1 rounded-full">
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

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
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

            <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-slate-700">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Total Bobot:</p>
                    <p class="text-2xl font-bold"
                       :class="totalBobot === 100 ? 'text-green-600' : 'text-red-600'"
                       x-text="totalBobot.toFixed(2) + '%'"></p>
                </div>
                <button @click="saveBobot" 
                        :disabled="totalBobot !== 100 || isSaving"
                        :class="totalBobot === 100 && !isSaving ? 'bg-primary hover:bg-primary-hover' : 'bg-gray-400 cursor-not-allowed'"
                        class="flex items-center gap-2 px-6 py-3 text-white rounded-xl font-bold text-sm shadow-lg transition-all">
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
        <div class="bg-white dark:bg-[#1a1d2e] rounded-2xl border border-gray-200 dark:border-slate-800 shadow-sm p-6"
             x-show="bobotLocked && editMode" x-cloak>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-2xl text-amber-600 dark:text-amber-400">edit_note</span>
                    <h3 class="text-lg font-bold text-[#111218] dark:text-white">Edit Bobot Penilaian</h3>
                </div>
                <span class="text-xs font-semibold text-amber-600 bg-amber-50 dark:bg-amber-900/20 px-3 py-1 rounded-full">
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

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
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

            <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-slate-700">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Total Bobot:</p>
                    <p class="text-2xl font-bold"
                       :class="totalBobot === 100 ? 'text-green-600' : 'text-red-600'"
                       x-text="totalBobot.toFixed(2) + '%'"></p>
                </div>
                <div class="flex gap-3">
                    <button @click="editMode = false" 
                            class="flex items-center gap-2 px-5 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-xl font-semibold text-sm transition-all">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                        Batal
                    </button>
                    <button @click="saveBobot" 
                            :disabled="totalBobot !== 100 || isSaving"
                            :class="totalBobot === 100 && !isSaving ? 'bg-primary hover:bg-primary-hover' : 'bg-gray-400 cursor-not-allowed'"
                            class="flex items-center gap-2 px-6 py-3 text-white rounded-xl font-bold text-sm shadow-lg transition-all">
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
            <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-2xl text-primary">edit_note</span>
                    <h3 class="text-lg font-bold text-[#111218] dark:text-white">Input Nilai Mahasiswa</h3>
                </div>
                <button @click="saveAllNilai"
                        :disabled="isSaving"
                        class="flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all">
                    <span class="material-symbols-outlined text-[20px]" x-show="!isSaving">save</span>
                    <span x-show="!isSaving">Simpan Semua Nilai</span>
                    <span x-show="isSaving">Menyimpan...</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                    <thead class="bg-gray-50 dark:bg-slate-800">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap">
                                No</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap">
                                NIM</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap min-w-[200px]">
                                Nama Mahasiswa</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap">
                                Partisipatif</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap">
                                Proyek</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap">
                                Quiz</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap">
                                Tugas</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap">
                                UTS</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider whitespace-nowrap">
                                UAS</th>
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
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white" x-text="index + 1"></td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white" x-text="student.nim"></td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white" x-text="student.name"></td>
                                <td class="px-4 py-3 text-center">
                                    <input type="number" 
                                           x-model.number="student.nilai_partisipatif"
                                           @input="validateGrade(student, 'nilai_partisipatif')"
                                           min="0" max="100" step="0.01"
                                           class="w-20 px-2 py-1 text-center border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-700 dark:text-white">
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <input type="number" 
                                           x-model.number="student.nilai_proyek"
                                           @input="validateGrade(student, 'nilai_proyek')"
                                           min="0" max="100" step="0.01"
                                           class="w-20 px-2 py-1 text-center border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-700 dark:text-white">
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <input type="number" 
                                           x-model.number="student.nilai_quiz"
                                           @input="validateGrade(student, 'nilai_quiz')"
                                           min="0" max="100" step="0.01"
                                           class="w-20 px-2 py-1 text-center border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-700 dark:text-white">
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <input type="number" 
                                           x-model.number="student.nilai_tugas"
                                           @input="validateGrade(student, 'nilai_tugas')"
                                           min="0" max="100" step="0.01"
                                           class="w-20 px-2 py-1 text-center border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-700 dark:text-white">
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <input type="number" 
                                           x-model.number="student.nilai_uts"
                                           @input="validateGrade(student, 'nilai_uts')"
                                           min="0" max="100" step="0.01"
                                           class="w-20 px-2 py-1 text-center border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-700 dark:text-white">
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <input type="number" 
                                           x-model.number="student.nilai_uas"
                                           @input="validateGrade(student, 'nilai_uas')"
                                           min="0" max="100" step="0.01"
                                           class="w-20 px-2 py-1 text-center border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-700 dark:text-white">
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
        function nilaiApp(studentsData, bobotData, classInfoData) {
            return {
                students: studentsData,
                bobot: bobotData,
                classInfo: classInfoData,
                bobotLocked: bobotData.is_locked,
                editMode: false,
                totalBobot: 0,
                isSaving: false,

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
                },

                async saveAllNilai() {
                    this.isSaving = true;

                    const nilaiData = this.students.map(student => ({
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
                        }
                    } catch (error) {
                        console.error('Error saving nilai:', error);
                        showError(error.response?.data?.message || 'Gagal menyimpan nilai.');
                    } finally {
                        this.isSaving = false;
                    }
                }
            }
        }
    </script>
    @endpush
@endsection
