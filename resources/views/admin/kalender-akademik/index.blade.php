@extends('layouts.admin')

@section('title', 'Kalender Akademik')

@section('breadcrumbs')
    <a href="@if(Route::has('admin.dashboard')){{ route('admin.dashboard') }}@else{{ url('/admin') }}@endif" class="mr-2 muted">Home</a>
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
    <div class="h-full flex flex-col bg-slate-50">
        <!-- Header -->
        <div class="mb-6 px-1 relative z-20">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Kalender Akademik</h2>
                    <p class="text-slate-500 mt-1 text-sm font-medium">
                        Kelola jadwal semester, periode KRS, dan agenda kegiatan.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 items-center ml-auto">
                    <div class="relative group">
                        <select id="filterSemester"
                            class="appearance-none bg-white border border-slate-200 text-slate-700 text-sm rounded-xl shadow-sm focus:ring-2 focus:ring-maroon/20 focus:border-maroon block w-full pl-4 pr-10 py-2.5 transition-all duration-200 hover:border-maroon/30 cursor-pointer font-semibold">
                            <option value="">-- Semua Semester --</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->nama_semester }} {{ $semester->tahun_ajaran }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>

                    <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                            class="px-5 py-2.5 bg-white text-slate-700 hover:text-maroon hover:bg-slate-50 border border-slate-200 rounded-xl hover:border-maroon/30 transition-all duration-200 shadow-sm font-bold text-sm flex items-center gap-2 group">
                            <i class="fas fa-file-import text-slate-400 group-hover:text-maroon transition-colors"></i>
                            <span>Import</span>
                        </button>

                        <button onclick="openEventModal()"
                            class="px-5 py-2.5 bg-maroon text-white rounded-xl hover:bg-red-900 shadow-md hover:shadow-lg transition-all duration-200 font-bold text-sm flex items-center gap-2 transform active:scale-95">
                            <i class="fas fa-calendar-plus"></i>
                            <span>Atur Jadwal</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Calendar Container -->
            <div class="flex-1 bg-white rounded-2xl shadow-lg border border-slate-200/60 overflow-hidden p-6 relative">
                <div id="calendar" class="h-full font-sans text-slate-600"></div>
            </div>
        </div>

        <!-- Semester Modal -->
        <div id="semesterModal" tabindex="-1" aria-hidden="true"
            class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300">
            <div
                class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-auto max-h-[90vh] overflow-hidden flex flex-col transform transition-all scale-100">
                <!-- Modal Header -->
                <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Pengaturan Semester</h3>
                        <p class="text-sm text-slate-500">Atur periode akademik & jadwal KRS</p>
                    </div>
                    <button onclick="closeSemesterModal()"
                        class="text-slate-400 hover:text-slate-600 p-2 rounded-full hover:bg-slate-100 transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-8 overflow-y-auto custom-scrollbar">
                    <form id="semesterForm" class="space-y-8">
                        <input type="hidden" name="semester_id" id="semester_id">

                        <!-- Selection -->
                        <div class="space-y-3">
                            <label class="block text-sm font-bold text-slate-700">Pilih Semester untuk Diedit</label>
                            <div class="relative">
                                <select id="semesterSelect"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-maroon/20 focus:border-maroon transition-all text-slate-700 font-medium appearance-none cursor-pointer">
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
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                            <p class="text-xs text-slate-500 ml-1">Pilih semester terlebih dahulu untuk memuat dan mengubah
                                data.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Periode Akademik -->
                            <div
                                class="bg-blue-50/50 rounded-xl p-6 border border-blue-100 space-y-4 hover:shadow-sm transition-shadow">
                                <h4 class="font-bold text-blue-900 flex items-center gap-2 border-b border-blue-200 pb-2">
                                    <i class="fas fa-calendar-alt text-blue-600"></i> Periode Akademik
                                </h4>
                                <div class="space-y-4">
                                    <div>
                                        <label
                                            class="block text-xs font-semibold text-blue-800 uppercase tracking-wide mb-1.5">Tanggal
                                            Mulai</label>
                                        <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                            class="w-full px-3 py-2 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-semibold text-blue-800 uppercase tracking-wide mb-1.5">Tanggal
                                            Selesai</label>
                                        <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                                            class="w-full px-3 py-2 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- Periode KRS -->
                            <div
                                class="bg-emerald-50/50 rounded-xl p-6 border border-emerald-100 space-y-4 hover:shadow-sm transition-shadow">
                                <h4 class="font-bold text-emerald-900 flex items-center gap-2 border-b border-emerald-200 pb-2">
                                    <i class="fas fa-file-signature text-emerald-600"></i> Periode KRS
                                </h4>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-emerald-800 uppercase tracking-wide mb-1.5">Mulai
                                                KRS</label>
                                            <input type="date" name="krs_mulai" id="krs_mulai"
                                                class="w-full px-3 py-2 bg-white border border-emerald-200 rounded-lg focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-emerald-800 uppercase tracking-wide mb-1.5">Selesai
                                                KRS</label>
                                            <input type="date" name="krs_selesai" id="krs_selesai"
                                                class="w-full px-3 py-2 bg-white border border-emerald-200 rounded-lg focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-sm">
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 p-3 bg-white/60 rounded-lg border border-emerald-100">
                                        <input type="checkbox" name="krs_dapat_diisi" id="krs_dapat_diisi"
                                            class="w-5 h-5 text-emerald-600 rounded border-emerald-300 focus:ring-emerald-500 cursor-pointer">
                                        <label for="krs_dapat_diisi"
                                            class="text-sm font-medium text-emerald-800 cursor-pointer select-none">Buka
                                            Pengisian KRS</label>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </form>
                </div>

                <!-- Footer -->
                <div class="px-8 py-5 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-3">
                    <button type="button" onclick="closeSemesterModal()"
                        class="px-6 py-2.5 text-slate-600 hover:text-slate-800 hover:bg-slate-200/50 border border-transparent rounded-lg transition font-medium text-sm">
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
            class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300">
            <div
                class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-auto overflow-hidden transform transition-all scale-100">
                <!-- Header -->
                <div class="px-6 py-4 flex justify-between items-center bg-gradient-to-r from-maroon to-red-700">
                    <h3 class="text-xl font-bold text-white" id="eventModalTitle">Kelola Jadwal</h3>
                    <button onclick="closeEventModal()"
                        class="text-white p-2 rounded-full hover:bg-white/10 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="eventForm" class="p-6 space-y-6">
                    <input type="hidden" name="event_id" id="event_id">

                    <div class="space-y-4">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kegiatan</label>
                            <input type="text" name="title" id="event_title" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-maroon/20 focus:border-maroon focus:bg-white transition-all text-slate-800 placeholder-slate-400"
                                placeholder="Contoh: Libur Hari Raya">
                        </div>

                        <!-- Type & Color (Color automatic) -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Tipe Kegiatan</label>
                            <select name="event_type" id="event_type" required
                                class="w-full px-3 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-maroon/20 focus:border-maroon focus:bg-white text-sm">
                                <option value="perkuliahan">Perkuliahan</option>
                                <option value="krs">Periode KRS</option>
                                <option value="krs_perubahan">KRS Perubahan</option>
                                <option value="uts">Ujian Tengah Semester (UTS)</option>
                                <option value="uas">Ujian Akhir Semester (UAS)</option>
                                <option value="libur_akademik">Libur Akademik</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                            <p class="text-xs text-slate-500 mt-1">*Warna label akan disesuaikan otomatis dengan tipe kegiatan.
                            </p>
                        </div>

                        <!-- Semester (required) -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Semester</label>
                            <select name="semester_id" id="event_semester" required
                                class="w-full px-3 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-maroon/20 focus:border-maroon focus:bg-white text-sm">
                                <option value="" disabled selected>-- Pilih Semester --</option>
                                @foreach($semesters as $semester)
                                    <option value="{{ $semester->id }}">{{ $semester->nama_semester }} {{ $semester->tahun_ajaran }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-slate-500 mt-1">Pilih semester agar event hanya muncul saat filter semester tersebut dipilih.</p>
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Mulai</label>
                                <input type="date" name="start_date" id="event_start" required
                                    class="w-full px-3 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-maroon/20 focus:border-maroon focus:bg-white text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Selesai</label>
                                <input type="date" name="end_date" id="event_end" required
                                    class="w-full px-3 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-maroon/20 focus:border-maroon focus:bg-white text-sm">
                            </div>
                        </div>

                        <!-- Desc -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Keterangan</label>
                            <textarea name="description" id="event_description" rows="3"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-maroon/20 focus:border-maroon focus:bg-white transition-all text-sm"
                                placeholder="Tambahkan detail opsional..."></textarea>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="pt-4 flex items-center justify-between border-t border-slate-100 mt-2">
                        <button type="button" id="deleteEventBtn"
                            class="px-4 py-2 text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors text-sm font-semibold hidden flex items-center gap-2">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </button>
                        <div class="flex gap-3 ml-auto">
                            <button type="button" onclick="closeEventModal()"
                                class="px-5 py-2.5 text-slate-700 bg-white border border-slate-200 rounded-full hover:bg-slate-50 font-medium text-sm">
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
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">

            <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl overflow-hidden">

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
                <form id="importForm" class="p-6 space-y-6">

                    <!-- Template Card -->
                    <div class="flex gap-4 items-start bg-indigo-50 border border-indigo-100 rounded-xl p-4">
                        <div class="w-12 h-12 flex items-center justify-center bg-indigo-100 rounded-lg">
                            <i class="fas fa-file-csv text-indigo-600 text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-indigo-900 mb-1">Butuh Template?</p>
                            <p class="text-sm text-indigo-700 mb-3">Gunakan template resmi agar format sesuai.</p>
                            <a href="{{ route('admin.kalender.import-template') }}"
                                class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-download"></i> Download Template
                            </a>
                        </div>
                    </div>

                    <!-- Upload Area -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">File Jadwal</label>

                        <label for="import_file"
                            class="flex flex-col items-center justify-center border-2 border-dashed border-slate-300 rounded-xl p-6 text-center cursor-pointer hover:bg-slate-50 transition">

                            <i class="fas fa-cloud-upload-alt text-3xl text-slate-500 mb-3"></i>

                            <p class="font-semibold text-slate-700">Klik untuk pilih file</p>
                            <p class="text-xs text-slate-400 mt-1 mb-3">atau drag & drop file di sini</p>

                            <div class="flex gap-2">
                                <span class="px-2 py-1 border rounded text-xs text-slate-600">CSV</span>
                                <span class="px-2 py-1 border rounded text-xs text-slate-600">TXT</span>
                                <span class="px-2 py-1 border rounded text-xs text-slate-600">PDF</span>
                            </div>

                            <input id="import_file" name="file" type="file" class="hidden" accept=".csv,.txt,.pdf" required>
                        </label>

                        <!-- File Selected -->
                        <div id="file-name"
                            class="hidden mt-3 flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-3">

                            <i class="fas fa-file-alt text-emerald-600"></i>
                            <span id="file-name-text" class="text-sm text-emerald-700 font-medium truncate"></span>

                            <button type="button"
                                onclick="document.getElementById('import_file').value='';document.getElementById('file-name').classList.add('hidden');"
                                class="ml-auto text-emerald-500 hover:text-emerald-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" onclick="closeImportModal()"
                            class="px-5 py-2.5 text-slate-600 hover:text-slate-800 rounded-md shadow-sm">
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
            <script>
                // Set worker
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

                .custom-scrollbar::-webkit-scrollbar-thumb {
                    background: #cbd5e1;
                    border-radius: 4px;
                }

                .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                    background: #94a3b8;
                }

                /* FullCalendar Customization */
                #calendar {
                    height: calc(100vh - 200px);
                    min-height: 500px;
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

                .fc-daygrid-day-number {
                    color: #475569;
                    font-weight: 600;
                    padding: 8px 12px !important;
                }

                .fc-day-today {
                    background-color: #fff1f2 !important;
                    /* Very light maroon tint for today */
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
                            left: 'prev,next today',
                            center: 'title',
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
                        if (!confirm('Hapus event ini?')) return;
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
                    // FC uses Exclusive End.
                    // Database needs Inclusive End.

                    let startStr = info.event.startStr;
                    let endStr = info.event.endStr; // Use endStr (Exclusive)

                    if (info.event.allDay) {
                        // Safety trim just in case (e.g. contains 'T')
                        startStr = startStr.split('T')[0];
                        if (endStr) {
                            endStr = endStr.split('T')[0];
                            // Convert Exclusive -> Inclusive (Subtract 1 Day)
                            endStr = addDaysToString(endStr, -1);
                        } else {
                            // null end means 1 day event -> end = start
                            endStr = startStr;
                        }
                    }

                    if (!confirm(`Update tanggal event ke ${startStr} s/d ${endStr}?`)) {
                        info.revert();
                        return;
                    }

                    const eventId = info.event.extendedProps.event_id;
                    fetch(`/admin/kalender-akademik/event/${eventId}/date`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            start: startStr,
                            end: endStr
                        })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                showNotification('Jadwal diperbarui', 'success');
                                calendar.refetchEvents(); // Ensure visuals are synced with DB
                            } else {
                                info.revert();
                                showNotification('Gagal update', 'error');
                            }
                        })
                        .catch(() => {
                            info.revert();
                            showNotification('Error koneksi', 'error');
                        });
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
                    div.className = `fixed top-6 right-6 px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 transform transition-all duration-500 translate-y-[-20px] opacity-0 z-[100] ${type === 'success' ? 'bg-white border-l-4 border-green-500 text-slate-700' : 'bg-white border-l-4 border-red-500 text-slate-700'
                        }`;

                    const icon = type === 'success' ? '<i class="fas fa-check-circle text-green-500 text-xl"></i>' : '<i class="fas fa-exclamation-circle text-red-500 text-xl"></i>';

                    div.innerHTML = `
                                                                                                                                                                                                                                    ${icon}
                                                                                                                                                                                                                                    <div>
                                                                                                                                                                                                                                        <h4 class="font-bold text-sm text-slate-800">${type === 'success' ? 'Berhasil' : 'Gagal'}</h4>
                                                                                                                                                                                                                                        <p class="text-xs text-slate-500 font-medium mt-0.5">${message}</p>
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
                    const fileName = e.target.files[0]?.name;
                    const nameContainer = document.getElementById('file-name');
                    const nameText = document.getElementById('file-name-text');

                    if (fileName) {
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