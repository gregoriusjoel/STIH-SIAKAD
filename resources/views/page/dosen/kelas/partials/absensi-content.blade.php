<div class="space-y-6 font-sans">
    <!-- Header -->
    <div
        class="bg-[#8B1538] text-white p-5 rounded-xl shadow-lg flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold">Absensi Kegiatan</h1>
            <p class="text-white/80 text-sm mt-1">{{ $class_info['topic'] }} untuk Kelas {{ $class_info['section'] }}
            </p>
        </div>
        <div class="flex gap-3">
            <button
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition-colors shadow-sm">
                <i class="fas fa-file-export"></i> Laporan
            </button>
            <a href="{{ route('dosen.kelas') }}"
                class="bg-red-700/50 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition-colors shadow-sm border border-red-500/30">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- LEFT COLUMN -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Info Kegiatan -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50">
                    <h3 class="font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                        <i class="fas fa-info-circle text-[#8B1538]"></i> Info Kegiatan
                    </h3>
                </div>
                <div class="p-4 space-y-4">
                    <div>
                        <p class="text-xs text-gray-500">Tipe Kegiatan</p>
                        <p class="font-medium text-gray-800 dark:text-gray-200">Perkuliahan Offline</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Mata Kuliah</p>
                        <p class="font-medium text-gray-800 dark:text-gray-200">{{ $class_info['name'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Tempat</p>
                        <p class="font-medium text-gray-800 dark:text-gray-200">{{ $class_info['room'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Waktu</p>
                        <p class="font-medium text-gray-800 dark:text-gray-200">{{ $class_info['date'] }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $class_info['time'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Status</p>
                        <span
                            class="inline-block px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-bold mt-1">
                            Terlaksana
                        </span>
                    </div>
                </div>
            </div>

            <!-- PIC Sekolah -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50">
                    <h3 class="font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                        <i class="fas fa-user-tie text-[#8B1538]"></i> PIC Sekolah
                    </h3>
                </div>
                <div class="p-4 space-y-3">
                    <div>
                        <p class="text-xs text-gray-500">Nama</p>
                        <p class="font-medium text-gray-800 dark:text-gray-200">{{ $class_info['dosen_name'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Jabatan</p>
                        <p class="font-medium text-gray-800 dark:text-gray-200">Dosen Pengampu</p>
                    </div>
                </div>
            </div>

            <!-- QR Code -->
            <div x-data="{ qrActive: true }"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div
                    class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50 text-center">
                    <h3 class="font-bold text-[#8B1538] dark:text-red-400 flex items-center justify-center gap-2">
                        <i class="fas fa-qrcode"></i> QR Code Absensi
                    </h3>
                </div>
                <div class="p-6 flex flex-col items-center">
                    <div class="bg-white p-2 rounded-lg border border-gray-200 mb-4 relative overflow-hidden">
                        <div x-show="!qrActive" x-transition.opacity
                            class="absolute inset-0 bg-gray-100/90 dark:bg-gray-800/90 flex flex-col items-center justify-center z-10 backdrop-blur-sm">
                            <i class="fas fa-lock text-3xl text-gray-400 mb-2"></i>
                            <span class="text-xs font-bold text-gray-500">QR Nonaktif</span>
                        </div>
                        <img :class="!qrActive ? 'opacity-20 blur-sm grayscale' : ''"
                            src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ route('dosen.kelas.detail', $id) }}"
                            alt="QR Code" class="w-40 h-40 transition-all duration-300">
                    </div>
                    <p class="text-xs text-gray-500 mb-4 text-center">Scan QR code untuk absen</p>
                    <button
                        class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg text-sm font-bold flex items-center justify-center gap-2 transition-colors mb-2">
                        <i class="fas fa-download"></i> Download QR
                    </button>

                    <button @click="qrActive = !qrActive"
                        :class="qrActive ? 'bg-red-500 hover:bg-red-600' : 'bg-blue-500 hover:bg-blue-600'"
                        class="w-full text-white py-2 rounded-lg text-sm font-bold flex items-center justify-center gap-2 transition-colors">
                        <i class="fas" :class="qrActive ? 'fa-ban' : 'fa-check-circle'"></i>
                        <span x-text="qrActive ? 'Nonaktifkan QR' : 'Aktifkan QR'"></span>
                    </button>
                </div>
            </div>

            <!-- Link Absensi -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50">
                    <h3 class="font-bold text-[#8B1538] dark:text-red-400 flex items-center gap-2">
                        <i class="fas fa-link"></i> Link Absensi
                    </h3>
                </div>
                <div class="p-4">
                    <div class="flex gap-2">
                        <input type="text" value="{{ route('dosen.kelas.detail', $id) }}" readonly
                            class="w-full text-xs bg-gray-50 border border-gray-200 rounded px-2 py-2 text-gray-600 focus:outline-none focus:border-[#8B1538]">
                        <button class="bg-[#8B1538] text-white px-3 rounded hover:bg-[#7A1231] transition-colors">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="lg:col-span-8 space-y-6 flex flex-col h-full" x-data="{
            students: {{ json_encode($students) }},
            searchText: '',
            currentPage: 1,
            pageSize: 16,
            get filteredStudents() {
                if (this.searchText === '') return this.students;
                const search = this.searchText.toLowerCase();
                return this.students.filter(s => 
                    s.name.toLowerCase().includes(search) || 
                    s.npm.toString().includes(search)
                );
            },
            get paginatedStudents() {
                let start = (this.currentPage - 1) * this.pageSize;
                let end = start + this.pageSize;
                return this.filteredStudents.slice(start, end);
            },
            get totalPages() {
                return Math.ceil(this.filteredStudents.length / this.pageSize) || 1;
            },
            nextPage() {
                if (this.currentPage < this.totalPages) this.currentPage++;
            },
            prevPage() {
                if (this.currentPage > 1) this.currentPage--;
            },
            formatStatus(status) {
                return status.charAt(0).toUpperCase() + status.slice(1);
            }
        }">
            <!-- Daftar Hadir -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden h-full flex flex-col">
                <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="font-bold text-[#8B1538] dark:text-red-400 flex items-center gap-2">
                        <i class="fas fa-users"></i> Daftar Hadir
                    </h3>
                    <div class="flex gap-2">
                        <button
                            class="text-xs bg-green-500 text-white px-3 py-1.5 rounded hover:bg-green-600 flex items-center gap-1">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>
                        <button
                            class="text-xs bg-red-500 text-white px-3 py-1.5 rounded hover:bg-red-600 flex items-center gap-1">
                            <i class="fas fa-file-pdf"></i> PDF
                        </button>
                    </div>
                </div>

                <div class="p-4 flex-1 flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            Tampilkan
                            <select x-model="pageSize" @change="currentPage = 1"
                                class="border border-gray-300 rounded px-2 py-1 text-xs focus:ring-[#8B1538] focus:border-[#8B1538]">
                                <option value="16">16</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                            data
                        </div>
                        <div class="relative">
                            <input type="text" placeholder="Cari..." x-model="searchText" @input="currentPage = 1"
                                class="pl-8 pr-4 py-1.5 border border-gray-300 rounded text-sm focus:ring-[#8B1538] focus:border-[#8B1538] w-48">
                            <i class="fas fa-search absolute left-2.5 top-2 text-gray-400 text-xs"></i>
                        </div>
                    </div>

                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-sm text-left">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0">
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Nama</th>
                                    <th class="px-4 py-3">Kelas</th>
                                    <th class="px-4 py-3">Kontak</th>
                                    <th class="px-4 py-3">Waktu</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(student, index) in paginatedStudents" :key="student.id">
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-4 py-3 font-medium"
                                            x-text="(currentPage - 1) * pageSize + index + 1"></td>
                                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                            <span x-text="student.name"></span>
                                            <div class="text-xs text-gray-500" x-text="student.npm"></div>
                                        </td>
                                        <td class="px-4 py-3">{{ $class_info['section'] }}</td>
                                        <td class="px-4 py-3" x-text="student.phone"></td>
                                        <td class="px-4 py-3">
                                            <template x-if="student.time != '-'">
                                                <span
                                                    class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300"
                                                    x-text="student.time"></span>
                                            </template>
                                            <template x-if="student.time == '-'">
                                                <span
                                                    class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">-</span>
                                            </template>
                                        </td>
                                        <td class="px-4 py-3">
                                            <template x-if="student.status == 'hadir'">
                                                <span
                                                    class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Hadir</span>
                                            </template>
                                            <template x-if="student.status == 'terlambat'">
                                                <span
                                                    class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Terlambat</span>
                                            </template>
                                            <template x-if="student.status != 'hadir' && student.status != 'terlambat'">
                                                <span
                                                    class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300"
                                                    x-text="formatStatus(student.status)"></span>
                                            </template>
                                        </td>
                                        <td class="px-4 py-3">
                                            <button
                                                class="text-white bg-red-500 hover:bg-red-600 rounded-lg px-3 py-1.5 text-xs transition-colors shadow-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="filteredStudents.length === 0">
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                        Tidak ada data ditemukan
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Controls -->
                    <div class="pt-4 flex items-center justify-between border-t border-gray-100 dark:border-gray-700"
                        x-show="totalPages > 1">
                        <span class="text-xs text-gray-500">
                            Halaman <span class="font-bold text-gray-700 dark:text-gray-300"
                                x-text="currentPage"></span> dari <span x-text="totalPages"></span>
                        </span>
                        <div class="flex gap-1">
                            <button @click="prevPage" :disabled="currentPage === 1"
                                class="px-3 py-1 border border-gray-300 rounded text-xs hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors dark:border-gray-600 dark:text-gray-300">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button @click="nextPage" :disabled="currentPage === totalPages"
                                class="px-3 py-1 border border-gray-300 rounded text-xs hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors dark:border-gray-600 dark:text-gray-300">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>