@extends('layouts.admin')

@section('title', 'Blast Email - Kirim Email Massal')
@section('page-title', 'Blast Email')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* TomSelect Premium Customization */
    .ts-wrapper.single .ts-control, .ts-wrapper.multi .ts-control {
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        background-color: #f8fafc;
        transition: all 0.2s ease;
    }
    .ts-wrapper.multi.has-items .ts-control {
        padding: 0.5rem 0.5rem;
    }
    .ts-wrapper.focus .ts-control {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        background-color: #ffffff;
    }
    .ts-dropdown {
        border-radius: 0.75rem !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden !important;
        padding: 0 !important;
        margin-top: 4px !important;
    }
    .ts-dropdown .ts-dropdown-content {
        padding: 0.5rem !important;
    }
    .ts-dropdown .option {
        border-radius: 0.5rem !important;
        padding: 0 !important;
        transition: all 0.2s ease !important;
        margin-bottom: 2px !important;
    }
    .ts-dropdown .option:last-child {
        margin-bottom: 0 !important;
    }
    .ts-dropdown .option.active {
        background-color: #eef2ff !important;
        color: inherit !important;
    }
    .ts-wrapper.multi .ts-control > div {
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 0.5rem;
        color: #1e293b;
        padding: 0.25rem 0.75rem;
        font-weight: 600;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    
    /* Flatpickr Modern Customization */
    .flatpickr-calendar {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        border: 1px solid #f1f5f9 !important;
        border-radius: 1.25rem !important;
        padding: 10px !important;
        font-family: inherit !important;
    }
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay {
        background: #e11d48 !important;
        border-color: #e11d48 !important;
        border-radius: 0.5rem !important;
    }
    .flatpickr-day:hover {
        background: #ffe4e6 !important;
        border-color: #ffe4e6 !important;
        border-radius: 0.5rem !important;
        color: #e11d48 !important;
    }
    .flatpickr-day {
        border-radius: 0.5rem !important;
    }
    .flatpickr-months .flatpickr-month {
        color: #1e293b !important;
        fill: #1e293b !important;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months {
        font-weight: 700 !important;
    }
    /* Custom Animations & Styles */
    .fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
        transform: translateY(10px);
    }
    
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .premium-input {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        transition: all 0.25s ease;
    }
    
    .premium-input:focus {
        background-color: #ffffff;
        border-color: #f43f5e;
        box-shadow: 0 0 0 4px rgba(244, 63, 94, 0.1);
        transform: translateY(-1px);
    }

    .custom-checkbox-wrapper input:checked + div {
        background-color: #f0f9ff;
        border-color: #3b82f6;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }
    
    .custom-checkbox-wrapper input:checked + div .check-icon {
        color: #2563eb;
        transform: scale(1);
        opacity: 1;
    }
    
    .custom-checkbox-wrapper div .check-icon {
        transform: scale(0.5);
        opacity: 0;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Gradient text */
    .text-gradient-maroon {
        background: linear-gradient(135deg, #9f1239, #be123c);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endpush

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 fade-in-up">
    <div>
        <h3 class="text-2xl font-bold text-slate-800 flex items-center gap-2.5 tracking-tight">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shadow-sm">
                <i class="fas fa-paper-plane text-lg"></i>
            </div>
            Broadcast <span class="text-gradient-maroon">Email</span>
        </h3>
        <p class="text-sm text-slate-500 mt-2 font-medium max-w-2xl">Kirim pesan dan pengumuman massal kepada mahasiswa dengan filter presisi.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.blast-email.outbox') }}" class="group px-4 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-sm font-bold hover:bg-sky-50 hover:border-sky-200 transition-all shadow-sm flex items-center gap-2.5">
            <i class="fas fa-inbox text-slate-400 group-hover:text-sky-600 transition-colors"></i> 
            <span>Antrean Outbox</span>
        </a>
        <a href="{{ route('admin.blast-email.logs') }}" class="group px-4 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm flex items-center gap-2.5">
            <i class="fas fa-history text-slate-400 group-hover:text-rose-600 transition-colors"></i> 
            <span>Riwayat Pengiriman</span>
        </a>
    </div>
</div>

<!-- Alert Messages -->
@if ($errors->any())
    <div class="mb-8 bg-red-50/80 backdrop-blur-sm border border-red-200 p-5 rounded-2xl shadow-sm fade-in-up delay-100 flex items-start">
        <div class="bg-red-100 p-2 rounded-lg mr-4">
            <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
        </div>
        <div>
            <strong class="text-red-800 text-sm block mb-1 font-bold">Harap periksa kembali isian Anda</strong>
            <ul class="text-sm text-red-600 list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    <!-- Form Column -->
    <div class="xl:col-span-2 fade-in-up delay-100">
        <div class="glass-card rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden relative">
            <!-- Decorative gradient orb -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-rose-400/20 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="px-8 py-6 border-b border-slate-100/80 bg-white/50 flex items-center justify-between relative z-10">
                <h5 class="text-lg font-bold text-slate-800 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center text-rose-600">
                        <i class="fas fa-pen-nib text-sm"></i>
                    </span>
                    Komposisi Pesan
                </h5>
            </div>
            
            <form id="blastEmailForm" action="{{ route('admin.blast-email.send') }}" method="POST" class="relative z-10">
                @csrf
                <div class="p-8 space-y-7">
                    <!-- Subject -->
                    <div class="group">
                        <label for="subject" class="block text-[11px] uppercase tracking-widest font-bold text-slate-500 mb-2">
                            Subjek Email <span class="text-rose-500 text-lg leading-3">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-heading text-slate-400 group-focus-within:text-rose-500 transition-colors"></i>
                            </div>
                            <input type="text" id="subject" name="subject" class="w-full pl-11 pr-4 py-3.5 premium-input rounded-xl text-sm text-slate-800 font-medium placeholder:text-slate-400 placeholder:font-normal @error('subject') border-red-500 ring-4 ring-red-500/10 @enderror" 
                                   placeholder="Contoh: Pengumuman Jadwal Ujian Akhir Semester" 
                                   value="{{ old('subject') }}" required maxlength="200">
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-[11px] text-slate-400 font-medium"><i class="fas fa-info-circle mr-1"></i>Maksimal 200 karakter</p>
                            @error('subject')
                                <p class="text-xs text-red-500 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Greeting -->
                    <div class="group">
                        <label for="greeting" class="block text-[11px] uppercase tracking-widest font-bold text-slate-500 mb-2">
                            Salam Pembuka <span class="text-rose-500 text-lg leading-3">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-handshake text-slate-400 group-focus-within:text-rose-500 transition-colors"></i>
                            </div>
                            <input type="text" id="greeting" name="greeting" class="w-full pl-11 pr-4 py-3.5 premium-input rounded-xl text-sm text-slate-800 font-medium placeholder:text-slate-400 placeholder:font-normal @error('greeting') border-red-500 ring-4 ring-red-500/10 @enderror"
                                   placeholder="Contoh: Assalamu'alaikum Wr. Wb. / Yth. Mahasiswa" 
                                   value="{{ old('greeting') }}" required maxlength="100">
                        </div>
                        @error('greeting')
                            <p class="text-xs text-red-500 font-semibold mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div id="messageSection" class="group">
                        <label for="message" class="block text-[11px] uppercase tracking-widest font-bold text-slate-500 mb-2">
                            Isi Pesan <span class="text-rose-500 text-lg leading-3">*</span>
                        </label>
                        <div class="relative rounded-xl overflow-hidden border border-slate-200 focus-within:border-rose-500 focus-within:ring-4 focus-within:ring-rose-500/10 transition-all bg-[#f8fafc] focus-within:bg-white">
                            <div class="bg-slate-50/80 border-b border-slate-100 px-4 py-2 flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider"><i class="fas fa-align-left mr-1.5"></i>Teks Utama</span>
                            </div>
                            <textarea id="message" name="message" class="w-full px-4 py-3 bg-transparent border-none text-sm text-slate-800 focus:ring-0 resize-none min-h-[160px] placeholder:text-slate-400 leading-relaxed"
                                      placeholder="Ketik isi pesan Anda di sini..." 
                                      required maxlength="5000">{{ old('message') }}</textarea>
                        </div>
                        <div class="flex justify-between items-center mt-2 px-1">
                            <p class="text-[11px] text-slate-400 font-medium">Dapat menggunakan paragraf berganda.</p>
                            <p class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded-md"><span id="charCount" class="text-rose-600">0</span> / 5000</p>
                        </div>
                        @error('message')
                            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent my-2"></div>

                    <!-- Filter Type -->
                    <div class="group relative z-20">
                        <label for="filter_type" class="block text-[11px] uppercase tracking-widest font-bold text-slate-500 mb-2">
                            Target Penerima <span class="text-rose-500 text-lg leading-3">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-users-cog text-slate-400 group-focus-within:text-rose-500 transition-colors"></i>
                            </div>
                            <select id="filter_type" name="filter_type" class="w-full pl-11 pr-10 py-3.5 premium-input rounded-xl text-sm text-slate-800 font-bold appearance-none cursor-pointer @error('filter_type') border-red-500 ring-4 ring-red-500/10 @enderror" required>
                                <option value="" class="font-normal">-- Pilih Kriteria Target --</option>
                                <option value="all" {{ old('filter_type') == 'all' ? 'selected' : '' }}>Semua Mahasiswa Aktif</option>
                                <option value="angkatan" {{ old('filter_type') == 'angkatan' ? 'selected' : '' }}>Filter Berdasarkan Angkatan</option>
                                <option value="prodi" {{ old('filter_type') == 'prodi' ? 'selected' : '' }}>Filter Berdasarkan Program Studi</option>
                                <option value="tingkat" {{ old('filter_type') == 'tingkat' ? 'selected' : '' }}>Filter Berdasarkan Tingkat Semester</option>
                                <option value="kelas" {{ old('filter_type') == 'kelas' ? 'selected' : '' }}>Filter Berdasarkan Kelas Perkuliahan</option>
                                <option value="status" {{ old('filter_type') == 'status' ? 'selected' : '' }}>Filter Berdasarkan Status Mahasiswa</option>
                                <option value="spesifik" {{ old('filter_type') == 'spesifik' ? 'selected' : '' }}>Filter Berdasarkan Mahasiswa Spesifik</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                        @error('filter_type')
                            <p class="text-xs text-red-500 font-semibold mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dynamic Filter Options -->
                    <div id="filterOptions" class="transition-all duration-300 relative z-40"></div>

                    <!-- Send Credentials Checkbox -->
                    <div class="pt-4 pb-2">
                        <label class="custom-checkbox-wrapper block relative cursor-pointer group">
                            <input type="checkbox" id="send_credentials" name="send_credentials" value="1" class="peer sr-only" {{ old('send_credentials') ? 'checked' : '' }} onchange="toggleCredentialsMode()">
                            <div class="flex items-start gap-4 p-5 rounded-2xl bg-slate-50 border-2 border-slate-200 transition-all duration-300 group-hover:border-blue-300 group-hover:bg-blue-50/50">
                                <div class="mt-0.5 relative flex items-center justify-center w-6 h-6 rounded border-2 border-slate-300 bg-white peer-checked:border-blue-500 peer-checked:bg-blue-500 transition-all flex-shrink-0">
                                    <i class="fas fa-check text-white text-xs check-icon absolute"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex flex-wrap sm:flex-nowrap items-center justify-between gap-2 mb-1 w-full pr-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shadow-inner flex-shrink-0">
                                                <i class="fas fa-key"></i>
                                            </div>
                                            <span class="font-bold text-slate-800 text-base">Kirim Kredensial Sistem</span>
                                        </div>
                                        <button type="button" id="btnEditTemplate" onclick="requestTemplateEdit(event)" class="hidden text-[10px] uppercase font-bold tracking-wider text-blue-600 bg-blue-100 hover:bg-blue-200 px-3 py-2 rounded-lg transition-all items-center gap-1.5 z-10 relative flex-shrink-0 border border-blue-200">
                                            <i class="fas fa-cog"></i> Sesuaikan Template
                                        </button>
                                    </div>
                                    <p class="text-xs text-slate-500 font-medium leading-relaxed pl-10">Aktifkan untuk mengirim email standar berisi informasi login (Email Kampus & Password default) kepada mahasiswa baru atau untuk reset sandi.</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="px-8 py-5 bg-slate-50/80 backdrop-blur-md border-t border-slate-200/60 rounded-b-3xl flex flex-wrap-reverse md:flex-wrap gap-3 items-center justify-between">
                    <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 text-slate-500 hover:text-slate-800 font-bold text-sm rounded-xl hover:bg-slate-200/50 transition-colors w-full md:w-auto text-center">
                        Batal
                    </a>
                    
                    <div class="flex flex-wrap md:flex-nowrap gap-3 w-full md:w-auto">
                        <button type="button" id="previewBtn" class="flex-1 md:flex-none px-5 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-50 hover:border-slate-400 hover:shadow-sm transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-search text-slate-400"></i> Cek Target
                        </button>
                        
                        <div class="relative group/btn flex-1 md:flex-none">
                            <button type="submit" class="w-full px-6 py-2.5 bg-gradient-to-r from-rose-700 to-rose-900 text-white rounded-xl text-sm font-bold shadow-lg shadow-rose-900/25 hover:shadow-rose-900/40 transform transition-all active:scale-[0.98] flex items-center justify-center gap-2 relative overflow-hidden">
                                <span class="absolute inset-0 w-full h-full bg-white/20 -translate-x-full group-hover/btn:translate-x-full transition-transform duration-700 ease-in-out"></span>
                                <i class="fas fa-paper-plane opacity-90"></i> Kirim ke Antrean
                            </button>
                        </div>
                        
                        <div class="relative group/btn-direct flex-1 md:flex-none">
                            <button type="button" id="immediateBtn" class="w-full px-6 py-2.5 bg-gradient-to-r from-rose-500 to-rose-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-rose-600/25 hover:shadow-rose-600/40 transform transition-all active:scale-[0.98] flex items-center justify-center gap-2 relative overflow-hidden" title="Kirim Langsung (Tanpa Antrean)">
                                <span class="absolute inset-0 w-full h-full bg-white/20 -translate-x-full group-hover/btn-direct:translate-x-full transition-transform duration-700 ease-in-out"></span>
                                <i class="fas fa-bolt opacity-90"></i> Kirim Langsung
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Sidebar Column -->
    <div class="xl:col-span-1 flex flex-col gap-6 fade-in-up delay-200">
        
        <!-- Preview Widget -->
        <div class="glass-card rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden relative group shrink-0">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 to-white/50 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-400/20 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="px-8 py-6 border-b border-slate-100/80 bg-white/50 flex items-center justify-between relative z-10">
                <h5 class="text-lg font-bold text-slate-800 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm">
                        <i class="fas fa-crosshairs text-sm"></i>
                    </span>
                    Estimasi Target
                </h5>
            </div>
            <div class="p-6 relative z-10">
                <div id="previewContent" class="text-center text-slate-500 py-6 flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mb-4 shadow-inner">
                        <i class="fas fa-users text-2xl text-slate-300"></i>
                    </div>
                    <p class="text-sm font-medium">Klik tombol <strong class="text-slate-700">"Cek Target"</strong> untuk menghitung jumlah penerima berdasarkan filter.</p>
                </div>
            </div>
        </div>

        <!-- Recent Blasts Widget -->
        <div class="glass-card rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden flex flex-col flex-1 relative">
            <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-rose-400/20 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="px-8 py-6 border-b border-slate-100/80 bg-white/50 flex items-center justify-between relative z-10">
                <h5 class="text-lg font-bold text-slate-800 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 shadow-sm">
                        <i class="fas fa-history text-sm"></i>
                    </span>
                    Aktivitas Terakhir
                </h5>
                <a href="{{ route('admin.blast-email.logs') }}" class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all shadow-sm group">
                    <i class="fas fa-arrow-right text-xs group-hover:translate-x-0.5 transition-transform"></i>
                </a>
            </div>
            <div class="p-0 flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar relative z-10">
                <div id="recentBlasts" class="absolute inset-0 p-6">
                    <div class="h-full flex flex-col items-center justify-center text-center text-slate-400 py-10">
                        <i class="fas fa-circle-notch fa-spin text-3xl mb-3 text-rose-200"></i>
                        <p class="text-xs font-bold uppercase tracking-wider">Memuat Data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Flatpickr & TomSelect Scripts -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterType = document.getElementById('filter_type');
    const messageField = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    const previewBtn = document.getElementById('previewBtn');
    const blastForm = document.getElementById('blastEmailForm');

    // Update char count
    if(messageField) {
        charCount.textContent = messageField.value.length;
        messageField.addEventListener('input', function() {
            charCount.textContent = this.value.length;
            if(this.value.length >= 4900) {
                charCount.classList.remove('text-rose-600');
                charCount.classList.add('text-red-600');
            } else {
                charCount.classList.add('text-rose-600');
                charCount.classList.remove('text-red-600');
            }
        });
    }

    // Handle filter type change
    if(filterType) {
        filterType.addEventListener('change', function() {
            renderFilterOptions(this.value);
        });
        
        // Initial render
        renderFilterOptions(filterType.value);
    }

    // Preview button
    if(previewBtn) {
        previewBtn.addEventListener('click', function(e) {
            e.preventDefault();
            getPreview();
        });
    }

    // Form submission with immediate flag
    const immediateBtn = document.getElementById('immediateBtn');
    if(immediateBtn) {
        immediateBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if(!blastForm.checkValidity()) {
                blastForm.reportValidity();
                return;
            }
            
            Swal.fire({
                title: 'Kirim Langsung?',
                text: "Proses ini akan mengirim email secara sinkron (tanpa antrean) dan mungkin memakan waktu lama jika penerima banyak.",
                icon: 'warning',
                iconColor: '#f59e0b',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fas fa-bolt mr-1"></i> Ya, Kirim Sekarang',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border border-slate-100',
                    title: 'text-xl font-black text-slate-800',
                    confirmButton: 'rounded-xl font-bold shadow-lg shadow-amber-500/30',
                    cancelButton: 'rounded-xl font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const existingImmediate = blastForm.querySelector('input[name="immediate"]');
                    if(existingImmediate) existingImmediate.remove();
                    
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'immediate';
                    input.value = '1';
                    blastForm.appendChild(input);
                    
                    submitBlastForm(true);
                }
            });
        });
    }

    // Queue button - use AJAX to submit without page reload
    if(blastForm) {
        blastForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if(!blastForm.checkValidity()) {
                blastForm.reportValidity();
                return;
            }
            
            Swal.fire({
                title: 'Jadwal Pengiriman',
                html: `
                    <div class="text-left mt-2">
                        <p class="text-sm text-slate-600 mb-4 font-medium leading-relaxed">Pesan ini akan dimasukkan ke dalam antrean. <strong class="text-slate-800">Wajib menentukan waktu pengiriman</strong> agar sistem dapat memprosesnya sesuai jadwal.</p>
                        <label for="swal-scheduled-at" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                            <i class="fas fa-calendar-alt text-rose-400 mr-1"></i> Pilih Waktu Pengiriman
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-clock text-slate-400"></i>
                            </div>
                            <input type="text" id="swal-scheduled-at" class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-slate-200 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 text-slate-700 font-bold transition-all cursor-pointer bg-slate-50" placeholder="Pilih tanggal dan waktu...">
                        </div>
                    </div>
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#9f1239',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: '<i class="fas fa-paper-plane mr-1"></i> Jadwalkan Pengiriman',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'rounded-xl px-5 py-2.5 shadow-lg shadow-rose-900/20 font-bold',
                    cancelButton: 'rounded-xl px-5 py-2.5 font-bold'
                },
                didOpen: () => {
                    flatpickr("#swal-scheduled-at", {
                        enableTime: true,
                        dateFormat: "Y-m-d H:i",
                        minDate: "today",
                        time_24hr: true,
                        locale: "id",
                    });
                },
                preConfirm: () => {
                    const val = document.getElementById('swal-scheduled-at').value;
                    if (!val) {
                        Swal.showValidationMessage('Anda wajib memilih jadwal pengiriman!');
                        return false;
                    }
                    return val;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let scheduledInput = document.getElementById('scheduled_at_hidden');
                    if (!scheduledInput) {
                        scheduledInput = document.createElement('input');
                        scheduledInput.type = 'hidden';
                        scheduledInput.id = 'scheduled_at_hidden';
                        scheduledInput.name = 'scheduled_at';
                        blastForm.appendChild(scheduledInput);
                    }
                    scheduledInput.value = result.value;
                    
                    submitBlastForm(false);
                }
            });
        });
    }
    
    // Load recent blasts
    loadRecentBlasts();
    
    // Initialize credentials mode if checkbox is already checked
    toggleCredentialsMode();
});

function submitBlastForm(isImmediate) {
    const blastForm = document.getElementById('blastEmailForm');
    const formData = new FormData(blastForm);
    
    // Show loading indicator
    Swal.fire({
        title: isImmediate ? 'Memproses Pengiriman...' : 'Memasukkan ke Antrean...',
        html: '<div class="mt-2 text-sm text-slate-500 font-medium">Mohon jangan tutup halaman ini.</div>',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
            const b = Swal.getPopup().querySelector('.swal2-loader');
            if(b) {
                b.style.borderColor = '#9f1239 transparent #9f1239 transparent';
            }
        },
        customClass: {
            popup: 'rounded-2xl shadow-2xl border border-slate-100 py-8',
            title: 'text-lg font-black text-slate-800'
        }
    });

    fetch('{{ route("admin.blast-email.send") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(res => {
        if(!res.ok) throw new Error('Network response was not ok');
        return res.json();
    })
    .then(data => {
        if(data.success) {
            let message = data.message;
            let additionalText = '';
            
            if(isImmediate) {
                additionalText = '<div class="text-left mt-4 text-[13px] text-blue-800 bg-blue-50 border border-blue-100 p-3 rounded-xl flex gap-3"><i class="fas fa-check-circle text-blue-500 text-lg mt-0.5"></i><div><strong>Email Terkirim Langsung</strong><br/>Proses sinkronisasi pengiriman telah selesai.</div></div>';
            } else {
                additionalText = '<div class="text-left mt-4 text-[13px] text-amber-800 bg-amber-50 border border-amber-100 p-3 rounded-xl flex gap-3"><i class="fas fa-clock text-amber-500 text-lg mt-0.5"></i><div><strong>Masuk Antrean (Queue)</strong><br/>Email akan dikirimkan otomatis di latar belakang secara bertahap.</div></div>';
            }
            
            Swal.fire({
                title: 'Berhasil!',
                html: `<div class="text-slate-600 font-medium">${message}</div>${additionalText}`,
                icon: 'success',
                iconColor: '#10b981',
                confirmButtonColor: '#0f172a',
                confirmButtonText: 'Tutup',
                customClass: {
                    popup: 'rounded-2xl shadow-2xl border border-slate-100',
                    title: 'text-xl font-black text-slate-800',
                    confirmButton: 'rounded-xl font-bold shadow-lg shadow-slate-900/20 px-8'
                }
            }).then(() => {
                // Reload the recent blasts
                loadRecentBlasts();
                // Optionally reset the form
                blastForm.reset();
                // Reset the filter options display
                const filterType = document.getElementById('filter_type');
                if(filterType) {
                    renderFilterOptions(filterType.value);
                }
                toggleCredentialsMode();
            });
        } else {
            throw new Error(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Gagal Memproses!',
            html: `<div class="text-sm font-medium text-slate-600">${error.message || 'Gagal mengirim email. Silakan periksa koneksi atau coba lagi.'}</div>`,
            icon: 'error',
            iconColor: '#ef4444',
            confirmButtonColor: '#0f172a',
            confirmButtonText: 'Mengerti',
            customClass: {
                popup: 'rounded-2xl shadow-2xl border border-slate-100',
                title: 'text-xl font-black text-slate-800',
                confirmButton: 'rounded-xl font-bold px-8'
            }
        });
    });
}

function loadRecentBlasts() {
    fetch('{{ route("admin.blast-email.logs") }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(res => {
        if(res.ok) return res.json();
        throw new Error('Network response was not ok');
    })
    .then(data => {
        const container = document.getElementById('recentBlasts');
        
        if (!data.success || !data.data || data.data.length === 0) {
            container.innerHTML = `
                <div class="h-full flex flex-col items-center justify-center text-center text-slate-400 py-10 fade-in-up">
                    <div class="w-16 h-16 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mb-3 shadow-inner">
                        <i class="fas fa-inbox text-2xl text-slate-300"></i>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-wider">Belum ada riwayat</p>
                </div>
            `;
            return;
        }

        let html = '<div class="space-y-3 pb-2">';
        data.data.slice(0, 10).forEach((log, index) => {
            const isSuccess = log.success;
            const statusIcon = isSuccess ? '<i class="fas fa-check-circle text-emerald-500"></i>' : '<i class="fas fa-times-circle text-red-500"></i>';
            const statusBg = isSuccess ? 'bg-emerald-50 border-emerald-100' : 'bg-red-50 border-red-100';
            
            const dateStr = new Date(log.created_at).toLocaleString('id-ID', {day: 'numeric', month: 'short', hour: '2-digit', minute:'2-digit'});
            
            html += `
                <div class="p-3.5 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-slate-200 transition-all group fade-in-up" style="animation-delay: ${index * 50}ms">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center justify-center w-8 h-8 rounded-xl ${statusBg} border flex-shrink-0">
                            ${statusIcon}
                        </div>
                        <div class="flex-1 min-w-0 pt-0.5">
                            <p class="text-sm font-bold text-slate-800 truncate" title="${log.subject}">${log.subject || 'Tanpa Subjek'}</p>
                            <p class="text-[11px] font-medium text-slate-500 truncate mt-0.5">${log.email_sent_to || 'Target tidak diketahui'}</p>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-2 flex items-center gap-1">
                                <i class="fas fa-clock text-slate-300"></i> ${dateStr}
                            </p>
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        
        container.innerHTML = html;
    })
    .catch(err => {
        const container = document.getElementById('recentBlasts');
        if(container) {
            container.innerHTML = `
                <div class="p-4 text-center mt-10">
                    <i class="fas fa-exclamation-triangle text-red-300 text-3xl mb-2"></i>
                    <p class="text-xs font-bold text-red-500 uppercase">Gagal memuat data</p>
                </div>
            `;
        }
    });
}

function renderFilterOptions(filterType) {
    const container = document.getElementById('filterOptions');
    if (!container) return;
    
    if (!filterType) {
        container.innerHTML = '';
        container.style.opacity = '0';
        container.style.transform = 'translateY(-10px)';
        return;
    }
    
    let html = '<div class="mt-5 p-5 rounded-2xl bg-indigo-50/50 border border-indigo-100 relative">';
    html += '<div class="absolute -right-4 -top-4 text-indigo-100/50 pointer-events-none overflow-hidden rounded-2xl w-full h-full"><i class="fas fa-filter text-8xl absolute -right-4 -top-4"></i></div>';
    html += '<div class="relative z-10">';
    
    const inputClass = "w-full px-4 py-3 bg-white border border-indigo-200 rounded-xl text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all appearance-none";
    const labelClass = "block text-[10px] uppercase tracking-wider font-bold text-indigo-800 mb-2 flex items-center gap-1.5";
    
    if (filterType === 'all') {
        html += `
            <div class="flex items-center gap-4 bg-white p-4 rounded-xl border border-indigo-100 shadow-sm">
                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-users text-lg"></i>
                </div>
                <div>
                    <h6 class="font-bold text-slate-800 text-sm">Mode Broadcast Menyeluruh</h6>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Pesan akan dikirim ke <strong>semua mahasiswa</strong> yang terdaftar di sistem SIAKAD.</p>
                </div>
            </div>
        `;
    } else if (filterType === 'angkatan') {
        html += `
            <div>
                <label for="angkatan" class="${labelClass}">
                    <i class="fas fa-calendar-alt text-indigo-500"></i> Pilih Tahun Angkatan
                </label>
                <div class="relative">
                    <select id="angkatan" name="angkatan" class="${inputClass}" required>
                        <option value="">-- Pilih Tahun --</option>
                        @foreach($angkatanList ?? [] as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-indigo-400">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>
        `;
    } else if (filterType === 'prodi') {
        html += `
            <div>
                <label for="prodi_id" class="${labelClass}">
                    <i class="fas fa-graduation-cap text-indigo-500"></i> Pilih Program Studi
                </label>
                <div class="relative">
                    <select id="prodi_id" name="prodi_id" class="${inputClass}" required>
                        <option value="">-- Pilih Prodi --</option>
                        @foreach($prodis ?? [] as $prodi)
                            <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-indigo-400">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>
        `;
    } else if (filterType === 'tingkat') {
        html += `
            <div>
                <label for="tingkat" class="${labelClass}">
                    <i class="fas fa-layer-group text-indigo-500"></i> Pilih Tingkat Semester
                </label>
                <div class="relative">
                    <select id="tingkat" name="tingkat" class="${inputClass}" required>
                        <option value="">-- Pilih Tingkat --</option>
                        @foreach($tingkatList ?? [] as $tingkat)
                            <option value="{{ $tingkat }}">Tingkat {{ $tingkat }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-indigo-400">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>
        `;
    } else if (filterType === 'kelas') {
        html += `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="prodi_id_kelas" class="${labelClass}">
                        1. Pilih Prodi Dulu
                    </label>
                    <div class="relative">
                        <select id="prodi_id_kelas" class="${inputClass}" required>
                            <option value="">-- Pilih Prodi --</option>
                            @foreach($prodis ?? [] as $prodi)
                                <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-indigo-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="kelas_perkuliahan_id" class="${labelClass}">
                        2. Pilih Kelas
                    </label>
                    <div class="relative">
                        <select id="kelas_perkuliahan_id" name="kelas_perkuliahan_id" class="${inputClass} disabled:bg-slate-100 disabled:opacity-50 disabled:cursor-not-allowed" required disabled>
                            <option value="">-- Menunggu Prodi --</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-indigo-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Setup listener for dependent dropdown
        setTimeout(() => {
            const prodiSelect = document.getElementById('prodi_id_kelas');
            if (prodiSelect) {
                prodiSelect.addEventListener('change', function() {
                    if (this.value) {
                        loadKelasPerProdi(this.value);
                    } else {
                        const kelasSelect = document.getElementById('kelas_perkuliahan_id');
                        kelasSelect.innerHTML = '<option value="">-- Menunggu Prodi --</option>';
                        kelasSelect.disabled = true;
                    }
                });
            }
        }, 50);

    } else if (filterType === 'status') {
        html += `
            <div>
                <label for="status" class="${labelClass}">
                    <i class="fas fa-flag text-indigo-500"></i> Pilih Status Mahasiswa
                </label>
                <div class="relative">
                    <select id="status" name="status" class="${inputClass}" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="aktif">Aktif</option>
                        <option value="cuti">Cuti</option>
                        <option value="lulus">Lulus</option>
                        <option value="tidak-aktif">Tidak Aktif</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-indigo-400">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>
        `;
    } else if (filterType === 'spesifik') {
        html += `
            <div>
                <label for="mahasiswa_ids" class="${labelClass}">
                    <i class="fas fa-user-check text-indigo-500"></i> Pilih Mahasiswa
                </label>
                <select id="mahasiswa_ids" name="mahasiswa_ids[]" multiple required class="w-full">
                </select>
                <p class="text-[11px] text-slate-500 font-medium mt-2"><i class="fas fa-info-circle mr-1"></i>Ketik nama atau NIM untuk mencari mahasiswa.</p>
            </div>
        `;
    }
    
    html += '</div></div>';
    container.innerHTML = html;
    
    // Animate in
    setTimeout(() => {
        container.style.opacity = '1';
        container.style.transform = 'translateY(0)';

        if (filterType === 'spesifik') {
            new TomSelect('#mahasiswa_ids', {
                valueField: 'id',
                labelField: 'nama',
                searchField: ['nama', 'nim'],
                plugins: ['remove_button'],
                placeholder: 'Ketik Nama atau NIM...',
                closeAfterSelect: true,
                preload: 'focus',
                onItemAdd: function() {
                    this.setTextboxValue('');
                    this.refreshOptions();
                },
                load: function(query, callback) {
                    fetch('{{ route("admin.blast-email.search-mahasiswa") }}?q=' + encodeURIComponent(query))
                        .then(response => response.json())
                        .then(json => {
                            callback(json);
                        }).catch(()=>{
                            callback();
                        });
                },
                render: {
                    option: function(item, escape) {
                        const name = escape(item.nama);
                        const nim = escape(item.nim);
                        const initials = name.substring(0, 2).toUpperCase();
                        
                        return `
                            <div class="flex items-center gap-3 p-2.5 cursor-pointer">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-bold text-sm shadow-sm flex-shrink-0">
                                    ${initials}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800 text-sm leading-tight">${name}</span>
                                    <span class="text-xs text-slate-500 font-medium mt-0.5 flex items-center gap-1">
                                        <i class="fas fa-id-card text-[10px] text-slate-400"></i> ${nim}
                                    </span>
                                </div>
                            </div>
                        `;
                    },
                    item: function(item, escape) {
                        return `
                            <div class="flex items-center gap-1.5 py-0.5">
                                <span class="font-bold text-sm text-slate-700">${escape(item.nama)}</span>
                                <span class="px-1.5 py-0.5 rounded bg-slate-100 text-[10px] text-slate-500 font-bold border border-slate-200">
                                    ${escape(item.nim)}
                                </span>
                            </div>
                        `;
                    }
                }
            });
        }
    }, 10);
}

function loadKelasPerProdi(prodiId) {
    const kelasSelect = document.getElementById('kelas_perkuliahan_id');
    if (!kelasSelect) return;
    
    kelasSelect.disabled = true;
    kelasSelect.innerHTML = '<option value="">Memuat Kelas...</option>';
    
    fetch(`/admin/blast-email/kelas/${prodiId}`)
        .then(res => res.json())
        .then(data => {
            kelasSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';
            if(Array.isArray(data) && data.length > 0) {
                data.forEach(kelas => {
                    const opt = document.createElement('option');
                    opt.value = kelas.id;
                    opt.textContent = `${kelas.nama_kelas} (Tingkat ${kelas.tingkat})`;
                    kelasSelect.appendChild(opt);
                });
                kelasSelect.disabled = false;
            } else {
                kelasSelect.innerHTML = '<option value="">Tidak ada kelas ditemukan</option>';
            }
        })
        .catch(err => {
            console.error('Error loading kelas:', err);
            kelasSelect.innerHTML = '<option value="">Gagal memuat kelas</option>';
        });
}

function getPreview() {
    const form = document.getElementById('blastEmailForm');
    
    if(!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const formData = new FormData(form);
    const previewContent = document.getElementById('previewContent');
    
    previewContent.innerHTML = `
        <div class="py-10 text-center flex flex-col items-center justify-center fade-in-up">
            <i class="fas fa-circle-notch fa-spin text-3xl text-indigo-500 mb-4"></i>
            <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Mengkalkulasi Target...</p>
        </div>
    `;
    
    fetch('{{ route("admin.blast-email.preview") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            let html = `
                <div class="bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl p-5 mb-5 shadow-lg shadow-indigo-500/20 text-white fade-in-up">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-users text-sm"></i>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider opacity-90">Total Penerima Valid</span>
                    </div>
                    <div class="text-3xl font-black ml-11">${data.total_recipients} <span class="text-sm font-medium opacity-80">Akun</span></div>
                </div>
            `;

            if (data.sample && data.sample.length > 0) {
                html += '<div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-3 px-1 fade-in-up delay-100">Cuplikan Target (Sample):</div>';
                html += '<div class="space-y-2 fade-in-up delay-200">';
                data.sample.forEach((mahasiswa, index) => {
                    const delay = 200 + (index * 50);
                    html += `
                        <div class="p-3 bg-white border border-slate-100 rounded-xl shadow-sm flex items-center gap-3 fade-in-up" style="animation-delay: ${delay}ms">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex flex-shrink-0 items-center justify-center text-slate-500 font-bold text-xs">
                                ${mahasiswa.nama ? mahasiswa.nama.charAt(0).toUpperCase() : '?'}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-slate-800 text-[13px] truncate">${mahasiswa.nama}</p>
                                <p class="text-[11px] text-slate-500 font-medium truncate">${mahasiswa.email}</p>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                
                if (data.total_recipients > data.sample.length) {
                    html += `<div class="text-center mt-3 text-[11px] font-bold text-slate-400 fade-in-up delay-300">+ ${data.total_recipients - data.sample.length} akun lainnya</div>`;
                }
            }

            previewContent.innerHTML = html;
        } else {
            previewContent.innerHTML = `
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 text-center fade-in-up">
                    <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-500 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-exclamation-triangle text-lg"></i>
                    </div>
                    <h6 class="font-bold text-amber-800 text-sm mb-1">Tidak Ada Penerima</h6>
                    <p class="text-xs text-amber-600/80 font-medium">${data.message || 'Kriteria filter yang dipilih tidak cocok dengan data mahasiswa manapun.'}</p>
                </div>
            `;
        }
    })
    .catch(err => {
        console.error('Error:', err);
        previewContent.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-2xl p-5 text-center fade-in-up">
                <i class="fas fa-times-circle text-2xl text-red-400 mb-2"></i>
                <h6 class="font-bold text-red-800 text-sm mb-1">Gagal Memuat Preview</h6>
                <p class="text-xs text-red-600/80 font-medium">Terjadi kesalahan koneksi. Silakan coba lagi nanti.</p>
            </div>
        `;
    });
}

function toggleCredentialsMode() {
    const credentialsCheckbox = document.getElementById('send_credentials');
    const messageSection = document.getElementById('messageSection');
    const subjectInput = document.getElementById('subject');
    const greetingInput = document.getElementById('greeting');
    const messageInput = document.getElementById('message');
    
    if(credentialsCheckbox && credentialsCheckbox.checked) {
        // Template values
        const templateSubject = 'Akun Login SIAKAD - Email dan Password Kampus Anda';
        const templateGreeting = 'Selamat Datang di SIAKAD STIH';
        const templateMessage = 'Akun SIAKAD Anda telah dibuat. Berikut adalah kredensial login Anda:';
        
        // Fill fields with template
        subjectInput.value = templateSubject;
        greetingInput.value = templateGreeting;
        messageInput.value = templateMessage;
        
        // Visual cue for disabled fields
        const overlayClass = "opacity-50 pointer-events-none grayscale-[30%]";
        subjectInput.closest('.group').classList.add(...overlayClass.split(' '));
        greetingInput.closest('.group').classList.add(...overlayClass.split(' '));
        
        // Automatically hide the message body
        messageSection.classList.add('hidden');
        
        // Actually disable fields so they can't be edited via keyboard navigation either
        subjectInput.readOnly = true;
        greetingInput.readOnly = true;
        messageInput.readOnly = true;
        
        // Remove required (akan dikirim dari job)
        subjectInput.removeAttribute('required');
        greetingInput.removeAttribute('required');
        messageInput.removeAttribute('required');
        
        // Show edit template button
        const btnEditTemplate = document.getElementById('btnEditTemplate');
        if (btnEditTemplate) {
            btnEditTemplate.classList.remove('hidden');
            btnEditTemplate.classList.add('flex');
        }
    } else {
        // Hide edit template button
        const btnEditTemplate = document.getElementById('btnEditTemplate');
        if (btnEditTemplate) {
            btnEditTemplate.classList.add('hidden');
            btnEditTemplate.classList.remove('flex');
        }
        
        // Enable visual fields
        const overlayClass = "opacity-50 pointer-events-none grayscale-[30%]";
        subjectInput.closest('.group').classList.remove(...overlayClass.split(' '));
        greetingInput.closest('.group').classList.remove(...overlayClass.split(' '));
        if(messageSection) messageSection.classList.remove(...overlayClass.split(' '));
        
        // Enable editing
        subjectInput.readOnly = false;
        greetingInput.readOnly = false;
        messageInput.readOnly = false;
        
        // Add required back
        subjectInput.setAttribute('required', 'required');
        greetingInput.setAttribute('required', 'required');
        messageInput.setAttribute('required', 'required');
        
        // Only clear template values if they were exactly the template
        if(subjectInput.value === 'Akun Login SIAKAD - Email dan Password Kampus Anda') {
            subjectInput.value = '';
            greetingInput.value = '';
            messageInput.value = '';
        }
        
        // Reset char count
        const charCount = document.getElementById('charCount');
        if(charCount) charCount.textContent = messageInput.value.length || '0';
    }
}

function requestTemplateEdit(event) {
    event.preventDefault();
    event.stopPropagation();

    Swal.fire({
        title: 'Sesuaikan Template',
        text: 'Masukkan kata sandi admin Anda untuk membuka kunci mode pengeditan template.',
        input: 'password',
        inputPlaceholder: 'Kata Sandi Admin',
        inputAttributes: {
            autocapitalize: 'off',
            autocorrect: 'off'
        },
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#9f1239',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: '<i class="fas fa-unlock mr-1"></i> Buka Akses',
        cancelButtonText: 'Batal',
        customClass: {
            confirmButton: 'rounded-xl px-5 py-2.5 font-bold shadow-lg shadow-rose-900/20',
            cancelButton: 'rounded-xl px-5 py-2.5 font-bold',
            input: 'rounded-xl border-slate-200 focus:ring-rose-500 focus:border-rose-500'
        },
        showLoaderOnConfirm: true,
        preConfirm: (password) => {
            if (!password) {
                Swal.showValidationMessage('Kata sandi harus diisi!');
                return false;
            }
            
            return fetch('{{ route("admin.verify-password") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ password: password })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Kata sandi salah atau tidak valid.');
                }
                return response.json();
            })
            .catch(error => {
                Swal.showValidationMessage(
                    `<i class="fas fa-exclamation-circle text-rose-500 mr-1"></i> ${error.message}`
                );
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed && result.value.success) {
            Swal.fire({
                icon: 'success',
                title: 'Akses Terbuka',
                text: 'Silakan ubah subjek dan isi pesan sesuai kebutuhan Anda.',
                timer: 2000,
                showConfirmButton: false
            });
            
            // Enable all fields
            const subjectInput = document.getElementById('subject');
            const greetingInput = document.getElementById('greeting');
            const messageInput = document.getElementById('message');
            const messageSection = document.getElementById('messageSection');
            
            const overlayClass = "opacity-50 pointer-events-none grayscale-[30%]";
            if(subjectInput) {
                subjectInput.closest('.group').classList.remove(...overlayClass.split(' '));
                subjectInput.readOnly = false;
            }
            if(greetingInput) {
                greetingInput.closest('.group').classList.remove(...overlayClass.split(' '));
                greetingInput.readOnly = false;
            }
            if(messageSection) {
                messageSection.classList.remove(...overlayClass.split(' '));
                messageSection.classList.remove('hidden');
            }
            if(messageInput) {
                messageInput.readOnly = false;
            }
            
            // Focus on subject to show it's editable
            setTimeout(() => { if(subjectInput) subjectInput.focus(); }, 100);
        }
    });
}
</script>
@endsection
