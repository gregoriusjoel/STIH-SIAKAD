@extends('layouts.admin')

@section('title', 'Kalender Akademik')

@section('breadcrumbs')
    <a href="@if(Route::has('admin.dashboard')){{ route('admin.dashboard') }}@else{{ url('/admin') }}@endif"
        class="mr-2 muted">Home</a>
    <i class="fas fa-chevron-right text-xs mr-2"></i>
    @if(Route::has('admin.akademik.index'))
        <a href="{{ route('admin.akademik.index') }}" class="mr-2 muted">Akademik</a>
    @else
        <span class="mr-2 muted">Akademik</span>
    @endif
    <i class="fas fa-chevron-right text-xs mr-2"></i>
    <span class="font-semibold">Kalender Akademik</span>
@endsection

@section('content')
    <div class="h-full flex flex-col bg-slate-50 dark:bg-gray-900">
        <!-- Header -->
        <div class="mb-6 px-1 relative z-20">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 dark:text-gray-100 tracking-tight">Kalender Akademik
                    </h2>
                    <p class="text-slate-500 dark:text-gray-400 mt-1 text-sm font-medium">
                        Kelola jadwal semester, periode KRS, dan agenda kegiatan.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 items-center w-full md:w-auto ml-auto">
                    <div class="relative group w-full sm:w-auto">
                        <select id="filterSemester"
                            class="appearance-none bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 text-slate-700 dark:text-gray-200 text-sm rounded-xl shadow-sm focus:ring-2 focus:ring-maroon/20 focus:border-maroon block w-full pl-4 pr-10 py-2.5 transition-all duration-200 hover:border-maroon/30 cursor-pointer font-semibold">
                            <option value="">-- Semua Semester --</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->nama_semester }} {{ $semester->tahun_ajaran }}
                                </option>
                            @endforeach
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500 dark:text-gray-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>

                    <div class="flex gap-3 w-full sm:w-auto">
                        <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                            class="flex-1 sm:flex-none justify-center px-5 py-2.5 bg-white dark:bg-gray-800 text-slate-700 dark:text-gray-300 hover:text-maroon dark:hover:text-red-400 hover:bg-slate-50 dark:hover:bg-gray-700 border border-slate-200 dark:border-gray-700 rounded-xl hover:border-maroon/30 transition-all duration-200 shadow-sm font-bold text-sm flex items-center gap-2 group">
                            <i
                                class="fas fa-file-import text-slate-400 dark:text-gray-500 group-hover:text-maroon dark:group-hover:text-red-400 transition-colors"></i>
                            <span>Import</span>
                        </button>

                        <a href="{{ route('admin.kalender.export') }}" target="_blank"
                            class="flex-1 sm:flex-none justify-center px-5 py-2.5 bg-white dark:bg-gray-800 text-slate-700 dark:text-gray-300 hover:text-maroon dark:hover:text-red-400 hover:bg-slate-50 dark:hover:bg-gray-700 border border-slate-200 dark:border-gray-700 rounded-xl hover:border-maroon/30 transition-all duration-200 shadow-sm font-bold text-sm flex items-center gap-2 group">
                            <i
                                class="fas fa-file-pdf text-slate-400 dark:text-gray-500 group-hover:text-maroon dark:group-hover:text-red-400 transition-colors"></i>
                            <span>Export</span>
                        </a>

                        <button onclick="openEventModal()"
                            class="flex-1 sm:flex-none justify-center px-5 py-2.5 bg-maroon text-white rounded-xl hover:bg-red-900 shadow-md hover:shadow-lg transition-all duration-200 font-bold text-sm flex items-center gap-2 transform active:scale-95">
                            <i class="fas fa-calendar-plus"></i>
                            <span>Atur Jadwal</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid: Calendar & List -->
        <div class="flex-1 grid grid-cols-1 lg:grid-cols-3 gap-6 min-h-0">

            <!-- Calendar (Left 2/3) -->
            <div
                class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200/60 dark:border-gray-700 p-4 md:p-6 relative flex flex-col min-h-[600px]">
                <div id="calendar" class="flex-1 w-full font-sans text-slate-600 dark:text-gray-300"></div>

                <!-- Calendar Legend -->
                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-gray-700">
                    <div class="flex flex-wrap items-center gap-4 text-xs text-slate-600 dark:text-gray-400">
                        <span class="font-semibold text-slate-700 dark:text-gray-300">Keterangan:</span>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-sm bg-red-500"></span>
                            <span>Libur</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-sm bg-blue-500"></span>
                            <span>KRS</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-sm bg-green-500"></span>
                            <span>Perkuliahan</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-sm bg-amber-500"></span>
                            <span>UTS/UAS</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-sm bg-orange-500"></span>
                            <span>Lainnya</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming List (Right 1/3) -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-slate-200/60 dark:border-gray-700 overflow-hidden flex flex-col h-[calc(100vh-200px)] lg:h-auto">
                <div
                    class="px-5 py-4 border-b border-slate-100 dark:border-gray-700 bg-slate-50/50 dark:bg-gray-900/50 flex justify-between items-center sticky top-0 z-10">
                    <div>
                        <h3 class="font-bold text-lg text-slate-800 dark:text-gray-100">Agenda Mendatang</h3>
                        <p class="text-xs text-slate-500 dark:text-gray-400">Jadwal terdekat selanjutnya</p>
                        <!-- Legend -->
                        <div class="flex flex-wrap gap-3 mt-2">
                            <div class="flex items-center gap-1.5" title="Hari Libur">
                                <span class="w-2.5 h-2.5 rounded-sm bg-red-500"></span>
                                <span
                                    class="text-[10px] font-medium text-slate-500 dark:text-gray-400 uppercase tracking-wide">Libur</span>
                            </div>
                            <div class="flex items-center gap-1.5" title="Periode KRS">
                                <span class="w-2.5 h-2.5 rounded-sm bg-blue-500"></span>
                                <span
                                    class="text-[10px] font-medium text-slate-500 dark:text-gray-400 uppercase tracking-wide">KRS</span>
                            </div>
                            <div class="flex items-center gap-1.5" title="Perkuliahan">
                                <span class="w-2.5 h-2.5 rounded-sm bg-green-500"></span>
                                <span
                                    class="text-[10px] font-medium text-slate-500 dark:text-gray-400 uppercase tracking-wide">Kuliah</span>
                            </div>
                            <div class="flex items-center gap-1.5" title="Ujian Tengah/Akhir Semester">
                                <span class="w-2.5 h-2.5 rounded-sm bg-amber-500"></span>
                                <span
                                    class="text-[10px] font-medium text-slate-500 dark:text-gray-400 uppercase tracking-wide">UTS/UAS</span>
                            </div>
                            <div class="flex items-center gap-1.5" title="Agenda Lainnya">
                                <span class="w-2.5 h-2.5 rounded-sm bg-orange-500"></span>
                                <span
                                    class="text-[10px] font-medium text-slate-500 dark:text-gray-400 uppercase tracking-wide">Lainnya</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2 py-1 rounded text-xs font-bold"
                        id="upcomingCount">
                        0
                    </div>
                </div>
                <div id="upcomingList" class="flex-1 overflow-y-auto p-0 custom-scrollbar relative">
                    <div class="flex flex-col items-center justify-center h-48 text-slate-400">
                        <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                        <span class="text-sm">Memuat jadwal...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Semester Modal -->
    <div id="semesterModal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm transition-opacity duration-300">
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl mx-auto max-h-[90vh] overflow-hidden flex flex-col transform transition-all scale-100">
            <!-- Modal Header -->
            <div
                class="px-6 py-4 md:px-8 md:py-5 border-b border-slate-100 dark:border-gray-700 flex justify-between items-center bg-slate-50/50 dark:bg-gray-900/50">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-gray-100">Pengaturan Semester</h3>
                    <p class="text-sm text-slate-500 dark:text-gray-400">Atur periode akademik & jadwal KRS</p>
                </div>
                <button onclick="closeSemesterModal()"
                    class="text-slate-400 hover:text-slate-600 dark:text-gray-500 dark:hover:text-gray-300 p-2 rounded-full hover:bg-slate-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 md:p-8 overflow-y-auto custom-scrollbar">
                <form id="semesterForm" class="space-y-8" data-no-loader>
                    <input type="hidden" name="semester_id" id="semester_id">

                    <!-- Selection -->
                    <div class="space-y-3">
                        <label class="block text-sm font-bold text-slate-700 dark:text-gray-300">Pilih Semester untuk
                            Diedit</label>
                        <div class="relative">
                            <select id="semesterSelect"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-gray-700 border border-slate-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition-all text-slate-700 dark:text-gray-200 font-medium appearance-none cursor-pointer">
                                <option value="">-- Pilih Semester --</option>
                                @foreach($semesters as $semester)
                                    <option value="{{ $semester->id }}" data-mulai="{{ $semester->tanggal_mulai }}"
                                        data-selesai="{{ $semester->tanggal_selesai }}"
                                        data-krs-mulai="{{ $semester->krs_mulai }}"
                                        data-krs-selesai="{{ $semester->krs_selesai }}"
                                        data-krs-aktif="{{ $semester->krs_dapat_diisi ? '1' : '0' }}"
                                        data-max-rendah="{{ $semester->max_sks_rendah }}"
                                        data-max-tinggi="{{ $semester->max_sks_tinggi }}">
                                        {{ $semester->nama_semester }} {{ $semester->tahun_ajaran }}
                                    </option>
                                @endforeach
                            </select>
                            <div
                                class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500 dark:text-gray-400">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-gray-400 ml-1">Pilih semester terlebih dahulu untuk
                            memuat dan mengubah data.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Periode Akademik -->
                        <div
                            class="bg-blue-50/50 dark:bg-blue-900/20 rounded-xl p-6 border border-blue-100 dark:border-blue-900/30 space-y-4 hover:shadow-sm transition-shadow">
                            <h4
                                class="font-bold text-blue-900 dark:text-blue-300 flex items-center gap-2 border-b border-blue-200 dark:border-blue-800 pb-2">
                                <i class="fas fa-calendar-alt text-blue-600 dark:text-blue-400"></i> Periode Akademik
                            </h4>
                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-blue-800 dark:text-blue-400 uppercase tracking-wide mb-1.5">Tanggal
                                        Mulai</label>
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                        class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-blue-200 dark:border-blue-900/50 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm dark:text-gray-200">
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-blue-800 dark:text-blue-400 uppercase tracking-wide mb-1.5">Tanggal
                                        Selesai</label>
                                    <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                                        class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-blue-200 dark:border-blue-900/50 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm dark:text-gray-200">
                                </div>
                            </div>
                        </div>

                        <!-- Periode KRS -->
                        <div
                            class="bg-emerald-50/50 dark:bg-emerald-900/20 rounded-xl p-6 border border-emerald-100 dark:border-emerald-900/30 space-y-4 hover:shadow-sm transition-shadow">
                            <h4
                                class="font-bold text-emerald-900 dark:text-emerald-300 flex items-center gap-2 border-b border-emerald-200 dark:border-emerald-800 pb-2">
                                <i class="fas fa-file-signature text-emerald-600 dark:text-emerald-400"></i> Periode KRS
                            </h4>
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-xs font-semibold text-emerald-800 dark:text-emerald-400 uppercase tracking-wide mb-1.5">Mulai
                                            KRS</label>
                                        <input type="date" name="krs_mulai" id="krs_mulai"
                                            class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-emerald-200 dark:border-emerald-900/50 rounded-lg focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm dark:text-gray-200">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-semibold text-emerald-800 dark:text-emerald-400 uppercase tracking-wide mb-1.5">Selesai
                                            KRS</label>
                                        <input type="date" name="krs_selesai" id="krs_selesai"
                                            class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-emerald-200 dark:border-emerald-900/50 rounded-lg focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm dark:text-gray-200">
                                    </div>
                                </div>
                                <div
                                    class="flex items-center gap-3 p-3 bg-white/60 dark:bg-gray-700/50 rounded-lg border border-emerald-100 dark:border-emerald-900/30">
                                    <input type="checkbox" name="krs_dapat_diisi" id="krs_dapat_diisi"
                                        class="w-5 h-5 text-emerald-600 rounded border-emerald-300 dark:border-emerald-700 focus:ring-emerald-500 cursor-pointer">
                                    <label for="krs_dapat_diisi"
                                        class="text-sm font-medium text-emerald-800 dark:text-emerald-300 cursor-pointer select-none">Buka
                                        Pengisian KRS</label>
                                </div>
                            </div>
                        </div>
                    </div>


                </form>
            </div>

            <!-- Footer -->
            <div
                class="px-8 py-5 border-t border-slate-100 dark:border-gray-700 bg-slate-50/50 dark:bg-gray-900/50 flex justify-end gap-3">
                <button type="button" onclick="closeSemesterModal()"
                    class="px-6 py-2.5 text-slate-600 dark:text-gray-400 hover:text-slate-800 dark:hover:text-gray-200 hover:bg-slate-200/50 dark:hover:bg-gray-700 border border-transparent rounded-lg transition font-medium text-sm">
                    Batal
                </button>
                <button type="submit" form="semesterForm"
                    class="px-8 py-2.5 bg-maroon text-white rounded-lg hover:bg-red-900 shadow-md hover:shadow-lg transition-all transform active:scale-95 font-semibold text-sm flex items-center gap-2">
                    <i class="fas fa-check"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    <!-- Event Modal -->
    <div id="eventModal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm transition-opacity duration-300">
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl mx-auto max-h-[90vh] overflow-hidden flex flex-col transform transition-all scale-100">
            <!-- Header -->
            <div class="px-6 py-4 flex justify-between items-center bg-gradient-to-r from-maroon to-red-700 flex-none">
                <h3 class="text-xl font-bold text-white" id="eventModalTitle">Kelola Jadwal</h3>
                <button onclick="closeEventModal()" class="text-white p-2 rounded-full hover:bg-white/10 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="eventForm" class="p-6 space-y-6 overflow-y-auto flex-1" data-no-loader>
                <input type="hidden" name="event_id" id="event_id">

                <div class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Nama Kegiatan</label>
                        <input type="text" name="title" id="event_title" required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-gray-700 border border-slate-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-maroon/20 focus:border-maroon focus:bg-white dark:focus:bg-gray-600 transition-all text-slate-800 dark:text-gray-100 placeholder-slate-400 dark:placeholder-gray-500"
                            placeholder="Contoh: Libur Hari Raya">
                    </div>

                    <!-- Type & Color (Color automatic) -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Tipe Kegiatan</label>
                        <select name="event_type" id="event_type" required
                            class="w-full px-3 py-3 bg-slate-50 dark:bg-gray-700 border border-slate-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-maroon/20 focus:border-maroon focus:bg-white dark:focus:bg-gray-600 text-sm dark:text-gray-200">
                            <option value="perkuliahan">Perkuliahan</option>
                            <option value="krs">Periode KRS</option>
                            <option value="krs_perubahan">KRS Perubahan</option>
                            <option value="uts">Ujian Tengah Semester (UTS)</option>
                            <option value="uas">Ujian Akhir Semester (UAS)</option>
                            <option value="libur_akademik">Libur Akademik</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                        <p class="text-xs text-slate-500 dark:text-gray-400 mt-1">*Warna label akan disesuaikan otomatis
                            dengan tipe kegiatan.
                        </p>
                    </div>

                    <!-- Semester (required) -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Semester</label>
                        <select name="semester_id" id="event_semester" required
                            class="w-full px-3 py-3 bg-slate-50 dark:bg-gray-700 border border-slate-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-maroon/20 focus:border-maroon focus:bg-white dark:focus:bg-gray-600 text-sm dark:text-gray-200">
                            <option value="" disabled selected>-- Pilih Semester --</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->nama_semester }} {{ $semester->tahun_ajaran }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 dark:text-gray-400 mt-1">Pilih semester agar event hanya muncul
                            saat filter semester tersebut dipilih.</p>
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Tanggal
                                Mulai</label>
                            <input type="date" name="start_date" id="event_start" required
                                class="w-full px-3 py-3 bg-slate-50 dark:bg-gray-700 border border-slate-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-maroon/20 focus:border-maroon focus:bg-white dark:focus:bg-gray-600 text-sm dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Tanggal
                                Selesai</label>
                            <input type="date" name="end_date" id="event_end" required
                                class="w-full px-3 py-3 bg-slate-50 dark:bg-gray-700 border border-slate-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-maroon/20 focus:border-maroon focus:bg-white dark:focus:bg-gray-600 text-sm dark:text-gray-100">
                        </div>
                    </div>

                    <!-- Desc -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-gray-300 mb-2">Keterangan</label>
                        <textarea name="description" id="event_description" rows="3"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-gray-700 border border-slate-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-maroon/20 focus:border-maroon focus:bg-white dark:focus:bg-gray-600 transition-all text-sm dark:text-gray-100 placeholder-slate-400 dark:placeholder-gray-500"
                            placeholder="Tambahkan detail opsional..."></textarea>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="pt-4 flex items-center justify-between border-t border-slate-100 dark:border-gray-700 mt-2">
                    <button type="button" id="deleteEventBtn"
                        class="px-4 py-2 text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 rounded-lg transition-colors text-sm font-semibold hidden flex items-center gap-2">
                        <i class="fas fa-trash-alt"></i> Hapus
                    </button>
                    <div class="flex gap-3 ml-auto">
                        <button type="button" onclick="closeEventModal()"
                            class="px-5 py-2.5 text-slate-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-full hover:bg-slate-50 dark:hover:bg-gray-700 font-medium text-sm">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 bg-maroon text-white rounded-full hover:bg-red-900 shadow-md hover:shadow-lg transition-all font-bold text-sm flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            Simpan Jadwal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Import Modal -->
    <div id="importModal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 dark:bg-black/80 backdrop-blur-sm px-4">

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-xl overflow-hidden">

            <!-- Header -->
            <div class="flex items-start justify-between px-6 py-5 bg-maroon rounded-t-2xl">
                <div>
                    <h3 class="text-xl font-bold text-white">Upload Jadwal</h3>
                    <p class="text-sm text-white mt-1">Import jadwal akademik dari file CSV atau PDF</p>
                </div>
                <button onclick="closeImportModal()"
                    class="text-white hover:text-white p-2 rounded-full hover:bg-maroon/80 transition">
                    <i class="fas fa-times text-white"></i>
                </button>
            </div>

            <!-- Body -->
            <form id="importForm" class="p-6 space-y-6" data-no-loader>

                <!-- Template Card -->
                <div
                    class="flex gap-4 items-start bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-900/30 rounded-xl p-4">
                    <div class="w-12 h-12 flex items-center justify-center bg-indigo-100 dark:bg-indigo-900/40 rounded-lg">
                        <i class="fas fa-file-csv text-indigo-600 dark:text-indigo-400 text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-indigo-900 dark:text-indigo-300 mb-1">Butuh Template?</p>
                        <p class="text-sm text-indigo-700 dark:text-indigo-400 mb-3">Gunakan template resmi agar format
                            sesuai.</p>
                        <a href="{{ route('admin.kalender.import-template') }}"
                            class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </div>
                </div>

                <!-- Upload Area -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-gray-300 mb-2">File Jadwal</label>

                    <label for="import_file"
                        class="flex flex-col items-center justify-center border-2 border-dashed border-slate-300 dark:border-gray-600 rounded-xl p-6 text-center cursor-pointer hover:bg-slate-50 dark:hover:bg-gray-700 transition">

                        <i class="fas fa-cloud-upload-alt text-3xl text-slate-500 dark:text-gray-400 mb-3"></i>

                        <p class="font-semibold text-slate-700 dark:text-gray-200">Klik untuk pilih file</p>
                        <p class="text-xs text-slate-400 dark:text-gray-500 mt-1 mb-3">atau drag & drop file di sini</p>

                        <div class="flex gap-2">
                            <span
                                class="px-2 py-1 border dark:border-gray-600 rounded text-xs text-slate-600 dark:text-gray-400">CSV</span>
                            <span
                                class="px-2 py-1 border dark:border-gray-600 rounded text-xs text-slate-600 dark:text-gray-400">TXT</span>
                            <span
                                class="px-2 py-1 border dark:border-gray-600 rounded text-xs text-slate-600 dark:text-gray-400">PDF</span>
                        </div>

                        <input id="import_file" name="file" type="file" class="hidden" accept=".csv,.txt,.pdf" required>
                    </label>

                    <!-- File Selected -->
                    <div id="file-name"
                        class="hidden mt-3 flex items-center gap-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-900/30 rounded-lg px-4 py-3">

                        <i class="fas fa-file-alt text-emerald-600 dark:text-emerald-400"></i>
                        <span id="file-name-text"
                            class="text-sm text-emerald-700 dark:text-emerald-300 font-medium truncate"></span>

                        <button type="button"
                            onclick="document.getElementById('import_file').value='';document.getElementById('file-name').classList.add('hidden');"
                            class="ml-auto text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-300">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
                    <button type="button" onclick="closeImportModal()"
                        class="px-5 py-2.5 text-slate-600 dark:text-gray-400 hover:text-slate-800 dark:hover:text-gray-200 rounded-md shadow-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-maroon text-white px-5 py-2.5 rounded-lg hover:bg-red-900 transition font-semibold flex items-center gap-2">
                        <i class="fas fa-file-import"></i>
                        Import Jadwal
                    </button>
                </div>

            </form>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
        <!-- PDF.js CDN -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
        <script>     // Set worker
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
        </script>
        <style>
            /* Custom Scrollbar for Modal */
            .custom-scrollbar::-webkit-scrollbar {
                width: 6px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: #f1f5f9;
            }

            .dark .custom-scrollbar::-webkit-scrollbar-track {
                background: #1f2937;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 4px;
            }

            .dark .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #4b5563;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }

            .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #6b7280;
            }

            /* FullCalendar Customization */
            #calendar {
                height: 100%;
                min-height: 500px;
            }

            /* Hide scrollbar but keep functionality */
            #calendar .fc-scroller {
                scrollbar-width: none;
                /* Firefox */
                -ms-overflow-style: none;
                /* IE/Edge */
            }

            #calendar .fc-scroller::-webkit-scrollbar {
                display: none;
                /* Chrome/Safari/Opera */
            }

            .fc {
                --fc-border-color: #e2e8f0;
                --fc-button-text-color: #fff;
                --fc-button-bg-color: #8B1538;
                --fc-button-border-color: #8B1538;
                --fc-button-hover-bg-color: #70102d;
                --fc-button-hover-border-color: #70102d;
                --fc-button-active-bg-color: #5c0d25;
                --fc-button-active-border-color: #5c0d25;

                --fc-event-bg-color: #3b82f6;
                --fc-event-border-color: #3b82f6;
                --fc-today-bg-color: #f8fafc;
                --fc-neutral-bg-color: #f1f5f9;

                font-family: 'Inter', system-ui, -apple-system, sans-serif;
            }

            .dark .fc {
                --fc-border-color: #374151;
                --fc-today-bg-color: #111827;
                --fc-neutral-bg-color: #1f2937;
                --fc-page-bg-color: #1f2937;
            }

            /* Toolbar */
            .fc-header-toolbar {
                margin-bottom: 2rem !important;
                padding-bottom: 0;
            }

            /* Force all toolbar chunks to be flex containers */
            .fc-toolbar-chunk {
                display: flex !important;
                align-items: center !important;
                gap: 8px !important;
                /* Proper spacing between items */
            }

            .fc-toolbar-title {
                font-size: 1.5rem !important;
                font-weight: 800;
                color: #1e293b;
                letter-spacing: -0.025em;
            }

            .dark .fc-toolbar-title {
                color: #f3f4f6;
            }

            .fc-button {
                border-radius: 0.75rem !important;
                padding: 0.6rem 1.2rem !important;
                font-weight: 600 !important;
                text-transform: capitalize;
                transition: all 0.2s;
                font-size: 0.875rem !important;
            }

            /* Custom "White Card" style for standard buttons */
            .fc-button-primary {
                background-color: #ffffff !important;
                border-color: #e2e8f0 !important;
                color: #475569 !important;
                box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            }

            .dark .fc-button-primary {
                background-color: #374151 !important;
                border-color: #4b5563 !important;
                color: #d1d5db !important;
            }

            /* Force separation for all buttons in toolbar */
            .fc-header-toolbar .fc-button {
                margin: 0 4px !important;
                border-radius: 0.75rem !important;
                /* Force rounded on all sides */
            }

            /* Remove button group grouping effects if present */
            .fc-button-group>.fc-button {
                border-radius: 0.75rem !important;
                margin: 0 4px !important;
                flex: none !important;
                /* Stop stretching */
            }

            .fc-button-group {
                gap: 4px;
                display: flex !important;
                background: transparent !important;
                border: none !important;
                box-shadow: none !important;
            }

            .fc-button-primary:hover {
                background-color: #f8fafc !important;
                border-color: #cbd5e1 !important;
                color: #1e293b !important;
                transform: translateY(-1px);
            }

            .dark .fc-button-primary:hover {
                background-color: #4b5563 !important;
                border-color: #6b7280 !important;
                color: #ffffff !important;
            }

            .fc-button-primary:focus {
                box-shadow: 0 0 0 2px rgba(139, 21, 56, 0.1) !important;
            }

            /* Active State (e.g. Month/Week view toggles) */
            .fc-button-active {
                background-color: #8B1538 !important;
                border-color: #8B1538 !important;
                color: #ffffff !important;
                box-shadow: 0 4px 6px -1px rgba(139, 21, 56, 0.2) !important;
            }

            /* Navigation Buttons (Prev/Next) */
            .fc-prev-button,
            .fc-next-button {
                padding: 0.6rem 1rem !important;
                /* Standar padding */
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                width: auto !important;
                /* Auto width */
                height: auto !important;
                /* Auto height */
                border-radius: 0.75rem !important;
                /* Match other buttons */
            }

            .fc-prev-button span,
            .fc-next-button span {
                display: none;
                /* Hide default chevron */
            }

            .fc-prev-button::after {
                font-family: "Font Awesome 6 Free";
                font-weight: 900;
                content: "\f053";
                /* fa-chevron-left */
                font-size: 0.9rem;
            }

            .fc-next-button::after {
                font-family: "Font Awesome 6 Free";
                font-weight: 900;
                content: "\f054";
                /* fa-chevron-right */
                font-size: 0.9rem;
            }

            /* Today Button */
            .fc-today-button {
                border-radius: 0.75rem !important;
                font-weight: 700 !important;
                background-color: #8B1538 !important;
                border-color: #8B1538 !important;
                color: white !important;
                opacity: 0.9;
                margin-left: 8px !important;
                /* Add gap between next and today */
            }

            .fc-today-button:hover {
                background-color: #70102d !important;
                opacity: 1;
            }

            /* Remove blue focus outline */
            .fc-button:focus {
                box-shadow: none !important;
            }

            /* Grid & Cells */
            .fc-theme-standard td,
            .fc-theme-standard th {
                border-color: #f1f5f9;
            }

            .fc-col-header-cell {
                background-color: #f8fafc;
                padding: 12px 0;
                color: #64748b;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.05em;
            }

            .dark .fc-col-header-cell {
                background-color: #111827;
                color: #9ca3af;
            }

            .fc-daygrid-day-number {
                color: #475569;
                font-weight: 600;
                padding: 8px 12px !important;
            }

            .dark .fc-daygrid-day-number {
                color: #9ca3af;
            }

            .fc-day-today {
                background-color: #fff1f2 !important;
                /* Very light maroon tint for today */
            }

            .dark .fc-day-today {
                background-color: #1e1b1b !important;
            }

            /* Events */
            .fc-event {
                border: none !important;
                padding: 2px 6px;
                font-size: 0.85rem;
                font-weight: 500;
                border-radius: 6px;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
                transition: transform 0.1s;
            }

            .fc-event:hover {
                transform: scale(1.01);
                filter: brightness(0.95);
            }

            .fc-daygrid-event-dot {
                border-color: currentColor;
            }

            /* Background Events */
            .semester-bg-event {
                opacity: 0.05 !important;
                background: repeating-linear-gradient(45deg,
                        #3b82f6,
                        #3b82f6 10px,
                        #60a5fa 10px,
                        #60a5fa 20px) !important;
            }

            @media (max-width: 640px) {
                .fc-header-toolbar {
                    flex-direction: column;
                    align-items: stretch !important;
                    gap: 1rem;
                }

                .fc-toolbar-chunk:nth-child(2) {
                    order: -1;
                    justify-content: center;
                }

                .fc-toolbar-chunk {
                    justify-content: space-between;
                }

                /* Toolbar Customization */
                .fc-header-toolbar {
                    flex-wrap: wrap;
                    gap: 0.5rem;
                }

                .fc-toolbar-chunk {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                /* Center Chunk (Prev Title Next) Styling */
                .fc-toolbar-chunk:nth-child(2) {
                    justify-content: center;
                    width: 100%;
                }

                @media (min-width: 640px) {
                    .fc-toolbar-chunk:nth-child(2) {
                        width: auto;
                    }
                }

                .fc-toolbar-title {
                    font-size: 1.5rem !important;
                    /* Mobile base */
                    margin: 0 1rem !important;
                    font-weight: 800;
                    color: #8B1538;
                    /* Maroon */
                }

                @media (min-width: 1024px) {
                    .fc-toolbar-title {
                        font-size: 1.75rem !important;
                    }
                }

                @media (min-width: 1280px) {
                    .fc-toolbar-title {
                        font-size: 2.25rem !important;
                    }
                }

                .fc-button-primary {
                    background-color: white !important;
                    border-color: #e2e8f0 !important;
                    color: #475569 !important;
                    box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
                    text-transform: capitalize;
                    font-weight: 600;
                    padding: 0.4rem 0.8rem !important;
                }

                .fc-button-primary:hover {
                    background-color: #f8fafc !important;
                    border-color: #cbd5e1 !important;
                    color: #1e293b !important;
                }

                .fc-button-active {
                    background-color: #8B1538 !important;
                    /* Active View Button */
                    border-color: #8B1538 !important;
                    color: white !important;
                }

                /* Compact prev/next buttons */
                .fc-prev-button,
                .fc-next-button {
                    border: none !important;
                    background: transparent !important;
                    color: #64748b !important;
                    /* Slate 500 */
                    box-shadow: none !important;
                }

                .fc-prev-button:hover,
                .fc-next-button:hover {
                    color: #8B1538 !important;
                    /* Maroon hover */
                    background: #fff1f2 !important;
                    /* Light red bg */
                }

                /* Mobile Adjustments */
                @media (max-width: 640px) {
                    .fc-header-toolbar {
                        flex-direction: column;
                        align-items: center;
                    }

                    .fc-toolbar-chunk {
                        width: 100%;
                        justify-content: center;
                    }
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
        <script>
            let calendar;

            // --- Robust Date Helper --
            // Adds 'days' to a YYYY-MM-DD string, handling month/year boundaries correctly in UTC.
            function addDaysToString(dateStr, days) {
                if (!dateStr) return '';
                // Parse YYYY-MM-DD
                const parts = dateStr.split('-');
                if (parts.length !== 3) return dateStr;

                const year = parseInt(parts[0], 10);
                const month = parseInt(parts[1], 10) - 1; // JS months are 0-11
                const day = parseInt(parts[2], 10);

                // Construct UTC Date
                const date = new Date(Date.UTC(year, month, day));
                // Add Days
                date.setUTCDate(date.getUTCDate() + days);

                // Return YYYY-MM-DD
                return date.toISOString().split('T')[0];
            }

            function toLocalISOString(date) {
                const offset = date.getTimezoneOffset() * 60000;
                return (new Date(date - offset)).toISOString().split('T')[0];
            }

            document.addEventListener('DOMContentLoaded', function () {
                // Color Input Listener Removed

                const calendarEl = document.getElementById('calendar');
                calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'today',
                        center: 'prev title next',
                        right: 'dayGridMonth,timeGridWeek,listMonth'
                    },
                    buttonText: {
                        today: 'Hari Ini',
                        month: 'Bulan',
                        week: 'Minggu',
                        list: 'Agenda'
                    },
                    themeSystem: 'standard',
                    locale: 'id',
                    dayMaxEvents: true,
                    events: function (info, successCallback, failureCallback) {
                        const semesterId = document.getElementById('filterSemester').value;
                        let url = '{{ route('admin.kalender.data') }}' + `?start=${info.startStr}&end=${info.endStr}`;
                        if (semesterId) {
                            url += `&semester_id=${semesterId}`;
                        }
                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                // BACKEND -> FRONTEND (Read)
                                // Database stores Inclusive End Date (e.g. ends Jan 5).
                                // FullCalendar needs Exclusive End Date (e.g. ends Jan 6 00:00).
                                // Fix: Add 1 Day to End Date.
                                const processedEvents = data.map(event => {
                                    if (event.end && !event.end.includes('T')) {
                                        // It's a date string YYYY-MM-DD
                                        // Add 1 day for FC display
                                        event.end = addDaysToString(event.end, 1);
                                    }
                                    return event;
                                });
                                successCallback(processedEvents);
                                updateUpcomingList(processedEvents);
                            })
                            .catch(error => failureCallback(error));
                    },
                    selectable: true,
                    editable: true,
                    droppable: true,
                    eventClick: function (info) {
                        if (info.event.extendedProps.event_id) {
                            editEvent(info.event);
                        }
                    },
                    select: function (info) {
                        // FRONTEND SELECTION
                        // FC gives Exclusive End Date.
                        // We want Inclusive for the Form.
                        let start = info.startStr;
                        let end = info.endStr;

                        // If all-day (YYYY-MM-DD), subtract 1 day from end
                        if (end && !end.includes('T')) {
                            end = addDaysToString(end, -1);
                        }

                        openEventModal(start, end);
                    },
                    eventDrop: function (info) {
                        confirmUpdateDate(info);
                    },
                    eventResize: function (info) {
                        confirmUpdateDate(info);
                    },
                    height: '100%',
                });
                calendar.render();

                function updateUpcomingList(events) {
                    const listEl = document.getElementById('upcomingList');
                    const countEl = document.getElementById('upcomingCount');

                    // Filter events: future or ongoing
                    const now = new Date();
                    now.setHours(0, 0, 0, 0); // compare start of today

                    const upcoming = events.filter(e => {
                        // Skip background events (semesters)
                        if (e.display === 'background') return false;

                        // Parse dates (FullCalendar events have date obj or ISO string)
                        // API returns strings: start, end
                        const endStr = e.end || e.start;
                        const endDate = new Date(endStr);

                        // Check if end date is >= today
                        return endDate >= now;
                    });

                    // Sort by start date
                    upcoming.sort((a, b) => new Date(a.start) - new Date(b.start));

                    countEl.innerText = upcoming.length;

                    if (upcoming.length === 0) {
                        listEl.innerHTML = `
                                                                                                                                                <div class="flex flex-col items-center justify-center h-full py-10 text-slate-400 dark:text-gray-500">
                                                                                                                                                    <i class="fas fa-calendar-times text-4xl mb-3 opacity-50"></i>
                                                                                                                                                    <p class="text-sm">Tidak ada agenda mendatang</p>
                                                                                                                                                </div>
                                                                                                                                            `;
                        return;
                    }

                    let html = '<div class="w-full px-1">';

                    upcoming.forEach(event => {
                        const start = new Date(event.start);
                        const day = start.toLocaleDateString('id-ID', { day: 'numeric' });
                        const month = start.toLocaleDateString('id-ID', { month: 'short' });
                        const year = start.getFullYear();
                        const title = event.title;

                        // Normalized Type
                        const rawType = event.event_type || 'Lainnya';
                        const type = rawType.replace('_', ' ');
                        const lowerTitle = title.toLowerCase();
                        const lowerType = rawType.toLowerCase();

                        // --- Color Logic ---
                        let theme = 'orange'; // default

                        if (lowerType.includes('libur') || lowerTitle.includes('libur')) {
                            theme = 'red';
                        } else if (lowerType.includes('krs') || lowerTitle.includes('krs')) {
                            theme = 'blue';
                        } else if (lowerType.includes('kuliah') || lowerTitle.includes('perkuliahan')) {
                            theme = 'green';
                        } else if (lowerType.includes('uts') || lowerType.includes('uas') || lowerTitle.includes('uts') || lowerTitle.includes('uas') || lowerTitle.includes('ujian')) {
                            theme = 'amber';
                        }

                        // Theme Styles Map
                        const styles = {
                            red: {
                                card: 'bg-red-50 dark:bg-red-900/10 border-red-100 dark:border-red-900/30',
                                title: 'text-red-700 dark:text-red-400',
                                icon: 'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 border-red-200 dark:border-red-800',
                                badge: 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300'
                            },
                            blue: {
                                card: 'bg-blue-50 dark:bg-blue-900/10 border-blue-100 dark:border-blue-900/30',
                                title: 'text-blue-700 dark:text-blue-400',
                                icon: 'bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-800',
                                badge: 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300'
                            },
                            green: {
                                card: 'bg-green-50 dark:bg-green-900/10 border-green-100 dark:border-green-900/30',
                                title: 'text-green-700 dark:text-green-400',
                                icon: 'bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 border-green-200 dark:border-green-800',
                                badge: 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300'
                            },
                            amber: {
                                card: 'bg-amber-50 dark:bg-amber-900/10 border-amber-100 dark:border-amber-900/30',
                                title: 'text-amber-700 dark:text-amber-400',
                                icon: 'bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800',
                                badge: 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300'
                            },
                            orange: {
                                card: 'bg-orange-50 dark:bg-orange-900/10 border-orange-100 dark:border-orange-900/30',
                                title: 'text-orange-700 dark:text-orange-400',
                                icon: 'bg-orange-100 dark:bg-orange-900/40 text-orange-600 dark:text-orange-400 border-orange-200 dark:border-orange-800',
                                badge: 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300'
                            }
                        };

                        const s = styles[theme];

                        html += `
                                                                                                                                                <div class="${s.card} p-4 rounded-xl shadow-sm border mb-3 hover:shadow-md transition-all cursor-pointer group" onclick="calendar.gotoDate('${event.start}')">
                                                                                                                                                    <div class="flex gap-4 items-center">
                                                                                                                                                        <div class="flex-none flex flex-col items-center justify-center w-14 h-14 rounded-xl border ${s.icon}">
                                                                                                                                                            <span class="text-xl font-bold leading-none">${day}</span>
                                                                                                                                                            <span class="text-[10px] uppercase font-bold mt-1">${month}</span>
                                                                                                                                                        </div>
                                                                                                                                                        <div class="flex-1 min-w-0">
                                                                                                                                                            <h4 class="text-sm font-bold ${s.title} line-clamp-2 leading-tight mb-1">
                                                                                                                                                                ${title}
                                                                                                                                                            </h4>
                                                                                                                                                            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-gray-400">
                                                                                                                                                                <span class="capitalize ${s.badge} px-2 py-0.5 rounded text-[10px] font-semibold tracking-wide truncate max-w-[100px]">
                                                                                                                                                                    ${type}
                                                                                                                                                                </span>
                                                                                                                                                                <span>${year}</span>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                            `;
                    });

                    html += '</div>';
                    listEl.innerHTML = html;
                }

                function updateUpcomingList_OLD(events) {
                    const listEl = document.getElementById('upcomingList');
                    const countEl = document.getElementById('upcomingCount');

                    // Filter events: future or ongoing
                    const now = new Date();
                    now.setHours(0, 0, 0, 0); // compare start of today

                    const upcoming = events.filter(e => {
                        // Skip background events (semesters)
                        if (e.display === 'background') return false;

                        // Parse dates (FullCalendar events have date obj or ISO string)
                        // API returns strings: start, end
                        const endStr = e.end || e.start;
                        const endDate = new Date(endStr);

                        // Check if end date is >= today
                        return endDate >= now;
                    });

                    // Sort by start date
                    upcoming.sort((a, b) => new Date(a.start) - new Date(b.start));

                    countEl.innerText = upcoming.length;

                    if (upcoming.length === 0) {
                        listEl.innerHTML = `
                                                                                                                                                                                                    <div class="flex flex-col items-center justify-center h-full py-10 text-slate-400 dark:text-gray-500">
                                                                                                                                                                                                        <i class="fas fa-calendar-times text-4xl mb-3 opacity-50"></i>
                                                                                                                                                                                                        <p class="text-sm">Tidak ada agenda mendatang</p>
                                                                                                                                                                                                    </div>
                                                                                                                                                                                                `;
                        return;
                    }

                    let html = '<div class="divide-y divide-slate-100 dark:divide-gray-700 w-full">';

                    upcoming.forEach(event => {
                        const start = new Date(event.start);
                        const day = start.toLocaleDateString('id-ID', {
                            day: 'numeric'
                        });
                        const month = start.toLocaleDateString('id-ID', {
                            month: 'short'
                        });
                        const year = start.getFullYear();
                        const title = event.title;
                        const type = event.event_type || 'Lainnya';

                        // Use the event color
                        const colorStyle = event.color ? `border-left: 4px solid ${event.color};` : 'border-left: 4px solid #ccc;';

                        html += `
                                                                                                                                                                                                    <div class="p-4 hover:bg-slate-50 dark:hover:bg-gray-700/50 transition cursor-pointer group w-full" onclick="calendar.gotoDate('${event.start}')">
                                                                                                                                                                                                        <div class="flex gap-4 w-full">
                                                                                                                                                                                                            <div class="flex-none flex flex-col items-center justify-center w-14 h-14 bg-slate-100 dark:bg-gray-700 rounded-xl border border-slate-200 dark:border-gray-600">
                                                                                                                                                                                                                <span class="text-xl font-bold text-slate-700 dark:text-gray-200 leading-none">${day}</span>
                                                                                                                                                                                                                <span class="text-[10px] uppercase font-bold text-slate-500 dark:text-gray-400 mt-1">${month}</span>
                                                                                                                                                                                                            </div>
                                                                                                                                                                                                            <div class="flex-1 min-w-0" style="${colorStyle} padding-left: 12px;">
                                                                                                                                                                                                                <h4 class="text-sm font-bold text-slate-800 dark:text-gray-100 line-clamp-2 leading-tight mb-1 group-hover:text-maroon transition-colors py-0.5">
                                                                                                                                                                                                                    ${title}
                                                                                                                                                                                                                </h4>
                                                                                                                                                                                                                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-gray-400">
                                                                                                                                                                                                                    <span class="capitalize bg-slate-100 dark:bg-gray-700/80 px-1.5 py-0.5 rounded text-[10px] font-semibold tracking-wide truncate max-w-[100px]">
                                                                                                                                                                                                                        ${type.replace('_', ' ')}
                                                                                                                                                                                                                    </span>
                                                                                                                                                                                                                    <span>${year}</span>
                                                                                                                                                                                                                </div>
                                                                                                                                                                                                            </div>
                                                                                                                                                                                                        </div>
                                                                                                                                                                                                    </div>
                                                                                                                                                                                                `;
                    });

                    html += '</div>';
                    listEl.innerHTML = html;
                }

                // Filters
                document.getElementById('filterSemester').addEventListener('change', function () {
                    calendar.refetchEvents();
                });

                // --- Semester Logic ---
                document.getElementById('semesterSelect').addEventListener('change', function () {
                    const option = this.options[this.selectedIndex];
                    if (option.value) {
                        document.getElementById('semester_id').value = option.value;
                        document.getElementById('tanggal_mulai').value = option.dataset.mulai || '';
                        document.getElementById('tanggal_selesai').value = option.dataset.selesai || '';
                        document.getElementById('krs_mulai').value = option.dataset.krsMulai || '';
                        document.getElementById('krs_selesai').value = option.dataset.krsSelesai || '';
                        document.getElementById('krs_dapat_diisi').checked = option.dataset.krsAktif === '1';

                    } else {
                        document.getElementById('semesterForm').reset();
                    }
                });

                document.getElementById('semesterForm').addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const semesterId = formData.get('semester_id');

                    if (!semesterId) {
                        showNotification('Pilih semester terlebih dahulu!', 'error');
                        return;
                    }

                    fetch(`/admin/kalender-akademik/semester/${semesterId}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            tanggal_mulai: formData.get('tanggal_mulai'),
                            tanggal_selesai: formData.get('tanggal_selesai'),
                            krs_mulai: formData.get('krs_mulai'),
                            krs_selesai: formData.get('krs_selesai'),
                            krs_dapat_diisi: formData.get('krs_dapat_diisi') ? true : false,

                        })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                closeSemesterModal();
                                calendar.refetchEvents();
                                showNotification('Semester berhasil diperbarui!', 'success');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            showNotification('Gagal menyimpan perubahan.', 'error');
                        });
                });

                // --- Event Form Logic ---
                document.getElementById('eventForm').addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const eventId = formData.get('event_id');
                    const url = eventId ? `/admin/kalender-akademik/event/${eventId}` : '{{ route('admin.kalender.event.store') }}';
                    const method = eventId ? 'PUT' : 'POST';

                    // Form dates are inclusive. Backend expects inclusive. Just send as is.

                    fetch(url, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(Object.fromEntries(formData))
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                closeEventModal();
                                calendar.refetchEvents();
                                showNotification(eventId ? 'Jadwal diperbarui!' : 'Jadwal dibuat!', 'success');
                            }
                        })
                        .catch(e => {
                            console.error(e);
                            showNotification('Terjadi kesalahan.', 'error');
                        });
                });

                document.getElementById('deleteEventBtn').addEventListener('click', function () {
                    showDeleteConfirm('event', function() {
                        // Delete logic here
                    });
                    const eventId = document.getElementById('event_id').value;
                    fetch(`/admin/kalender-akademik/event/${eventId}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                closeEventModal();
                                calendar.refetchEvents();
                                showNotification('Jadwal dihapus.', 'success');
                            }
                        });
                });
            });

            // --- Logic for Drag & Drop Updates ---
            function confirmUpdateDate(info) {
                // FRONTEND -> BACKEND (Write)
                // FC uses Exclusive End. Database stores inclusive end dates.

                let startStr = info.event.startStr;
                let endStr = info.event.endStr; // Exclusive end from FullCalendar

                if (info.event.allDay) {
                    // Trim any time portion
                    startStr = startStr.split('T')[0];
                    if (endStr) {
                        endStr = endStr.split('T')[0];
                        // Convert Exclusive -> Inclusive (Subtract 1 Day)
                        endStr = addDaysToString(endStr, -1);
                    } else {
                        // null end means single-day event
                        endStr = startStr;
                    }
                }

                showConfirm(
                    `Update tanggal event ke ${startStr} s/d ${endStr}?`,
                    async function() {
                        const eventId = info.event.extendedProps.event_id;
                        try {
                            const res = await fetch(`/admin/kalender-akademik/event/${eventId}/date`, {
                                method: 'PUT',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ start: startStr, end: endStr })
                            });
                            const data = await res.json();
                            if (data.success) {
                                showNotification('Jadwal diperbarui', 'success');
                                calendar.refetchEvents();
                            } else {
                                info.revert();
                                showNotification('Gagal update', 'error');
                            }
                        } catch (e) {
                            info.revert();
                            showNotification('Error koneksi', 'error');
                        }
                    },
                    function() {
                        // Cancelled by user -> revert
                        info.revert();
                    },
                    'Konfirmasi Update'
                );
            }

            // --- Modal Functions ---
            function openSemesterModal() {
                const modal = document.getElementById('semesterModal');
                modal.classList.remove('hidden');
                setTimeout(() => { // Transition
                    modal.querySelector('div').classList.remove('scale-100'); // Reset first if needed? No, standard logic
                }, 10);
            }

            function closeSemesterModal() {
                document.getElementById('semesterModal').classList.add('hidden');
                document.getElementById('semesterForm').reset();
            }

            function openEventModal(startStr = null, endStr = null) {
                document.getElementById('eventModalTitle').textContent = 'Buat Jadwal';
                document.getElementById('eventForm').reset();
                document.getElementById('event_id').value = '';
                document.getElementById('deleteEventBtn').classList.add('hidden');
                // document.getElementById('colorHex').textContent = '#3788d8';
                // document.getElementById('event_color').value = '#3788d8';

                if (startStr) document.getElementById('event_start').value = startStr;
                if (endStr) document.getElementById('event_end').value = endStr;

                // Preselect semester based on current filter
                const currentFilter = document.getElementById('filterSemester')?.value;
                const semSelectNew = document.getElementById('event_semester');
                if (semSelectNew) semSelectNew.value = currentFilter || '';

                document.getElementById('eventModal').classList.remove('hidden');
            }

            function editEvent(event) {
                document.getElementById('eventModalTitle').textContent = 'Edit Jadwal';
                document.getElementById('event_id').value = event.extendedProps.event_id;
                document.getElementById('event_title').value = event.title;
                document.getElementById('event_description').value = event.extendedProps.description || '';
                document.getElementById('event_type').value = event.extendedProps.type;

                // const color = event.backgroundColor || '#3788d8';
                // document.getElementById('event_color').value = color; // Input removed
                // document.getElementById('colorHex').textContent = color;

                // Dates for Form (Inclusive)
                // FC Event Start is correct.
                // FC Event End is Exclusive.
                const startStr = event.startStr.split('T')[0];
                let endStr = startStr;

                if (event.end) {
                    // Convert FC Exclusive End -> DB Inclusive
                    const endDate = new Date(event.end); // Date Obj
                    endDate.setDate(endDate.getDate() - 1);
                    endStr = toLocalISOString(endDate);
                }

                document.getElementById('event_start').value = startStr;
                document.getElementById('event_end').value = endStr;

                // Populate semester select if present
                const semSel = document.getElementById('event_semester');
                if (semSel) {
                    semSel.value = event.extendedProps.semester_id || '';
                }

                document.getElementById('deleteEventBtn').classList.remove('hidden');
                document.getElementById('eventModal').classList.remove('hidden');
            }

            function closeEventModal() {
                document.getElementById('eventModal').classList.add('hidden');
            }

            function openImportModal() {
                document.getElementById('importModal').classList.remove('hidden');
            }
            window.openImportModal = openImportModal;

            function closeImportModal() {
                document.getElementById('importModal').classList.add('hidden');
                document.getElementById('importForm').reset();
                document.getElementById('file-name').classList.add('hidden');
            }

            function showNotification(message, type = 'success') {
                const div = document.createElement('div');
                div.className = `fixed top-6 right-6 px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 transform transition-all duration-500 translate-y-[-20px] opacity-0 z-[100] ${type === 'success' ? 'bg-white dark:bg-gray-800 border-l-4 border-green-500 text-slate-700 dark:text-gray-200 shadow-xl' : 'bg-white dark:bg-gray-800 border-l-4 border-red-500 text-slate-700 dark:text-gray-200 shadow-xl'
                    }`;

                const icon = type === 'success' ? '<i class="fas fa-check-circle text-green-500 text-xl"></i>' : '<i class="fas fa-exclamation-circle text-red-500 text-xl"></i>';

                div.innerHTML = `
                                                                                                                                                                                                                                                                                                                                                                                                                ${icon}
                                                                                                                                                                                                                                                                                                                                                                                                                <div>
                                                                                                                                                                                                                                                                                                                                                                                                                    <h4 class="font-bold text-sm text-slate-800 dark:text-gray-100">${type === 'success' ? 'Berhasil' : 'Gagal'}</h4>
                                                                                                                                                                                                                                                                                                                                                                                                                    <p class="text-xs text-slate-500 dark:text-gray-400 font-medium mt-0.5">${message}</p>
                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                            `;

                document.body.appendChild(div);

                // Animate In
                setTimeout(() => {
                    div.classList.remove('translate-y-[-20px]', 'opacity-0');
                }, 10);

                setTimeout(() => {
                    div.classList.add('translate-y-[-20px]', 'opacity-0');
                    setTimeout(() => div.remove(), 500);
                }, 3000);
            }
            document.getElementById('import_file').addEventListener('change', function (e) {
                const file = e.target.files[0];
                const fileName = file?.name;
                const nameContainer = document.getElementById('file-name');
                const nameText = document.getElementById('file-name-text');

                if (file) {
                    // Validate file format
                    const allowedExtensions = ['.csv', '.txt', '.pdf'];
                    const lowerFileName = fileName.toLowerCase();
                    const isValidExtension = allowedExtensions.some(ext => lowerFileName.endsWith(ext));

                    if (!isValidExtension) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Format File Tidak Valid!',
                            html: 'File yang diizinkan: <strong>CSV, TXT, atau PDF</strong>.<br><br>File "<strong>' + fileName + '</strong>" tidak dapat diupload.',
                            confirmButtonColor: '#8B1538',
                            confirmButtonText: 'OK'
                        });
                        e.target.value = '';
                        nameContainer.classList.add('hidden');
                        return;
                    }

                    nameText.textContent = `File terpilih: ${fileName}`;
                    nameContainer.classList.remove('hidden');
                } else {
                    nameContainer.classList.add('hidden');
                }
            });

            document.getElementById('importForm').addEventListener('submit', async function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const fileInput = document.getElementById('import_file');
                const file = fileInput.files[0];

                if (!file) return;

                const btn = this.querySelector('button[type="submit"]');
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

                try {
                    // Check if PDF
                    if (file.type === 'application/pdf') {
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Reading PDF...';
                        const arrayBuffer = await file.arrayBuffer();
                        const pdf = await pdfjsLib.getDocument(arrayBuffer).promise;
                        let fullText = '';

                        // Loop pages
                        for (let i = 1; i <= pdf.numPages; i++) {
                            const page = await pdf.getPage(i);
                            const textContent = await page.getTextContent();
                            const pageText = textContent.items.map(item => item.str).join(' ');
                            fullText += pageText + '\n';
                        }

                        // Append text to form data (as a simulated text file or raw string)
                        formData.append('pdf_text_content', fullText);
                        console.log("PDF Text Extracted:", fullText.substring(0, 100) + "...");
                    }

                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Importing...';

                    const res = await fetch('{{ route('admin.kalender.import') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    const data = await res.json();

                    btn.disabled = false;
                    btn.innerHTML = originalText;

                    if (data.success) {
                        closeImportModal();
                        calendar.refetchEvents();
                        showNotification(data.message, 'success');
                    } else {
                        showNotification(data.message || 'Gagal mengimport data', 'error');
                    }

                } catch (err) {
                    console.error(err);
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    showNotification('Gagal memproses file: ' + err.message, 'error');
                }
            });
        </script>
    @endpush
@endsection